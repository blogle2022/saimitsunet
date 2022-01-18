<?php

$payment_jp = array(
	"JNB" => "����ѥ�ͥåȶ��",
	"RSN" => "�ߤ��۶��",
	"MUF" => "�����ɩ�գƣʶ��",
	"PST" => "͹�ؿ���",
	"TST" => "������������",
	"SMB" => "���潻ͧ���",
	"YMT" => "������",	
);

$kouza_no = array(
	"JNB" => "��Ź�Ķ��� �����001-2695831 ���˥磻����������",
	"RSN" => "��Į��Ź �����2135449",
	"MUF" => "��Į��Ź �����4514762",
	"PST" => "���µ����ֹ� 00130-6-631616 �����ѻ�����դ������ޤ���",
	"TST" => "",
	"SMB" => "�ֺ��Ź �����8523755"
);
	





class shopItem {
	var $id;
	var $category;	## 00..99
	var $itemcode;	## 0000..9999
	var $itemname;
	var $price;	## ��ȴ���β���
	var $delivery;	## ������Υ�������
	var $manufacturer;	## ��¤��
	var $ptype;	## ??
	var $flg;	## ??

	var $imagefile0;
	var $imagefile1;
	var $imagefile2;
	var $imagefile3;
	var $imagefile0_local;
	var $imagefile1_local;
	var $imagefile2_local;
	var $imagefile3_local;

	var $descr1;

	
	//var $descr2;
	//var $descr3;
	//var $descr4;
	//var $descr5;

	var $stock;	## �߸�
	var $arrival_span;	## �����ٻ���

	var $shortView;
	var $url;


	var $price_str;
	var $price_tax_str;

	var $errmsg;


	function shopItem($itemcode="") {
		if($itemcode!="") $this->setItemcode($itemcode);
	}

	function setItemcode($itemcode) {
		global $db;
		if(ereg("^[0-9]{4}$",$itemcode)) {
			$this->itemcode = $itemcode;

			$sql = "SELECT count(*) FROM shop_item ";
			$sql .= "WHERE itemcode='" . $this->itemcode . "'";
			$result = $db->getOne($sql);

			if($this->is_new == False) {
				## �������ϤǤϤʤ��Τ�¸�ߤ��ʤ�
				if($result < 1) {
					$this->errmsg = "shopItem: no item found with code " . $this->itemcode . ".";
					return False;
				} else {
					return True;
				}
			} else {
				## �������ϤʤΤ˴���¸�ߤ���
				if(0 < $result) {
					$this->errmsg = "shopItem: item already exists with code " . $this->itemcode . ".";
					return False;
				} else {
					return True;
				}
			}

		} else {
			$this->errmsg = "shopItem: Invalid itemcode.";
			return False;
		}
		return True;
	}


	function decrStock($count) {
		## �߸˸���
		## returns:
		## �߸���­ -3
		## ���� 1

		global $db;
	      	$db->query("LOCK TABLES shop_item WRITE");
		$count_cur  = "SELECT stock FROM shop_item ";
		$count_cur .= "WHERE itemcode='" . $this->itemcode . "'"; 
		$cur_stock = $db->getOne($count_cur);
		if($cur_stock < $count) {
			$this->errmsg = "short of stock";
			$db->query("UNLOCK TABLES");
			return(-3);
		}
		$rest = $cur_stock - $count;
		$sql = "UPDATE shop_item SET stock='$rest' ";
		$sql .= "WHERE itemcode='" . $this->itemcode . "'";
	      	$db->query("UNLOCK TABLES");
		return(1);
	}
	function incrStock($count) {
		## �߸��ɲ�
		## returns:
		## ���� 1

		global $db;
	      	$db->query("LOCK TABLES shop_item WRITE");
		$count_cur  = "SELECT stock FROM shop_item ";
		$count_cur .= "WHERE itemcode='" . $this->itemcode . "'"; 
		$cur_stock = $db->getOne($count_cur);
		$rest = $cur_stock + $count;
		$sql = "UPDATE shop_item SET stock='$rest' ";
		$sql .= "WHERE itemcode='" . $this->itemcode . "'";
	      	$db->query("UNLOCK TABLES");
		return(1);
	}

	function loadData() {
		global $db;

		$sql = "SELECT * FROM shop_item WHERE itemcode='";
		$sql .= $this->itemcode;
		$sql .= "'";

		$res = $db->query($sql);
		$obj = $res->fetchRow(DB_FETCHMODE_ASSOC);

		$this->id = $obj["id"];
		$this->category = $obj["category"];
		$this->itemname = $obj["itemname"];
		//$this->itemcode = $obj["itemcode"];
		$this->price = $obj["price"];
		$this->delivery = $obj["delivery"];
		$this->manufacturer = $obj["manufacturer"];
		$this->ptype = $obj["ptype"];
		$this->flg = $obj["flg"];
		$this->imagefile0 = $obj["imagefile0"];
		$this->imagefile1 = $obj["imagefile1"];
		$this->imagefile2 = $obj["imagefile2"];
		$this->imagefile3 = $obj["imagefile3"];
		$this->imagefile0_local = $obj["imagefile0_local"];
		$this->imagefile1_local = $obj["imagefile1_local"];
		$this->imagefile2_local = $obj["imagefile2_local"];
		$this->imagefile3_local = $obj["imagefile3_local"];
		$this->descr1 = $obj["descr1"];
		//$this->descr2 = $obj["descr2"];
		//$this->descr3 = $obj["descr3"];
		//$this->descr4 = $obj["descr4"];
		//$this->descr5 = $obj["descr5"];
		$this->stock = $obj["stock"];
		$this->arrival_span = $obj["arrival_span"];
		if($this->arrival_span=="") {
			$this->arrival_span = "̤��";
		}
		$res->free();

	}
}

##
## CartItem���֥������Ȥϡ�Cart���֥������ȤΥ��С����֥������ȤȤʤ롥
## Cart�����CartItem�����äƤ��륤�᡼���Ǥ���
##
class CartItem {
	/** Ϣ�� **/
	var $seq;

	/** ���֥������ȼ��̻� **/
	var $id;

	/** �桼��ID **/
	var $base;

	/** 
	��ʸID
	��Cart���֥������ȤȤ��Υ����ƥ���ӤĤ��륭������� 
	**/
	var $transaction_id;

	var $item_name;
	var $item_price;
	var $item_count;

	/** �����ֹ� **/
	var $itemcode;

	/** ���ʥޥ�����ξ��� **/
	var $master;

	/** 
	 ���������ե饰
	 DB�������˵�ư���ۤʤ�
 
	 if(this->isnew) "INSERT INTO ..."
	 else "UPDATE ..."

	**/
	var $isnew;

	function CartItem($id="") {
		/** constructor **/
		global $user;

		$this->_init();
		$this->setId($id);

		/** �����Ǥʤ����DB��¸�ߤ���Τǥ��ɤ��� **/
		if(!$this->isnew) {
			$this->load();
		}
		/** ���ʥޥ�������ǡ�������ɤ��� **/
		$this->loadMaster();
	
	}

	function _init() {
		global $user;
		$this->setItemcode(0);
		$this->setItemCount(0);
		$this->isnew = 0;
		$this->base = $user->base_id;
	}

	function setId($id="") {
		/**
			�����Ȥ��ƴ�¸��ID��Ϳ���ʤ���С�
			��ưŪ�˿��������ե饰��Ω����
			������ID����Ϳ����롥
		**/
		if($id == "") {
			$this->id = md5(uniqid("CARTASDFGFAA"));
			$this->isnew = 1;
		} else {
			$this->id = $id;
			$this->isnew = 0;
		}
	}

	function changeOwner($base) {
		global $db;
		/**
			��ͭ��ID($base)���ѹ����뤳�Ȥǡ�
			��ͭ�Ԥ��ѹ����롥

			����ϡ������󤻤�����ʪ�򳫻Ϥ����桼������
			����ǥ�����򤷤����ˡ�
			�����Ȥ�ꥻ�åȤ����ˡ���ʪ�ξ��֤������Ѥ����褦��
			���뤿��Ǥ��롥
		
		**/
		if(ereg("^[0-9]+$",$base)) {
			$sql = "UPDATE cartItem SET base=? ";
			$sql .= "WHERE id=? AND base=?";
			$data = array(
				$base,
				$this->id,
				$this->base);
			$st = $db->prepare($sql);
			$db->execute($st, $data);
			$this->base = $base;
		}
	}

	function remove() {
		/**
			��ʬ���Ȥ�DB����ä�
		**/
		global $db;
		global $user;

		$sql = "DELETE FROM cartItem WHERE id=? AND base=?";
		$st = $db->prepare($sql);
		$data = array($this->id, $user->base_id);
		$db->execute($st, $data);

	}

	function setParentId($id) {
		/**
			�ƥ����ȥ��֥������Ȥ�ID�򥻥åȤ���
		**/
		$this->transaction_id = $id;
	}

	function setItemCount($count) {
		/** �Ŀ����ѹ�����
		 ��ʸȯ������ѹ���ǽ
		 �߸˴���������̤���� **/
		$this->item_count = $count;
		return True;
	}
	function setItemcode($itemcode) {
		/**
			���Ȥ�itemcode��0�Ǥ��� = ̤����ξ���
			�Ǥ���С�itemcode�򥻥åȤ���
			���Ǥ˥��åȤ���Ƥ�����硤�ѹ��ϵ�����ʤ�
		**/
		if($this->itemcode == 0) {
			$this->itemcode = $itemcode;
			$this->loadMaster();
		} else {
			return False;
		}
		return True;
	}


	function decrStock() {

		global $db;
		/** �߸ˤ򸺾������� **/
		/** PT (�ݥ����)�κ߸ˤ�̵�¤ʤΤǡ���˿����֤� **/
		if($this->master->category == 'PT') {
			return True;
		}
		$sql = "SELECT stock FROM shop_item WHERE itemcode=?";
		$st = $db->prepare($sql);
		$data = array($this->itemcode);
		$res = $db->execute($st, $data);
		$row = $res->fetchRow();
		$cur_stock = $row[0];

		/**
			�߸���­�ξ�硤�����֤�
			�߸ˤ�­��Ƥ���С��߸ˤ򸺤餹
		**/
		if($cur_stock < $this->item_count) {
			return False;
		} else {
			$new_stock = $cur_stock - $this->item_count;
			$sql = "UPDATE shop_item SET stock=? WHERE itemcode=?";
			$st = $db->prepare($sql);
			$data = array($new_stock, $this->itemcode);
			$db->execute($st, $data);
			return True;
		}
	}

	function loadMaster() {
		/**
			���ʥޥ������龦�ʾ������ɤ���
		**/
		if($this->itemcode != 0) {
			$this->master = new shopItem($this->itemcode);
			$this->master->loadData();
			$this->item_name = $this->master->itemname;
			$this->item_price = $this->master->price;
		}

	}

	function load() {
		global $db;
		/**
			DB����¸���줿��ʬ���Ȥ���ɤ���
		**/
		$sql = "SELECT * FROM cartItem ";
		$sql .= "WHERE id=?";
		$st = $db->prepare($sql);
		$res = $db->execute($st, $this->id);

		$obj = $res->fetchRow(DB_FETCHMODE_ASSOC);

		$this->setParentId($obj["transaction_id"]);
		$this->setItemCount($obj["item_count"]);
		$this->setItemcode($obj["itemcode"]);

		return True;
	}

	function save() {
		global $db;
		/**
			DB�˼�ʬ���Ȥ���¸����
		**/
		if($this->isnew) {
			$sql = "INSERT INTO cartItem ";
			$sql .= "(id,base,transaction_id,item_count,itemcode)";
			$sql .= " VALUES ";
			$sql .= "(?,?,?,?,?)";
			$data = array(
				$this->id,
				$this->base,
				$this->transaction_id,
				$this->item_count,
				$this->itemcode);
		} else {
			$sql = "UPDATE cartItem SET ";
			$sql .= "item_count=? WHERE id=?";
			$data = array(
				$this->item_count,
				$this->id);
		}
		
		$st = $db->prepare($sql);
		$db->execute($st, $data);
		$this->isnew = 0;
	}
}




class Cart {

	/** Ϣ�� **/
	var $seq;

	/** ���̻� **/
	var $id;

	/** �桼��ID **/
	var $base;

	/** ��ʸ������ **/
	var $tr_name;
	var $tr_zip;
	var $tr_adr;
	var $tr_mail;
	var $tr_tel;

	/** ���� **/
	// 0: ��ʪ����
	// 1: ��ʸ��
	// 2: ����ѡ�̤ȯ����
	// 3: ȯ����
	// 4: ��ʸ��������������̤��ǧ
	var $tr_status;


	/** ������ **/
	var $rc_name;
	var $rc_zip;
	var $rc_adr;
	var $rc_tel;

	/** ���� **/
	var $delivery;

	/** ������ **/
	var $cr_unixtime;

	/** ������ **/
	var $sm_unixtime;

	/** ���� **/
	var $message;

	/** ��ʧ���� **/
	var $payment;

	/** ��ʧ��Ϣ���� **/
	/** �����ܺ����� **/
	var $payinfo01;	   ## ���ݶ������Ǥ���� 1
	var $payinfo02;    ## ��������
	var $payinfo03;    ## ����Ͱ�����
	var $payinfo04;    ## �ݥ�����Ͱ�����
	var $payinfo05;    ## ͹�ؿ���Ϣ��
	var $payinfo06;    ## �������
	var $payinfo07;    ## ��������
	var $payinfo08;    ## ȯ������

	/** �����߼�����ʸ���� **/

	/** ��ʪ **/
	/** ���CartItem���֥������Ȥ��Ǽ�������� **/
	var $items;

	/** ����flg **/
	var $isnew;

	/** ������ɬ�פ��ɤ��� **/
	var $hasDelivery;

	var $msg = "";

	function Cart() {
		global $user;
		$this->items = array();
		$this->base = $user->base_id;
	}

	function setId($id="") {
		if($id == "") {
			$this->id = md5(uniqid("CARTASDFGFAA"));
			$this->cr_unixtime = time();

			$this->isnew = 1;
		} else {
			$this->id = $id;
			$this->isnew = 0;
		}

	}

	function setTrStatus($tr_status) {
		global $db;

		$this->sm_unixtime = time();

		$this->tr_status = $tr_status;

		$sql = "UPDATE Cart SET tr_status=?,sm_unixtime=? WHERE id=? AND base=?";
		$data = array($this->tr_status, $this->sm_unixtime, $this->id, $this->base);
		$st = $db->prepare($sql);
		$db->execute($st, $data);

	}

	function decrStock() {
		$ret = True;
		foreach($this->items as $item) {
			$retval = $item->decrStock();
			if(!$retval) {
				$this->msg .= "�������������ޤ��󡣾��ʡ�" . $item->item_name;
				$this->msg .= "�פ���ʸ�����μ¹���˺߸ˤ��ʤ��ʤ�ޤ�����<br>";
				$this->msg .= "�㤤ʪ��������Ȥ򤴳�ǧ����������";
				$item->remove();
				$ret = False;
			}
		}
		return $ret;
	}


	function purchase() {
		global $db;
		/**
			�桼����������¹Ԥ������˼¹Ԥ���᥽�å�
			tr_status��1(��ʸ�Ѥ�)�˥��åȤ���
		**/
		$this->tr_status = 1;
		$this->sm_unixtime = time();
		//$this->save();

		$total = 0;
		foreach($this->items as $item) {
			$s_total = $item->item_price * $item->item_count;
			$total += $s_total;
		}

		$sql = "UPDATE Cart SET tr_status=1,sm_unixtime=?,payinfo06=? WHERE id=? AND base=?";
		$data = array(time(), $total, $this->id, $this->base);
		$st = $db->prepare($sql);
		$db->execute($st, $data);
	}


	function calcDelivery($postal) {
		/**
			���ֹ椫����������׻�����
			dbconn.php�˵��Ҥδؿ�calcDelivery()����Ѥ��Ƥ��ޤ�
		**/
		$delv = calcDelivery($postal);
		$this->setDelivery($delv);
	}


	function sendMail() {

		// �������ʤ����ν���
		// ��ʸ��λ
		global $payment_jp;
		global $kouza_no;

		$mailStr = $this->tr_mail;
		$todayStr = date("Y-m-d H:i:s", $this->sm_unixtime);
		$customId = $this->base;
		$kouzaStr = $payment_jp[$this->payment];
		$kouzaStr .= " " . $kouza_no[$this->payment];

		$calcStr = "����̾\tñ��\t��ʸ��\t����\n";
		$calcStr .= "--------------------------------------------------------------\n";

		$total = 0;
		foreach($this->items as $item) {
			$calcStr .= $item->item_name . "\t";
			$calcStr .= $item->item_price . "\t";
			$calcStr .= $item->item_count . "\t";
			$s_total = $item->item_price * $item->item_count;
			$total += $s_total;
			$calcStr .= $s_total . " ��\n";
		}
		$calcStr .= "--------------------------------------------------------------\n";
		$calcStr .= "���\t" . $total . " ��";
		$tranId = $this->id;
		$nameStr = $this->tr_name;
		$telStr = $this->tr_tel;
		$zipStr = $this->tr_zip;
		$adrStr = $this->tr_adr;
##
## �桼������ʸ���Ƴ�ǧ�᡼�������
##
$mailtext = <<<EOF
Subject: =?ISO-2022-JP?B?GyRCIVpAaiQkSTQyX0U5JTklRiVpIVsbKEIg?=
 =?ISO-2022-JP?B?GyRCJCo8aDB6RmJNRiROJDQzTkcnGyhC?=
From: shop@saimitsu.jp
To: $mailStr
Content-Type: text/plain; charset=ISO-2022-JP
Content-Transfer-Encoding: 7bit
Mime-Version: 1.0

����ʸ����
��ʸ���� $todayStr
�������ֹ桧 $customId
��ʸ������ ��λ
��ʧ��ˡ�� $kouzaStr
ʧ����ۡ� $total ��

����Կ����ξ��ϡ����������̾�������˾嵭�������ֹ� $customId ��
�����줯�������������ֹ�ϡ�����¾�����������Ƥ䤪����ˤĤ��ƤΤ�
�䤤��碌�κݤ�ɬ�פȤʤ�ޤ��Τǡ����ڤ˹����Ƥ���������
�����������Τ�̾���Ȱۤʤ�̾���Ǥ����⤵�줿���ϳ�ǧ���٤��ʤ���
���������ޤ���������Ǥ�����Ϣ��򤪴ꤤ�������ޤ���
��������ǤΤ�����ξ��⤪����Ǥ�����Ϣ��ꤤ�ޤ���
��������������Ϥޤ��Ȥ˶�������ޤ��������ͤΤ���ô�Ǥ��ꤤ�������ޤ���

--------------------------------------------------------------
�����Ѥ��꤬�Ȥ��������ޤ��������ͤ�ꤤ������������ʸ��
�������̤�Ǥ������Ƥ򤴳�ǧ����������
--------------------------------------------------------------
̾����$nameStr
���á�$telStr
͹���ֹ桧$zipStr
���ꡧ$adrStr
�Żҥ᡼�롧$mailStr

��ʸID��$tranId
��ʸ��
--------------------------------------------------------------
$calcStr



EOF;

		$mailtext = mb_convert_encoding($mailtext,"jis","euc");
		$cmd = "/usr/sbin/sendmail -fshop@saimitsu.jp $mailStr";
		$cmd = escapeshellcmd($cmd);
		$fp = popen($cmd, "w");
		fwrite($fp, $mailtext);
		pclose($fp);

##
## shop@saimitsu.jp �ˡ���ʸ���Ƥι�����᡼�������
##
$mailtext2 = <<<EOF
Subject: =?ISO-2022-JP?B?GyRCIVpDbUo4OTUkKCFbGyhC?=
 $customId
From: shop@saimitsu.jp
To: shop@saimitsu.jp
Content-Type: text/plain; charset=ISO-2022-JP
Content-Transfer-Encoding: 7bit
Mime-Version: 1.0

��ʸ��

��ʸ���� $todayStr
�������ֹ桧 $customId
��ʸ������ ��λ
��ʧ��ˡ�� $kouzaStr
ʧ����ۡ� $total ��

̾����$nameStr
���á�$telStr
͹���ֹ桧$zipStr
���ꡧ$adrStr
�Żҥ᡼�롧$mailStr

��ʸID��$tranId
��ʸ��
--------------------------------------------------------------
$calcStr



EOF;
		$mailtext2 = mb_convert_encoding($mailtext2,"jis","euc");
		$cmd = "/usr/sbin/sendmail -fshop@saimitsu.jp shop@saimitsu.jp";
		$cmd = escapeshellcmd($cmd);
		$fp = popen($cmd, "w");
		fwrite($fp, $mailtext2);
		pclose($fp);



	}




	function sendMailDelivery() {
		// ���sendMail()��Ʊ�ͤ���
		// ��ʪ������������������ν���
		// ��ʸ��λ
		global $payment_jp;
		global $kouza_no;

		$mailStr = $this->tr_mail;
		$todayStr = date("Y-m-d H:i:s", $this->sm_unixtime);
		$customId = $this->base;
		$kouzaStr = $payment_jp[$this->payment];
		$kouzaStr .= " " . $kouza_no[$this->payment];
		$kouzaStr .= " ������ҥ磻����������";

		$calcStr = "����̾\tñ��\t��ʸ��\t����\n";
		$calcStr .= "--------------------------------------------------------------\n";

		$total = 0;
		foreach($this->items as $item) {
			$calcStr .= $item->item_name . "\t";
			$calcStr .= $item->item_price . "\t";
			$calcStr .= $item->item_count . "\t";
			$s_total = $item->item_price * $item->item_count;
			$total += $s_total;
			$calcStr .= $s_total . " ��\n";
		}

		if($this->payinfo03>0) {
			$calcStr .= "����Ͱ�����\t\t\t-" . $this->payinfo03 . " ��\n";
			$total -= $this->payinfo03;
		}


		if($this->payinfo04>0) {
			$calcStr .= "�ݥ���Ȥ�����\t\t\t-" . $this->payinfo04 . " ��\n";
			$total -= $this->payinfo04;
		}

		if($this->payinfo02>0) {
			$calcStr .= "��������\t\t\t" . $this->payinfo02 . " ��\n";
			$total += $this->payinfo02;
		}

		if($this->delivery > 1) {
			$calcStr .= "����\t\t\t" . $this->delivery . " ��\n";
			$total += $this->delivery;
		}
	

		$calcStr .= "--------------------------------------------------------------\n";
		$calcStr .= "���\t" . $total . " ��";
		$tranId = $this->id;
		$nameStr = $this->tr_name;
		$telStr = $this->tr_tel;
		$zipStr = $this->tr_zip;
		$adrStr = $this->tr_adr;

		$rc_nameStr = $this->rc_name;
		$rc_telStr = $this->rc_tel;
		$rc_zipStr = $this->rc_zip;
		$rc_adrStr = $this->rc_adr;


		$rcpt = "shop@saimitsu.jp";

$mailtext = <<<EOF
Subject: =?ISO-2022-JP?B?GyRCIVpAaiQkSTQyX0U5JTklRiVpIVsbKEIg?=
 =?ISO-2022-JP?B?GyRCJCo8aDB6RmJNRiROJDQzTkcnGyhC?=
From: shop@saimitsu.jp
To: $mailStr
Content-Type: text/plain; charset=ISO-2022-JP
Content-Transfer-Encoding: 7bit
Mime-Version: 1.0

����ʸ����
��ʸ���� $todayStr
�������ֹ桧 $customId
��ʸ������ ��λ
��ʧ��ˡ�� $kouzaStr
ʧ����ۡ� $total ��

����Կ����ξ��ϡ����������̾�������˾嵭�������ֹ� $customId ��
�����줯�������������ֹ�ϡ�����¾�����������Ƥ䤪����ˤĤ��ƤΤ�
�䤤��碌�κݤ�ɬ�פȤʤ�ޤ��Τǡ����ڤ˹����Ƥ���������
�����������Τ�̾���Ȱۤʤ�̾���Ǥ����⤵�줿���ϳ�ǧ���٤��ʤ���
���������ޤ���������Ǥ�����Ϣ��򤪴ꤤ�������ޤ���
��������ǤΤ�����ξ��⤪����Ǥ�����Ϣ��ꤤ�ޤ���
��������������Ϥޤ��Ȥ˶�������ޤ��������ͤΤ���ô�Ǥ��ꤤ�������ޤ���
�������Ϥ����Ϥ���������������͹���ֹ椫��׻����Ƥ���ޤ�������ۤ�
��㤢����Ϥ��䤤��碌����������

--------------------------------------------------------------
�����Ѥ��꤬�Ȥ��������ޤ��������ͤ�ꤤ������������ʸ��
�������̤�Ǥ������Ƥ򤴳�ǧ����������
--------------------------------------------------------------
��ʸ�Ծ���
̾����$nameStr
���á�$telStr
͹���ֹ桧$zipStr
���ꡧ$adrStr
�Żҥ᡼�롧$mailStr

���������
̾����$rc_nameStr
���á�$rc_telStr
͹���ֹ桧$rc_zipStr
���ꡧ$rc_adrStr


��ʸID��$tranId
��ʸ��
--------------------------------------------------------------
$calcStr



EOF;

		$mailtext = mb_convert_encoding($mailtext,"jis","euc");
		$cmd = "/usr/sbin/sendmail -fshop@saimitsu.jp $mailStr";
		$cmd = escapeshellcmd($cmd);
		$fp = popen($cmd, "w");
		fwrite($fp, $mailtext);
		pclose($fp);



$mailtext2 = <<<EOF
Subject: =?ISO-2022-JP?B?GyRCIVpETEhOQ21KODk1JCghWxsoQg==?=
 $customId
From: shop@saimitsu.jp
To: shop@saimitsu.jp
Content-Type: text/plain; charset=ISO-2022-JP
Content-Transfer-Encoding: 7bit
Mime-Version: 1.0

��ʸ��

��ʸ���� $todayStr
�������ֹ桧 $customId
��ʸ������ ��λ
��ʧ��ˡ�� $kouzaStr
ʧ����ۡ� $total ��

��ʸ�Ծ���
̾����$nameStr
���á�$telStr
͹���ֹ桧$zipStr
���ꡧ$adrStr
�Żҥ᡼�롧$mailStr

���������
̾����$rc_nameStr
���á�$rc_telStr
͹���ֹ桧$rc_zipStr
���ꡧ$rc_adrStr


��ʸID��$tranId
��ʸ��
--------------------------------------------------------------
$calcStr



EOF;
		$mailtext2 = mb_convert_encoding($mailtext2,"jis","euc");
		$cmd = "/usr/sbin/sendmail -fshop@saimitsu.jp shop@saimitsu.jp";
		$cmd = escapeshellcmd($cmd);
		$fp = popen($cmd, "w");
		fwrite($fp, $mailtext2);
		pclose($fp);
	}




	##
	## ���ޤ��ޤʥ��顼��å������ʤɤ���¸���褦�Ȥ��������ȤäƤ��ޤ���
	##
	function setMessage($msg) {
		$this->message = $msg;
	}



	function changeOwner($base) {
		global $db;
		/**
			��ͭ��ID($base)���ѹ����뤳�Ȥǡ�
			��ͭ�Ԥ��ѹ����롥

			����ϡ������󤻤�����ʪ�򳫻Ϥ����桼������
			����ǥ�����򤷤����ˡ�
			�����Ȥ�ꥻ�åȤ����ˡ���ʪ�ξ��֤������Ѥ����褦��
			���뤿��Ǥ��롥
		
		**/
		if(ereg("^[0-9]+$",$base)) {
			$sql = "UPDATE Cart SET base=? ";
			$sql .= "WHERE id=? AND base=?";
			$data = array(
				$base,
				$this->id,
				$this->base);
			$st = $db->prepare($sql);
			$db->execute($st, $data);
			$this->base = $base;
		}
		foreach($this->items as $item) {
			$item->changeOwner($base);
		}
	}

	function addItem($item) {
		/**
			��ʪ��������ʪ�������
		**/
		if($this->id=="") {
			$this->setId();
		}
		$item->transaction_id = $this->id;
		$this->items[] = $item;
	}

	function removeItem($item_id) {
		/**
			��ʪ����������ʪ��Ф�
		**/
		if(is_object($this->items["$item_id"])) {
			$this->items["$item_id"]->remove();
		}		
		//$this->save();
	}

	function changeCount($item_id, $count) {
		/**
			�Ŀ����ѹ�����
		**/
		$this->items["$item_id"]->setItemCount($count);
		$this->items["$item_id"]->save();
	}

	function setPayment($payment) {
		$this->payment = $payment;

	}

	function usePoint($point) {
		/**
			��ʪ�ι����˺ݤ����ݥ���Ȥ���񤹤�
		**/
		$this->payinfo04 = $point;
	}

	function setTrInfo($tr_name, $tr_zip, $tr_adr, $tr_tel, $tr_mail) {
		/**
			�����Ծ���Υ��å�
		**/
		$this->tr_name = $tr_name;
		$this->tr_zip = $tr_zip;
		$this->tr_adr = $tr_adr;
		$this->tr_tel = $tr_tel;
		$this->tr_mail = $tr_mail;

	}

	function setRcInfo($rc_name, $rc_zip, $rc_adr, $rc_tel) {
		/**
			��ʪ�μ���;���Υ��å�
		**/
		$this->rc_name = $rc_name;
		$this->rc_zip = $rc_zip;
		$this->rc_adr = $rc_adr;
		$this->rc_tel = $rc_tel;
	}


	function load() {
		global $db;


		/**
			DB���鼫ʬ���Ȥ���ɤ���
		**/
		if($this->isnew) return False;

		$sql = "SELECT * FROM Cart ";
		$sql .= "WHERE id=?";
		$st = $db->prepare($sql);
		$res = $db->execute($st, $this->id);

		$obj = $res->fetchRow(DB_FETCHMODE_ASSOC);

		$this->seq = $obj["seq"];
		$this->base = $obj["base"];
		$this->tr_name = $obj["tr_name"];
		$this->tr_zip = $obj["tr_zip"];
		$this->tr_adr = $obj["tr_adr"];
		$this->tr_mail = $obj["tr_mail"];
		$this->tr_tel = $obj["tr_tel"];
		$this->tr_status = $obj["tr_status"];

		$this->rc_name = $obj["rc_name"];
		$this->rc_zip = $obj["rc_zip"];
		$this->rc_adr = $obj["rc_adr"];
		$this->rc_tel = $obj["rc_tel"];

		$this->cr_unixtime = $obj["cr_unixtime"];
		$this->sm_unixtime = $obj["sm_unixtime"];

		$this->payment = $obj["payment"];
		$this->payinfo01 = $obj["payinfo01"];
		$this->payinfo02 = $obj["payinfo02"];
		$this->payinfo03 = $obj["payinfo03"];
		$this->payinfo04 = $obj["payinfo04"];
		$this->payinfo05 = $obj["payinfo05"];
		$this->payinfo06 = $obj["payinfo06"];
		$this->payinfo07 = $obj["payinfo07"];
		$this->payinfo08 = $obj["payinfo08"];


		$this->delivery = $obj["delivery"];

		$this->loadItem();
		return True;
	}

	function setDelivery($dl) {
		global $db;
		$this->delivery = $dl;
		/**
			��������DB�˽񤭹���
		**/
		$sql = "UPDATE Cart SET delivery=? WHERE id=? AND base=?";
		$data = array($this->delivery, $this->id, $this->base);
		$st = $db->prepare($sql);
		$db->execute($st, $data);

	}

	function save() {
		global $db;
		/**
			��ʬ���Ȥ�DB����¸����
		**/
		if($this->id=="") {
			$this->setId();
		}

		if($this->isnew) {
			$sql = "INSERT INTO Cart ";
			$sql .= "(id,base,tr_status,cr_unixtime,";
			$sql .= "tr_name,tr_zip,tr_adr,tr_tel,tr_mail,";
			$sql .= "rc_name,rc_zip,rc_adr,rc_tel,";
			$sql .= "delivery,payment,message,";
			$sql .= "payinfo01,payinfo02,payinfo03,payinfo04,";
			$sql .= "payinfo05,payinfo06,payinfo07,payinfo08";
			$sql .= ")";
			$sql .= " VALUES ";
			$sql .= "(?,?,?,?,";
			$sql .= "?,?,?,?,?,";
			$sql .= "?,?,?,?,";
			$sql .= "?,?,?,";
			$sql .= "?,?,?,?,";
			$sql .= "?,?,?,?)";
			$data = array(
				$this->id,
				$this->base,
				0,
				$this->cr_unixtime,
				$this->tr_name,
				$this->tr_zip,
				$this->tr_adr,
				$this->tr_tel,
				$this->tr_mail,
				$this->rc_name,
				$this->rc_zip,
				$this->rc_adr,
				$this->rc_tel,
				$this->delivery,
				$this->payment,
				$this->message,
				$this->payinfo01,
				$this->payinfo02,
				$this->payinfo03,
				$this->payinfo04,
				$this->payinfo05,
				$this->payinfo06,
				$this->payinfo07,
				$this->payinfo08
				);
		} else {
			$sql = "UPDATE Cart SET ";
			$sql .= "tr_name=?, tr_zip=?, tr_adr=?, tr_mail=?,";
			$sql .= "tr_tel=?, tr_status=?, rc_name=?, rc_zip=?,";
			$sql .= "rc_adr=?, rc_tel=?, sm_unixtime=?,";
			$sql .= "payment=?,";
			$sql .= "message=?,delivery=?,";
			$sql .= "payinfo01=?, payinfo02=?,";
			$sql .= "payinfo03=?, payinfo04=?,";
			$sql .= "payinfo05=?, payinfo06=?,";
			$sql .= "payinfo07=?, payinfo08=? ";
			$sql .= " WHERE id=?";
			$data = array(
				$this->tr_name,
				$this->tr_zip,
				$this->tr_adr,
				$this->tr_mail,
				$this->tr_tel,
				$this->tr_status,
				$this->rc_name,
				$this->rc_zip,
				$this->rc_adr,
				$this->rc_tel,
				$this->sm_unixtime,
				$this->payment,
				$this->message,
				$this->delivery,
				$this->payinfo01,
				$this->payinfo02,
				$this->payinfo03,
				$this->payinfo04,
				$this->payinfo05,
				$this->payinfo06,
				$this->payinfo07,
				$this->payinfo08,
				$this->id);
		}

		$st = $db->prepare($sql);
		$db->execute($st, $data);
		$this->saveItem();

		return True;


	}

	function saveItem() {
		/**
			��ʪ�����������ʪ��DB����¸����
		**/
		foreach($this->items as $item) {
			$item->save();
		}
		return True;
	}

	function clearAll() {
		/**
			��ʪ��������ˤ���
		**/
		foreach($this->items as $item) {
			$item->remove();
			unset($item);
		}
		$this->items = array();
	}

	function loadItem() {
		global $db;

		/**
			��ʪ������������äƤ�����ʪ��DB������ɤ��롥
		**/
		$ids = array();
		$sql = "SELECT id FROM cartItem ";
		$sql .= "WHERE base=? AND transaction_id=?";
		$sql .= " ORDER by seq";
		$data = array($this->base, $this->id);
		$st = $db->prepare($sql);
		$res = $db->execute($st, $data);
		$n=0;
		while($row = $res->fetchRow()) {
			$ids[$n] = $row[0];
			$n++;
		}
		$this->items = array();
		for($i=0; $i<$n; $i++) {
			$key = $ids[$i];
			$this->items[$key] = new CartItem();
			$this->items[$key]->setId($key);
			$this->items[$key]->load();
			$this->hasDelivery += $this->items[$key]->master->delivery;
		}

	}


	function printOut() {
		/**
			���٤���Ϥ���
		**/
?>
<table border="1" cellspacing="0" cellpadding="5">
<tr>
<td>����̾</td>
<td>ñ  ��</td>
<td>��ʸ��</td>
<td>��  ��</td>
</tr>
<?php
$total = 0;
$shouhin_total = 0;

// �������٤�������

foreach($this->items as $item) {
?>
<tr>
<td><?php echo $item->item_name; ?></td>
<td><?php echo $item->item_price; ?> ��</td>
<td><?php echo $item->item_count; ?></td>
<td><?php 
$s_total = $item->item_price * $item->item_count;
$total += $s_total;

// �ݥ���Ȥϲ������оݤ˴ޤ�ʤ��Τǡ�
// ���ξ��ʤΤߤι�פ򻻽С�
if($item->master->category != 'PT') {
	$shouhin_total += $s_total;
}


echo $s_total;
?> ��</td>
</tr>
<?php
}
//
// �������٤����ޤ�
//

//
// ����Ͱ�����������
//
global $user;

// �ݥ���Ƚ��orͭ�����flg�ǳ��ȯ��
if(0<$user->point || 0<$user->usertype) {
	$this->payinfo03 = intval($shouhin_total * 0.05);
	if($this->payinfo03 > 0) {
?>
<tr>
<td colspan="3">����Ͱ���</td>
<td>-<?php 
$total -= $this->payinfo03;
echo $this->payinfo03;
?> ��</td>
<?php
	}
}

//
// ����Ͱ��������ޤ�
//


//
// �ݥ�������Ѥ�������
//
if(0<$this->payinfo04) {
?>
<tr>
<td colspan="3">�ݥ���Ȥ������Ͱ���</td>
<td>-<?php 
$total -= $this->payinfo04;
echo $this->payinfo04;
?> ��</td>
<?php
}
//
// �ݥ�������Ѥ����ޤ�
//

//
// ����ɽ����������
//
if(1<$this->delivery) {
?>
<tr>
<td colspan="3">����</td>
<td align="right"><?php 
$total += $this->delivery;
echo $this->delivery;
?> ��</td>
<?php
}
//
// ����ɽ�������ޤ�
//

//
// �������������
//
if($this->payment == 'YMT') {
	if($total < 10000) {
		$daibiki = 315;
	} else if($total < 30000) {
		$daibiki = 420;
	} else if($total < 100000) {
		$daibiki = 630;
	} else if($total <= 300000) {
		$daibiki = 1050;
	}
$this->payinfo02 = $daibiki;
?>
<tr>
<td colspan="3">��������</td>
<td align="right"><?php 
$total += $daibiki;
echo $daibiki;
?> ��</td>
</tr>
<?php
}
//
// ��������ޤ�
//

?>
<tr>
<td colspan="3">��  ��</td>
<td><?php echo $total; ?> ��</td>
</tr>
<?php


$this->payinfo06 = $total;
$this->save();
//
// ������������
//
if($this->hasDelivery>0 && $this->delivery < 100) {
?>
<tr>
<td colspan="4" align="right">
������������������ޤ���<a href="souryou.php" target="_blank">���ܤ����Ϥ�����</a>��
</td>
</tr>
<?php
}
//
// ���������ޤ�
//


?>
</table>
<?php

	}


}







?>
