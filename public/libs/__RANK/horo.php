<?php

function LoadPNG ($imgname) {
  $im = @imagecreatefrompng ($imgname); /* �����ץ���ߤ� */
  if (!$im) { /* ���Ԥ������ */
    $im = ImageCreate (150,30); /* ���β�������� */
    $bgc = ImageColorAllocate ($im,255,255,255);
    $tc  = ImageColorAllocate ($im,0,0,0);
    ImageFilledRectangle ($im,0,0,150,30,$bgc);
    ImageString ($im,1,5,5,"Error loading $imgname",$tc); 
  }
  return $im;
}

##
## $deg�٤ΰ��֤��������
##
function lineByDegree(&$image, $deg, $len, $color) {
	$width = imagesx($image);
	$height = imagesy($image);
        imageline(
                $image,
                $width/2, $height/2,
                $width/2 - $len*cos(deg2rad($deg)),
                $height/2 + $len*sin(deg2rad($deg)),
                $color);
}      

$house_matrix = array(
	"Sun" => "1",
	"Moon" => "2",
	"Mercury" => "3",
	"Venus" => "4",
	"Mars" => "5",
	"Jupiter" => "6",
	"Saturn" => "7",
	"Uranus" => "8",
	"Neptune" => "9",
	"Pluto" => "10",
	"ar" => "11",
	"ta" => "12",
	"ge" => "13",
	"cn" => "14",
	"le" => "15",
	"vi" => "16",
	"li" => "17",
	"sc" => "18",
	"sa" => "19",
	"cp" => "20",
	"aq" => "21",
	"pi" => "22");

$Signs = array(
	"ar" => "1",
	"ta" => "2",
	"ge" => "3",
	"cn" => "4",
	"le" => "5",
	"vi" => "6",
	"li" => "7",
	"sc" => "8",
	"sa" => "9",
	"cp" => "10",
	"aq" => "11",
	"pi" => "12");



$zodfile = array(
	"ar" => "ohitsuji.png",
	"ta" => "ousi.png",
	"ge" => "futago.png",
	"cn" => "kani.png",
	"le" => "shishi.png",
	"vi" => "otome.png",
	"li" => "tenbin.png",
	"sc" => "sasori.png",
	"sa" => "ite.png",
	"cp" => "yagi.png",
	"aq" => "mizugame.png",
	"pi" => "uo.png",
);

$starfile = array(
	"Sun" => "taiyou12.png",
	"Moon" => "tuki12.png",
	"Mercury" => "suisei12.png",
	"Venus" => "kinsei12.png",
	"Mars" => "kasei12.png",
	"Jupiter" => "mokusei12.png",
	"Saturn" => "dosei12.png",
	"Uranus" => "tennou12.png",
	"Neptune" => "kaiou12.png",
	"Pluto" => "meiou12.png");

$jp = array(
	"ar" => "���Ӻ�",
	"ta" => "�����",
	"ge" => "�лҺ�",
	"cn" => "����",
	"le" => "��Һ�",
	"vi" => "������",
	"li" => "ŷ���",
	"sc" => "긺�",
	"sa" => "�ͼ��",
	"cp" => "���Ӻ�",
	"aq" => "���Ӻ�",
	"pi" => "����",
	"Sun" => "����",
	"Moon" => "��",
	"Mercury" => "����",
	"Venus" => "����",
	"Mars" => "����",
	"Jupiter" => "����",
	"Saturn" => "����",
	"Uranus" => "ŷ����",
	"Neptune" => "������",
	"Pluto" => "̽����");

$aspect_def = array(
	"conjunction",
	"semi-sextile",
	"semi-square",
	"sextile",
	"quintile",
	"square",
	"trine",
	"sesquiquadrate",
	"quincunx",
	"opposition");

function zodOrder($start) {
	$ary = array();
	$str = "ar,ta,ge,cn,le,vi,li,sc,sa,cp,aq,pi,ar,ta,ge,cn,le,vi,li,sc,sa,cp,aq,pi";
	if(ereg("($start,[a-z,]{32})", $str, $ary)) return $ary[1];
}




?>