<?php

use App\Services\Model;

require_once __DIR__ . '/../../bootstrap/uranai.php';

$baseEmail = $_SESSION['user']['mail'];
$horoscopeModel = new Model('horoscope');

if ($request->post('method') === 'put') {
    $horoData = [
        'horoscope' => $request->post('horoscope'),
        'nickname' => $request->post('nickname'),
        'birthday' => $request->post('birthday'),
        'birthtime' => $request->post('birthtime'),
        'birth_place' => $request->post('area') . $request->post('city'),
    ];
    $horoscopeModel->updateOrInsert($horoData, 'horoscope');
}

$targetList = array_column($horoscopeModel->find('base_email', '=', $baseEmail), null, 'horoscope');

$horoscope = $request->post('horoscope');
$target = $targetList[$horoscope];

if ($birthday = $target['birthday']) {
    $birthtime = $target['birthtime'];
    $birthPlace = $target['birth_place'];

    list($year, $month, $day) = explode("-", $birthday);
    list($hour, $minute) = explode(":", $birthtime);

    $positionList = $location[$birthPlace] ?: $location['東京都新宿区'];
    list($lon, $lat, $tz) = $positionList;

    $horo = new HoroScope(
        $year,
        $month,
        $day,
        $hour,
        $min,
        $lon,
        $lat,
        $tz
    );

    $horo->invokeSWE();
    $horo->getAbsDegree();

    $planets = array();
    $signs = array();
    $pl_idx = array();
    $sg_idx = array();

    $planets[0] = "";
    $signs[0] = $horo->planets["Ascendant"]->sign;

    $pl_idx[0] = "";
    $sg_idx[0] = $house_matrix["$signs[0]"] - 10;


    for ($i = 1; $i <= 12; $i++) {
        ##list($planets[$i], $signs[$i]) = $horo->calcHousePlanet($i);
        $planets[$i] = $horo->calcHousePlanet($i);

        $pl_idx[$i] = $house_matrix["$planets[$i]"];
        $sg_idx[$i] = $house_matrix["$signs[$i]"];
    }

    $file = public_path() . "/libs/birth_01/h0";
    $head = array(
        "将来", "プロフィール", "マネー", "インテリジェンス",
        "ファミリー", "ラブ", "ヘルス", "マリッジ",
        "セックス", "国際性", "ビジネス", "適性", "メンタリティー"
    );

    $sg_offset = 70 * 13 * ($sg_idx[0] - 1) * 2;

    $fp = fopen($file, "r");
    fseek($fp, $sg_offset);
    for ($l = 0; $l < 13; $l++) {
        $buf = stripLine(fgets($fp));
        $msg[0][$l] = $buf;
    }
    fclose($fp);
    $msg[0][0] = "<b>" . $head[0] . "<br>" . $jp["$signs[0]"] . "が上昇宮にあります</b><br><br>\n";

    for ($i = 1; $i <= 12; $i++) {
        $file = public_path() . "/libs/birth_01/h" . $i;
        $pl_offset = 70 * 13 * ($pl_idx[$i] - 1) * 2;
        $fp = fopen($file, "r");
        fseek($fp, $pl_offset);
        for ($l = 0; $l < 13; $l++) {
            $buf = stripLine(fgets($fp));
            $msg[$i][$l] = $buf;
        }
        $msg[$i][0] = "<b>" . $head[$i] . "<br>" . $jp["$planets[$i]"] . "が第" . $i . "室にあります</b><br><br>\n";
        fclose($fp);
    }


    for ($i = 0; $i < count($msg); $i++) {
        for ($l = 0; $l < count($msg[$i]); $l++) {
            $text .= $msg[$i][$l];
        }
        $text .= "<br><br><br>";
    }
}

$params = [
    'horoarg' => $horo->horoarg,
    'title' => 'ホロスコープ',
    'text' => $text,
    'token' => csrf(),
    'horo_target_list' => $targetList,
    'method' => $request->method(),
    'horoscope' => $horoscope,
    'nickname' => $target['nickname'],
];

$_SESSION['token'] = $params['token'];

echo $response->view('uranai.horo', $params);
