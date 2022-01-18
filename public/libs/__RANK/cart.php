<?php

$payment_jp = array(
	"JNB" => "ジャパンネット銀行",
	"RSN" => "みずほ銀行",
	"MUF" => "東京三菱ＵＦＪ銀行",
	"PST" => "郵便振替",
	"TST" => "東京スター銀行",
	"SMB" => "三井住友銀行",
	"YMT" => "代金引換",	
);

$kouza_no = array(
	"JNB" => "本店営業部 （普）001-2695831 カ）ワイズクエスト",
	"RSN" => "麹町支店 （普）2135449",
	"MUF" => "麹町支店 （普）4514762",
	"PST" => "口座記号番号 00130-6-631616 振替用紙を送付いたします。",
	"TST" => "",
	"SMB" => "赤坂支店 （普）8523755"
);
	





class shopItem {
	var $id;
	var $category;	## 00..99
	var $itemcode;	## 0000..9999
	var $itemname;
	var $price;	## 税抜本体価格
	var $delivery;	## 送料（離島除く）
	var $manufacturer;	## 製造元
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

	var $stock;	## 在庫
	var $arrival_span;	## 再入荷時期

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
				## 新規入力ではないのに存在しない
				if($result < 1) {
					$this->errmsg = "shopItem: no item found with code " . $this->itemcode . ".";
					return False;
				} else {
					return True;
				}
			} else {
				## 新規入力なのに既に存在する
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
		## 在庫減少
		## returns:
		## 在庫不足 -3
		## 正常 1

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
		## 在庫追加
		## returns:
		## 正常 1

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
			$this->arrival_span = "未定";
		}
		$res->free();

	}
}

##
## CartItemオブジェクトは，Cartオブジェクトのメンバーオブジェクトとなる．
## Cartの中にCartItemが入っているイメージです．
##
class CartItem {
	/** 連番 **/
	var $seq;

	/** オブジェクト識別子 **/
	var $id;

	/** ユーザID **/
	var $base;

	/** 
	注文ID
	親Cartオブジェクトとこのアイテムを結びつけるキーの役割 
	**/
	var $transaction_id;

	var $item_name;
	var $item_price;
	var $item_count;

	/** 商品番号 **/
	var $itemcode;

	/** 商品マスタ上の情報 **/
	var $master;

	/** 
	 新規作成フラグ
	 DB更新時に挙動が異なる
 
	 if(this->isnew) "INSERT INTO ..."
	 else "UPDATE ..."

	**/
	var $isnew;

	function CartItem($id="") {
		/** constructor **/
		global $user;

		$this->_init();
		$this->setId($id);

		/** 新規でなければDBに存在するのでロードする **/
		if(!$this->isnew) {
			$this->load();
		}
		/** 商品マスタからデータをロードする **/
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
			引数として既存のIDを与えなければ，
			自動的に新規作成フラグが立ち，
			新しいIDが付与される．
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
			所有者ID($base)を変更することで，
			所有者を変更する．

			これは，ログインせずに買物を開始したユーザが，
			途中でログインをした場合に，
			カートをリセットせずに，買物の状態が引き継がれるように
			するためである．
		
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
			自分自身をDBから消す
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
			親カートオブジェクトのIDをセットする
		**/
		$this->transaction_id = $id;
	}

	function setItemCount($count) {
		/** 個数を変更する
		 注文発生後も変更可能
		 在庫管理処理が未実装 **/
		$this->item_count = $count;
		return True;
	}
	function setItemcode($itemcode) {
		/**
			自身のitemcodeが0である = 未設定の状態
			であれば，itemcodeをセットする
			すでにセットされていた場合，変更は許されない
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
		/** 在庫を減少させる **/
		/** PT (ポイント)の在庫は無限なので，常に真を返す **/
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
			在庫不足の場合，偽を返す
			在庫が足りていれば，在庫を減らす
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
			商品マスタから商品情報をロードする
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
			DBに保存された自分自身をロードする
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
			DBに自分自身を保存する
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

	/** 連番 **/
	var $seq;

	/** 識別子 **/
	var $id;

	/** ユーザID **/
	var $base;

	/** 注文した人 **/
	var $tr_name;
	var $tr_zip;
	var $tr_adr;
	var $tr_mail;
	var $tr_tel;

	/** 状態 **/
	// 0: 買物途中
	// 1: 注文済
	// 2: 入金済（未発送）
	// 3: 発送済
	// 4: 注文受けたが、送料未確認
	var $tr_status;


	/** 送付先 **/
	var $rc_name;
	var $rc_zip;
	var $rc_adr;
	var $rc_tel;

	/** 送料 **/
	var $delivery;

	/** 作成日 **/
	var $cr_unixtime;

	/** 確定日 **/
	var $sm_unixtime;

	/** 伝言 **/
	var $message;

	/** 支払方式 **/
	var $payment;

	/** 支払関連情報 **/
	/** 現状詳細不明 **/
	var $payinfo01;	   ## 新課金方式であれば 1
	var $payinfo02;    ## 代引手数料
	var $payinfo03;    ## 会員値引き額
	var $payinfo04;    ## ポイント値引き額
	var $payinfo05;    ## 郵便振替連番
	var $payinfo06;    ## 購入総額
	var $payinfo07;    ## 入金日付
	var $payinfo08;    ## 発送日付

	/** 振込み識別用文字列 **/

	/** 品物 **/
	/** 上のCartItemオブジェクトを格納する配列 **/
	var $items;

	/** 新規flg **/
	var $isnew;

	/** 配送が必要かどうか **/
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
				$this->msg .= "申し訳ございません。商品「" . $item->item_name;
				$this->msg .= "」は注文処理の実行中に在庫がなくなりました。<br>";
				$this->msg .= "買い物かごの中身をご確認ください。";
				$item->remove();
				$ret = False;
			}
		}
		return $ret;
	}


	function purchase() {
		global $db;
		/**
			ユーザが購入を実行した時に実行するメソッド
			tr_statusを1(注文済み)にセットする
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
			〒番号から配送料を計算する
			dbconn.phpに記述の関数calcDelivery()を使用しています
		**/
		$delv = calcDelivery($postal);
		$this->setDelivery($delv);
	}


	function sendMail() {

		// 配送がない場合の処理
		// 注文完了
		global $payment_jp;
		global $kouza_no;

		$mailStr = $this->tr_mail;
		$todayStr = date("Y-m-d H:i:s", $this->sm_unixtime);
		$customId = $this->base;
		$kouzaStr = $payment_jp[$this->payment];
		$kouzaStr .= " " . $kouza_no[$this->payment];

		$calcStr = "商品名\t単価\t注文数\t小計\n";
		$calcStr .= "--------------------------------------------------------------\n";

		$total = 0;
		foreach($this->items as $item) {
			$calcStr .= $item->item_name . "\t";
			$calcStr .= $item->item_price . "\t";
			$calcStr .= $item->item_count . "\t";
			$s_total = $item->item_price * $item->item_count;
			$total += $s_total;
			$calcStr .= $s_total . " 円\n";
		}
		$calcStr .= "--------------------------------------------------------------\n";
		$calcStr .= "合計\t" . $total . " 円";
		$tranId = $this->id;
		$nameStr = $this->tr_name;
		$telStr = $this->tr_tel;
		$zipStr = $this->tr_zip;
		$adrStr = $this->tr_adr;
##
## ユーザに注文内容確認メールを送信
##
$mailtext = <<<EOF
Subject: =?ISO-2022-JP?B?GyRCIVpAaiQkSTQyX0U5JTklRiVpIVsbKEIg?=
 =?ISO-2022-JP?B?GyRCJCo8aDB6RmJNRiROJDQzTkcnGyhC?=
From: shop@saimitsu.jp
To: $mailStr
Content-Type: text/plain; charset=ISO-2022-JP
Content-Transfer-Encoding: 7bit
Mime-Version: 1.0

ご注文内容
注文日： $todayStr
お客様番号： $customId
注文状況： 完了
支払方法： $kouzaStr
払込金額： $total 円

※銀行振込の場合は、お振り込み名義の前に上記お客様番号 $customId を
お入れください。この番号は、その他、お申込内容やお取引についてのお
問い合わせの際に必要となりますので、大切に控えてください。
※お申込時のお名前と異なる名義でご入金された場合は確認が遅くなる場合
がございます。お手数ですがご連絡をお願いいたします。
※金額相違でのご入金の場合もお手数ですがご連絡願います。
※お振込手数料はまことに恐れ入りますがお客様のご負担でお願いいたします。

--------------------------------------------------------------
ご利用ありがとうございます。お客様よりいただいたご注文は
下記の通りです。内容をご確認ください。
--------------------------------------------------------------
名前：$nameStr
電話：$telStr
郵便番号：$zipStr
住所：$adrStr
電子メール：$mailStr

注文ID：$tranId
注文品
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
## shop@saimitsu.jp に，注文内容の控えをメールで送る
##
$mailtext2 = <<<EOF
Subject: =?ISO-2022-JP?B?GyRCIVpDbUo4OTUkKCFbGyhC?=
 $customId
From: shop@saimitsu.jp
To: shop@saimitsu.jp
Content-Type: text/plain; charset=ISO-2022-JP
Content-Transfer-Encoding: 7bit
Mime-Version: 1.0

注文控

注文日： $todayStr
お客様番号： $customId
注文状況： 完了
支払方法： $kouzaStr
払込金額： $total 円

名前：$nameStr
電話：$telStr
郵便番号：$zipStr
住所：$adrStr
電子メール：$mailStr

注文ID：$tranId
注文品
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
		// 上のsendMail()と同様だが
		// 荷物の配送処理がある場合の処理
		// 注文完了
		global $payment_jp;
		global $kouza_no;

		$mailStr = $this->tr_mail;
		$todayStr = date("Y-m-d H:i:s", $this->sm_unixtime);
		$customId = $this->base;
		$kouzaStr = $payment_jp[$this->payment];
		$kouzaStr .= " " . $kouza_no[$this->payment];
		$kouzaStr .= " 株式会社ワイズクエスト";

		$calcStr = "商品名\t単価\t注文数\t小計\n";
		$calcStr .= "--------------------------------------------------------------\n";

		$total = 0;
		foreach($this->items as $item) {
			$calcStr .= $item->item_name . "\t";
			$calcStr .= $item->item_price . "\t";
			$calcStr .= $item->item_count . "\t";
			$s_total = $item->item_price * $item->item_count;
			$total += $s_total;
			$calcStr .= $s_total . " 円\n";
		}

		if($this->payinfo03>0) {
			$calcStr .= "会員値引き額\t\t\t-" . $this->payinfo03 . " 円\n";
			$total -= $this->payinfo03;
		}


		if($this->payinfo04>0) {
			$calcStr .= "ポイントご利用\t\t\t-" . $this->payinfo04 . " 円\n";
			$total -= $this->payinfo04;
		}

		if($this->payinfo02>0) {
			$calcStr .= "代引手数料\t\t\t" . $this->payinfo02 . " 円\n";
			$total += $this->payinfo02;
		}

		if($this->delivery > 1) {
			$calcStr .= "送料\t\t\t" . $this->delivery . " 円\n";
			$total += $this->delivery;
		}
	

		$calcStr .= "--------------------------------------------------------------\n";
		$calcStr .= "合計\t" . $total . " 円";
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

ご注文内容
注文日： $todayStr
お客様番号： $customId
注文状況： 完了
支払方法： $kouzaStr
払込金額： $total 円

※銀行振込の場合は、お振り込み名義の前に上記お客様番号 $customId を
お入れください。この番号は、その他、お申込内容やお取引についてのお
問い合わせの際に必要となりますので、大切に控えてください。
※お申込時のお名前と異なる名義でご入金された場合は確認が遅くなる場合
がございます。お手数ですがご連絡をお願いいたします。
※金額相違でのご入金の場合もお手数ですがご連絡願います。
※お振込手数料はまことに恐れ入りますがお客様のご負担でお願いいたします。
※送料はご入力いただいた送付先郵便番号から計算しておりますが、金額に
相違ある場合はお問い合わせください。

--------------------------------------------------------------
ご利用ありがとうございます。お客様よりいただいたご注文は
下記の通りです。内容をご確認ください。
--------------------------------------------------------------
注文者情報
名前：$nameStr
電話：$telStr
郵便番号：$zipStr
住所：$adrStr
電子メール：$mailStr

送付先情報
名前：$rc_nameStr
電話：$rc_telStr
郵便番号：$rc_zipStr
住所：$rc_adrStr


注文ID：$tranId
注文品
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

注文控

注文日： $todayStr
お客様番号： $customId
注文状況： 完了
支払方法： $kouzaStr
払込金額： $total 円

注文者情報
名前：$nameStr
電話：$telStr
郵便番号：$zipStr
住所：$adrStr
電子メール：$mailStr

送付先情報
名前：$rc_nameStr
電話：$rc_telStr
郵便番号：$rc_zipStr
住所：$rc_adrStr


注文ID：$tranId
注文品
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
	## さまざまなエラーメッセージなどを保存しようとしたが，使っていません
	##
	function setMessage($msg) {
		$this->message = $msg;
	}



	function changeOwner($base) {
		global $db;
		/**
			所有者ID($base)を変更することで，
			所有者を変更する．

			これは，ログインせずに買物を開始したユーザが，
			途中でログインをした場合に，
			カートをリセットせずに，買物の状態が引き継がれるように
			するためである．
		
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
			買物かごに品物を入れる
		**/
		if($this->id=="") {
			$this->setId();
		}
		$item->transaction_id = $this->id;
		$this->items[] = $item;
	}

	function removeItem($item_id) {
		/**
			買物かごから品物を出す
		**/
		if(is_object($this->items["$item_id"])) {
			$this->items["$item_id"]->remove();
		}		
		//$this->save();
	}

	function changeCount($item_id, $count) {
		/**
			個数を変更する
		**/
		$this->items["$item_id"]->setItemCount($count);
		$this->items["$item_id"]->save();
	}

	function setPayment($payment) {
		$this->payment = $payment;

	}

	function usePoint($point) {
		/**
			品物の購入に際し，ポイントを消費する
		**/
		$this->payinfo04 = $point;
	}

	function setTrInfo($tr_name, $tr_zip, $tr_adr, $tr_tel, $tr_mail) {
		/**
			購入者情報のセット
		**/
		$this->tr_name = $tr_name;
		$this->tr_zip = $tr_zip;
		$this->tr_adr = $tr_adr;
		$this->tr_tel = $tr_tel;
		$this->tr_mail = $tr_mail;

	}

	function setRcInfo($rc_name, $rc_zip, $rc_adr, $rc_tel) {
		/**
			品物の受取人情報のセット
		**/
		$this->rc_name = $rc_name;
		$this->rc_zip = $rc_zip;
		$this->rc_adr = $rc_adr;
		$this->rc_tel = $rc_tel;
	}


	function load() {
		global $db;


		/**
			DBから自分自身をロードする
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
			配送料をDBに書き込む
		**/
		$sql = "UPDATE Cart SET delivery=? WHERE id=? AND base=?";
		$data = array($this->delivery, $this->id, $this->base);
		$st = $db->prepare($sql);
		$db->execute($st, $data);

	}

	function save() {
		global $db;
		/**
			自分自身をDBに保存する
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
			買物かごの中の品物をDBに保存する
		**/
		foreach($this->items as $item) {
			$item->save();
		}
		return True;
	}

	function clearAll() {
		/**
			買物かごを空にする
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
			買物かごの中に入っている品物をDBからロードする．
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
			明細を出力する
		**/
?>
<table border="1" cellspacing="0" cellpadding="5">
<tr>
<td>商品名</td>
<td>単  価</td>
<td>注文数</td>
<td>小  計</td>
</tr>
<?php
$total = 0;
$shouhin_total = 0;

// 商品明細ここから

foreach($this->items as $item) {
?>
<tr>
<td><?php echo $item->item_name; ?></td>
<td><?php echo $item->item_price; ?> 円</td>
<td><?php echo $item->item_count; ?></td>
<td><?php 
$s_total = $item->item_price * $item->item_count;
$total += $s_total;

// ポイントは会員割引対象に含めないので、
// 通販商品のみの合計を算出。
if($item->master->category != 'PT') {
	$shouhin_total += $s_total;
}


echo $s_total;
?> 円</td>
</tr>
<?php
}
//
// 商品明細ここまで
//

//
// 会員値引きここから
//
global $user;

// ポイント所持or有料会員flgで割引発生
if(0<$user->point || 0<$user->usertype) {
	$this->payinfo03 = intval($shouhin_total * 0.05);
	if($this->payinfo03 > 0) {
?>
<tr>
<td colspan="3">会員値引き</td>
<td>-<?php 
$total -= $this->payinfo03;
echo $this->payinfo03;
?> 円</td>
<?php
	}
}

//
// 会員値引きここまで
//


//
// ポイント利用ここから
//
if(0<$this->payinfo04) {
?>
<tr>
<td colspan="3">ポイントご利用値引き</td>
<td>-<?php 
$total -= $this->payinfo04;
echo $this->payinfo04;
?> 円</td>
<?php
}
//
// ポイント利用ここまで
//

//
// 送料表示ここから
//
if(1<$this->delivery) {
?>
<tr>
<td colspan="3">送料</td>
<td align="right"><?php 
$total += $this->delivery;
echo $this->delivery;
?> 円</td>
<?php
}
//
// 送料表示ここまで
//

//
// 代引きここから
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
<td colspan="3">代引手数料</td>
<td align="right"><?php 
$total += $daibiki;
echo $daibiki;
?> 円</td>
</tr>
<?php
}
//
// 代引ここまで
//

?>
<tr>
<td colspan="3">合  計</td>
<td><?php echo $total; ?> 円</td>
</tr>
<?php


$this->payinfo06 = $total;
$this->save();
//
// 送料ここから
//
if($this->hasDelivery>0 && $this->delivery < 100) {
?>
<tr>
<td colspan="4" align="right">
※別途送料がかかります（<a href="souryou.php" target="_blank">→詳しくはこちら</a>）
</td>
</tr>
<?php
}
//
// 送料ここまで
//


?>
</table>
<?php

	}


}







?>
