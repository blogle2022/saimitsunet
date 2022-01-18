<?
session_start();
if(!session_is_registered('uid')) {
	header("Location: $baseurl/index.html");
	exit;
}
?>
