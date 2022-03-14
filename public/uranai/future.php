<?php

use App\Services\Planetpos;

require_once __DIR__ . '/../../bootstrap/uranai.php';

$horo->invokeSWE();
$horo->getAbsDegree();

$p = intval(httpValue("p"));
if ($p === 0 || !is_numeric($p)) {
    redirect('?p=1');
}

$futureRange = json_decode(file_get_contents(array_to_path([storage_path(), 'system', 'future_range.json'])), true);
$targetYear = $futureRange['year'];
$period = $futureRange['period'];
$planetpos = new Planetpos();
$posThisYear = $planetpos->find('datestr', $futureRange['year'] . $futureRange['from'], $futureRange['year'] . $futureRange['to']);
$resultsCount = count($posThisYear);

switch ($p) {
    case 1:
        $planet = $horo->calcHousePlanet(1);
        $hash = ($user->b_day + $user->b_hour + $user->b_min) % 16;
        $pl_idx = $house_matrix[$planet];
        $file = public_path() . "/libs/future/5f_07";
        $mix = new MixMsg($file);
        $mix->setLineLen(91);
        $mix->setLinesPar(5);
        $mix->setTotalLines(21);
        $mix->setHash($hash);
        $text = $mix->getMessage($pl_idx);
        $title = "未来予測";
        $desc = "あなたの生まれ持った運命になります";

        break;

    case 2:
        list($pa, $pb, $msgid) = matrix2($horo, 180, 5);
        $hash = ($user->b_day + $user->b_hour + $user->b_min) % 16;
        $file = public_path() . "/libs/future/5f_05";
        $mix = new MixMsg($file);
        $mix->setLineLen(62);
        $mix->setLinesPar(5);
        $mix->setTotalLines(21);
        $mix->setHash($hash);
        $text = space2br(euc2utf($mix->getMessage($msgid))) ?: "ご入力いただいているデータでの星のめぐり合わせが無い状態にあります(詳細は<a href=about_non.php target=_blank>こちら</a>)";
        $title = "未来への注意";
        $desc = "あなたの生まれ持った運命になります<br>注意点を確認し、回避していきましょう";
        break;

    case 3:
        ## 求めたい日時

        $tgt["year"] = httpValue("p_year");
        $tgt["month"] = httpValue("p_month");
        $tgt["day"] = httpValue("p_day");
        $p_type = httpValue("p_type");

        $tgt["unixtime"] = mktime(0, 0, 0, $tgt["month"], $tgt["day"], $tgt["year"]);

        ## 出生日時との差を計算
        $date_progress = compareDate($horo->year, $horo->month, $horo->day, $tgt["year"], $tgt["month"], $tgt["day"]);
        ## 1年1日法 => 出生後1年後の運勢は1日後のホロスコープに既定される。
        ## よって生まれてから$prog日後の運勢を見る。
        $prog = $date_progress / 365;

        ## N日後を計算
        $base_unixtime = mktime($horo->hour, $horo->min, 0,  $horo->month, $horo->day, $horo->year);
        $base_unixtime += $prog * 86400;

        ## 1時間ごとに惑星位置計算

        for ($i = 0; $i < 24; $i++) {
            $prog_unixtime = $base_unixtime + $i * 3600;

            $yr = date("Y", $prog_unixtime);
            $mo = date("m", $prog_unixtime);
            $dy = date("d", $prog_unixtime);
            $hr = date("H", $prog_unixtime);
            $mi = date("i", $prog_unixtime);

            $p_horo[$i] = new HoroScope($yr, $mo, $dy, $hr, $mi, $user->lon, $user->lat, $user->tz);
            $p_horo[$i]->invokeSWE();
            $p_horo[$i]->getAbsDegree();
        }

        $bplnt = array("Sun", "Moon", "Mercury", "Venus", "Mars", "Jupiter");
        $pplnt = array("Sun", "Moon", "Mercury", "Venus", "Mars", "Jupiter", "Saturn", "Uranus", "Neptune", "Pluto");



        $degree_g = array(30, 60, 120);
        $degree_b = array(45, 90, 180);

        for ($i = 0; $i < 24; $i++) {
            for ($j = 0; $j < 6; $j++) {
                for ($k = 0; $k < 10; $k++) {
                    if ($bplnt[$j] === $pplnt[$k]) {
                        continue;
                    }
                    $deg1 = $horo->planets["$bplnt[$j]"]->absPosBySign;
                    $deg2 = $p_horo[$i]->planets["$pplnt[$k]"]->absPosBySign;
                    $asp = abs($deg1 - $deg2);

                    for ($l = 0; $l < 3; $l++) {
                        $dif = abs($asp - $degree_g[$l]);
                        if ($dif > 5)  continue;
                        if ($dif < intval($diff["$bplnt[$j]"]["$pplnt[$k]"]["g"]) || strcmp("", $diff["$bplnt[$j]"]["$pplnt[$k]"]["g"]) == 0) {
                            $diff["$bplnt[$j]"]["$pplnt[$k]"]["g"] = $dif;
                            $time["$bplnt[$j]"]["$pplnt[$k]"]["g"] = $i;
                        }
                    }
                    for ($l = 0; $l < 3; $l++) {
                        $dif = abs($asp - $degree_b[$l]);
                        if ($dif > 5)  continue;
                        if ($dif < intval($diff["$bplnt[$j]"]["$pplnt[$k]"]["b"]) || strcmp("", $diff["$bplnt[$j]"]["$pplnt[$k]"]["b"]) == 0) {
                            $diff["$bplnt[$j]"]["$pplnt[$k]"]["b"] = $dif;
                            $time["$bplnt[$j]"]["$pplnt[$k]"]["b"] = $i;
                        }
                    }
                }
            }
        }
        ## 文書ファイルの指定
        $hash = ($horo->day + $horo->hour + $horo->min) % 16;
        foreach ($time as $key => $value) {
            foreach ($value as $key2 => $value2) {
                foreach ($value2 as $key3 => $value3) {
                    $sql = "SELECT type,idx FROM progress WHERE birth='$key' and prog='$key2' and gorb='$key3'";
                    $res = $db->query($sql);
                    while (list($type, $idx) = $res->fetchRow(DB_FETCHMODE_ARRAY)) {
                        if ($type === $p_type) {
                            echo "<!-- $key $key2 $key3 $value3 ... $type $idx-->";
                            $textfile = "./libs/progress/" . $type . ".txt.euc";
                            $prog_unixtime = $tgt["unixtime"]  + 86400 * 15 * $value3;
                            $prog_date = date("<b>Y年m月d日頃", $prog_unixtime);
                            $text .= utf2euc($prog_date);
                            $text .= utf2euc($jp[$key] . "と" . $jp[$key2] . "が");
                            if ($key3 === 'g') {
                                $text .= utf2euc("吉相座になっています。</b><br><br>");
                            } else {
                                $text .= utf2euc("凶相座になっています。</b><br><br>");
                            }
                            $mix = new MixMsg($textfile);
                            $mix->setLineLen(42);
                            $mix->setLinesPar(4);
                            $mix->setTotalLines(17);
                            $mix->setHash($hash);
                            $text .= $mix->getMessage($idx);
                            $text .= "<br><br>";
                        }
                    }
                }
            }
        }

        $text = space2br(euc2utf($text));


        $title = "未来占星術";

        break;

    case 4:
        $horo->getAbsDegreeBySign();


        ###### このシーケンスで占う対象
        ###### ランキングの項目を番号で管理しています．
        $ranks = array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38);

        include(public_path() . "/libs/ranking.php");

        $bplnts = array(
            "Sun", "Moon", "Mercury", "Venus",    "Mars",
            "Jupiter"
        );



        $text .= "<h3>＜ホロスコープから見る誕生時の惑星位置＞</h3>";
        $text .= '<table border="0">';
        foreach ($bplnts as $bplnt) {

            $text .= "<tr>";
            $text .= '<td align="left">';
            $text .= '<br><div align="left"><font color="#000000">■あなたの誕生時の</font> <b><font size="3" color="#993333">'
                . $jp["$bplnt"]
                . '</font></b>は<b><font size="3" color="#993333">'
                . $jp[$horo->sign[$bplnt]]
                . '</font></b> <font color="#000000">にあります</font>'
                . '<br /><br />'
                . '<img src="/images/dia_blue.gif"><font size="2" color="#000000">誕生時の'
                . $jp["$bplnt"]
                . 'で見るあなたの星座相性ランキング</font></div>'
                . '<br />';
            $text .= "</td>";
            $text .= "</tr>";
            for ($i = 0; $i < count($ranks); $i++) {
                if (strcmp($jp[$bplnt], $rank_planet["$ranks[$i]"]) == 0) {
                    $text .= "<tr>";
                    $text .= '<td align="left">';
                    //			$text .= '<font color="#000000">→誕生時の';
                    //			$text .= "<b>";
                    //			$text .= euc2utf($rank_planet["$ranks[$i]"]);
                    //			$text .= "</b>";
                    $text .= '<div align="left"><font color="#000000"><img src="/images/dia_pink.gif">';
                    $text .= '<font size="3"><b>';
                    $text .= $rank_title["$ranks[$i]"];
                    $text .= '</b></font>ナンバーワン星座は⇒<a href="/uranai/ranking.php?rid=' . $ranks[$i] . '" target="_blank">';
                    $text .= "<font color=\"#0000FF\">こちら</font></b></a>";
                    $text .= "</div></td>";
                    $text .= "</tr>";
                }
            }
        }
        $text .= "</table>";

        $text = space2br($text);

        $title = "星座別ランキング";

        break;

    case 5:
        $horo->getAbsDegreeBySign();

        $obj = $posThisYear;
        $cnt = $resultsCount;

        ## 現在の星の名前の配列
        $cpls = array(
            "Sun", "Mercury", "Venus", "Mars", "Jupiter",
            "Saturn", "Uranus", "Neptune", "Pluto"
        );
        ### 誕生時の星の名前配列
        $bpls = array(
            "Sun", "Mercury", "Venus", "Mars", "Jupiter",
            "Saturn", "Uranus", "Neptune", "Pluto"
        );

        $bSun = $horo->planets["Sun"]->absPosBySign;
        // phase-1
        $msgdir = "/home/saimitsucom/www/msg";

        /**
		 begins_*  星が一定の角度をとり始める日付
		 ends_*  星が一定の角度をとらなくなる日付

         **/



        $begins = array();     ### 意味のある角度をとり始める日
        $ends = array();       ### 意味のある角度をとらなくなる日
        $msgs = array();       ### 文章
        $aspinfos = array();   ### 角度の説明

        ##
        ## 誕生時の星それぞれについて，現在の太陽の位置との角度が０度になる日を見てゆく
        ##
        for ($i = 0; $i < 9; $i++) {
            $b = $horo->planets["$bpls[$i]"]->absPosBySign;
            $in = False;

            for ($j = 0; $j < $cnt; $j++) {
                $t = $obj[$j]["Sun"];

                if (hasAspect($t, $b, 0, 1)) {
                    if (!$in) {
                        $begin = $obj[$j]["datestr"];
                        $begins[] = $begin;
                        $in = True;

                        $dbKeys = ['TP', 'BP', 'ASP'];
                        $conditions = array_combine($dbKeys, ["Sun", $bpls[$i], "0"]);
                        $results = $planetpos->aspect('ft004', $conditions);
                        $msgs["$begin"] = $results[0];
                        $aspinfo = "Tの太陽と、Bの" . $jp["$bpls[$i]"] . "がコンジャンクション";
                        $aspinfos["$begin"] = $aspinfo;
                    }
                } else {
                    if ($in) {
                        $ends["$begin"] = $obj[$j]["datestr"];
                        $in = False;
                        break;
                    }
                }
            }
        }

        ##
        ## 誕生時の星それぞれについて，現在の太陽の位置との角度が１８０度になる日を見てゆく
        ##
        for ($i = 0; $i < 9; $i++) {

            $b = $horo->planets["$bpls[$i]"]->absPosBySign;

            $in = False;

            for ($j = 0; $j < $cnt; $j++) {
                $t = $obj[$j]["Sun"];

                if (hasAspect($t, $b, 180, 1)) {
                    if (!$in) {
                        $begin = $obj[$j]["datestr"];
                        $begins[] = $begin;
                        $in = True;
                        $dbKeys = ['TP', 'BP', 'ASP'];
                        $conditions = array_combine($dbKeys, ["Sun", $bpls[$i], "180"]);
                        $results = $planetpos->aspect('ft004', $conditions);
                        $msgs["$begin"] = $results[0];
                        $aspinfo = "Tの太陽と、Bの" . $jp["$bpls[$i]"] . "がオポジション";
                        $aspinfos["$begin"] = $aspinfo;
                    }
                } else {
                    if ($in) {
                        $ends["$begin"] = $obj[$j]["datestr"];
                        $in = False;
                        break;
                    }
                }
            }
        }


        ## 年変更
        sort($begins);
        foreach ($begins as $begin) {
            $text .= "<b>";
            $text .= splitDateStr($begin) . "～" . splitDateStr($ends[$begin]);
            $text .= "の、";
            $text .= $aspinfos[$begin];
            $text .= "</b><br>";
            $text .= euc2utf($msgs[$begin]['FT_TXT']) . "<br><br>";
        }

        $title = $targetYear . "年" . $period . "の運気";
        $desc = "$targetYear 年の運気になります<br>特筆すべきタイミングを表示しています";

        break;

    case 6:
        $horo->getAbsDegreeBySign();
        /**
		 来年の星の位置をDBからロードする
		20201201井村日付変更
         **/

        $obj = $posThisYear;
        $cnt = $resultsCount;

        ## 現在の星の名前の配列
        $cpls = array(
            "Sun", "Mercury", "Venus", "Mars", "Jupiter",
            "Saturn", "Uranus", "Neptune", "Pluto"
        );

        ## 生まれた時の火星の位置
        $bMars = $horo->planets["Mars"]->absPosBySign;
        ## 生まれた時の金星の位置
        $bVenus = $horo->planets["Venus"]->absPosBySign;

        // phase-1
        $msgdir = "/home/saimitsucom/www/msg";
        /**
		 begins_*  星が一定の角度をとり始める日付
		 ends_*  星が一定の角度をとらなくなる日付

         **/
        $begins_a = array();
        $ends_a = array();
        $msgs_a = array();
        $aspinfos_a = array();

        $begins_b = array();
        $ends_b = array();
        $msgs_b = array();
        $aspinfos_b = array();


        ##
        ## 誕生時の金星が，いずれかの星と0度の位置になる期間を探す
        ##
        ##
        $b = $bVenus;
        for ($i = 0; $i < 9; $i++) {
            $in = False;
            for ($j = 0; $j < $cnt; $j++) {
                $t = $obj[$j]["$cpls[$i]"];

                if (hasAspect($t, $b, 0, 1)) {
                    if (!$in) {
                        $begin = $obj[$j]["datestr"];
                        $begins_a[] = $begin;
                        $in = True;
                        $dbKeys = ['TP', 'BP', 'ASP'];
                        $conditions = array_combine($dbKeys, [$cpls[$i], "Venus", "0"]);
                        $results = $planetpos->aspect('ft001', $conditions);
                        $msgs_a["$begin"] = $results[0];
                        $aspinfo = "Tの" . $jp["$cpls[$i]"] . "と、Bの金星がコンジャンクション";
                        $aspinfos_a["$begin"] = $aspinfo;
                    }
                } else {
                    if ($in) {
                        $ends_a["$begin"] = $obj[$j]["datestr"];
                        $in = False;
                        break;
                    }
                }
            }
        }


        ##
        ## 誕生時の金星が，いずれかの星と120度の位置になる期間を探す
        ##
        ##

        for ($i = 0; $i < 9; $i++) {
            $in = False;
            for ($j = 0; $j < $cnt; $j++) {
                $t = $obj[$j]["$cpls[$i]"];
                //if(hasAspect($t,$b,120,1)) {
                if (abs(($t - $b) - 120) < 1) {
                    if (!$in) {
                        $begin = $obj[$j]["datestr"];
                        $begins_a[] = $begin;
                        $in = True;
                        $dbKeys = ['TP', 'BP', 'ASP'];
                        $conditions = array_combine($dbKeys, [$cpls[$i], "Venus", "120"]);
                        $results = $planetpos->aspect('ft001', $conditions);
                        $msgs_a["$begin"] = $results[0];
                        $aspinfo = "Tの" . $jp["$cpls[$i]"] . "と、Bの金星がトライン";
                        $aspinfos_a["$begin"] = $aspinfo;
                    }
                } else {
                    if ($in) {
                        $ends_a["$begin"] = $obj[$j]["datestr"];
                        $in = False;
                        break;
                    }
                }
            }
        }

        ##
        ## 誕生時の金星が，いずれかの星と180度の位置になる期間を探す
        ##
        ##
        for ($i = 0; $i < 9; $i++) {
            $in = False;
            for ($j = 0; $j < $cnt; $j++) {
                $t = $obj[$j]["$cpls[$i]"];
                if (hasAspect($t, $b, 180, 1)) {
                    if (!$in) {
                        $begin = $obj[$j]["datestr"];
                        $begins_a[] = $begin;
                        $in = True;
                        $dbKeys = ['TP', 'BP', 'ASP'];
                        $conditions = array_combine($dbKeys, [$cpls[$i], "Venus", "180"]);
                        $results = $planetpos->aspect('ft001', $conditions);
                        $msgs_a["$begin"] = $results[0];
                        $aspinfo = "Tの" . $jp["$cpls[$i]"] . "と、Bの金星がオポジション";
                        $aspinfos_a["$begin"] = $aspinfo;
                    }
                } else {
                    if ($in) {
                        $ends_a["$begin"] = $obj[$j]["datestr"];
                        $in = False;
                        break;
                    }
                }
            }
        }

        ##
        ## 誕生時の火星が，いずれかの星と0度の位置になる期間を探す
        ##
        ##
        $b = $bMars;
        for ($i = 0; $i < 9; $i++) {
            $in = False;
            for ($j = 0; $j < $cnt; $j++) {
                $t = $obj[$j]["$cpls[$i]"];
                if (hasAspect($t, $b, 0, 1)) {
                    if (!$in) {
                        $begin = $obj[$j]["datestr"];
                        $begins_b[] = $begin;
                        $in = True;
                        $dbKeys = ['TP', 'BP', 'ASP'];
                        $conditions = array_combine($dbKeys, [$cpls[$i], "Mars", "0"]);
                        $results = $planetpos->aspect('ft001', $conditions);
                        $msgs_b["$begin"] = $results[0];
                        $aspinfo = "Tの" . $jp["$cpls[$i]"] . "と、Bの火星がコンジャンクション";
                        $aspinfos_b["$begin"] = $aspinfo;
                    }
                } else {
                    if ($in) {
                        $ends_b["$begin"] = $obj[$j]["datestr"];
                        $in = False;
                        break;
                    }
                }
            }
        }

        ##
        ## 誕生時の火星が，いずれかの星と180度の位置になる期間を探す
        ##
        ##
        for ($i = 0; $i < 9; $i++) {
            $in = False;
            for ($j = 0; $j < $cnt; $j++) {
                $t = $obj[$j]["$cpls[$i]"];
                if (hasAspect($t, $b, 180, 1)) {
                    if (!$in) {
                        $begin = $obj[$j]["datestr"];
                        $begins_b[] = $begin;
                        $in = True;
                        $dbKeys = ['TP', 'BP', 'ASP'];
                        $conditions = array_combine($dbKeys, [$cpls[$i], "Mars", "180"]);
                        $results = $planetpos->aspect('ft001', $conditions);
                        $msgs_b["$begin"] = $results[0];
                        $aspinfo = "Tの" . $jp["$cpls[$i]"] . "と、Bの火星がオポジション";
                        $aspinfos_b["$begin"] = $aspinfo;
                    }
                } else {
                    if ($in) {
                        $ends_b["$begin"] = $obj[$j]["datestr"];
                        $in = False;
                        break;
                    }
                }
            }
        }

        sort($begins_a);
        $text .= '<h3 class="title">愛の変遷と出会いを知る</h3>';

        foreach ($begins_a as $begin) {
            $text .= '<b>';
            $text .= splitDateStr($begin) . "～" . splitDateStr($ends_a[$begin]);
            $text .= "の、";
            $text .= $aspinfos_a[$begin];
            $text .= "</b><br>";
            $text .= euc2utf($msgs_a[$begin]['FT_TXT'])  . "<br><br>";
        }

        sort($begins_b);
        $text .= '<h3 class="title">情熱の高まりと対象を知る</h3>';

        foreach ($begins_b as $begin) {
            $text .= '<b>';
            $text .= splitDateStr($begin) . "～" . splitDateStr($ends_b[$begin]);
            $text .= "の、";
            $text .= $aspinfos_b[$begin];
            $text .= "</b><br>";

            $text .= euc2utf($msgs_b[$begin]['FT_TXT']) . "<br><br>";
        }


        $title = $targetYear . "年" . $period . "の恋愛運";
        $desc = "$targetYear 年の運気になります<br>特筆すべきタイミングを表示しています";
        break;

    case 7:
        $horo->getAbsDegreeBySign();

        /**
         * 対象年の星の位置をDBからロードする
         * 201201井村日付変更
         **/
        $obj = $posThisYear;
        $cnt = $resultsCount;

        ## 現在の星の名前の配列
        $cpls = array(
            "Sun", "Mercury", "Venus", "Mars", "Jupiter",
            "Saturn", "Uranus", "Neptune", "Pluto"
        );
        ### 誕生時の星の名前配列
        $bpls = array(
            "Sun", "Mercury", "Venus", "Mars", "Jupiter",
            "Saturn", "Uranus", "Neptune", "Pluto"
        );

        $bSun = $horo->planets["Sun"]->absPosBySign;
        $bMoon = $horo->planets["Moon"]->absPosBySign;
        $bMercury = $horo->planets["Mercury"]->absPosBySign;


        // phase-1
        $msgdir = "/home/saimitsucom/www/msg";
        /**
         * begins_*  星が一定の角度をとり始める日付
         * ends_*  星が一定の角度をとらなくなる日付
         **/

        $begins_a = array();
        $ends_a = array();
        $msgs_a = array();
        $aspinfos_a = array();

        $begins_b = array();
        $ends_b = array();
        $msgs_b = array();
        $aspinfos_b = array();

        $begins_c = array();
        $ends_c = array();
        $msgs_c = array();
        $aspinfos_c = array();


        //
        //
        // Phase 1
        //

        $b = $bSun;
        // 誕生時の太陽と各惑星の0度になる日をさがす
        for ($i = 1; $i < 9; $i++) {
            $in = False;
            for ($j = 0; $j < $cnt; $j++) {
                $t = $obj[$j]["$cpls[$i]"];

                if (hasAspect($t, $b, 0, 1)) {
                    if (!$in) {
                        $begin = $obj[$j]["datestr"];
                        $begins_a[] = $begin;
                        $in = True;
                        $dbKeys = ['TP', 'BP', 'ASP'];
                        $conditions = array_combine($dbKeys, [$cpls[$i], "Sun",  "0"]);
                        $results = $planetpos->aspect('ft005', $conditions);
                        $msgs_a["$begin"] = $results[0];
                        $aspinfo = "Tの" . $jp["$cpls[$i]"] . "と、Bの太陽がコンジャンクション";
                        $aspinfos_a["$begin"] = $aspinfo;
                    }
                } else {
                    if ($in) {
                        $ends_a["$begin"] = $obj[$j]["datestr"];
                        $in = False;
                        break;
                    }
                }
            }
        }

        // 誕生時の太陽と各惑星の180度になる日をさがす
        for ($i = 1; $i < 9; $i++) {
            $in = False;
            for ($j = 0; $j < $cnt; $j++) {
                $t = $obj[$j]["$cpls[$i]"];
                if (hasAspect($t, $b, 180, 1)) {
                    if (!$in) {
                        $begin = $obj[$j]["datestr"];
                        $begins_a[] = $begin;
                        $in = True;
                        $dbKeys = ['TP', 'BP', 'ASP'];
                        $conditions = array_combine($dbKeys, [$cpls[$i], "Sun",  "180"]);
                        $results = $planetpos->aspect('ft005', $conditions);
                        $msgs_a["$begin"] = $results[0];
                        $aspinfo = "Tの" . $jp["$cpls[$i]"] . "と、Bの太陽がオポジション";
                        $aspinfos_a["$begin"] = $aspinfo;
                    }
                } else {
                    if ($in) {
                        $ends_a["$begin"] = $obj[$j]["datestr"];
                        $in = False;
                        break;
                    }
                }
            }
        }

        // 誕生時の太陽と外惑星の90度（上方，下方あり）になる日をさがす
        // +90度と-90度の両方を評価する
        for ($i = 3; $i < 9; $i++) {
            $in = False;
            for ($j = 0; $j < $cnt; $j++) {
                $t = $obj[$j]["$cpls[$i]"];
                if (hasAspect($t, $b, 90, 1)) {
                    if (!$in) {
                        // 上方 or 下方 square
                        $mid = ($t + $b) / 2;
                        if (360 < $mid) $mid -= 360;
                        if (0 <= $mid && $mid < 180) {
                            $ASP = "90b";    // 下方
                            $AspStr = "下方へのスクエア";
                        } else {
                            $ASP = "90a";    // 上方
                            $AspStr = "上方へのスクエア";
                        }

                        $begin = $obj[$j]["datestr"];
                        $begins_a[] = $begin;
                        $in = True;
                        $dbKeys = ['TP', 'BP', 'ASP'];
                        $conditions = array_combine($dbKeys, [$cpls[$i], "Sun",  $ASP]);
                        $results = $planetpos->aspect('ft005', $conditions);
                        $msgs_a["$begin"] = $results[0];
                        $aspinfo = "Tの" . $jp["$cpls[$i]"] . "と、Bの太陽が" . $AspStr;
                        $aspinfos_a["$begin"] = $aspinfo;
                    }
                } else {
                    if ($in) {
                        $ends_a["$begin"] = $obj[$j]["datestr"];
                        $in = False;
                        break;
                    }
                }
            }
        }


        //
        //
        // Phase 2
        //

        $b = $bMoon;
        // 誕生時の月と各惑星の0度
        for ($i = 1; $i < 9; $i++) {
            $in = False;
            for ($j = 0; $j < $cnt; $j++) {
                $t = $obj[$j]["$cpls[$i]"];

                if (hasAspect($t, $b, 0, 1)) {
                    if (!$in) {
                        $begin = $obj[$j]["datestr"];
                        $begins_b[] = $begin;
                        $in = True;
                        $dbKeys = ['TP', 'BP', 'ASP'];
                        $conditions = array_combine($dbKeys, [$cpls[$i], "Moon",  "0"]);
                        $results = $planetpos->aspect('ft005', $conditions);
                        $msgs_b["$begin"] = $results[0];
                        $aspinfo = "Tの" . $jp["$cpls[$i]"] . "と、Bの月がコンジャンクション";
                        $aspinfos_b["$begin"] = $aspinfo;
                    }
                } else {
                    if ($in) {
                        $ends_b["$begin"] = $obj[$j]["datestr"];
                        $in = False;
                        break;
                    }
                }
            }
        }

        // 誕生時の月と各惑星の180度
        for ($i = 1; $i < 9; $i++) {
            $in = False;
            for ($j = 0; $j < $cnt; $j++) {
                $t = $obj[$j]["$cpls[$i]"];
                if (hasAspect($t, $b, 180, 1)) {
                    if (!$in) {
                        $begin = $obj[$j]["datestr"];
                        $begins_b[] = $begin;
                        $in = True;
                        $dbKeys = ['TP', 'BP', 'ASP'];
                        $conditions = array_combine($dbKeys, [$cpls[$i], "Moon",  "180"]);
                        $results = $planetpos->aspect('ft005', $conditions);
                        $msgs_b["$begin"] = $results[0];
                        $aspinfo = "Tの" . $jp["$cpls[$i]"] . "と、Bの月がオポジション";
                        $aspinfos_b["$begin"] = $aspinfo;
                    }
                } else {
                    if ($in) {
                        $ends_b["$begin"] = $obj[$j]["datestr"];
                        $in = False;
                        break;
                    }
                }
            }
        }

        // 誕生時の月と外惑星の90度（上方，下方あり）
        for ($i = 3; $i < 9; $i++) {
            $in = False;
            for ($j = 0; $j < $cnt; $j++) {
                $t = $obj[$j]["$cpls[$i]"];
                if (hasAspect($t, $b, 90, 1)) {
                    if (!$in) {

                        // 上方 or 下方 square
                        $mid = ($t + $b) / 2;
                        if (360 < $mid) $mid -= 360;
                        if (0 <= $mid && $mid < 180) {
                            $ASP = "90b";    // 下方
                            $AspStr = "下方へのスクエア";
                        } else {
                            $ASP = "90a";    // 上方
                            $AspStr = "上方へのスクエア";
                        }

                        $begin = $obj[$j]["datestr"];
                        $begins_b[] = $begin;
                        $in = True;
                        $dbKeys = ['TP', 'BP', 'ASP'];
                        $conditions = array_combine($dbKeys, [$cpls[$i], "Moon",  $ASP]);
                        $results = $planetpos->aspect('ft005', $conditions);
                        $msgs_b["$begin"] = $results[0];
                        $aspinfo = "Tの" . $jp["$cpls[$i]"] . "と、Bの月が" . $AspStr;
                        $aspinfos_b["$begin"] = $aspinfo;
                    }
                } else {
                    if ($in) {
                        $ends_b["$begin"] = $obj[$j]["datestr"];
                        $in = False;
                        break;
                    }
                }
            }
        }


        //
        //
        // Phase 2
        //

        $b = $bMercury;
        // 誕生時の水星と各惑星の0度
        for ($i = 1; $i < 9; $i++) {
            $in = False;
            for ($j = 0; $j < $cnt; $j++) {
                $t = $obj[$j]["$cpls[$i]"];

                if (hasAspect($t, $b, 0, 1)) {
                    if (!$in) {
                        $begin = $obj[$j]["datestr"];
                        $begins_c[] = $begin;
                        $in = True;
                        $dbKeys = ['TP', 'BP', 'ASP'];
                        $conditions = array_combine($dbKeys, [$cpls[$i], "Mercury",  "0"]);
                        $results = $planetpos->aspect('ft005', $conditions);
                        $msgs_c["$begin"] = $results[0];
                        $aspinfo = "Tの" . $jp["$cpls[$i]"] . "と、Bの水星がコンジャンクション";
                        $aspinfos_c["$begin"] = $aspinfo;
                    }
                } else {
                    if ($in) {
                        $ends_c["$begin"] = $obj[$j]["datestr"];
                        $in = False;
                        break;
                    }
                }
            }
        }

        // 誕生時の水星と各惑星の180度
        for ($i = 1; $i < 9; $i++) {
            $in = False;
            for ($j = 0; $j < $cnt; $j++) {
                $t = $obj[$j]["$cpls[$i]"];
                if (hasAspect($t, $b, 180, 1)) {
                    if (!$in) {
                        $begin = $obj[$j]["datestr"];
                        $begins_c[] = $begin;
                        $in = True;
                        $dbKeys = ['TP', 'BP', 'ASP'];
                        $conditions = array_combine($dbKeys, [$cpls[$i], "Mercury",  "180"]);
                        $results = $planetpos->aspect('ft005', $conditions);
                        $msgs_c["$begin"] = $results[0];
                        $aspinfo = "Tの" . $jp["$cpls[$i]"] . "と、Bの水星がオポジション";
                        $aspinfos_c["$begin"] = $aspinfo;
                    }
                } else {
                    if ($in) {
                        $ends_c["$begin"] = $obj[$j]["datestr"];
                        $in = False;
                        break;
                    }
                }
            }
        }



        sort($begins_a);
        $text .= '<h3 class="title">1.' . $targetYear . '年' . $period . 'の転機を知る</h3>';

        foreach ($begins_a as $begin) {
            $text .= '<b>';
            $text .= splitDateStr($begin) . "～" . splitDateStr($ends_a[$begin]);
            $text .= "の、";
            $text .= $aspinfos_a[$begin];
            $text .= "</b><br>";

            $text .= euc2utf($msgs_a[$begin]['FT_TXT']) . "<br><br>";
        }


        sort($begins_b);
        $text .= '<h3 class="title">2.心境変化と対応を知る</h3>';

        foreach ($begins_b as $begin) {
            $text .= '<b>';
            $text .= splitDateStr($begin) . "～" . splitDateStr($ends_b[$begin]);
            $text .= "の、";
            $text .= $aspinfos_b[$begin];
            $text .= "</b><br>";

            $text .= euc2utf($msgs_b[$begin]['FT_TXT']) . "<br><br>";
        }


        sort($begins_c);
        $text .= '<h3 class="title">3.ビジネスチャンスの変化を知る</h3>';

        foreach ($begins_c as $begin) {
            $text .= '<b>';
            $text .= splitDateStr($begin) . "～" . splitDateStr($ends_c[$begin]);
            $text .= "の、";
            $text .= $aspinfos_c[$begin];
            $text .= "</b><br>";

            $text .= euc2utf($msgs_c[$begin]['FT_TXT']) . "<br><br>";
        }

        $title = $targetYear . "年" . $period . "のマネー運";
        $desc = "$targetYear 年の運気になります<br>特筆すべきタイミングを表示しています";

        break;

    case 8:
        $horo->getAbsDegreeBySign();


        $datestr = array(
            "1月3日", "1月10日", "1月17日", "1月24日",
            "1月31日", "2月7日", "2月14日", "2月21日",
            "2月28日", "3月7日", "3月14日", "3月21日", "2月28日",
            "4月4日", "4月11日", "4月18日", "4月25日",
            "5月2日", "5月9日", "5月16日", "5月23日", "5月30日",
            "6月6日", "6月13日", "6月20日", "6月27日"
        );

        $tran["Jupiter"] = array(
            303.412847527778, 305.035518, 306.679918583333, 308.337046833333, 309.999340972222, 311.659974083333, 313.311403444444, 314.945104027778, 316.553614, 318.130039111111, 319.666562583333, 321.154467416667, 322.585632027778, 323.95271925, 325.2468815, 326.458669583333, 327.578909388889, 328.599591305556, 329.510840861111, 330.302783583333, 330.966110111111, 331.493643472222, 331.876816166667, 332.108639861111, 332.184366694444, 332.103598027778,
        );
        $tran["Mars"] = array(
            28.5626406666667, 31.7765918611111, 35.1776710555556, 38.7338957222222, 42.41564525, 46.2010512222222, 50.0765789166667, 54.0271097777778, 58.0371589166667, 62.0957048611111, 66.19750425, 70.3357763333333, 74.5020158055556, 78.6904891666667, 82.8998408611111, 87.1277101111111, 91.3692904444444, 95.6216512222222, 99.8853151388889, 104.160467083333, 108.444392027778, 112.735749861111, 117.035944194444, 121.3467015, 125.666542194444, 129.995028333333,
        );
        $tran["Sun"] = array(
            283.58223925, 290.717320722222, 297.850683388889, 304.973946138889, 312.084269638889, 319.18266175, 326.266849138889, 333.329538027778, 340.367333861111, 347.382328166667, 354.374926, 1.34006188888889, 8.27451783333333, 15.1813267222222, 22.0636504722222, 28.9191068611111, 35.7450390277778, 42.5449220833333, 49.3241846944444, 56.0830581944444, 62.8194971944444, 69.5368274722222, 76.2418747777778, 82.9371423333333, 89.6210617222222, 96.2962805277778,
        );
        $tran["Venus"] = array(
            263.855762111111, 272.628181527778, 281.404145694444, 290.178785416667, 298.951280861111, 307.722139555556, 316.4895445, 325.249230222222, 333.999497388889, 342.741177861111, 351.473693388889, 0.193567916666667, 8.89842852777778, 17.5891630277778, 26.2667671111111, 34.9294345277778, 43.5748148333333, 52.2039024444444, 60.8189835, 69.4198908611111, 78.0041468611111, 86.5717152777778, 95.1246785555556, 103.663409638889, 112.184362888889, 120.685131555556,
        );
        $natal["Sun"] = $horo->planets["Sun"]->absPosBySign;
        $natal["Jupiter"] = $horo->planets["Jupiter"]->absPosBySign;
        $natal["Saturn"] = $horo->planets["Saturn"]->absPosBySign;
        $natal["Venus"] = $horo->planets["Venus"]->absPosBySign;


        $asps = array(0, 60, 90, 120, 180);
        $orbs = array(
            "Jupiter" => 5,
            "Mars" => 5,
            "Venus" => 7,
            "Sun" => 7
        );

        $bplnts = array("Sun", "Jupiter", "Saturn", "Venus");
        $cplnts = array("Jupiter", "Mars", "Venus", "Sun");
        $starts = array(
            "Sun" => 1,
            "Jupiter" => 2,
            "Saturn" => 3,
            "Venus" => 4
        );
        $lucky = array("", "", "", "", "");

        foreach ($bplnts as $bplnt) {
            $basp = $natal["$bplnt"];
            $mindiff = 180;
            $idx = $starts["$bplnt"];
            foreach ($cplnts as $cplnt) {
                $orb = $orbs["$cplnt"];
                foreach ($asps as $asp) {
                    for ($i = 0; $i < 26; $i++) {
                        $tasp = $tran["$cplnt"][$i];

                        $width = abs($basp - $tasp);
                        $dif = abs($width - $asp);
                        if ($dif < $mindiff) {
                            $mindiff = $dif;
                            $textidx["$starts[$bplnt]"] = $idx;
                        }
                    }
                    $idx += 4;
                }
            }
        }

        $lasps = array(120, 60, 0);
        $lcplnts = array("Jupiter", "Venus", "Sun");

        foreach ($bplnts as $bplnt) {
            $basp = $natal["$bplnt"];
            foreach ($lcplnts as $cplnt) {
                if (strcmp($lucky["$starts[$bplnt]"], "") != 0) break;
                $orb = $orbs["$cplnt"];
                foreach ($lasps as $asp) {
                    if (strcmp($lucky["$starts[$bplnt]"], "") != 0) break;
                    for ($i = 0; $i < 26; $i++) {
                        $tasp = $tran["$cplnt"][$i];
                        if (hasAspect($basp, $tasp, $asp, $orb)) {
                            $lucky["$starts[$bplnt]"] = strval($i);
                            break;
                        }
                    }
                }
            }
        }

        $mod = intval($day) % 16;
        $fp = fopen("/home/saimitsucom/www/msg/ft2006b.txt", "r");
        for ($j = 0; $j < 4; $j++) { ### Sun,Jupiter,Saturn,Venus
            $firstpos = ($textidx[$j + 1] - 1) * 40;
            for ($k = 0; $k < 4; $k++) {
                $mixpos[$k] = $firstpos + $k * 5;
                if (($mod & (2 ^ $k)) > 0) $mixpos[$k] += 20;
                ##echo "$mixpos[$k], ";
                fseek($fp, ($mixpos[$k] + 1) * 56);
                $text[$j] .= stripLine(fread($fp, 56 * 4));
            }
            $text[$j] = space2br(euc2utf($text[$j]));
            $lucky_idx = $lucky[$j + 1];
            $text[$j] = preg_replace("/\*\*月\*\*日/u", $datestr[$lucky_idx], $text[$j]);
        }
        fclose($fp);

        $text = <<<EOF
<b>総合運</b><br>
$text[0]
<br><br>
<b>よいこと</b><br>
$text[1]
<br><br>
<b>注意すること</b><br>
$text[2]
EOF;


        $title = $targetYear . "年" . $period . "半年占い";
        $desc = "$targetYear 年の運気になります<br>特筆すべきタイミングを表示しています";
        break;

    default:
}

$params = [
    'title' => $title,
    'myself' => $myself,
    'text' => $text,
    'p' => $p,
    'person' => $request->post('person'),
    'partners' => $user->partners,
    'targetYear' => $targetYear,
    'period' => $period,
    'desc' => $desc,
];
$_SESSION['token'] = csrf();
$params['token'] = $_SESSION['token'];

echo $response->view('uranai.future', $params);
