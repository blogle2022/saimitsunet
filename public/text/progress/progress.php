<?php
include("libs/pageopen.php");
include("libs/astro.class");
include("libs/horo.inc");

## 出生時のホロスコープ計算
$horo->invokeSWE();
$horo->getAbsDegree();



## 求めたい日時

$tgt["year"] = httpValue("p_year");
$tgt["month"] = httpValue("p_month");
$tgt["day"] = httpValue("p_day");
$p_type = httpValue("p_type");

## 出生日時との差を計算
$date_progress = compareDate($horo->year, $horo->month, $horo->day, $tgt["year"], $tgt["month"], $tgt["day"]);
## 1年1日法 => 出生後1年後の運勢は1日後のホロスコープに既定される。
## よって生まれてから$prog日後の運勢を見る。
$prog = $date_progress / 365;

## N日後を計算

$progressed = computeDate($horo->year, $horo->month, $horo->day, $prog);

## 「年-月-日 時:分:秒」に分解
list($ymd, $his) = explode(" ", $progressed);
$progress = explode("-", $ymd);
$progress_his = explode(":", $his);

## プログレスのホロスコープを作成

	$y = intval($progress[0]);
	$m = intval($progress[1]);
	$d = intval($progress[2]);
	$h = intval($progress_his[0]);
	$mi = intval($progress_his[1]);

## 1時間ごとに惑星位置計算
for($i=0; $i<24; $i++) {
	$hh = $h + $i;
	$p_horo[$i] = new HoroScope($y, $m, $d,$hh, $mi,$user->lon, $user->lat, $user->tz);
	$p_horo[$i]->invokeSWE();
	$p_horo[$i]->getAbsDegree();
}

$bplnt = array("Sun","Moon","Mercury","Venus", "Mars","Jupiter");
$pplnt = array("Sun","Moon","Mercury","Venus","Mars", "Jupiter", "Saturn", "Uranus", "Neptune", "Pluto");

## use hasAspect($deg1, $deg2, $asp, $error)


$degree_g = array(30, 60, 120);
$degree_b = array(45, 90, 180);

for($i=0; $i<24; $i++) {
	for($j=0; $j<6; $j++) {
		for($k=0; $k<10; $k++) {
			$deg1 = $horo->planets["$bplnt[$j]"]->absPosBySign;
			$deg2 = $p_horo[$i]->planets["$pplnt[$k]"]->absPosBySign;
			$asp = abs($deg1 - $deg2);
			for($l=0; $l<3; $l++) {
				$dif = abs($asp - $degree_g[$l]);
				if(!
				if($dif < $diff["$bplnt[$j]"]["$pplnt[$k]"]["g"] || strcmp("",$diff["$bplnt[$j]"]["$pplnt[$k]"]["g"])==0 ) {
					$diff["$bplnt[$j]"]["$pplnt[$k]"]["g"] = $dif;
					$time["$bplnt[$j]"]["$pplnt[$k]"]["g"] = $i;
				}
			}
		}
	}
}

## 文書ファイルの指定
$textfile = "/home/stella-net.jp/progress/" . $p_type . ".txt";






?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>あなたとあなたの大切な人の全てが占える 細密占星術 | 恋愛運</title>
<meta name="keywords" content="">
<meta name="description" content="">
<link href="./shared/css/style_import.css" rel="stylesheet" type="text/css" />
<script src="./shared/js/jquery.js"  type="text/javascript"></script>
<script src="./shared/js/scrollsmooth.js"     type="text/javascript"></script>
</head>
<body>
<!----↓Header---->
<?=$header_2?>
<!----↑Header---->
<!----↓MainVisual---->
<?= $navi ?>
<!----↑MainVisual---->
<!----↓contents---->
<div id="contentsArea">
  <div id="container" class="clearfix">
  <div id="pankuzu"><a href="index.php">トップ</a> &gt; <a href="love.php"> 恋愛と結婚運 </a>&gt; 結婚運</div>
    <!----↓LeftColumn---->
    <div class="left">
      <!----↓HsMenu01---->
      <div class="contentsBox">
        <div class="contentsBoxTop">
          <h2 class="title"><?=$targetname?>さんの未来</h2>
        </div>
        <div class="contentsBoxBottom">
          <div class="horoscopeResult clearfix">

          <table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr><td align="center">
<form method="POST" action="<?=$thispage ?>">
<select name="person">
<option value="myself" SELECTED><?=$myself ?></option>
<?php
$SELECTMENU="";
for($i=0; $i<count($user->partners); $i++) {

	$id = $user->partners_id[$i];

	$SELECTMENU .= "<option value=\"";
	$SELECTMENU .= $id;
	$SELECTMENU .= "\">";
	$SELECTMENU .= $user->partners[$id]->username;
	$SELECTMENU .= "</option>";
}
?>
<?=$SELECTMENU ?></select> さんの
<select name="p_year">
<?php
$thisyear = intval(date("Y"));
for($i=0; $i<100; $i++) {
	$py = $thisyear + $i;
?>
<option value="<?=$py ?>"><?=$py ?></option>
<?php
}
?>
</select>
年
<SELECT name="p_month"><OPTION VALUE=1>1</OPTION><OPTION VALUE=2>2</OPTION><OPTION VALUE=3>3</OPTION><OPTION VALUE=4>4</OPTION><OPTION VALUE=5>5</OPTION><OPTION VALUE=6>6</OPTION><OPTION VALUE=7>7</OPTION><OPTION VALUE=8>8</OPTION><OPTION VALUE=9>9</OPTION><OPTION VALUE=10>10</OPTION><OPTION VALUE=11>11</OPTION><OPTION VALUE=12>12</OPTION></SELECT>
月
<SELECT name="p_day"><OPTION VALUE=1>1</OPTION><OPTION VALUE=2>2</OPTION><OPTION VALUE=3>3</OPTION><OPTION VALUE=4>4</OPTION><OPTION VALUE=5>5</OPTION><OPTION VALUE=6>6</OPTION><OPTION VALUE=7>7</OPTION><OPTION VALUE=8>8</OPTION><OPTION VALUE=9>9</OPTION><OPTION VALUE=10>10</OPTION><OPTION VALUE=11>11</OPTION><OPTION VALUE=12>12</OPTION><OPTION VALUE=13>13</OPTION><OPTION VALUE=14>14</OPTION><OPTION VALUE=15>15</OPTION><OPTION VALUE=16>16</OPTION><OPTION VALUE=17>17</OPTION><OPTION VALUE=18>18</OPTION><OPTION VALUE=19>19</OPTION><OPTION VALUE=20>20</OPTION><OPTION VALUE=21>21</OPTION><OPTION VALUE=22>22</OPTION><OPTION VALUE=23>23</OPTION><OPTION VALUE=24>24</OPTION><OPTION VALUE=25>25</OPTION><OPTION VALUE=26>26</OPTION><OPTION VALUE=27>27</OPTION><OPTION VALUE=28>28</OPTION><OPTION VALUE=29>29</OPTION><OPTION VALUE=30>30</OPTION><OPTION VALUE=31>31</OPTION></SELECT>
日頃の
<select name="p_type">
<option value="p_1">環境</option>
<option value="p_2<?php echo $user->sex ?>">結婚</option>
<option value="p_3">健康</option>
<option value="p_4">才能</option>
<option value="p_5">財産</option>
<option value="p_6">人生設計</option>
<option value="p_7">恋愛</option>
</select> について
 <input type="submit" name="submit" value="占う"></form></td></tr>

</table>

              <p><?=space2br(euc2utf($text))?>
            </p>
          </div>
        </div>
      </div>
      <!----↑HsMenu01---->
    </div>
    <!----↑Left Column---->
    <!----↓Right Column---->
<?=$right_column ?>
    <!----↑Right Column---->
    <div id="pageTopButtonArea">
    <ul>
      <li><a href="#pagetop">ページトップへ</a></li>
      </ul>
    </div>
  </div>
  <div class="clear"></div>
</div>
<!----↑Contents---->
</div>
<!----↓Footer---->
<?= $footer ?>
<!----↑Footer---->
</body>
</html>
