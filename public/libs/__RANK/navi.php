<TABLE WIDTH=800 BORDER=0 CELLPADDING=0 CELLSPACING=0>
	<TR>
		<td align="center" class="topmenu" >
<a href="<?php echo $baseurl; ?>/index.php">HOME</a>
		</td>
		<td align="center" class="topmenu" >
<a href="<?php echo $baseurl; ?>/1f/index.php">����/�����Τ���</a>
		</td>
		<td align="center" class="topmenu" >
<a href="<?php echo $baseurl; ?>/2f/index.php">���Ƥȷ�</a>
		</td>
		<td align="center" class="topmenu" >
<a href="<?php echo $baseurl; ?>/3f/index.php">��ʬȯ��</a>
		</td>
		<td align="center" class="topmenu" >
<a href="<?php echo $baseurl; ?>/4f/index.php">LOVE&SEX</a>
		</td>
		<td align="center" class="topmenu" >
<a href="<?php echo $baseurl; ?>/5f/index.php">̤������</a>
		</td>
		<td align="center" class="topmenu" >
<a href="<?php echo $baseurl; ?>/6f/index.php">���Υ�å�����</a>
		</td>
		<td align="center" class="topmenu" >
<a href="<?php echo $baseurl; ?>/7f/index.php">�Ż��Ȥ���</a>
		</td>
		<td align="center" class="topmenu" >
<a href="<?php echo $baseurl; ?>/premium_buy.php">�ץ�ߥ���</a>
		</td>
		<td align="center" class="topmenu" >
<a href="<?php echo $baseurl; ?>/shop_index.php">����åԥ�</a>
		</td>
	</TR>
</TABLE>
<TABLE WIDTH=800 BORDER=0 CELLPADDING=0 CELLSPACING=0>
	<TR>
<?php
if(is_object($user)) {
	if(get_class($user) == 'user') {
		//list($b_y, $b_m, $b_d) = explode("-", $user->base_born);
		//$birth_utime = mktime(0,0,0,$b_m,$b_d,$b_y,0);
		//$today_utime = date("U");
		//$elapsed = $today_utime - $birth_utime;
		//$t_y = date("Y",$today_utime);
		//$t_m = date("n",$today_utime);
		//$t_d = date("j",$today_utime);

		$nc = $user->base_nick;
		$edays = intval($elapsed/86400);
$greeting = <<<EOF
�褦������<b>$nc</b>����
| <a href="$baseurl/6f/index2.php"><b>���ʤ��α���</b></a>
| <a href="$baseurl/1f/mail.php"><b>�᡼���ۿ�����</b></a>
| <a href="$baseurl/userinfo.php"><b>��Ͽ����</b></a>
| <a href="$baseurl/cart_show.php"><b>�㤤ʪ����</b></a>
| <a href="$baseurl/logout.php"><b>��������</b></a>
EOF;
		$pointinfo = "����ݥ����:" . $user->point . "pts";
		$pointinfo .= '&nbsp;(<a href="' . $baseurl . '/point.php">�ܺ�</a>)';
	} else {
		$pointinfo = "������:0pts";
		$nc = $user->base_nick;
$greeting  = "�褦������<b>$nc</b>����"; 
if(!$isTop) {
	$greetine .= "| <a href=\"$baseurl/index.php\"><b>������</b></a> ";
}
$greeting .= "| <a href=\"$baseurl/6f/index2.php\"><b>���ʤ��α���(̵��)</b></a>";
$greeting .= "| <a href=\"$baseurl/cart_show.php\"><b>�㤤ʪ����</b></a>";
$greeting .= "| <a href=\"$baseurl/registration.html\"><b>������Ͽ����</b></a>";
	}
}
?>
		<td align="left" class="infobanner"><?php
echo $pointinfo;
?>		</td>
		<td align="right" class="infobanner" ><?php
echo $greeting;
?></TD></TR></TABLE>
