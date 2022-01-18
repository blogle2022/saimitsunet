<?php

##
## ���ȤǰϤޤ줿ʸ�������Ϥ���
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
## �������ư��PID�����롥���ε������ȯ�����Ǥ���
##
function getPid()
{
	exec('echo $$', $out);
	return $out[0];
}

##
## ;ʬ�ʥ���饯����������
##
function stripLine($buf)
{
	$buf = ereg_replace("��", " ", $buf);
	$buf = ereg_replace(" +", "", $buf);
	$buf = ereg_replace("\n", "", $buf);
	$buf = ereg_replace("\n", "", $buf);
	$buf = ereg_replace("��", "��<br>\n", $buf);
	return $buf;
}

##
## ����򵯤���������ʸ����������
##
function stripSuspiciousChars($str)
{
	$str = ereg_replace("\"", "", $str);
	$str = ereg_replace("<", "", $str);
	$str = ereg_replace(">", "", $str);
	return $str;
}

##
## GET/POST�Τ�����Ǥ��äƤ⡤�ѿ�̾����ꤹ�뤳�Ȥ�http�ѥ�᡼��������ؿ�
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
		$res = $_GET["$varname"];
	}
	##
	## �ʰ�SQL���󥸥���������к��ΤҤȤ�
	## ";" �� SQL���ơ��ȥ��Ȥ�Ʊ�路�Ƥ���������Ȥߤʤ���";"��������
	##
	if (ereg(";", $res)) {
		if (
			eregi("update ", $res)
			|| eregi("delete ", $res)
			|| eregi("drop ", $res)
			|| eregi("create ", $res)
		) {
			ereg_replace(";", "", $res);
		}
	}
	return $res;
}
##
## $start����$end�ޤǤθ̤�$value���ޤޤ�뤫��Ƚ��
## ñ����羮��Ӥˡ�360��(0��)��ޤ������ν������ɲä��Ƥ��롥
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
## �嵭radInclude�Υ����ꥢ��
##
function ri($start, $end, $value)
{
	return radInclude($start, $end, $value);
}

##
## $base���٤�$value��û�����
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
## �嵭radShift�Υ����ꥢ��
##
function rs($base, $value)
{
	return radShift($base, $value);
}


##
## transit�׻���Ԥʤ���
##
## ���Ĥ�horoscope������ˤȤꡤ���줾�����Ʊ�Τγ��٤���֤���Ӥ��ޤ���
## weekly�ꤤ�ǻ��Ѥ��ޤ���
##   $horo=���ޤ줿�Ȥ���Horoscope
##   $horo2=������Horoscope
##   $horo3=1���ָ��Horoscope
##
function transit($horo, $horo2, $horo3)
{

	## ���ߤ���
	$cplnts = array(
		"Sun", "Mercury", "Venus", "Mars", "Jupiter",
		"Saturn", "Uranus", "Neptune", "Pluto"
	);
	## ���ޤ줿������
	$bplnts = array(
		"Sun", "Moon", "Mercury", "Venus", "Mars", "Jupiter",
		"Saturn", "Uranus", "Neptune", "Pluto"
	);
	$msgidx = 1;

	## ��̣�Τ�����٤����줿�顤$mess����˥ѥ�᡼����push����
	$mess = array();

	foreach ($cplnts as $cpl) {

		##
		## $degl�����������ΰ���
		## $degu�ϰ콵�ָ�����ΰ���
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

			## $deg�����ޤ줿�������ΰ���

			$p = $horo->planets[$bpl];
			$deg  = $p->absPosBySign;
			#echo "C:$cpl ($degl-$degu) : B:$bpl $deg<br>\n";
			#echo "$cpl\t$degl\t$degu\t$bpl\t$deg\n";

			if (ri($degl, $degu, $deg)) {
				##
				## $degl��$degu�δ֤�$deg�������硤
				## ��������1���ְ���˽Ťʤ���ִط��ˤʤ뤳�Ȥ��̣����
				##
				$mess[] = "$cpl,$degl,$degu,$bpl,$deg,0,$msgidx";
			}
			$msgidx++;
		}
		## �롼�פ��������Ƥ���
		reset($bplnts);
		foreach ($bplnts as $bpl) {

			$p = $horo->planets[$bpl];
			$deg  = $p->absPosBySign;
			#echo "C:$cpl ($degl-$degu) : B:$bpl $deg<br>\n";


			##
			## �����$deg�����ޤ줿�������ΰ��֤�����
			## 180�١�90�١�-90�٥��եȤ��������֤�׻����롥
			##
			$opp = rs($deg, 180);
			$sq1 = rs($deg, 90);
			$sq2 = rs($deg, -90);

			##
			## ���줾�졤����콵�֤�����ư������Ӥ�������������֤�
			## �Ȥ뤳�Ȥ�����С�$mess�˥ѥ�᡼���򥻥åȤ���
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
		## ���٥롼�פ�ꥻ�å�
		reset($bplnts);
		foreach ($bplnts as $bpl) {
			$p = $horo->planets[$bpl];
			$deg  = $p->absPosBySign;
			#echo "C:$cpl ($degl-$degu) : B:$bpl $deg<br>\n";
			##
			## �����$deg�����ޤ줿�������ΰ��֤�����
			## 120�١�-120�١�60��, -60�٥��եȤ��������֤�׻����롥
			##

			$tr1 = rs($deg, 120);
			$tr2 = rs($deg, -120);
			$sx1 = rs($deg, 60);
			$sx2 = rs($deg, -60);


			##
			## ���줾�졤����콵�֤�����ư������Ӥ�������������֤�
			## �Ȥ뤳�Ȥ�����С�$mess�˥ѥ�᡼���򥻥åȤ���
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
## ����matrix2()��Ȥ�����
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
## Horoscope���֥������Ȥȡ����١�����������ˤȤ�
## Horoscope�˴ޤޤ�뤤���줫����Ʊ�Τ�$degree�ǻ��ꤵ�줿���٤�Ȥ뤫�ɤ���
## ������å�����
##
function matrix2($horo, $degree, $error)
{

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
## ����$deg1, $deg2�κ���$asp��Ʊ������Ƚ�ǡ����Ƹ�����$error��Ϳ����
## $deg1=30, $deg2=90, $asp=60 �Ǥ���С���̤Ͽ��Ǥ��롥
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
## ͭ���ʥ᡼�륢�ɥ쥹���ɤ�����ʰ�Ƚ�ꤹ��
## ������RFC Compliant�ǤϤ���ޤ���
##
function isValidEmail($mail)
{
	if (!eregi(
		"^([a-z0-9_]|\\-|\\.)+@(([a-z0-9_]|\\-)+\\.)+[a-z]{2,4}$",
		$mail
	)) {
		return False;
	}
	return True;
}

##
## Ϳ����줿�᡼�륢�ɥ쥹��¸�ߤ��뤫������å�����
## mail�ϥ���������Ѥ���ơ��֥�
## mail2�ϥ᡼��ޥ������������Ѥ���ơ��֥�
## mob_mail�Ϸ��Ӹ����������Ѥ���ơ��֥�
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
## �ѿ�ʸ���ˤ�������ѥ���ɤ���������
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



##
## 2006ǯ�����ε쥷���ƥ�ǡ��ڡ����Υƥ�ץ졼�Ȥˤ�������򤷤Ƥ���̾��
##
function HTML_replace($key, $val, $text)
{
	$ptn = "<% " . $key . " %>";
	$text = str_replace($ptn, "$val", $text);
	return $text;
}


##
## �ե�����Υץ�������˥塼��ɽ������ؿ�
## $options��Ϣ�����������Ƥ����ȡ������ͤ˽��ä��������롥
## $cur�ǻ��ꤷ���ͤ�"SELECTED"�ˤʤ롥
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
	// ���ߤ���$n������ǯ���������֤�
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
## Ϳ����줿����ʸ�����1ǯ����֤���
## �������ʤ����ϡ�������1ǯ����֤���
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
## Ϳ����줿����ʸ���� YYYYMMDD �� YYYYǯMM��DD�����Ѵ���������Ǥ�
##
function splitDateStr($datestr)
{
	$datY = substr($datestr, 0, 4);
	$datM = substr($datestr, 4, 2);
	$datD = substr($datestr, 6, 2);
	$str = $datY . "ǯ" . $datM . "��" . $datD . "��";
	return $str;
}


define(SECOND_DAY, 60 * 60 * 24);
function smktime($hour = 0, $minute = 0, $second = 0, $month = 0, $day = 0, $year = 0, $dst = 0)
{
	if ($year >= 1970 || mktime(0, 0, 0, 1, 1, 1969) == -31568400) {
		$ret = mktime($hour, $minute, $second, $month, $day, $year, $dst);
	} else {
		$base = 1972 + ($year % 4);
		$_y = $base - $year;
		$ret = mktime($hour, $minute, $second, $month, $day, $base);
		$ret = $ret - ($_y * SECOND_DAY * 365) - (ceil($_y / 4) * SECOND_DAY);
	}
	return $ret;
}



?>