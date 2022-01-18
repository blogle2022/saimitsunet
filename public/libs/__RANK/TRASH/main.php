<?
$view = httpValue("view");
$fortune = httpValue("fortune");


if(!$fortune) {
	include("./$view.php");
} else {
	include("./fortune.php");
}
