<?

use App\Services\Model;

class Partner
{

	var $seq;
	var $id;
	var $username;
	var $sex;
	var $mar;
	var $b_year;
	var $b_mon;
	var $b_day;
	var $b_hour;
	var $b_min;
	var $b_place;

	var $lon;
	var $lat;
	var $tz;

	var $mailaddr;

	function __construct($id)
	{
		$this->id = $id;
		$this->load();
	}

	function load()
	{
		$partnerinfo = new Model('partnerinfo');
		$partner = $partnerinfo->get('id', '=', $this->id);

		if ($partner) {
			$this->seq = $partner['seq'];
			$this->username = $partner['username'];
			$this->sex = $partner['sex'];
			$this->mar = $partner['mar'];
			$this->b_year = $partner['b_year'];
			$this->b_mon = $partner['b_mon'];
			$this->b_day = $partner['b_day'];
			$this->b_hour = $partner['b_hour'];
			$this->b_min = $partner['b_min'];
			$this->b_place = $partner['b_place'];
			$this->mailaddr = $partner['mailaddr'];
			list($this->lon, $this->lat, $this->tz) = location($this->b_place);
			if ($this->lon == "" || $this->lat == "") {
				$this->lon = 139.75;
				$this->lat = 35.68;
				$this->tz = -9;
			}
		} else {
			return False;
		}
	}
}





## anonUser
## ログインしていない状態のユーザオブジェクト
## ログインしていなくてもショッピングカートが利用できるための
## ゲストユーザのセッション維持を目的とする．
class anonUser
{

	var $base_id;
	var $base_nick;
	var $now;

	function __construct($uid)
	{
		$this->base_id = $uid;
		$this->base_nick = "ゲスト";
		$this->now = time();
	}
	function loadbase()
	{
	}
	function loadmail()
	{
	}

	## 有料会員チェックは常に偽を返す
	function checkPremiumFortune($ftcode)
	{
		return False;
	}
}

##
## ログイン後のユーザオブジェクト
##
##
class User
{
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
	var $virtual_point;
	var $now;
	var $archivedir;

	##
	## 出生年月日と時間
	##
	var $year;
	var $month;
	var $day;
	var $ampm;
	var $hour;
	var $hour12;
	var $min;

	##
	## あの人
	##
	var $partners_id = array();
	var $partners = array();

	var $usertype;		## 0: 無料会員 1: 有料会員 2:おためし会員

	var $registered = 1;	## 登録されているかいないか
	## anonUserでないので、Trueで初期化

	var $availMonth;	## 有料コンテンツ有効期間
	## 今月が2月で，availMonth==3の場合
	## 3,4,5月の3カ月が有効期間となる
	## ※2007/10以降の新課金方式では使用しない

	var $expiration;	## いつまで利用可能か YYYYMMDDHHMMSS形式

	var $exp = array();	## $expirationを分解 年月日時分秒
	var $expired;		## 有効期限切れかどうか

	function __construct($uid)
	{
		$this->base_id = $uid;
		$this->now = time();
	}

	function loadbase()
	{
		$base_obj = $_SESSION['user'];

		$this->base_pass = $base_obj['pass'];
		$this->base_name = $base_obj['name'];
		$this->base_nick = $base_obj['nick'];
		$this->base_sex = $base_obj['sex'];
		$this->sex = $base_obj['sex'];
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

		list($this->lon, $this->lat, $this->tz) = location($this->base_bwhere);
		if ($this->lon == "" || $this->lat == "") {
			$this->lon = 139.75;
			$this->lat = 35.68;
			$this->tz = -9;
		}
		$this->parseDateTime();
		$this->loadPartners();


		// workaround
		$this->b_year = $this->year;
		$this->b_mon = $this->month;
		$this->b_day = $this->day;
		$this->b_hour = $this->hour;
		$this->b_min = $this->min;
	}

	function parseDateTime()
	{
		##
		## さまざまな場面で利用しやすいように，生年月日を
		## splitしてそれぞれ変数化しておく
		list($year, $month, $day) = explode("-", $this->base_born);
		$this->year = intval($year);
		$this->month = intval($month);
		$this->day = intval($day);

		list($hour, $min, $dummy) = explode(":", $this->base_time);
		$this->hour = intval($hour);
		$this->min = intval($min);

		if ($this->hour < 12) {
			$this->ampm = "am";
			$this->hour12 = $this->hour;
		} else if (12 <= $this->hour) {
			$this->ampm = "pm";
			$this->hour12 = $this->hour - 12;
		}
	}

	function setUsertype($ut)
	{
		global $db;
		$this->usertype = $ut;

		if ($ut == 1) {
			$this->expiration = date("YmdHis", time());
		}

		## テーブルpremiumStatusに自身のデータが存在するかを確認する
		$sql = "SELECT count(*) FROM premiumStatus WHERE base='";
		$sql .= $this->base_id . "'";

		$exist = $db->getOne($sql);

		if ($exist) {
			## もし存在するならUPDATE文を作成する
			$sql = "UPDATE premiumStatus SET usertype=? WHERE base=?";
		} else {
			## 存在しないならINSERT文を作成する
			$sql = "INSERT INTO premiumStatus SET usertype=?, base=?";
		}
		$data = array($this->usertype, $this->base_id);
		$st = $db->prepare($sql);
		$db->execute($st, $data);
		return;
	}

	function loadPartners()
	{
		global $db;

		$this->partners = array();
		$this->partners_id = array();

		$partners =  new Model('partnerinfo');
		$partnerRecords = $partners->find('mailaddr', '=', $_SESSION['user']['mail']);

		foreach ($partnerRecords as $key => $record) {
			$this->partners_id[$key] = $record['seq'];
			$this->partners[$record['seq']] = new Partner($record['id']);
			$this->partners[$record['seq']]->load();
		}
	}

	function extendMonth($n)
	{
		global $db;
		$y = intval(substr($this->expiration, 0, 4));
		$m = intval(substr($this->expiration, 4, 2));
		$d = intval(substr($this->expiration, 6, 2));

		$current = $y . "-" . $m . "-" . $d;

		$new_exp = date("YmdHis", strtotime("$current +$n month"));

		//$new_month = $m + $n;
		//$new_year = $y;
		//for($new_month=$m+$n ; 12<$new_month ; $new_month-=12) {
		//	$new_year++;
		//}
		//$new_exp = $new_year . substr("00".$new_month,-2) . "01000000";
		//echo $new_exp;

		$sql = "UPDATE premiumStatus SET expiration=? WHERE base=?";
		$data = array($new_exp, $this->base_id);
		$st = $db->prepare($sql);
		$db->execute($st, $data);
	}
}
