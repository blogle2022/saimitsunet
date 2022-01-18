<table border="0" cellpadding="1" cellspacing="0" width="<? echo $menu_w; ?>">
<?
$fp = fopen("./items.dat", "r");
while($line = fgets($fp, 256)) {
	if(ereg("^#", $line)) continue;
	if(ereg("^;", $line)) continue;

	$line = trim($line);
	list($id, $type, $title, $page) = explode("\t", $line);
	switch($type) {
 		case '1':
			echo '<tr><td bgcolor="#dddddd"><b>';
			echo $title;
			echo '</b></td></tr>';
			break;
		case '2':
			echo '<tr><td bgcolor="#ffffff">';
			if($page != "") {
				echo '<a href="./index.php?fortune=1&keyword=';
				echo $page;
				echo '">';
			}
			echo $title;
			if($page != "") {
				echo "</a>";
			}	
			echo '</td></tr>';
			break;
 		case '3':
			echo '<tr><td bgcolor="#ffffff">';
			echo $title;
			echo '</td></tr>';
			break;
		default:
			break;
	}


}
fclose($fp);
?>

</table>
