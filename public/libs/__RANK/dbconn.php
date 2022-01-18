<?php
##
## MySQL��³�롼����
##
require_once 'DB.php';
$dbhost = "127.0.0.1";
$dbuser = "astro";
$dbpass = "JKd5ahb9";
$dbname = "userDB";
$dbport = "3308";

$dsn = "mysql://$dbuser:$dbpass@$dbhost:$dbport/$dbname";
$db =& DB::connect($dsn);
if (DB::isError($db)) {
        die($db->getMessage());
} else {
	$db->query("SET NAMES ujis");
}


##
## ��ƻ�ܸ��Ȼ�Į¼̾������ˤȤ�
## ���١����١������ॾ���󥪥ե��åȤ��֤�
## ���ߤϻ��Ѥ��Ƥ��ޤ���
## �����˲��� location() �Ǳ��Ѥ��Ƥ��ޤ�
##
function getLocation($pref, $town) {
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
## ��̾�����١����١������ॾ���󥪥ե��åȤ�Ϣ��������ɤ߹���
##
include("/home/saimitsu/stella_lib/zip/location.php");

##
## ��location.phpc���ɤ߹����Ϣ���������̾�Ǹ�������
##
function location($place) {
	global $location;
	return $location[$place];
}

##
## ���ֹ椫����ƻ�ܸ������
##
function getPrefByPostal($postal) {
	global $db;

	## ���������ˤ���
	$postal = ereg_replace("[^0-9]","",$postal);

	## 7��·�äƤ��ʤ��Ƥ�ޥå�����褦��like�Ǹ�������
	## ��3���Ʊ����ƻ�ܸ��Ǥ���а��פ���
	$pref = "";
	$sql = "SELECT pref FROM postal WHERE new7 like ?";
	$data = array("$postal%");
	$st = $db->prepare($sql);
	$res = $db->execute($st, $data);
	while($row = $res->fetchRow()) {
		$pref = $row[0];
	}
	return $pref;
}


##
## ���ֹ椫����ƻ�ܸ����ᡤ���������֤�
##
function calcDelivery($postal) {

	$pref = getPrefByPostal($postal);
	$dv = array(
			"�̳�ƻ" => 1000,
			"�Ŀ���" => 700,
			"���ĸ�" => 700,
			"��긩" => 700,
			"�ܾ븩" => 600,
			"������" => 600,
			"ʡ�縩" => 600,
			"��븩" => 600,
			"���ڸ�" => 600,
			"���ϸ�" => 600,
			"��̸�" => 600,
			"���ո�" => 600,
			"�����" => 600,
			"�����" => 600,
			"������" => 600,
			"Ĺ�" => 600,
			"���㸩" => 600,
			"�ٻ���" => 600,
			"���" => 600,
			"ʡ�温" => 600,
			"�Ų���" => 600,
			"���θ�" => 600,
			"���츩" => 600,
			"���Ÿ�" => 600,
			"������" => 700,
			"���츩" => 700,
			"���ɸ�" => 700,
			"�²λ���" => 700,
			"�����" => 700,
			"ʼ�˸�" => 700,
			"������" => 800,
			"���縩" => 800,
			"������" => 800,
			"Ļ�踩" => 800,
			"�纬��" => 800,
			"���" => 900,
			"���縩" => 900,
			"���θ�" => 900,
			"��ɲ��" => 900,
			"ʡ����" => 1000,
			"���츩" => 1000,
			"Ĺ�긩" => 1000,
			"���ܸ�" => 1000,
			"��ʬ��" => 1000,
			"�ܺ긩" => 1000,
			"�����縩" => 1000,
			"���츩" => 1100
		);
	$var = $dv[$pref];
	if(!$var) $var = 1100;
	return $var;
}
