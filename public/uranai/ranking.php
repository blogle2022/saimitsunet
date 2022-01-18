<?php
require_once __DIR__ . '/../../bootstrap/uranai.php';

$rid = httpValue('rid');



##
## ファイル指定
##
include(public_path() . "/libs/ranking.php");
$file =  public_path() . "/libs/ranking/12rank/" . $rid . ".dat";

## ファイルが存在すれば，読み込む
if (file_exists($file)) {
  $fp = fopen($file, "r");
  $cnt = 0;
  while (!feof($fp)) {
    $buf = fgets($fp, 8192);
    ### 「第1位：○○座 ##### 文章」という構造になっているので，
    ###  各行を ##### で分割する
    list($ttl, $txt) = explode(" ##### ", $buf);
    $title[$cnt] = $ttl;
    $restext[$cnt] = $txt;
    ## 10文字未満だったら文章が無いとし，抜ける
    if (strlen($buf) < 10) break;
    $cnt++;
  }
}
$text .= <<< EOF
              <table width="100%" border="0" cellpadding="10" cellspacing="0">
                <tr>
                  <td>
		<div align="center">
                    <img src="/images/logo_ranking.gif" width="500" height="209"><br><br>
<font color="#FF0000" size="3">
誕生時の
<b>
EOF;

$text .= $rank_planet[$rid];
$text .= <<< EOF
</b>
でみる，星座別
<b>
EOF;
$text .= $rank_title[$rid];
$text .= <<< EOF
</b>
</font><br><br>

<table border="0">
EOF;


for ($i = 0; $i < $cnt; $i++) {

  $text .= "<tr><td nowrap><a href=\"#$i\">" . $title[$i] . "</a></td></tr>";
}

$text .= '</table><br><br>';

for ($i = 0; $i < $cnt; $i++) {
  $color = $rank_color[$i];

  $j = $i + 1;
  $text .= <<<EOF
<a name="$i">
<TABLE cellpadding="0" cellspacing="0">
  <TBODY>
    <TR>
        <TD colspan="3" height="50" align="center" bgcolor="#ffffff">
	<IMG src="/images/12-$j.gif" width="132" height="50" border="0" alt=""></TD>
    </TR>
    <TR>
      <TD rowspan="3" width="9" bgcolor="$color"></TD>
        <TD width="450" height="8" bgcolor="$color"></TD>
      <TD rowspan="3" width="10" bgcolor="$color"></TD>
    </TR>
      <TR>
        <TD width="431" height="150" align="center" bgcolor="#ffffff"><br>
<strong><font size="3">
EOF;
  $text .= $title[$i];
  $text .= <<< EOF
</font></strong>
          <TABLE width="100%" border="0" cellpadding="15" cellspacing="0">
            <tr>
              <TD bgcolor="#ffffff" align="left">
$restext[$i]

</TD>
            </tr>
          </TABLE></TD>
    </TR>
    <TR>
      <TD width="450" height="8" bgcolor="$color"></TD>
    </TR>
  </TBODY>
</TABLE>
<a href="#top">先頭にもどる</a>
<br>
<br>
EOF;
}



$text .= <<<EOF
		</div>
		</td>
                </tr>
	      </table>
EOF;

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>あなたと大切な人の星座相性がわかる星占い-細密占星術- | 星座別ランキング</title>
  <meta name="keywords" content="星占い,星座,相性,占星術,ダイエット,運勢,星座別ランキング">
  <meta name="description" content="星占いとタロットでわかるあなたと大切な人との星座運勢。ホロスコープを使った恋愛、ダイエット、未来、相性診断も充実。星座別自分チェックであなたの自分力を確認">
  <link href="./shared/css/style_import.css" rel="stylesheet" type="text/css" />
  <link rel="shortcut icon" href="images/topmenu/star.png" />
  <script src="./shared/js/jquery.js" type="text/javascript"></script>
  <script src="./shared/js/scrollsmooth.js" type="text/javascript"></script>
</head>

<body>
  <!----↓Header---->
  <!----↑Header---->
  <!----↓MainVisual---->
  <!----↑MainVisual---->
  <!----↓contents---->
  <div id="contentsArea">
    <div id="container" class="clearfix">
      <div id="pankuzu"><a href="toppage.php">トップ</a> &gt; 星座別ランキング</div>
      <!----↓LeftColumn---->
      <div class="left">
        <!----↓HsMenu01---->
        <div class="contentsBox">
          <div class="contentsBoxTop">
          </div>
          <div class="contentsBoxBottom">
            <div class="horoscopeResult clearfix">

              <table width="100%" border="0" cellspacing="0" cellpadding="0">
              </table>
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
              </table>

              <p><?= $text ?>
              </p>
            </div>
          </div>
        </div>
        <!----↑HsMenu01---->
      </div>
      <!----↑Left Column---->
      <!----↓Right Column---->
      <!----↑Right Column---->
      <div id="pageTopButtonArea">
      </div>
    </div>
    <div class="clear"></div>
  </div>
  <!----↑Contents---->
  </div>
  <!----↓Footer---->
  <!----↑Footer---->
</body>

</html>