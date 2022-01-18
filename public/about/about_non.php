<!DOCTYPE html>
<html lang="ja">

<head>
	<title>細密占星術 | 星のめぐり合わせの注意</title>
	<meta charset="UTF-8">
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
	<meta http-equiv="Content-Style-Type" content="text/css;charset=UTF-8">
	<meta http-equiv="Content-Script-Type" content="text/javascript;charset=UTF-8">
	<meta name="generator" content="Miyaco Sakurahata">
	<link rel="stylesheet" href="css.css">
	<script src="jquery.js"></script>
</head>

<body>
	<div id="MembersGradient">
		<div id="MembersWaterdrop"></div>
	</div>
	<div id="MembersShadowedBox">
		<div id="MembersHeader">
			<div id="MembersTitle"><a href="toppage.php" id="toplink"></a></div>
		</div>
		<div id="MembersHoloscopemenuLine">
			ようこそ<?php echo $nick; ?>さん
			<a href="logout.php"><img src="./shared/images/common/bt_logout.jpg" /></a>
			<a href="edit.php"><img src="./shared/images/common/bt_edit.jpg" alt="" class="pl5" /></a>
			<?php echo $expiration_date; ?>
		</div>
		<div id="TopBreadcrumbs">
			<a href="toppage.php">トップ</a> ▶ 星のめぐり合わせの注意
		</div>
		<div id="GeneralBox">
			<h1 id="NoLoginContentTitle">星のめぐり合わせの注意</h1>
			<div id="DescriptionsContent">
				<p><span class="pt10 pb30">当サイトでは惑星の運行や惑星の取る角度を正確に計算し、<br />

						<br />
						正確なホロスコープによって占い結果を算出しております。<br />
						<br />
						そのため稀に<strong>「該当する星のめぐり合わせはありません」</strong><br />
						<br />
						との表記がされることがございます。
						<br />
						<br />
						これは、例えば天文学上、発生することのない星と星の角度、<br />
						<br />
						誕生時の星座と星の角度などを取る場合等に表示されます。<br />
						<br />
						ある程度の日数を経過しますと、存在しうる角度となり別の<br />
						<br />
						占い文が表示されます。このような仕様となっておりますことを<br />
						<br />
						ご了承いただけますよう、よろしくお願いいたします。<br />
						<br />
						なお、これらは今後変更されることもございます。
					</span></p>
			</div>
		</div>
		<footer id="FooterTop">
			<?php echo $footer; ?>
		</footer>
	</div>
</body>

</html>