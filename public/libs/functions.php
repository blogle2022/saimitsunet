<?php

// statics

#デフォルト
$itemcode_array = array(
	"9002" => "3000ポイント",
	"9003" => "5000ポイント",
	"9004" => "8000ポイント",
	"9005" => "15000ポイント",
	"9006" => "1000ポイント"
);
#変更したらpci/SSLとCONTINUEも変更すること

#デフォルト
$itemcode_point = array(
	"9002" => 3000,
	"9003" => 5000,
	"9004" => 8000,
	"9005" => 15000,
	"9006" => 1000
);

$payment_array = array(
	"RSN" => "銀行振込",
	"MUF" => "銀行振込",
	"JNB" => "銀行振込",
	"PST" => "郵便振替"
);

$usertype_array = array(
	0 => "無料会員",
	1 => "有料会員",
	2 => "おためし会員"
);

function splitLongDateTime($str)
{
	$YYYY = substr($str, 0, 4);
	$MM = substr($str, 4, 2);
	$DD = substr($str, 6, 2);
	$HH = substr($str, 8, 2);
	$MN = substr($str, 10, 2);
	$SC = substr($str, 12, 2);
	return "$YYYY/$MM/$DD $HH:$MN:$SC";
}

##
## 赤枠で囲まれた文字列を出力する
##
function redRectText($str)
{
?>
	<table border="0" cellspacing="0" cellpadding="1">
		<tr>
			<td bgcolor="#FF0000">
				<table border="0" cellspacing="0" cellpadding="10">
					<tr>
						<td align="center" bgcolor="#ffffff">
							<div style="font-size: 12px; font-weight: bold;"><?php echo $str; ?></div>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
<?php
}


##
## シェルを起動しPIDを得る．一種の疑似乱数発生機です．
##
function getPid()
{
	exec('echo $$', $out);
	return $out[0];
}

##
## 余分なキャラクタを削除する
##
function stripLine($buf)
{
	$buf = preg_replace("/　/", " ", $buf);
	$buf = preg_replace("/ +/", "", $buf);
	$buf = preg_replace("/\n/", "", $buf);
	$buf = preg_replace("/\n/", "", $buf);
	$buf = preg_replace("/。/", "。<br>\n", $buf);
	$buf = preg_replace("/！/", "！<br>\n", $buf);
	$buf = preg_replace("/？/", "？<br>\n", $buf);
	return $buf;
}

##
## 問題を起こしそうな文字を削除する
##
function stripSuspiciousChars($str)
{
	$str = preg_replace("/\"/", "", $str);
	$str = preg_replace("/</", "", $str);
	$str = preg_replace("/>/", "", $str);
	return $str;
}

##
## GET/POSTのいずれであっても，変数名を指定することでhttpパラメータを得る関数
##
function httpValue($varname)
{
	global $_SERVER, $_GET, $_POST;
	$res = "";
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		$res = $_POST["$varname"];
		if (gettype($res) == "string") {
			$res = stripcslashes($res);
		}
	} else {
		if (isset($_GET["$varname"])) {
			$res = $_GET["$varname"];
		}
	}
	return $res;
}
##
## $startから$endまでの弧に$valueが含まれるかを判断
## 単純な大小比較に，360度(0度)をまたぐ場合の処理を追加している．
##
function radInclude($start, $end, $value)
{
	if ($end < $start) {
		$tmp = $start;
		$start = $end;
		$end = $tmp;
	}
	if (($end - $start) < 180) {
		if (($start < $value) && ($value < $end)) {
			return True;
		}
	} else {
		if (((0 < $value) && ($value < $start))
			||  (($end < $value) && ($value < 360))
		) {
			return True;
		}
	}
	return False;
}

##
## 上記radIncludeのエイリアス
##
function ri($start, $end, $value)
{
	return radInclude($start, $end, $value);
}

##
## $base角度に$valueを加算する
##
function radShift($base, $value)
{
	$result = $base + $value;
	if (360 <= $result) {
		$result -= 360;
	}
	if ($result < 0) {
		$result += 360;
	}
	return $result;
}

##
## 上記radShiftのエイリアス
##
function rs($base, $value)
{
	return radShift($base, $value);
}


##
## transit計算を行なう．
##
## ３つのhoroscopeを引数にとり，それぞれの星同士の角度を順番に比較します．
## weekly占いで使用します．
##   $horo=生まれたときのHoroscope
##   $horo2=明日のHoroscope
##   $horo3=1週間後のHoroscope
##
function transit($horo, $horo2, $horo3)
{

	## 現在の星
	$cplnts = array(
		"Sun", "Mercury", "Venus", "Mars", "Jupiter",
		"Saturn", "Uranus", "Neptune", "Pluto"
	);
	## 生まれた時の星
	$bplnts = array(
		"Sun", "Moon", "Mercury", "Venus", "Mars", "Jupiter",
		"Saturn", "Uranus", "Neptune", "Pluto"
	);
	$msgidx = 1;

	## 意味のある角度が現れたら，$mess配列にパラメータをpushする
	$mess = array();

	foreach ($cplnts as $cpl) {

		##
		## $deglは明日の星の位置
		## $deguは一週間後の星の位置
		##
		$p = $horo2->planets[$cpl];
		$degl = $p->absPosBySign;
		$p = $horo3->planets[$cpl];
		$degu = $p->absPosBySign;
		if ($degu < $degl) {
			$tmp = $degl;
			$degl = $degu;
			$degu = $tmp;
		}


		foreach ($bplnts as $bpl) {

			## $degは生まれた時の星の位置

			$p = $horo->planets[$bpl];
			$deg  = $p->absPosBySign;
			#echo "C:$cpl ($degl-$degu) : B:$bpl $deg<br>\n";
			#echo "$cpl\t$degl\t$degu\t$bpl\t$deg\n";

			if (ri($degl, $degu, $deg)) {
				##
				## $deglと$deguの間に$degがある場合，
				## 星と星が1週間以内に重なる位置関係になることを意味する
				##
				$mess[] = "$cpl,$degl,$degu,$bpl,$deg,0,$msgidx";
			}
			$msgidx++;
		}
		## ループを初期化しておく
		reset($bplnts);
		foreach ($bplnts as $bpl) {

			$p = $horo->planets[$bpl];
			$deg  = $p->absPosBySign;
			#echo "C:$cpl ($degl-$degu) : B:$bpl $deg<br>\n";


			##
			## 今回も$degは生まれた時の星の位置だが，
			## 180度，90度，-90度シフトさせた位置を計算する．
			##
			$opp = rs($deg, 180);
			$sq1 = rs($deg, 90);
			$sq2 = rs($deg, -90);

			##
			## それぞれ，今後一週間の星の動きと比較し，該当する位置を
			## とることがあれば，$messにパラメータをセットする
			##
			if (ri($degl, $degu, $opp)) {
				$mess[] = "$cpl,$degl,$degu,$bpl,$deg,180,$msgidx";
			}
			if (ri($degl, $degu, $sq1)) {
				$mess[] = "$cpl,$degl,$degu,$bpl,$deg,90,$msgidx";
			}
			if (ri($degl, $degu, $sq2)) {
				$mess[] = "$cpl,$degl,$degu,$bpl,$deg,90,$msgidx";
			}
			$msgidx++;
		}
		## 再度ループをリセット
		reset($bplnts);
		foreach ($bplnts as $bpl) {
			$p = $horo->planets[$bpl];
			$deg  = $p->absPosBySign;
			#echo "C:$cpl ($degl-$degu) : B:$bpl $deg<br>\n";
			##
			## 今回も$degは生まれた時の星の位置だが，
			## 120度，-120度，60度, -60度シフトさせた位置を計算する．
			##

			$tr1 = rs($deg, 120);
			$tr2 = rs($deg, -120);
			$sx1 = rs($deg, 60);
			$sx2 = rs($deg, -60);


			##
			## それぞれ，今後一週間の星の動きと比較し，該当する位置を
			## とることがあれば，$messにパラメータをセットする
			##
			if (ri($degl, $degu, $tr1)) {
				$mess[] = "$cpl,$degl,$degu,$bpl,$deg,120,$msgidx";
			}
			if (ri($degl, $degu, $tr2)) {
				$mess[] = "$cpl,$degl,$degu,$bpl,$deg,120,$msgidx";
			}
			if (ri($degl, $degu, $sx1)) {
				$mess[] = "$cpl,$degl,$degu,$bpl,$deg,60,$msgidx";
			}
			if (ri($degl, $degu, $sx2)) {
				$mess[] = "$cpl,$degl,$degu,$bpl,$deg,60,$msgidx";
			}
			$msgidx++;
		}
		reset($bplnts);
	}
	return $mess;
}




##
## 下のmatrix2()を使うこと
##
function matrix($horo, $degree, $error)
{
	$plnts = array(
		"Sun", "Moon", "Mercury", "Venus", "Mars", "Jupiter",
		"Saturn", "Uranus", "Neptune", "Pluto"
	);

	$rdegree = 360 - $degree;

	$idx = 1;
	$found = 0;
	$result = array();
	$mindif = 180;
	for ($i = 0; $i < 10; $i++) {
		if ($found) break;
		for ($j = $i + 1; $j < 10; $j++) {
			$i_absPos = $horo->planets["$plnts[$i]"]->absPos;
			$j_absPos = $horo->planets["$plnts[$j]"]->absPos;
			$asp = abs($i_absPos - $j_absPos);

			$dif = abs($degree - $asp);
			if ($dif < $mindif) {
				$mindif = $dif;
				$retval = array($plnts[$i], $plnts[$j], $idx);
			}
			$rdif = abs($rdegree - $asp);
			if ($rdif < $mindif) {
				$mindif = $rdif;
				$retval = array($plnts[$i], $plnts[$j], $idx);
			}
			$idx++;
		}
	}
	return $retval;
}

##
## Horoscopeオブジェクトと，角度，誤差を引数にとる
## Horoscopeに含まれるいずれかの星同士が$degreeで指定された角度をとるかどうか
## をチェックする
##
function matrix2($horo, $degree, $error)
{

	$error = 8;

	$plnts = array(
		"Sun", "Moon", "Mercury", "Venus", "Mars", "Jupiter",
		"Saturn", "Uranus", "Neptune", "Pluto"
	);

	$rdegree = 360 - $degree;

	$idx = 1;
	$found = 0;
	##$result = array("","",46);
	$mindif = 180;
	for ($i = 0; $i < 10; $i++) {
		if ($found) break;
		for ($j = $i + 1; $j < 10; $j++) {
			$i_absPos = $horo->planets["$plnts[$i]"]->absPos;
			$j_absPos = $horo->planets["$plnts[$j]"]->absPos;
			$asp = abs($i_absPos - $j_absPos);

			$dif = abs($degree - $asp);
			if ($dif < $mindif && $dif < $error) {
				$mindif = $dif;
				$retval = array($plnts[$i], $plnts[$j], $idx);
			}
			$rdif = abs($rdegree - $asp);
			if ($rdif < $mindif && $rdif < $error) {
				$mindif = $rdif;
				$retval = array($plnts[$i], $plnts[$j], $idx);
			}
			$idx++;
		}
	}
	return $retval;
}


##
## 角度$deg1, $deg2の差が$aspと同じかを判断．許容誤差は$errorで与える
## $deg1=30, $deg2=90, $asp=60 であれば，結果は真である．
##
function hasAspect($deg1, $deg2, $asp, $error)
{
	$low1 = $asp - $error;
	$high1 = $asp + $error;

	$low2 = (360 - $asp) - $error;
	$high2 = (360 - $asp) + $error;

	$dif = abs($deg1 - $deg2);

	if (
		(($low1 < $dif) && ($dif < $high1)) ||
		(($low2 < $dif) && ($dif < $high2))
	) {
		return True;
	}
	return False;
}


function isSquare($deg1, $deg2)
{
	return hasAspect($deg1, $deg2, 90, 8);
}

function isOpposite($deg1, $deg2)
{
	return hasAspect($deg1, $deg2, 180, 8);
}

function isSextile($deg1, $deg2)
{
	return hasAspect($deg1, $deg2, 60, 8);
}

function isTrine($deg1, $deg2)
{
	return hasAspect($deg1, $deg2, 120, 8);
}

##
## 有効なメールアドレスかどうかを簡易判定する
## 正式なRFC Compliantではありません．
##
function isValidEmail($mail)
{
	if (!preg_match(
		"/^([a-z0-9_]|\\-|\\.|\\+)+@(([a-z0-9_]|\\-)+\\.)+[a-z]{2,4}$/",
		$mail
	)) {
		return False;
	}
	return True;
}

##
## 与えられたメールアドレスが存在するかをチェックする
## mailはログインに用いるテーブル
## mail2はメールマガジン配送に用いるテーブル
## mob_mailは携帯向け配送に用いるテーブル
##
function search_id($srch, &$flg)
{
	global $db;
	$flg = 0;
	$n = $db->getOne("SELECT count(*) FROM mail WHERE mail='$srch' AND flg=1");
	if ($n != 0) $flg = 1;

	$n = $db->getOne("SELECT count(*) FROM mail2 WHERE mail='$srch' AND flg>0");
	if ($n != 0) $flg += 2;

	$n = $db->getOne("SELECT count(*) FROM mob_mail WHERE mail='$srch' AND flg=1");
	if ($n != 0) $flg += 4;

	return;
}

##
## 英数文字によるランダムパスワードを生成する
##
function generate($length)
{
	$validchars = 'abcdefghijkmnpqrstuvwxyz123456789';
	$password = '';
	// A}CNb100|A
	// B
	mt_srand((float) microtime() * 1000000);
	for ($i = 0; $i < $length; $i++) {
		$password .= substr($validchars, mt_rand(0, strlen($validchars)), 1);
		// LXg_IA
		// ApX[hB
	}
	return $password;
}



function FileRead($file)
{
	global $goback;
	$body = "";
	if (!($tmp = fopen("$file", 'r'))) {
		die;
	}
	while (!feof($tmp)) {
		$body .= fgets($tmp, 64000);
	}
	fclose($tmp);
	return $body;
}

##
## 2006年以前の旧システムで，ページのテンプレートによる生成をしていた名残
##
function HTML_replace($key, $val, $text)
{
	$ptn = "<% " . $key . " %>";
	$text = str_replace($ptn, "$val", $text);
	return $text;
}


##
## フォームのプルダウンメニューを表示する関数
## $optionsに連想配列を入れておくと，その値に従って生成する．
## $curで指定した値が"SELECTED"になる．
function selectMenu($name, $options, $cur)
{

	echo '<SELECT NAME="' . $name . '">';
	while (list($key, $val) = each($options)) {
		echo '<option value="' . $key . '"';
		if ($key == $cur) echo " SELECTED";
		echo '>' . $val . "</option>\n";
	}
	echo "</select>";
}



function errormsg($str)
{
?>
	<table border="0" cellpadding="1" cellspacing="1">
		<tr>
			<td bgcolor="#efefef"><b>Error: </b>
				<font color="#ff6666">
					<?
					echo $str;
					?>
				</font>
			</td>
		</tr>
	</table>
<?
}


function successmsg($str)
{
?>
	<table border="0" cellpadding="1" cellspacing="1">
		<tr>
			<td bgcolor="#efefef"><b>OK: </b>
				<?
				echo $str;
				?>
			</td>
		</tr>
	</table>
<?
}

function nextNMonth($n)
{
	// 現在から$nか月後の年月を配列で返す
	$y = (int) date("Y");
	$m = (int) date("m");

	$new_month = $m + $n;
	$new_year = $y;

	for ($new_month = $m + $n; 12 < $new_month; $new_month -= 12) {
		$new_year++;
	}
	return array($new_year, $new_month);
}
##
## 与えられた日付文字列の1年後を返す．
## 引数がない場合は，本日の1年後を返す．
##
function yearAfter($datestr = "")
{
	if ($datestr == "") {
		$t = time();
		$datY = date("Y", $t);
		$datM = date("m", $t);
		$datD = date("d", $t);
	} else {
		$datY = substr($datestr, 0, 4);
		$datM = substr($datestr, 4, 2);
		$datD = substr($datestr, 6, 2);
	}
	$after = date("Ymd", mktime(0, 0, 0, $datM, $datD - 1, $datY + 1));
	return $after;
}

##
## 与えられた日付文字列 YYYYMMDD を YYYY年MM月DD日に変換するだけです
##
function splitDateStr($datestr)
{
	$datY = substr($datestr, 0, 4);
	$datM = substr($datestr, 4, 2);
	$datD = substr($datestr, 6, 2);
	$str = $datY . "年" . $datM . "月" . $datD . "日";
	return $str;
}


define('SECOND_DAY', 60 * 60 * 24);
function smktime($hour = 0, $minute = 0, $second = 0, $month = 0, $day = 0, $year = 0, $dst = 0)
{
	if ($year >= 1970 || mktime(0, 0, 0, 1, 1, 1969) == -31568400) {
		$ret = mktime($hour, $minute, $second, $month, $day, $year);
	} else {
		$base = 1972 + ($year % 4);
		$_y = $base - $year;
		$ret = mktime($hour, $minute, $second, $month, $day, $base);
		$ret = $ret - ($_y * SECOND_DAY * 365) - (ceil($_y / 4) * SECOND_DAY);
	}
	return $ret;
}


function HTML_output($html, $convert = 0)
{
	global $_SERVER;
	global $user;
	global $auth;
	global $targetname;
	global $expiration_date;
	global $horo;
	global $content_base;


	//include("/home/saimitsu/htdocs/pc/parts/loginform");

	##
	## $convertフラグが真の場合、文字コードを変換する。
	##
	//if($convert==1) $html = mb_convert_encoding($html, "SJIS", "EUC");
	if ($convert == 1) $html = mb_convert_encoding($html, "SJIS", "UTF8");

	$html = HTML_replace("HEADER", $header, $html);
	$html = HTML_replace("HEADER2", $header2, $html);
	$html = HTML_replace("HEADER3", $header3, $html);
	//$html = HTML_replace("LOGINFORM", $loginform, $html);
	$html = HTML_replace("MENU1", $menu1, $html);
	$html = HTML_replace("MENU2", $menu2, $html);
	$html = HTML_replace("MENU3", $menu3, $html);
	$html = HTML_replace("MENU4", $menu4, $html);
	$html = HTML_replace("MENU5", $menu5, $html);
	$html = HTML_replace("MENU6", $menu6, $html);
	$html = HTML_replace("FOOTER", $footer, $html);
	$html = HTML_replace("FOOTER2", $footer2, $html);
	$html = HTML_replace("USERNAME", $user->base_nick, $html);
	$html = HTML_replace("KIHON", $kihon, $html);
	$html = HTML_replace("KIHON2", $kihon2, $html);
	$html = HTML_replace("KIHON3", $kihon3, $html);
	$html = HTML_replace("NAVI", $navi, $html);
	$html = HTML_replace("NAVI2", $navi2, $html);
	$html = HTML_replace("SELECTMENU",  mb_convert_encoding($SELECTMENU, "UTF-8", "EUC"), $html);
	$html = HTML_replace("SELECTMENU2",  mb_convert_encoding($SELECTMENU2, "UTF-8", "EUC"), $html);
	if (!$targetname) $targetname = $user->base_nick;
	$html = HTML_replace("TARGET", mb_convert_encoding($targetname, "UTF-8", "EUC"), $html);
	$html = HTML_replace("HOROSCOPE", $horo, $html);

	##
	## 出力
	##
	echo $html;
}
function calcVirtualAspect($deg1, $deg2)
{

	$deg1 = floatval($deg1);
	$deg2 = floatval($deg2);
	if ($deg1 < 0) $deg1 += 360;
	if ($deg2 < 0) $deg2 += 360;

	$avg = ($deg1 + $deg2) / 2;
	if (abs($avg - $deg1) > 90 || abs($avg - $deg2)) $avg -= 180;
	if ($avg < 0) $avg += 360;

	return $avg;
}



function virtualHoro($horo1, $horo2)
{

	$virtualAspect = array();
	$plnts = array(
		"Sun", "Moon", "Mercury", "Venus",
		"Mars", "Jupiter", "Saturn", "Uranus",
		"Neptune", "Pluto"
	);
	$horo1->getAbsDegreeBySign();
	$horo2->getAbsDegreeBySign();

	foreach ($plnts as $pl) {
		$virtualAspect[$pl] = calcVirtualAspect($horo1->planets[$pl]->absPosBySign, $horo2->planets[$pl]->absPosBySign);
	}
	return $virtualAspect;
}
function _NOcalcMatch($Sun0, $Sun1, $Planet0, $Planet1)
{
	if (isSquare($Sun0, $Sun1)) $x = 0;
	else if (isOpposite($sun0, $Sun1)) $x = 1;
	else if (isSextile($Sun0, $Sun1))  $x = 2;
	else if (isTrine($Sun0, $Sun1)) $x = 3;
	else $x = 4;


	if (isSquare($Planet0, $Planet1)) $y = 0;
	else if (isOpposite($Planet0, $Planet1)) $y = 1;
	else if (isSextile($Planet0, $Planet1)) $y = 2;
	else if (isTrine($Planet0, $Planet1)) $y = 3;
	else $y = 4;

	$matrix = array(
		"0" => array("0" => "A", "1" => "A", "2" => "C", "3" => "C", "4" => "A"),
		"1" => array("0" => "A", "1" => "A", "2" => "C", "3" => "C", "4" => "A"),
		"2" => array("0" => "C", "1" => "C", "2" => "B", "3" => "B", "4" => "B"),
		"3" => array("0" => "C", "1" => "C", "2" => "B", "3" => "B", "4" => "B"),
		"4" => array("0" => "A", "1" => "A", "2" => "B", "3" => "B", "4" => "C")
	);
	$idx[0] = $matrix[$x][$y];

	if (isSquare($Sun0, $Planet1) || isOpposite($Sun0, $Planet1)) $idx[1] = "A";
	else if (isSextile($Sun0, $Planet1) || isTrine($Sun0, $Planet1)) $idx[1] = "B";
	else $idx[1] = "C";

	if (isSquare($Sun1, $Planet0) || isOpposite($Sun1, $Planet0)) $idx[2] = "A";
	else if (isSextile($Sun1, $Planet0) || isTrine($Sun1, $Planet0)) $idx[2] = "B";
	else $idx[2] = "C";

	$s["A"]["A"]["A"] = 1;
	$s["A"]["A"]["C"] = 2;
	$s["A"]["A"]["B"] = 3;
	$s["A"]["C"]["A"] = 4;
	$s["A"]["C"]["C"] = 5;
	$s["A"]["C"]["B"] = 6;
	$s["A"]["B"]["A"] = 7;
	$s["A"]["B"]["C"] = 8;
	$s["A"]["B"]["B"] = 9;
	$s["B"]["B"]["B"] = 10;
	$s["B"]["B"]["C"] = 11;
	$s["B"]["B"]["A"] = 12;
	$s["B"]["C"]["B"] = 13;
	$s["B"]["C"]["C"] = 14;
	$s["B"]["C"]["A"] = 15;
	$s["B"]["A"]["B"] = 16;
	$s["B"]["A"]["C"] = 17;
	$s["B"]["A"]["A"] = 18;
	$s["C"]["B"]["B"] = 19;
	$s["C"]["B"]["C"] = 20;
	$s["C"]["B"]["A"] = 21;
	$s["C"]["C"]["B"] = 22;
	$s["C"]["C"]["C"] = 23;
	$s["C"]["C"]["A"] = 24;
	$s["C"]["A"]["B"] = 25;
	$s["C"]["A"]["C"] = 26;
	$s["C"]["A"]["A"] = 27;


	return $s["$idx[0]"]["$idx[1]"]["$idx[2]"];
}

function splitDateTimeStr($str)
{
	$vars = array();
	$vars[0] = substr($str, 0, 4);
	$vars[1] = substr($str, 4, 2);
	$vars[2] = substr($str, 6, 2);
	$vars[3] = substr($str, 8, 2);
	$vars[4] = substr($str, 10, 2);
	$vars[5] = substr($str, 12, 2);
	return $vars;
}

function splitDateTimeStrText($str)
{
	$vars = array();
	$vars[0] = substr($str, 0, 4);
	$vars[1] = substr($str, 4, 2);
	$vars[2] = substr($str, 6, 2);
	$vars[3] = substr($str, 8, 2);
	$vars[4] = substr($str, 10, 2);
	$vars[5] = substr($str, 12, 2);
	$expiration_date =
		$vars[0] . "/" .
		$vars[1] . "/" .
		$vars[2] . " " .
		$vars[3] . ":" .
		$vars[4] . ":" .
		$vars[5];
	return $expiration_date;
}


function calcMatch($Sun0, $Sun1, $Planet0, $Planet1)
{

	##echo intval($Sun0) . "<br>";
	##echo intval($Sun1) . "<br>";
	##echo intval($Planet0) . "<br>";
	##echo intval($Planet1) . "<br><br>";
	#echo "<!--";
	#echo "$Sun0, $Sun1, $Planet0, $Planet1";
	#echo "-->";
	if (isSquare($Sun0, $Sun1)) $x = 0;
	else if (isOpposite($Sun0, $Sun1)) $x = 1;
	else if (isSextile($Sun0, $Sun1))  $x = 2;
	else if (isTrine($Sun0, $Sun1)) $x = 3;
	else $x = 4;


	if (isSquare($Planet0, $Planet1)) $y = 0;
	else if (isOpposite($Planet0, $Planet1)) $y = 1;
	else if (isSextile($Planet0, $Planet1)) $y = 2;
	else if (isTrine($Planet0, $Planet1)) $y = 3;
	else $y = 4;

	$matrix = array(
		"0" => array("0" => "A", "1" => "A", "2" => "C", "3" => "C", "4" => "A"),
		"1" => array("0" => "A", "1" => "A", "2" => "C", "3" => "C", "4" => "A"),
		"2" => array("0" => "C", "1" => "C", "2" => "B", "3" => "B", "4" => "B"),
		"3" => array("0" => "C", "1" => "C", "2" => "B", "3" => "B", "4" => "B"),
		"4" => array("0" => "A", "1" => "A", "2" => "B", "3" => "B", "4" => "C")
	);
	$idx[0] = $matrix[$x][$y];
	if (isSquare($Sun0, $Planet1) || isOpposite($Sun0, $Planet1)) $idx[1] = "A";
	else if (isSextile($Sun0, $Planet1) || isTrine($Sun0, $Planet1)) $idx[1] = "B";
	else $idx[1] = "C";

	if (isSquare($Sun1, $Planet0) || isOpposite($Sun1, $Planet0)) $idx[2] = "A";
	else if (isSextile($Sun1, $Planet0) || isTrine($Sun1, $Planet0)) $idx[2] = "B";
	else $idx[2] = "C";

	$s["A"]["A"]["A"] = 1;
	$s["A"]["A"]["C"] = 2;
	$s["A"]["A"]["B"] = 3;
	$s["A"]["C"]["A"] = 4;
	$s["A"]["C"]["C"] = 5;
	$s["A"]["C"]["B"] = 6;
	$s["A"]["B"]["A"] = 7;
	$s["A"]["B"]["C"] = 8;
	$s["A"]["B"]["B"] = 9;
	$s["B"]["B"]["B"] = 10;
	$s["B"]["B"]["C"] = 11;
	$s["B"]["B"]["A"] = 12;
	$s["B"]["C"]["B"] = 13;
	$s["B"]["C"]["C"] = 14;
	$s["B"]["C"]["A"] = 15;
	$s["B"]["A"]["B"] = 16;
	$s["B"]["A"]["C"] = 17;
	$s["B"]["A"]["A"] = 18;
	$s["C"]["B"]["B"] = 19;
	$s["C"]["B"]["C"] = 20;
	$s["C"]["B"]["A"] = 21;
	$s["C"]["C"]["B"] = 22;
	$s["C"]["C"]["C"] = 23;
	$s["C"]["C"]["A"] = 24;
	$s["C"]["A"]["B"] = 25;
	$s["C"]["A"]["C"] = 26;
	$s["C"]["A"]["A"] = 27;


	return $s["$idx[0]"]["$idx[1]"]["$idx[2]"];
}

function euc2utf($str)
{
	$str = mb_convert_encoding($str, "UTF-8", "EUC");
	return $str;
}

function utf2euc($str)
{
	$str = mb_convert_encoding($str, "EUC", "UTF-8");
	return $str;
}

function space2br($str)
{
	$str = preg_replace("/　+/u", "<br>", $str);
	return $str;
}

function compareDate($year1, $month1, $day1, $year2, $month2, $day2)
{
	$dt1 = mktime(0, 0, 0, $month1, $day1, $year1);
	$dt2 = mktime(0, 0, 0, $month2, $day2, $year2);
	$diff = $dt2 - $dt1;
	$diffDay = $diff / 86400; //1日は86400秒
	return $diffDay;
}

function computeDate($year, $month, $day, $addDays)
{
	$baseSec = mktime(0, 0, 0, $month, $day, $year); //基準日を秒で取得
	$addSec = $addDays * 86400; //日数×１日の秒数
	$targetSec = $baseSec + $addSec;
	return date("Y-m-d H:i:s", $targetSec);
}
