<?php


function showItemInfo($category,$itemcode) {
	global $db;
	$sql = "SELECT itemname,price,delivery,manufacturer,imagefile0,stock,descr1 FROM shop_item WHERE category=? and itemcode=?";
	$sth = $db->prepare($sql);
	$data = array($category, $itemcode);
	$res = $db->execute($sth, $data);
	$row = $res->fetchRow();
	$itemname = $row[0];
	$price = $row[1];
	$delivery = $row[2];
	$manufacturer = $row[3];
	$imagefile0 = $row[4];
	$stock = $row[5];
	$descr1 = $row[6];


?>
<table width="98%" border="0" cellpadding="10" cellspacing="0">
	<tr><form method="GET" action="cart_mod.php">
		<td width="400"><img src="<?php echo $imagefile0; ?>" width="400" height="300"></td>
		<td valign="top"><input type="hidden" name="itemcode" value="<?php echo $itemcode; ?>"> 
			<p>商品番号：<?php echo $category . "-" . $itemcode; ?><br>
			<?php echo $itemname; ?><br>
			価格：<?php echo $price; ?>円（税込み）
			</p>
			<p>注文個数
			<select name="count">
				<option value="1" selected>1</option>
				<option value="2">2</option>
				<option value="3">3</option>
				<option value="4">4</option>
				<option value="5">5</option>
			</select>
<?php
if($stock < 1) {
?>
<input type="submit" name="add" value="　　 在庫切れ 　　" DISABLED><br>
在庫：<font color="#ff6666">なし</font>
<?php
} else if($stock < 5) {
?>
<input type="submit" name="add" value="　　買い物かごに入れる　　"><br>
在庫：<font color="#ff6666">わずか</font>
<?php
} else {
?>
<input type="submit" name="add" value="　　買い物かごに入れる　　"><br>
在庫：有
<?php
}
?>

			</p>
			<p><font size="2"><?php echo $descr1; ?></font></p>
		</td>
	</form></tr>
</table>
<?php
}




?>
