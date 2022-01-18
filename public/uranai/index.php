<?php

use App\Services\Model;

require_once __DIR__ . '/../../bootstrap/uranai.php';

$customer = $_SESSION['stripe_customer'];

function getMessages(int $daysAfter, $horo, $user): array
{
    $horo->invokeSWE();
    $horo->getAbsDegree();

    ##
    ## get today's Moon pos
    ##
    $ymd = date("Ymd", time() + (24 * 60 * 60) * $daysAfter);
    $planetpos = new Model('planetpos');
    $planetposRecord = $planetpos->get('datestr', '=', $ymd);
    $Moon = $planetposRecord['Moon'];

    $asps = array(0, 120, 60, 144, 72, 30, 35, 135, 150, 90, 180);
    $bpls = array("Venus", "Mars", "Jupiter", "Mercury", "Sun", "Moon", "Saturn");
    $portion = $user->b_year + $user->b_mon + $user->b_day
        + $user->b_hour + $user->b_min + $ymd;
    $mod = $portion % 5 + 11;
    $addmod = $portion % 12 + 1;
    $b = array(
        "Venus" => $horo->planets["Venus"]->absPosBySign,
        "Mars" => $horo->planets["Mars"]->absPosBySign,
        "Jupiter" => $horo->planets["Jupiter"]->absPosBySign,
        "Mercury" => $horo->planets["Mercury"]->absPosBySign,
        "Sun" => $horo->planets["Sun"]->absPosBySign,
        "Moon" => $horo->planets["Moon"]->absPosBySign,
        "Saturn" => $horo->planets["Saturn"]->absPosBySign
    );

    $msgkey = array(
        "Venus" => $mod,
        "Mars" => $mod,
        "Jupiter" => $mod,
        "Mercury" => $mod,
        "Sun" => $mod,
        "Moon" => $mod,
        "Saturn" => $mod
    );

    $mindif = 3.0;
    ## 既婚か未婚かで文章ファイルを変える
    if (httpValue("mariage") == "m") {
        $file = public_path() . "/libs/today_tomorrow/ft002_m.txt";
    } else {
        $file = public_path() . "/libs/today_tomorrow/ft002_s.txt";
    }

    ## 文章ファイルを最初に全部読み込んで，$ft配列にセットする
    $fp = fopen($file, "r");
    $count = 0;
    while (!feof($fp)) {
        $buf = fgets($fp, 256);
        $ft[$count] = preg_replace("/^[a-zA-Z]\-[0-9]+/", "", $buf);
        $count++;
    }

    $printmsg = array();
    $offset = 0;
    $str_offset = "";
    for ($i = 0; $i < count($bpls); $i++) {

        ##
        ## 意味のある角度を検出
        ## 外側のループ：誕生時の惑星
        ## 内側のループ：角度
        $bp = $bpls[$i];
        $k = abs($Moon - $b[$bp]);
        if (180 < $k) $k = 360 - $k;

        //echo "Moon: $Moon " . $bp . ":" . $b[$bp] . " DIFF: $k <br>";

        $found = $mod;
        for ($j = 0; $j < count($asps); $j++) {
            $curdiff = abs($k - $asps[$j]);
            //echo "$curdiff $asps[$j] <br>";
            if ($curdiff < $mindif) {
                /**
                 *                   意味のある角度が見つかったら文章番号をセットし，
                 *                 抜ける→次の惑星へ
                 **/
                $found = $j;
                $msgkey[$bp] = $found;
                # last;
            }
        }

        $ofs = $offset + ($found - 1) * 12 + $addmod;
        $printmsg[$bp] = $ft[$ofs];
        $mod16 = $found % 16;
        switch ($mod16) {
            case 1:
                $file = "7.jpg";
                break;
            case 2:
                $file = "7.jpg";
                break;
            case 3:
                $file = "6.jpg";
                break;
            case 4:
                $file = "6.jpg";
                break;
            case 5:
                $file = "5.jpg";
                break;
            case 6:
                $file = "5.jpg";
                break;
            case 7:
                $file = "3.jpg";
                break;
            case 8:
                $file = "3.jpg";
                break;
            case 9:
                $file = "2.jpg";
                break;
            case 10:
                $file = "2.jpg";
                break;
            case 11:
                $file = "1.jpg";
                break;
            case 12:
                $file = "4.jpg";
                break;
            case 13:
                $file = "4.jpg";
                break;
            case 14:
                $file = "4.jpg";
                break;
            case 15:
                $file = "3.jpg";
                break;
            default:
                $file = "3.jpg";
                break;
        }
        $rank[$bp]  = "images/dailyrank/" . $file;
        $str_offset .= $found . "_";
        $offset += 192;
    }

    $all = True;
    if (httpValue("check_all") == 'on') {
        $all = True;
    }

    if ($all || httpValue("check_a") == 'on') {
        $MoonMsg = mb_convert_encoding($printmsg["Moon"], "UTF-8");
    }
    if ($all || httpValue("check_b") == 'on') {
        $SunMsg = mb_convert_encoding($printmsg["Sun"], "UTF-8");
    }
    if ($all || httpValue("check_c") == 'on') {
        $VenusMsg = mb_convert_encoding($printmsg["Venus"], "UTF-8");
    }
    if ($all || httpValue("check_d") == 'on') {
        $MarsMsg = mb_convert_encoding($printmsg["Mars"], "UTF-8");
    }
    if ($all || httpValue("check_e") == 'on') {
        $JupiterMsg = mb_convert_encoding($printmsg["Jupiter"], "UTF-8");
    }
    if ($all || httpValue("check_f") == 'on') {
        $MercuryMsg = mb_convert_encoding($printmsg["Mercury"], "UTF-8");
    }
    if ($all || httpValue("check_g") == 'on') {
        $SaturnMsg = mb_convert_encoding($printmsg["Saturn"], "UTF-8");
    }

    $days = [
        '今日', '明日',
    ];

    $da = "$daysAfter 日後";

    if (isset($days[$daysAfter])) {
        $da = $days[$daysAfter];
    }

    $params = [
        'ranks' => $rank,
        'messages' => [
            'Moon' => [
                'title' => '対人関係',
                'text' => $MoonMsg
            ],
            'Mercury' => [
                'title' => '仕事',
                'text' => $MercuryMsg
            ],
            'Jupiter' => [
                'title' => 'お金',
                'text' => $JupiterMsg
            ],
            'Sun' => [
                'title' => '健康',
                'text' => $SunMsg
            ],
            'Venus' => [
                'title' => '恋愛',
                'text' => $VenusMsg
            ],
        ],
        'title' => $da,
    ];

    return $params;
}

$today = getMessages(0, $horo, $user);
$tomorrow = getMessages(1, $horo, $user);

$params = [
    'uranai' => [
        'today' => $today,
        'tomorrow' => $tomorrow,
    ],
    'customer' => $customer,
];
$_SESSION['token'] = csrf();
$params['token'] = $_SESSION['token'];

echo $response->view('uranai.index', $params);
