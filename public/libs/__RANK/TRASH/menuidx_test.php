<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<META NAME="description" CONTENT="星占いサイト「細密占星術」。毎日の運勢、相性占いなど。">
<META NAME="keywords" CONTENT="相性占い,恋愛占い,ホロスコ−プ,無料,相性,タロット,星占い,占い,黄道十二宮,Alphonse,占星術,占星学,パワーストーン,出会い,チャンス,プレゼント">
<META NAME="ROBOTS" CONTENT="ALL">
<meta http-equiv="Content-Type" content="text/html; charset=euc-jp">
<title>占い百貨店 Stella/フロアーのご案内</title>
		<csscriptdict import>
			<script type="text/javascript" src="../GeneratedItems/CSScriptLib.js"></script>
		</csscriptdict><csactiondict>
			<script type="text/javascript"><!--
CSInit[CSInit.length] = new Array(CSILoad,/*CMP*/'button',/*URL*/'images/left_menu_regist_01s.gif',/*URL*/'images/left_menu_regist_02s.gif',/*URL*/'','新規会員登録');

// --></script>
		</csactiondict>

</head>

<body onload="CSScriptInit();" BGCOLOR=#cccccc LEFTMARGIN=0 TOPMARGIN=0 MARGINWIDTH=0 MARGINHEIGHT=0>
<!--全体テーブル -->
<table width="800" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
<!--ヘッダーテーブル -->
<table width="800" border="0" cellspacing="0" cellpadding="0">
	<TR>
		<TD>
			<IMG SRC="images/menuindex_01.jpg" WIDTH=800 HEIGHT=88 ALT="header image"></TD>
	</TR>
	<TR><form method="post" action="./login.php">
		<TD align="right" valign="center" background="./images/login_bar.jpg"><font size="-1"><noscript>JavaScriptが使えません。JavaScriptを無効にしているか、JavaScriptに対応していないブラウザのため、このページは正常に表示されません。</noscript>
<?
include("pagehead");
?>
		</font></TD></form>
	</TR>
        <TR>
                <TD align="right" bgcolor="#ffffcc"><font size="-1">
<?
include("greeting");
?>
                </font><TD>
        </TR>
</table>
<!--ヘッダーテーブル終了 --></td>
  </tr>
	<tr><td bgcolor="#ffffcc" align="center"><font size="-1">
<table width="700" border="0" cellspacing="0" cellpadding="1">
  <tr>
    <td bgcolor="#000000">
      <table width="700" border="0" cellspacing="0" cellpadding="4">
        <tr>
          <td bgcolor="#ffffee">
            <center>
<?
if(is_object($user)) {
?>
<a href="./userinfo.php">登録情報</a>
<?
} else {
?>
<a href="./registration.html">新規登録</a>
<?
}
?>
&nbsp;|&nbsp;
<a href="./4f/index.php">恋愛占い/相性占い</a>
&nbsp;|&nbsp;
<a href="./2f/index.php">美容占い/健康占い</a>
&nbsp;|&nbsp;
<a href="./1f/index.php">毎日の運勢</a>
&nbsp;|&nbsp;
<a href="./7f/index.php">マネー占い</a>
&nbsp;|&nbsp;
<a href="./5f/index.php">未来を占う</a>
&nbsp;|&nbsp;
<a href="./6f/index.php">誕生日から占う</a>
	</font>
            </center>
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
</td>
</tr>
  <tr>
    <td bgcolor="#FFFFCC">
<!--中段大テーブル -->
    <table width="800" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="494" align="center" valign="top"><table width="495" border="0" cellpadding="0" cellspacing="0">
          <tr align="center">
            <td colspan="2"><img src="images/spacer.gif" width="495" height="5"></td>
            </tr>
          <tr align="center">
            <td colspan="2">
<?
include("banner/a_2.txt");
?>
          </td>
          </tr>
          <tr>
            <td width="143" align="left" valign="top">
<!--左メニューーテーブル -->
            <table width="142" border="0" cellpadding="0" cellspacing="0">
              
          <tr>
            <td colspan="2"><img src="images/spacer.gif" width="142" height="10"></td>
            </tr>
          <tr>
            <td><img src="images/spacer.gif" width="18" height="28"></td>
            <td><a href="topix/index.php"><img src="images/left_menu_01.gif" alt="トピックス" width="123" height="28" border="0"></a></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td><a href="itokawa/index.php"><img src="images/left_menu_02.gif" alt="糸川英夫コーナー" width="123" height="28" border="0"></a></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td><a href="fan/index.php"><img src="images/left_menu_03.gif" alt="ファンのページ" width="123" height="28" border="0"></a></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td><a href="http://saimitsu-powerstone.seesaa.net" target="_blank"><img src="images/izawabutton.jpg" alt="パワーストーン講座" border="0"></a></td>
          </tr>
<!--          <tr>
            <td>&nbsp;</td>
            <td><img src="images/left_menu_05.gif" alt="今日のお誕生日" width="123" height="28" border="0"></td>
          </tr>
-->
<!--
          <tr>
            <td>&nbsp;</td>
            <td><img src="images/left_menu_blank.gif" width="123" height="28"></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td><img src="images/left_menu_blank.gif" width="123" height="28"></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td><img src="images/left_menu_blank.gif" width="123" height="28"></td>
          </tr>

          <tr>
            <td>&nbsp;</td>
            <td><img src="images/spacer.gif" width="123" height="3"></td>
          </tr>
--><?
if(!$auth){
?>
          <tr>
            <td>&nbsp;</td>
            <td><csobj al="新規会員登録" h="123" ht="images/left_menu_regist_02s.gif" st="新規会員登録" t="Button" w="123"><a href="registration.html" onmouseover="return CSIShow(/*CMP*/'button',1)" onmouseout="return CSIShow(/*CMP*/'button',0)" onclick="return CSButtonReturn()"><img src="images/left_menu_regist_01s.gif" width="123" height="123" name="button" border="0" alt="新規会員登録"></a></csobj></td>
          </tr>
<?
} else {
?>
          <tr>
            <td>&nbsp;</td>
            <td><csobj al="登録情報変更" h="158" ht="images/left_menu_regist_02.gif" st="登録情報変更" t="Button" w="123"><a href="userinfo.php"><img src="images/left_menu_regist_03.gif" width="123" height="158" name="button" border="0" alt="登録情報変更"></a></csobj></td>
          </tr>

<?
}
?>
<?
include("banner/b.txt");
?>
            </table>
<!--左メニューーテーブル終了 -->
            </td>
            <td width="352" valign="top">
<!--中央テーブル -->
            <table width="352" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td colspan="3"><img src="images/spacer.gif" width="350" height="10"></td>
                </tr>
              <tr>
                <td>&nbsp;</td>
                <td width="332"><div align="left"><strong><font color="#CC0000" size="2">　What's New </font></strong></div></td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td width="10" height="110">&nbsp;</td>
                <td width="332" height="110" bgcolor="#FFFFFF">      
<!--中央 フレーム１ -->
      <iframe src="./wn1.html" width="332" height="110" frameborder="0"  STYLE="border-style: dotted; border-color: #666666; border-width:1">このページはインラインフレームで作成されております。<br>
      未対応ブラウザの方はご覧になれません。<br>
      推奨ブラウザはIE5.0以上、NN6以上です。</iframe>
<!--中央 フレーム１終了 -->                </td>
                <td width="10" height="110">&nbsp;</td>
              </tr>
              <tr>
                <td colspan="3"><img src="images/spacer.gif" width="350" height="10"></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
                <td width="332"><div align="left"><strong><font color="#CC0000" size="2">　Stellaからのおしらせ</font></strong></div></td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td width="10" height="90">&nbsp;</td>
                <td width="332" height="90" bgcolor="#FFFFFF">
<!--中央 フレーム２ -->
      <iframe src="./wn2.html" width="332" height="90" frameborder="0"  STYLE="border-style: dotted; border-color: #666666; border-width:3">このページはインラインフレームで作成されております。<br>
      未対応ブラウザの方はご覧になれません。<br>
      推奨ブラウザはIE5.0以上、NN6以上です。</iframe>
<!--中央 フレーム２終了 -->                </td>
                <td width="10" height="90">&nbsp;</td>
              </tr>
              <tr>
                <td colspan="3"><img src="images/spacer.gif" width="350" height="10"></td>
              </tr>
              <tr>
                <td width="10" height="90">&nbsp;</td>
                <td width="332" height="90" bgcolor="#FFFFFF">
<!--中央 フレーム３ -->
      <iframe src="./wn3.html" width="332" height="90" frameborder="0"  STYLE="border-style: dotted; border-color: #666666; border-width:3">このページはインラインフレームで作成されております。<br>
      未対応ブラウザの方はご覧になれません。<br>
      推奨ブラウザはIE5.0以上、NN6以上です。</iframe>
<!--中央 フレーム３終了 -->                </td>
                <td width="10" height="90">&nbsp;</td>
              </tr>
              <tr>
                <td colspan="3"><img src="images/spacer.gif" width="350" height="10"></td>
              </tr>
            </table>            </td>
          </tr>
          <tr>
            <td colspan="2">
<!--下部広告テーブル -->
<?
include("banner/c.txt");
?>
<!--下部広告テーブル終了 -->
            </td>
            </tr>
        </table>        </td>
        <td width="300" valign="top">
<!--右案内テーブル上部 -->
<table width="300" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td><img src="images/spacer.gif" width="300" height="5"></td>
      </tr>
    <tr>
      <td colspan="3"><img src="images/menuindex_04ss.jpg" alt="占い百貨店・フロアーのご案内" width="250" height="22"></td>
      </tr>
</table>
<!--右案内テーブル -->
<table width="300" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td width="45"><a href="1f/index.php"><img src="images/menuindex_10_1.gif" alt="今日のこと　今週のこと" width="45" height="56" border="0"></a></td>
      <td width="5" bgcolor="#FFFFCC"><img src="images/spacer.gif" width="5" border="0"></td>
      <td bgcolor="#FFFFCC"><strong><a href="1f/index.php" style="text-decoration:none"><font color="#CC0000" size="2">今日のこと　今週のこと</font></a></strong><br>
      <a href="1f/index.php" style="text-decoration:none"><font color="#666666" size="1">星が教えてくれる、今日のこと、今週のこと。<br>
自分の行動に自信がない方は、まずこのフロアー。</font></font></a></td>
    </tr>
    <tr>
      <td width="45"><a href="2f/index.php"><img src="images/menuindex_12_1.gif" alt="美容と健康" width="45" height="56" border="0"></a></td>
      <td width="5" bgcolor="#FFFFCC"><img src="images/spacer.gif" width="5" border="0"></td>
      <td bgcolor="#FFFFCC"><strong><a href="2f/index.php" style="text-decoration:none"><font color="#CC0000" size="2">美容と健康</font></a></strong><br>
      <a href="2f/index.php" style="text-decoration:none"><font color="#666666" size="1">ダイエットにスポーツ、レジャー。無駄なく、<br>
        無理なく続けるためのヒントはこのフロアーから。 </font></a></td>
    </tr>
    <tr>
      <td width="45"><a href="3f/index.php"><img src="images/menuindex_13_1.gif" alt="自分発見" width="45" height="57" border="0"></a></td>
      <td width="5" bgcolor="#FFFFCC"><img src="images/spacer.gif" width="5" border="0"></td>
      <td bgcolor="#FFFFCC"> <strong><a href="3f/index.php" style="text-decoration:none"><font color="#CC0000" size="2">自分発見</font></a></strong><a href="3f/index.php" style="text-decoration:none"><br>
          <font color="#666666" size="1">本当の自分、隠れている才能。自分のことを<br>
      いちばん知らないのは自分かも。そんな方は。</font></a></td>
    </tr>
    <tr>
      <td width="45"><a href="4f/index.php"><img src="images/menuindex_17_1.gif" alt="ＬＯＶＥ＆ＳＥＸ" width="45" height="56" border="0"></a></td>
      <td width="5" bgcolor="#FFFFCC"><img src="images/spacer.gif" width="5" border="0"></td>
      <td bgcolor="#FFFFCC"> <strong><a href="4f/index.php" style="text-decoration:none"><font color="#CC0000" size="2">LOVE &amp; SEX </font></a></strong><a href="4f/index.php" style="text-decoration:none"><br>
          <font color="#666666" size="1">相性、結婚、SEX。男と女、二つの星が交わるときに、<br>
      すてきな出会いが。まずその前に。 </font></a></td>
    </tr>
    <tr>
      <td width="45"><a href="5f/index.php"><img src="images/menuindex_18_1.gif" alt="未来日記" width="45" height="56" border="0"></a></td>
      <td width="5" bgcolor="#FFFFCC"><img src="images/spacer.gif" width="5" border="0"></td>
      <td bgcolor="#FFFFCC"> <strong><a href="5f/index.php" style="text-decoration:none"><font color="#CC0000" size="2">未来日記</font></a></strong> <a href="5f/index.php" style="text-decoration:none"><br>
          <font color="#666666" size="1">もしも未来が覗けたら、きっと未来は素晴らしい<br>
      ものになる。そこで、あなただけの未来日記。 </font></a></td>
    </tr>
    <tr>
      <td width="45"><a href="6f/index.php"><img src="images/menuindex_22_1.gif" alt="星のメッセージ集" width="45" height="56" border="0"></a></td>
      <td width="5" bgcolor="#FFFFCC"><img src="images/spacer.gif" width="5" border="0"></td>
      <td bgcolor="#FFFFCC"><strong><a href="6f/index.php" style="text-decoration:none"><font color="#CC0000" size="2">星のメッセージ集 </font></a></strong><br>
          <a href="6f/index.php" style="text-decoration:none"><font color="#666666" size="1">宇宙にある星は毎日毎日、あなたにメッセージを<br>
      送っています。それをキャッチするためのフロアー。 </font></a></td>
    </tr>
    <tr>
      <td width="45"><a href="7f/index.php"><img src="images/menuindex_23_1.gif" alt="仕事とお金" width="45" height="57" border="0"></a></td>
      <td width="5" bgcolor="#FFFFCC"><img src="images/spacer.gif" width="5" border="0"></td>
      <td bgcolor="#FFFFCC"> <strong><a href="7f/index.php" style="text-decoration:none"><font color="#CC0000" size="2">仕事とお金 </font></a></strong><a href="7f/index.php" style="text-decoration:none"><br>
          <font color="#666666" size="1">ビジネスで成功したい。お金持ちになりたい。<br>
      そんな方のためのヒントが満載されたフロアー。 </font></a></td>
    </tr>
<!--
    <tr>
      <td width="45"><a href="8f/index.php"><img src="images/menuindex_28_1.gif" alt="イベントホール" width="45" height="56" border="0"></a></td>
      <td width="5" bgcolor="#FFFFCC"><img src="images/spacer.gif" width="5" border="0"></td>
      <td bgcolor="#FFFFCC"> <strong><a href="8f/index.php" style="text-decoration:none"><font color="#CC0000" size="2">イベントホール</font></a></strong><a href="8f/index.php" style="text-decoration:none"><br>
          <font color="#666666" size="1">現金が当たる推理ゲームやメルマガ広告の説明、<br>
        参加すると着実に増えるポイント。</font></a></td>
    </tr>
    <tr>
      <td width="45"><a href="9f/index.php"><img src="images/menuindex_35_1.gif" alt="星座別レシピ" width="45" height="56" border="0"></a></td>
      <td width="5" bgcolor="#FFFFCC"><img src="images/spacer.gif" width="5" border="0"></td>
      <td bgcolor="#FFFFCC"> <strong><a href="9f/index.php" style="text-decoration:none"><font color="#CC0000" size="2">星座別レシピ </font></a></strong><a href="9f/index.php" style="text-decoration:none"><br>
          <font color="#666666" size="1">おいしく食べて、いっぱい食べて、幸運ゲット。<br>
      星座別にお料理のレシピを紹介するフロアーです。</font></a><font color="#666666" size="1">&nbsp; </font></td>
    </tr>
    <tr>
      <td colspan="3"><img src="images/spacer.gif" width="1" height="10"><img src="images/spacer.gif" width="1" height="10"></td>
      </tr>
-->
</table>
<!--右案内テーブル終了 -->        </td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td><csobj csref="footer.html" h="60" occur="33" t="Component" w="800">
						<table width="800" border="0" cellspacing="0" cellpadding="0" height="60">
							<tr height="32">
								<td align="center" bgcolor="#ffffcc" height="32"><font size="-2"><a href="agreement.php">会員規約</a>　</font><font size="-2" color="#ff6600">I</font><font size="-2">　</font><font size="-2"><a href="privacy.php">プライバシーポリシー</a>　</font><font size="-2" color="#ff6600">I</font><font size="-2">　<a href="company.php">会社概要</a>　</font><font size="-2" color="#ff6600">I</font><font size="-2">　<a href="mailto:support@saimitsu.jp" target="_blank">お問い合わせ</a>　</font><font size="-2" color="#ff6600">I</font><font size="-2">　<a href="qa.php">Q &amp; A</a></font></td>
							</tr>
							<tr>
								<td><img src="images/top_27.jpg" alt="" width="799" height="27" border="0"></td>
							</tr>
						</table></csobj></td>
  </tr>
</table>
</body>
</html>
