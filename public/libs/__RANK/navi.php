<TABLE WIDTH=800 BORDER=0 CELLPADDING=0 CELLSPACING=0>
	<TR>
		<td align="center" class="topmenu" >
<a href="<?php echo $baseurl; ?>/index.php">HOME</a>
		</td>
		<td align="center" class="topmenu" >
<a href="<?php echo $baseurl; ?>/1f/index.php">今日/今週のこと</a>
		</td>
		<td align="center" class="topmenu" >
<a href="<?php echo $baseurl; ?>/2f/index.php">美容と健康</a>
		</td>
		<td align="center" class="topmenu" >
<a href="<?php echo $baseurl; ?>/3f/index.php">自分発見</a>
		</td>
		<td align="center" class="topmenu" >
<a href="<?php echo $baseurl; ?>/4f/index.php">LOVE&SEX</a>
		</td>
		<td align="center" class="topmenu" >
<a href="<?php echo $baseurl; ?>/5f/index.php">未来日記</a>
		</td>
		<td align="center" class="topmenu" >
<a href="<?php echo $baseurl; ?>/6f/index.php">星のメッセージ</a>
		</td>
		<td align="center" class="topmenu" >
<a href="<?php echo $baseurl; ?>/7f/index.php">仕事とお金</a>
		</td>
		<td align="center" class="topmenu" >
<a href="<?php echo $baseurl; ?>/premium_buy.php">プレミアム</a>
		</td>
		<td align="center" class="topmenu" >
<a href="<?php echo $baseurl; ?>/shop_index.php">ショッピング</a>
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
ようこそ！<b>$nc</b>さん。
| <a href="$baseurl/6f/index2.php"><b>あなたの運勢</b></a>
| <a href="$baseurl/1f/mail.php"><b>メール配信設定</b></a>
| <a href="$baseurl/userinfo.php"><b>登録情報</b></a>
| <a href="$baseurl/cart_show.php"><b>買い物かご</b></a>
| <a href="$baseurl/logout.php"><b>ログアウト</b></a>
EOF;
		$pointinfo = "所持ポイント:" . $user->point . "pts";
		$pointinfo .= '&nbsp;(<a href="' . $baseurl . '/point.php">詳細</a>)';
	} else {
		$pointinfo = "ゲスト:0pts";
		$nc = $user->base_nick;
$greeting  = "ようこそ！<b>$nc</b>さん。"; 
if(!$isTop) {
	$greetine .= "| <a href=\"$baseurl/index.php\"><b>ログイン</b></a> ";
}
$greeting .= "| <a href=\"$baseurl/6f/index2.php\"><b>あなたの運勢(無料)</b></a>";
$greeting .= "| <a href=\"$baseurl/cart_show.php\"><b>買い物かご</b></a>";
$greeting .= "| <a href=\"$baseurl/registration.html\"><b>新規登録する</b></a>";
	}
}
?>
		<td align="left" class="infobanner"><?php
echo $pointinfo;
?>		</td>
		<td align="right" class="infobanner" ><?php
echo $greeting;
?></TD></TR></TABLE>
