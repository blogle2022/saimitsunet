<?
$keyword = httpValue("keyword");
$sql = "SELECT * FROM fortunes WHERE keyword='$keyword'";
$res = $db->query($sql);
$f_obj = $res->fetchRow(DB_FETCHMODE_ASSOC);
include("./forms/$keyword.form");
?>
