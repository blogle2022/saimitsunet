<?php
require_once public_path() . "/libs/dbconn.php";
require_once public_path() . "/libs/user.php";
require_once public_path() . "/libs/functions.php";
$auth = 0;

$p = intval(httpValue("p"));

if ($p == 0) {
	$p = 1;
}

if (!isset($_SESSION['user'])) {
	/**
	 * セッション変数UIDが登録されていない場合は、
	 * ゲストログインを行う。
	 * 通常ユーザのUID: int(11)
	 * ゲストのUID: t+TIMESTAMP+DUMMYPID
	 **/
	$anonuid = 't' . time() . getPid();
	$sessid = md5(uniqid("AFDSDAE"));
	$_SESSION['uid'] = $anonuid;
}

if (!isset($_SESSION['user'])) {
	/**
	 * tで始まるUIDであればゲストログイン
	 **/
	$user = new anonUser($_SESSION['uid']);
	$user->loadbase();
	$user->loadmail();
	$auth = 0;
} else {
	/**
	 * 数字のみであれば通常ログイン
	 **/
	$user = new User($_SESSION['uid']);
	$user->loadbase();
}

$payment_needed = <<< EOM
<center>このコンテンツのご利用には、有料会員登録が必要です。
<br>
<br>
下記ボタンより会費を購入してください。
<br>
<a href="point.php"><img border="0" src="shared/images/sign_up/bt_pay.jpg"></a></center>
EOM;

if (isset($user->registered) && $user->registered) {
	$nick = htmlspecialchars($user->base_nick);
} else {
	$nick = "ゲスト";
}


$save_user = isset($_COOKIE["save_user"]) ? htmlspecialchars($_COOKIE["save_user"]) : null;
$save_pass = isset($_COOKIE["save_pass"]) ? htmlspecialchars($_COOKIE["save_pass"]) : null;

$myself = $user->base_nick;
$thispage = $_SERVER["SCRIPT_NAME"];

$SELECTMENU = <<< EOF
<form method="POST" action="$thispage">
<input type="hidden" name="p" value="$p">
<select name="person">
<option value="myself" SELECTED>$myself</option>
EOF;

if (isset($user->partners)) {
	for ($i = 0; $i < count($user->partners); $i++) {

		$id = $user->partners_id[$i];

		$SELECTMENU .= "<option value=\"";
		$SELECTMENU .= $id;
		$SELECTMENU .= "\">";
		$SELECTMENU .= $user->partners[$id]->username;
		$SELECTMENU .= "</option>";
	}
} else {
	$SELECTMENU .= "<option>対象者が登録されていません<\/option>";
}

$SELECTMENU .= <<< EOF
</select> さんを <div id="SubmitButton" class="SubmitButton" style="display: inline-block; _display: inline;"><input type="submit" value="占う"></div></form>
EOF;


$right_column = <<<EOM
    <div class="right">
      <div class="banner"><img src="images/topmenu/right_uranaimenu.jpg" /></div>
      <div class="banner"><a href="./point.php"><img src="images/topmenu/right_kaiin.jpg" /></a></div>
      <!--<div class="banner"><a href="./persons_list.php"><img src="images/topmenu/bt_partner.jpg" /></a></div>-->
      <div class="banner2"><img src="images/topmenu/right_mikata.jpg" /></div>
      <div class="banner">
               <a href="about_01.php"><img border="0" src="images/topmenu/mikata_1.jpg" /></a><br />
	  <a href="about_02.php"><img border="0" src="images/topmenu/mikata_2.jpg" /></a><br />
	  <a href="about_03.php"><img border="0" src="images/topmenu/mikata_3.jpg" /></a><br />
	  <a href="about_04.php"><img border="0" src="images/topmenu/mikata_4.jpg" /></a><br />
	  <a href="about_05.php"><img border="0" src="images/topmenu/mikata_5.jpg" /></a><br />
	  <a href="about_06.php"><img border="0" src="images/topmenu/mikata_6.jpg" /></a><br />
	  <a href="about_07.php"><img border="0" src="images/topmenu/mikata_7.jpg" /></a><br />
  	  <a href="about_08.php"><img border="0" src="images/topmenu/mikata_8.jpg" /></a><br />
	  </div>
  	  <div class="banner"><a href="/itokawa"><img src="images/topmenu/mitoh_banner02.jpg" /></a></div>

    </div>
EOM;


$left_column = <<<EOM
	<p id="ColumnLeftMenu0"></p>
	<p id="ColumnLeftMenu0b"></p>
		<ul>
			<li class="ColumnLeftMenuColor1"><img src="new.png" align="absmiddle"><a href="column_power003.php">新潟･弥彦神社と弥彦山</a></li>
			<li class="ColumnLeftMenuColor1"><a href="column_fortune003.php">星座のおさらい</a></li>
			<li class="ColumnLeftMenuColor1"><a href="column_lifestyle002.php">ベランダとガーデニング</a></li>
			<li class="ColumnLeftMenuColor1"><a href="column_power002.php">島根･出雲大社</a></li>
			<li class="ColumnLeftMenuColor1"><a href="column_fortune002.php">アスペクトとは</a></li>
			<li class="ColumnLeftMenuColor1"><a href="column_power001.php">京都･音羽の滝</a></li>
			<li class="ColumnLeftMenuColor1"><a href="column_lifestyle001.php">冬場のお風呂</a></li>
			<li class="ColumnLeftMenuColor1"><a href="column_fortune001.php">ホロスコープが表すこと</a></li>
		</ul>
EOM;

$SELECTMENU2 = <<< EOF
<form method="POST" action="$thispage">
<input type="hidden" name="p" value="$p">
<select name="person">
EOF;
if (isset($user->partners)) {
	for ($i = 0; $i < count($user->partners); $i++) {

		$id = $user->partners_id[$i];

		$SELECTMENU2 .= "<option value=\"";
		$SELECTMENU2 .= $id;
		$SELECTMENU2 .= "\">";
		$SELECTMENU2 .= $user->partners[$id]->username;
		$SELECTMENU2 .= "</option>";
	}
} else {
	$SELECTMENU .= "<option>対象者が登録されていません<\/option>";
}

$SELECTMENU2 .= <<< EOF
</select> さんとの運勢を <div id="SubmitButton" class="SubmitButton" style="display: inline-block; _display: inline;"><input type="submit" value="占う"></div></form>
EOF;


$footer = <<< EOF
<div id="footerArea">
  <div id="footer">
    <div class="left"> | <a href="rules.php">利用規約</a> | <a href="commercial.php">特定商取引法に基づく表記</a> | <a href="privacy.php">プライバシーポリシー</a> | <a href="company.php">運営者概要</a> | <a href="mailto:info@saimitsu.net">お問い合わせ</a> | <a href="unregister.php">退会</a> |</div>
    <div class="right">お問い合わせに当たっては「saimitsu.net」からのメール受信設定をお願いいたします。</div>
    <br>
    <div class="right"> c 2019 細密占星術運営委員会 All rights Reserved.</div>
    <div id="AdGoogleFooter">
<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<!-- 広告003 -->
<ins class="adsbygoogle"
     style="display:inline-block;width:468px;height:60px"
     data-ad-client="ca-pub-8243655003444487"
     data-ad-slot="8393842812"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>
    </div>
  </div>
</div>
EOF;

$footer_TOP = <<< EOF
<div id="footerArea">
  <div id="footer">
    <div class="left"> | <a href="rules.php">利用規約</a> | <a href="commercial.php">特定商取引法に基づく表記</a> | <a href="privacy.php">プライバシーポリシー</a> | <a href="company.php">運営者概要</a> | <a href="mailto:info@saimitsu.net">お問い合わせ</a> | <a href="unregister.php">退会</a> |</div>
    <div class="right">お問い合わせに当たっては「saimitsu.net」からのメール受信設定をお願いいたします。</div>

    <div class="right"> c 2019 細密占星術運営委員会 All rights Reserved.</div>
  </div>
</div>
EOF;

$footer_bl = <<< EOF
<div id="footerArea">
  <div id="footer">
    <div class="left"> | <a href="rules.php">利用規約</a> | <a href="commercial.php">特定商取引法に基づく表記</a> | <a href="privacy.php">プライバシーポリシー</a> | <a href="company.php">運営者概要</a> | <a href="mailto:info@saimitsu.net">お問い合わせ</a> | <a href="unregister.php">退会</a> |</div>
    <div class="right"> c 2019 細密占星術運営委員会 All rights Reserved.</div>
    	<div id="AdGoogleFooter">
<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<!-- 広告003 -->
<ins class="adsbygoogle"
     style="display:inline-block;width:468px;height:60px"
     data-ad-client="ca-pub-8243655003444487"
     data-ad-slot="8393842812"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>
	</div>
  </div>
</div>
EOF;






$navi = <<< EOF
<!--<div id="navigationArea">
  <div id="mainNavi">
    <div class="left"><a href="./index.php"><img src="./shared/images/common/bt_hs_menu.jpg" /></a></div>
    <div class="center"><a href="./index.php"><img src="./shared/images/common/bt_about_menu.jpg" /></a></div>
    <div class="right"><a href="./point.php"><img src="./shared/images/common/bt_payment.jpg" /></a></div>
  </div>
</div>-->
EOF;


$tarot_navi = <<< EOF
<ul>
							<li><a href="tarot/shuffle.cgi?s0=a"><font color="#0000FF">今日一日の運勢</font></a></li>
							<li><a href="tarot/shuffle.cgi?s0=b"><font color="#0000FF">二人の恋愛相性</font></a></li>
							<li><a href="tarot/shuffle.cgi?s0=c"><font color="#0000FF">夫婦生活の相性</font></a></li>
							<li><a href="tarot/shuffle.cgi?s0=d"><font color="#0000FF">パートナーとのSEX</font></a></li>
							<li><a href="tarot/shuffle.cgi?s0=e"><font color="#0000FF">幸せな結婚に向けて</font></a></li>
</ul>
EOF;



include(public_path() . "/libs/text.php");
