<?php
require_once __DIR__ . "/../../bootstrap/uranai.php";
/**
 *
 * $basesize: Horoscope外周の半径
 * $z_inside: １２星座環の内周半径
 * $h_in    : Cusp番号環の内周半径
 * $h_out   : Cusp番号環の外周半径
 * $num_base: Cusp番号の中心からの距離
 * $margin  : Horoscopeの合計マージン幅
 * $outsize : 最終出力画像のサイズ
 *
 */

$debug = False;
if ($_SESSION['uid'] == '117758') $debug = True;
if ($debug) $log = fopen("/tmp/horo.log", "w");

if ($_GET["basesize"] != "")
    $basesize = $_GET["basesize"];
else
    $basesize = 160;

$z_inside = $basesize - 40;
$h_in = $z_inside * 1 / 3;
$h_out = $h_in + 25;
$num_base = $h_in + 15;
$margin = 70;
$outsize = $basesize * 2 + $margin;


$keys = array();
/**
 *
 * Parameter形式と解析方式
 * 星座(英小文字２byte)と相対角(float)を連結した形式で送られてくるので、
 *  Sun=sc9.00121244
 *  => $zod["Sun"] = "sc"
 *     $pos["Sun"] = 9.00121244
 */

while (list($key, $val) = each($_GET)) {
    $ary = array();
    $keys[] = $key;
    if (preg_match("/([a-z]{2})([0-9]+\.[0-9]+)/", $val, $ary)) {
        $zod[$key] = $ary[1];
        $pos[$key] = floatval($ary[2]);
    }
}

// Cusp1の星座を起点とする１２宮の配列を作成する
// $zodOrder : カンマ区切り
// $zods     : 配列
// $degree   : 上昇宮を０°としたときの、各星座の角度
$zodOrder = zodOrder($zod["Ascendant"]);
if ($debug) fwrite($log, $_SESSION['uid']);
if ($debug) fwrite($log, "$zodOrder\n");
$zods = explode(",", $zodOrder);
for ($i = 0; $i < 12; $i++) {
    $zodname = $zods[$i];
    $degree[$zodname] = $i * 30 - $pos["Ascendant"];
    if ($debug) fwrite($log, "$zodname : $degree[$zodname]\n");
}

// １２宮の角度と、１２宮からの相対角度を合算し、絶対角度を求める。
foreach ($keys as $key) {
    $abs_pos[$key] = floatval($degree["$zod[$key]"] + $pos[$key]);
}

// 扱いやすいように、星関連の角度をまとめる
$starpos = array(
    "Sun" => $abs_pos["Sun"],
    "Moon" => $abs_pos["Moon"],
    "Mercury" => $abs_pos["Mercury"],
    "Venus" => $abs_pos["Venus"],
    "Mars" => $abs_pos["Mars"],
    "Jupiter" => $abs_pos["Jupiter"],
    "Saturn" => $abs_pos["Saturn"],
    "Uranus" => $abs_pos["Uranus"],
    "Neptune" => $abs_pos["Neptune"],
    "Pluto" => $abs_pos["Pluto"]
);

// 扱いやすいように、ハウス関連の角度をまとめる
$housepos = array(
    $abs_pos["house1"],
    $abs_pos["house2"],
    $abs_pos["house3"],
    $abs_pos["house4"],
    $abs_pos["house5"],
    $abs_pos["house6"],
    $abs_pos["house7"],
    $abs_pos["house8"],
    $abs_pos["house9"],
    $abs_pos["house10"],
    $abs_pos["house11"],
    $abs_pos["house12"]
);

// Base CANVAS definition
$image = imagecreate($basesize * 2, $basesize * 2);
$baseimage = imagecreate($outsize, $outsize);

// Color definition
$white = imagecolorallocate($image, 255, 255, 255);
$black = imagecolorallocate($image, 0, 0, 0);
$blue = imagecolorallocate($image, 0, 0, 255);
$gray = imagecolorallocate($image, 200, 200, 200);


for ($i = 0; $i < 12; $i++) {
    $zodname = $zods[$i];
    if ($debug) fwrite($log, "$zodname ($i) : $degree[$zodname]\n");
    // １２宮環に５°刻みの目盛を入れる
    lineByDegree($image, $degree[$zodname], $basesize - 1, $black);
    lineByDegree($image, $degree[$zodname] + 5, $z_inside + 3, $black);
    lineByDegree($image, $degree[$zodname] + 10, $z_inside + 5, $black);
    lineByDegree($image, $degree[$zodname] + 15, $z_inside + 3, $black);
    lineByDegree($image, $degree[$zodname] + 20, $z_inside + 5, $black);
    lineByDegree($image, $degree[$zodname] + 25, $z_inside + 3, $black);

    // 星座画像の配置
    $x = $basesize - 10 - ($basesize - 20) * (cos(deg2rad((15 - $pos["Ascendant"]) + ($i * 30))));
    $y = $basesize - 10 + ($basesize - 20) * (sin(deg2rad((15 - $pos["Ascendant"]) + ($i * 30))));

    $zodimg = LoadPNG($zodfile[$zodname]);
    imagecopy($image, $zodimg, $x, $y, 0, 0, 24, 24);
}

// １２宮環の内側を白で塗り潰す
imagefilledellipse($image, $basesize, $basesize, $z_inside * 2, $z_inside * 2, $white);



// Stars
asort($starpos);    #### 角度順にsortする

foreach ($starpos as $key => $val) {
    // 隣接する星との角度が５°未満なら、１段階中心に寄せる。
    if ($lastval != "") {
        if (abs($val - $lastval) < 5) {
            $down++;
        } else {
            $down = 0;
        }
    }
    $x = ($basesize - 6) - ($z_inside - 9 - $down * 15) * (cos(deg2rad($val)));
    $y = ($basesize - 6) + ($z_inside - 9 - $down * 15) * (sin(deg2rad($val)));
    $starimg = LoadPNG($starfile[$key]);
    imagecopy($image, $starimg, $x, $y, 0, 0, 12, 12);
    $lastval = $val;
}
// foreach回したので、一応resetしとく
reset($starpos);

// 12 Houses
for ($i = 0; $i < 12; $i++) {
    // Cusp線を引く
    lineByDegree($image, $housepos[$i], $z_inside, $gray);

    // 1-12の数字の位置を決める
    if ($i == 11)  $numpos = ($housepos[11] + $housepos[0] + 360) / 2;
    else        $numpos = ($housepos[$i] + $housepos[$i + 1]) / 2;

    if ($i > 9) {
        if ($numpos < 180) {
            $numpos += 180;
        }
    }
    $x = $basesize - $num_base * (cos(deg2rad($numpos)));
    $y = $basesize - 4 + $num_base * (sin(deg2rad($numpos)));
    imagestring($image, 2, $x, $y, strval($i + 1), $black);
}



imagefilledellipse($image, $basesize, $basesize, $h_in * 2 - 2, $h_in * 2 - 2, $white);
imageellipse($image, $basesize, $basesize, $h_in * 2, $h_in * 2, $black);
imageellipse($image, $basesize, $basesize, $h_out * 2, $h_out * 2, $gray);


imageellipse($image, $basesize, $basesize, $z_inside * 2, $z_inside * 2, $black);
imageellipse($image, $basesize, $basesize, $basesize * 2 - 2, $basesize * 2 - 2, $black);




foreach ($starpos as $key => $val) {
    lineByDegree($image, $val, $h_in, $gray);
}

imagecopy(
    $baseimage,
    $image,
    $margin / 2,
    $margin / 2,
    0,
    0,
    $basesize * 2,
    $basesize * 2
);
$baseblue = imagecolorallocate($baseimage, 0, 0, 255);

lineByDegree($baseimage, 0, $basesize + 10, $baseblue);
imagestring(
    $baseimage,
    5,
    $outsize / 2 - ($basesize + 30) * cos(deg2rad(0)),
    $outsize / 2 + ($basesize + 30) * sin(deg2rad(0)),
    "ASC",
    $baseblue
);

lineByDegree($baseimage, $abs_pos["MC"], $basesize + 10, $baseblue);
imagestring(
    $baseimage,
    5,
    $outsize / 2 - ($basesize + 30) * cos(deg2rad($abs_pos["MC"])),
    $outsize / 2 + ($basesize + 30) * sin(deg2rad($abs_pos["MC"])),
    "MC",
    $baseblue
);
if ($debug) fclose($log);
// 描画
header("Content-type: image/png");
imagepng($baseimage);
