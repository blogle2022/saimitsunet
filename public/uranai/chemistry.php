<?php

require_once '../../bootstrap/uranai.php';

use App\Services\Model;

require_once __DIR__ . '/../../bootstrap/uranai.php';

$p = httpValue("p");
if (!$p) {
    $p = 1;
}

switch ($p) {
    case 1:
        $title = "総合";
        $desc = "生まれ持った相性になります";
        $horo = new HoroScope(
            $user->b_year,
            $user->b_mon,
            $user->b_day,
            $user->b_hour,
            $user->b_min,
            $user->lon,
            $user->lat,
            $user->tz
        );

        $target = httpValue('person');
        #if(!$target) {
        #	$target = $user->partners_id[0];
        #}

        if ($target) {
            $partner = $user->partners[$target];
            $horo2 = new HoroScope(
                $partner->b_year,
                $partner->b_mon,
                $partner->b_day,
                $partner->b_hour,
                $partner->b_min,
                $partner->lon,
                $partner->lat,
                $partner->tz
            );
            $targetname = $user->partners[$target]->username;

            $horo->invokeSWE();
            $horo->getAbsDegree();
            $horo2->invokeSWE();
            $horo2->getAbsDegree();


            $Sun0 = $horo->planets["Sun"]->absPosBySign;
            $Sun1 = $horo2->planets["Sun"]->absPosBySign;

            $Moon0 = $horo->planets["Moon"]->absPosBySign;
            $Moon1 = $horo2->planets["Moon"]->absPosBySign;

            $Jupiter0 = $horo->planets["Jupiter"]->absPosBySign;
            $Jupiter1 = $horo2->planets["Jupiter"]->absPosBySign;

            $Mars0 = $horo->planets["Mars"]->absPosBySign;
            $Mars1 = $horo2->planets["Mars"]->absPosBySign;

            $Venus0 = $horo->planets["Venus"]->absPosBySign;
            $Venus1 = $horo2->planets["Venus"]->absPosBySign;

            $Mercury0 = $horo->planets["Mercury"]->absPosBySign;
            $Mercury1 = $horo2->planets["Mercury"]->absPosBySign;

            $idx[0] = calcMatch($Sun0, $Sun1, $Moon0, $Moon1);      ## 性格
            #$idx[1] = 30 + calcMatch($Sun0, $Sun1, $Jupiter0, $Jupiter1); ##幸せ
            $idx[2] = 90 + calcMatch($Sun0, $Sun1, $Venus0, $Venus1);       ## 愛情
            $idx[1] = 120 + calcMatch($Sun0, $Sun1, $Mercury0, $Mercury1);  ## 知性
            #$idx[4] = 60 + calcMatch($Sun0, $Sun1, $Mars0, $Mars1); ## セックス
            $thisyear = intval(date("Y", time()));
            $thisday = intval(date("j", time()));
            $mod = ($user->b_day + $partner->b_day) % 2;
            $file = public_path() . "/libs/aishou/4f_01a_dousei";

            $line_len = 73;
            $line_cnt = 3;
            $text_cnt = 13;

            $hash = intval(($partner->b_year + $partner->b_mon + $partner->b_day + $partner->b_hour + $partner->b_min) % 16);


            $mix = new MixMsg($file);
            $mix->setLineLen($line_len);
            $mix->setLinesPar($line_cnt);
            $mix->setTotalLines($text_cnt);
            $mix->setHash($hash);
            # $titles = array("性格", "知性", "友情", "幸福",   "セックス");
            $titles = array("性格", "知性", "友情",);
            $limit = count($titles);
            for ($i = 0; $i < $limit; $i++) {
                $text .= '<p class="mb-5">';
                $text .= "<h4>" . $titles[$i] . "</h4>";
                $text .= $mix->getMessage($idx[$i]);
                $text .= "</p>";
            }

            break;
        }
    case 2:
        $title = "週間";
        $desc = "今週の相性になります";
        $limit = 5;
        $horo = new HoroScope(
            $user->b_year,
            $user->b_mon,
            $user->b_day,
            $user->b_hour,
            $user->b_min,
            $user->lon,
            $user->lat,
            $user->tz
        );
        $target = httpValue('person');
        if ($target) {

            $partner = $user->partners[$target];
            $horo2 = new HoroScope(
                $partner->b_year,
                $partner->b_mon,
                $partner->b_day,
                $partner->b_hour,
                $partner->b_min,
                $partner->lon,
                $partner->lat,
                $partner->tz
            );
            $targetname = $user->partners[$target]->username;



            $horo->invokeSWE();
            $horo->getAbsDegree();
            $horo2->invokeSWE();
            $horo2->getAbsDegree();



            $virtualAspect = virtualHoro($horo, $horo2);
            $tomorrow = time();
            $year_tmr = date("Y", $tomorrow);
            $month_tmr = date("n", $tomorrow);
            $day_tmr = date("j", $tomorrow);

            $weeklater = $tomorrow + 86400 * 6;
            $year_wek = date("Y", $weeklater);
            $month_wek = date("n", $weeklater);
            $day_wek = date("j", $weeklater);
            $dateFormat = 'Y年m月d日';
            $uranaiRange = [date($dateFormat, $tomorrow), date($dateFormat, $weeklater)];

            $horo_tmr = new HoroScope($year_tmr, $month_tmr, $day_tmr, 0, 0, $user->lon, $user->lat, $user->tz);
            $horo_tmr->invokeSWE();
            $horo_tmr->getAbsDegree();
            $horo_tmr->getAbsDegreeBySign();

            $horo_wek = new HoroScope($year_wek, $month_wek, $day_wek, 0, 0, $user->lon, $user->lat, $user->tz);
            $horo_wek->invokeSWE();
            $horo_wek->getAbsDegree();
            $horo_wek->getAbsDegreeBySign();

            $moon_tmr = $horo_tmr->planets["Moon"]->absPosBySign;
            $moon_wek = $horo_wek->planets["Moon"]->absPosBySign;

            $mod = $partner->b_day % 2;
            if ($moon_tmr < 0) $moon_tmr += 360;
            if ($moon_wek < 0) $moon_wek += 360;
            $asps = array(0, 120, 60, 144, 72, 30, 45, 135, 150, 90, 180);
            $vplnts = array("Moon", "Mercury", "Venus",);
            $filename[0] = public_path() . "/libs/weekly_love/a01_dousei.txt";
            $filename[1] = public_path() . "/libs/weekly_love/a01_dousei.txt";
            $titles = array(
                "Venus" => "友情度",
                "Mercury" => "知性度",
                "Moon" => "性格度"
            );
            $txtidx = array(
                "Venus" => 1,
                //"Mars" => 17,
                "Jupiter" => 17,
                "Mercury" => 33,
                "Moon" => 49
            );

            foreach ($vplnts as $vpl) {

                $idx = $txtidx["$vpl"];
                $kekkaTitle = $titles["$vpl"];
                $deg = $virtualAspect["$vpl"];
                $msgidx = 0;
                foreach ($asps as $asp) {
                    $lower = $deg - $asp;
                    $upper = $deg + $asp;
                    if (ri($moon_tmr, $moon_wek, $lower) || ri($moon_tmr, $moon_wek, $upper)) {
                        $msgidx = $idx;
                        break;  ## みつかったら抜けないと、悪い結果が出まくる
                    }
                    $idx++;
                }
                if (!$msgidx) {
                    $msgidx = $idx + 11 + $partner->b_day % 5;
                }
                ##echo "$msgidx , ";

                $offset = 75 * 13 * ($msgidx - 1) + 75;
                $fp = fopen($filename[0], "r");
                $line_offset = 13 * ($msgidx - 1);

                $cur = 0;
                $read = 0;
                $in = False;
                $out = False;

                $text .= "<h4>" . $kekkaTitle . "</h4>";
                while (!feof($fp)) {
                    $line = stripLine(fgets($fp));
                    if (!$in && $cur > $line_offset) {
                        $in = True;
                        ##echo "IN:$cur ";
                    }
                    if ($in && !$out) {
                        $text .= $line;
                        $read++;
                    }
                    if ($read == 12) $out = True;
                    if ($in && $out) break;
                    $cur++;
                }
                $text .= "<br><br>";
            }
        }

        break;

    default:
}

$partnerinfo = new Model('partnerinfo');
$partners = $partnerinfo->find('mailaddr', '=', $_SESSION['user']['mail']);
$p = $p ?: 1;
$params = [
    'title' => $title,
    'desc' => $desc,
    'text' => space2br($text),
    'p' => $p,
    'partners' => $partners,
    'thispage' => '?p=' . $p,
    'target' => $target,
    'range' => $uranaiRange,
    'today' => $today,
];
$_SESSION['token'] = csrf();
$params['token'] = $_SESSION['token'];
echo $response->view('uranai.aishou', $params);
