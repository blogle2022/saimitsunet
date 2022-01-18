<?php
##
## MySQL接続ルーチン
##

use Dotenv\Dotenv;

require_once __DIR__ . '/../../vendor/autoload.php';
$dotenv = Dotenv::createImmutable(__DIR__ . '/../..');
$dotenv->load();
$dbhost = $_ENV['dbhost'];
$dbuser = $_ENV['dbuser'];
#$dbpass = "VpOgokgz1";
$dbpass = $_ENV['dbpass'];
$dbname = $_ENV['db'];
$dbport = $_ENV['dbport'];


$db = new mysqli($dbhost, $dbuser, $dbpass, $dbname, $dbport);
if ($db->error) {
	die($db->get_warnings());
} else {
	$db->query("SET NAMES ujis");
}


##
## 都道府県と市町村名を引数にとり
## 緯度，径度，タイムゾーンオフセットを返す
## 現在は使用していません．
## かわりに下の location() で運用しています
##
function getLocation($pref, $town)
{
	global $db;
	$sql = "SELECT lon,lat,tz,pref,town ";
	$sql .= "FROM areaDB.zip_tbl WHERE ";
	$sql .= "pref='" . $pref . "' and ";
	$sql .= " town='" . $town . "'";
	$res = $db->query($sql);
	$obj = $res->fetchRow(DB_FETCHMODE_ASSOC);
	$lon = $obj['lon'];
	$lat = $obj['lat'];
	$tz = $obj['tz'];

	return array($lon, $lat, $tz);
}

##
## 地名，緯度，径度，タイムゾーンオフセットの連想配列を読み込む
##
include(public_path() . "/libs/location.php");

##
## 上のlocation.phpで読み込んだ連想配列を地名で検索する
##
function location($place)
{
	global $location;
	return $location[$place];
}

##
## 〒番号から都道府県を求める
##
function getPrefByPostal($postal)
{
	global $db;

	## 数字だけにする
	$postal = preg_replace("/[^0-9]/", "", $postal);

	## 7桁揃っていなくてもマッチするようにlikeで検索する
	## 旧3桁は同じ都道府県であれば一致する
	$pref = "";
	$sql = "SELECT pref FROM postal WHERE new7 like ?";
	$data = array("$postal%");
	$st = $db->prepare($sql);
	$res = $db->execute($st, $data);
	while ($row = $res->fetchRow()) {
		$pref = $row[0];
	}
	return $pref;
}


##
## 〒番号から都道府県を求め，配送料を返す
##
function calcDelivery($postal)
{

	$pref = getPrefByPostal($postal);
	$dv = array(
		"北海道" => 1000,
		"青森県" => 700,
		"秋田県" => 700,
		"岩手県" => 700,
		"宮城県" => 600,
		"山形県" => 600,
		"福島県" => 600,
		"茨城県" => 600,
		"栃木県" => 600,
		"群馬県" => 600,
		"埼玉県" => 600,
		"千葉県" => 600,
		"東京都" => 600,
		"神奈川県" => 600,
		"山梨県" => 600,
		"長野県" => 600,
		"新潟県" => 600,
		"富山県" => 600,
		"石川県" => 600,
		"福井県" => 600,
		"静岡県" => 600,
		"愛知県" => 600,
		"岐阜県" => 600,
		"三重県" => 600,
		"京都府" => 700,
		"滋賀県" => 700,
		"奈良県" => 700,
		"和歌山県" => 700,
		"大阪府" => 700,
		"兵庫県" => 700,
		"岡山県" => 800,
		"広島県" => 800,
		"山口県" => 800,
		"鳥取県" => 800,
		"島根県" => 800,
		"香川県" => 900,
		"徳島県" => 900,
		"高知県" => 900,
		"愛媛県" => 900,
		"福岡県" => 1000,
		"佐賀県" => 1000,
		"長崎県" => 1000,
		"熊本県" => 1000,
		"大分県" => 1000,
		"宮崎県" => 1000,
		"鹿児島県" => 1000,
		"沖縄県" => 1100
	);
	$var = $dv[$pref];
	if (!$var) $var = 1100;
	return $var;
}
