<?

require_once 'dbconn.php';
require_once 'cart.php';

## anonUser
## �������󤷤Ƥ��ʤ����֤Υ桼�����֥�������
## �������󤷤Ƥ��ʤ��Ƥ⥷��åԥ󥰥����Ȥ����ѤǤ��뤿���
## �����ȥ桼���Υ��å����ݻ�����Ū�Ȥ��롥
## ���Τ��ᡤ��������Ƥ���᥽�åɤϡ�����åԥ󥰥����Ȥξ��֤�DB����
## �����ɤ��� loadCart() �ΤߤǤ��롥
class anonUser {

	var $base_id;
	var $base_nick;
	var $now;
	var $cart;

	function anonUser($uid) {
		$this->base_id = $uid;
		$this->base_nick = "������";
		$this->now = time();

	}
	function loadbase() {
		$this->loadCart();
	}
	function loadmail() {}

	function loadCart() {
		global $db;

		$sql  = "SELECT id,cr_unixtime FROM Cart ";
		$sql .= "WHERE base=? AND tr_status=0 ";
		$sql .= "ORDER by cr_unixtime DESC LIMIT 1";

		$st = $db->prepare($sql);
		$res = $db->execute($st, $this->base_id);

		$row = $res->fetchRow();
		$id = $row[0];
		$cr_unixtime = $row[1];

		$elapsed = $this->now - $cr_unixtime;

		$this->cart = new Cart();
		$this->cart->setId($id);
		$this->cart->load();
	}

	## ͭ����������å��Ͼ�˵����֤�
	function checkPremiumFortune($ftcode) {
		return False;
	}
}

##
## ���������Υ桼�����֥�������
##
##
class User {

	var $base_id;
	var $base_pass;
	var $base_name;
	var $base_nick;
	var $base_sex;
	var $base_mariage;
	var $base_born;
	var $base_time;
	var $base_bwhere;
	var $base_zip;
	var $base_adr;
	var $base_adr2;
	var $base_tel;
	var $base_home;
	var $base_job;
	var $base_office;
	var $base_adr3;
	var $base_udate;
	var $base_flg;
	var $userflg;
	var $mail_mail;
	var $mail_flg;
	var $mail2_mail;
	var $mail2_accept;
	var $mail2_flg;
	var $mob_mail_mail;
	var $mob_mail_flg;
	var $point;
	var $now;
	var $archivedir;

	##
	## ����ǯ�����Ȼ���
	##
	var $year;
	var $month;
	var $day;
	var $ampm;
	var $hour;
	var $hour12;
	var $min;


	##
	## 2006.12ͭ�����ʹߤ��ɲä��줿�ꤤ�����Ѳ�ǽ�ե饰
	## ͭ�����¤�yyyymmdd����������ʸ����ǳ�Ǽ����Ƥ��롥
	##
	var $ft001;
	var $ft002;
	var $ft003;
	var $ft004;
	var $ft005;
	var $ft006;
	var $ft007;
	var $ft008;
	var $ft009;
	var $ft010;
	var $ft011;
	var $ft012;
	var $ft013;
	var $ft014;
	var $ft015;
	var $ft016;


	var $cart;		## ����åԥ󥰥����ȥ��֥������ȳ�Ǽ��

	var $usertype;		## 0: ̵����� 1: ͭ�����

	var $availMonth;	## ͭ������ƥ��ͭ������
				## ���2��ǡ�availMonth==3�ξ��
				## 3,4,5���3���ͭ�����֤Ȥʤ�
	var $expiration;	## ͭ������ƥ�����Ѵ��� YYYYMMDDHHMMSS

	function User($uid) {
		$this->base_id = $uid;
		$this->now = time();
	}

	function loadbase() {
		global $db;

		/** load table 'base' **/

		$query = "SELECT * FROM base WHERE id=?";
		$st = $db->prepare($query);
		$base_res = $db->execute($st, $this->base_id);

		$base_obj = $base_res->fetchRow(DB_FETCHMODE_ASSOC);

		$this->base_pass = $base_obj['pass'];
		$this->base_name = $base_obj['name'];
		$this->base_nick = $base_obj['nick'];
		$this->base_sex = $base_obj['sex'];
		$this->base_mariage = $base_obj['mariage'];
		$this->base_born = $base_obj['born'];
		$this->base_time = $base_obj['time'];
		$this->base_bwhere = $base_obj['bwhere'];
		$this->base_zip = $base_obj['zip'];
		$this->base_adr = $base_obj['adr'];
		$this->base_adr2 = $base_obj['adr2'];
		$this->base_tel = $base_obj['tel'];
		$this->base_home = $base_obj['home'];
		$this->base_job = $base_obj['job'];
		$this->base_office = $base_obj['office'];
		$this->base_adr3 = $base_obj['adr3'];
		$this->base_udate = $base_obj['udate'];
		$this->base_flg = $base_obj['flg'];
		$this->base_class = $base_obj['class'];
		$this->userflg = $base_obj['userflg'];
		$base_res->free();

		$this->parseDateTime();
		$this->loadPoint();
		$this->loadCart();
		$this->loadPremiumStatus();
	}

	function loadCart() {
		global $db;

		##
		## �㤤ʪ����Υ����Ȥ����뤫�ɤ���������å�����
		##
		$sql  = "SELECT id,cr_unixtime FROM Cart ";
		$sql .= "WHERE base=? AND tr_status=0 ";
		$sql .= "ORDER by cr_unixtime DESC LIMIT 1";

		$st = $db->prepare($sql);
		$res = $db->execute($st, $this->base_id);

		$row = $res->fetchRow();
		$id = $row[0];
		$cr_unixtime = $row[1];

		$elapsed = $this->now - $cr_unixtime;

		$this->cart = new Cart();

		##
		## �㤤ʪ����Υ����Ȥ�����С������ȥ��֥������Ȥ�ƹ��ۤ���
		##
		if($id) {
			$this->cart->setId($id);
			$this->cart->load();
		}
	}

	function parseDateTime() {
		##
		## ���ޤ��ޤʾ��̤����Ѥ��䤹���褦�ˡ���ǯ������
		## split���Ƥ��줾���ѿ������Ƥ���
		list($year, $month, $day) = explode("-", $this->base_born);
		$this->year = intval($year);
		$this->month = intval($month);
		$this->day = intval($day);

		list($hour, $min, $dummy) = explode(":", $this->base_time);
		$this->hour = intval($hour);
		$this->min = intval($min);

		if($this->hour < 12) {
			$this->ampm = "am";
			$this->hour12 = $this->hour;
		} else if(12 <= $this->hour) {
			$this->ampm = "pm";
			$this->hour12 = $this->hour - 12;
		}

	}

	function loadPoint() {
		global $db;

		##
		## ����ݥ���Ȥ�DB��������ɤ���
		##
		/** load table 'point' **/
		$query  = "SELECT point FROM point ";
		$query .= "WHERE base='";
		$query .= $this->base_id;
		$query .= "'";

		$this->point = $db->getOne($query);
		if(!$this->point) {
			$this->point = 0;
		}
	}

	function loadPremiumStatus() {
		global $db;

		##
		## ͭ������ξ��֤�DB��������ɤ���
		##
		$sql = "SELECT usertype,availMonth,expiration ";
		$sql .= " FROM premiumStatus ";
		$sql .= " WHERE base=?";
		$st = $db->prepare($sql);
		$res = $db->execute($st, $this->base_id);
		$row = $res->fetchRow();
		$this->usertype = $row[0];
		$this->availMonth = $row[1];
		$this->expiration = $row[2];

		##
		## �쥳���ɤ�¸�ߤ��ʤ���硤usertype,availMonth�϶���ˤʤ�Τ�
		## ���줾�� 0 ������Ū�˥��åȤ���
		##
		if(strcmp($this->usertype, "")==0) {
			$this->usertype = 0;
		}
		if(strcmp($this->availMonth, "")==0) {
			$this->availMonth = 0;
		}
		if(strcmp($this->expiration, "")==0) {
			if($this->usertype == "1") {
				list($exp_y, $exp_m) = nextNMonth($this->availMonth+1);
				$expiration = $exp_y . substr("00".$exp_m,-2)."01000000";
				$sql = "UPDATE premiumStatus SET expiration=? WHERE base=?";
				$data = array($expiration, $this->base_id);
				$st = $db->prepare($sql);
				$db->execute($st, $data);

			}
		}

		##
		## ft001��ft016��2006/12�ʹߤ˼�������ͭ���ꤤ�����Ѳ�ǽ�ե饰
		## ����ޤǻ�����
		##
		$cols  = "ft001,ft002,ft003,ft004,";
		$cols .= "ft005,ft006,ft007,ft008,";
		$cols .= "ft009,ft010,ft011,ft012,";
		$cols .= "ft013,ft014,ft015,ft016";
		$sql = "SELECT $cols FROM premiumFortune WHERE base=?";
		$st = $db->prepare($sql);
		$res = $db->execute($st, $this->base_id);
		$row = $res->fetchRow();
		$this->ft001 = $row[0];
		$this->ft002 = $row[1];
		$this->ft003 = $row[2];
		$this->ft004 = $row[3];
		$this->ft005 = $row[4];
		$this->ft006 = $row[5];
		$this->ft007 = $row[6];
		$this->ft008 = $row[7];
		$this->ft009 = $row[8];
		$this->ft010 = $row[9];
		$this->ft011 = $row[10];
		$this->ft012 = $row[11];
		$this->ft013 = $row[12];
		$this->ft014 = $row[13];
		$this->ft015 = $row[14];
		$this->ft016 = $row[15];

		return True;
	}

	function loadmail() {
		global $db;

		##
		## �᡼��ޥ������Ϣ�Υѥ�᡼����DB��������ɤ���
		##
		/** load table 'mail' **/
		$query  = "SELECT * FROM mail ";
		$query .= "WHERE base='";
		$query .= $this->base_id;
		$query .= "'";

		$mail_res = $db->query($query);
		$mail_obj = $mail_res->fetchRow(DB_FETCHMODE_ASSOC);
		$mail_res->free();

		$this->mail_mail = $mail_obj['mail'];
		$this->mail_flg = $mail_obj['flg'];


		/** load table 'mail2' **/
		$query  = "SELECT * FROM mail2 ";
		$query .= "WHERE base='";
		$query .= $this->base_id;
		$query .= "'";

		$mail2_res = $db->query($query);
		$mail2_obj = $mail2_res->fetchRow(DB_FETCHMODE_ASSOC);
		$mail2_res->free();

		$this->mail2_mail = $mail2_obj['mail'];
		$this->mail2_accept = $mail2_obj['accept'];
		$this->mail2_flg = $mail2_obj['flg'];

		/** load table 'mob_mail' **/
		$query  = "SELECT * FROM mob_mail ";
		$query .= "WHERE base='";
		$query .= $this->base_id;
		$query .= "'";

		$mob_mail_res = $db->query($query);
		$mob_mail_obj = $mob_mail_res->fetchRow(DB_FETCHMODE_ASSOC);
		$mob_mail_res->free();

		$this->mob_mail_mail = $mob_mail_obj['mail'];
		$this->mob_mail_flg = $mob_mail_obj['flg'];
	}

	function strHttpParam() {
		##
		## POST�ǡ�����ñ���ʸ�������¸������Ū�Ǻ�����
		## ���Ѥ��Ƥ��ʤ�
		##
		global $_SERVER, $_POST, $_GET;

		if($_SERVER["REQUEST_METHOD"] == "POST") {
			$i = 0;
			while(list($key, $value) = each($_POST)) {
				$qstr[$i] = "$key=" . urlencode($value);
				$i++;
			}
			$query_string = implode("&", $qstr);
		} else {
			$query_string = $_SERVER["QUERY_STRING"];
		}
		return $query_string;
	}


	function pointLog($operation,$amount,$nominal) {
		global $db;
		##
		## �ݥ������Ģ�˥ǡ������ɲä���
		##
		## operation: 'incr' �ޤ��� 'decr'
		## amount: ��갷���ݥ���ȳ�
		## nominal: ����
		##
		$sql = "INSERT INTO pointlog SET ";
		$sql .= "base=?,unixtime=?,operation=?,nominal=?,amount=?";
		$now = time();
		$data = array($this->base_id, $now, $operation, $nominal, $amount);
		$st = $db->prepare($sql);
		$db->execute($st, $data);

		return;

	}


	function checkPremiumFortune($ftcode) {
		## ftcode: ft001��ft016 ���ͤ򸽺ߤ����դ���Ӥ���
		## �������ߡ����Ѳ�ǽ����Ƚ�ꤹ��
		$todayStr = intval(date("Ymd", time()));
		$expiration = intval($this->$ftcode);

		if($todayStr <= $expiration) {
			return True;
		}
		return False;
	}

	function setUsertype($ut) {
		global $db;
		$this->usertype = $ut;

		## �ơ��֥�premiumStatus�˼��ȤΥǡ�����¸�ߤ��뤫���ǧ����
		$sql = "SELECT count(*) FROM premiumStatus WHERE base='";
		$sql .= $this->base_id . "'";

		$exist = $db->getOne($sql);

		if($exist) {
			## �⤷¸�ߤ���ʤ�UPDATEʸ���������
			$sql = "UPDATE premiumStatus SET usertype=? WHERE base=?";
		} else {
			## ¸�ߤ��ʤ��ʤ�INSERTʸ���������
			$sql = "INSERT INTO premiumStatus SET usertype=?, base=?";
		}
		$data = array($this->usertype, $this->base_id);
		$st = $db->prepare($sql);
		$db->execute($st, $data);
		return;
	}


	function incrAvailMonth($n) {
		global $db;
		##
		## �����ΤޤȤ�ʧ����Ԥʤä���硤������availMonth�����ä���
		## table lock�ˤ�transaction��Ԥʤ�
		##
		$db->query("LOCK TABLES premiumStatus WRITE");
		$sql  = "SELECT availMonth,expiration FROM premiumStatus ";
		$sql .= " WHERE base='" . $this->base_id . "'";
		$res = $db->query($sql);
		$row = $res->fetchRow();
		$cur = $row[0];
		$expiration = $row[1];

		$cur += $n;
		$sql = "REPLACE INTO premiumStatus (base,usertype,availMonth,expiration) ";
		$sql .= "VALUES (?,?,?,?)";
		$data = array($this->base_id, 1, $cur, $expiration);
		$st = $db->prepare($sql);
		$db->execute($st, $data);
		$this->availMonth = $cur;
		$db->query("UNLOCK TABLES");
	}

	function extendMonth($n) {
		global $db;

		//
		// �ܹ��ѥ롼�������Ѵ��¤�$n�����Ĺ����
		//
		if(ereg("^200", $this->expiration)) {
			$y = intval(substr($this->expiration, 0, 4));
			$m = intval(substr($this->expiration, 4, 2));
		} else {
			$y = 2007;
			$m = 10;
		}
	        $new_month = $m + $n;
		$new_year = $y;
		for($new_month=$m+$n ; 12<$new_month ; $new_month-=12) {
			$new_year++;
		}

		$new_exp = $new_year . substr("00".$new_month,-2) . "01000000";
		$sql = "UPDATE premiumStatus SET expiration=? WHERE base=?";
		$data = array($new_exp, $this->base_id);
		$st = $db->prepare($sql);
		$db->execute($st,$data);
	}

	function decrPoint($pt) {
		global $db;
		##
		## �ݥ���Ȥ����ʬ�������񤹤�
		## �ݥ������­�Ǿ���Ǥ��ʤ����� -2 ���֤�
		## �ݥ������Ģ�ؤε��ܤϼ�ư�ǹԤʤ��ޤ���
		##
		$db->query("LOCK TABLES point WRITE");

		$this->loadPoint();

		if( ($this->point - $pt) < 0 ) {
			$db->query("UNLOCK TABLES");
			return -2;
		}
		$this->point -= $pt;

		$sql = "REPLACE INTO point SET "
		. "base=" . $this->base_id . ","
		. "point=" . $this->point . ","
		. "last_update=" . $this->now;

		$db->query($sql);

		$db->query("UNLOCK TABLES");
		return True;

	}

	function incrPoint($pt) {
		##
		## �ݥ���Ȥ����ʬ������Ϳ���������
		## ��Υݥ���Ⱦ���� -1 �򤫤�������Ǥ��롥
		$this->decrPoint($pt * -1);
	}
}
