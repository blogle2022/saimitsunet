<?php


function LoadPNG($imgname)
{
	$im = @imagecreatefrompng(array_to_path([public_path(), $imgname])); /* オープンを試みる */
	if (!$im) { /* 失敗した場合 */
		$im = ImageCreate(150, 30); /* 空の画像を作成 */
		$bgc = ImageColorAllocate($im, 255, 255, 255);
		$tc  = ImageColorAllocate($im, 0, 0, 0);
		ImageFilledRectangle($im, 0, 0, 150, 30, $bgc);
		ImageString($im, 1, 5, 5, "Error loading $imgname", $tc);
	}
	return $im;
}
function LoadGIF($imgname)
{
	$im = @imagecreatefromgif($imgname); /* オープンを試みる */
	if (!$im) { /* 失敗した場合 */
		$im = ImageCreate(150, 30); /* 空の画像を作成 */
		$bgc = ImageColorAllocate($im, 255, 255, 255);
		$tc  = ImageColorAllocate($im, 0, 0, 0);
		ImageFilledRectangle($im, 0, 0, 150, 30, $bgc);
		ImageString($im, 1, 5, 5, "Error loading $imgname", $tc);
	}
	return $im;
}

##
## $deg度の位置に線を引く
##
function lineByDegree(&$image, $deg, $len, $color)
{
	$width = imagesx($image);
	$height = imagesy($image);
	imageline(
		$image,
		$width / 2,
		$height / 2,
		$width / 2 - $len * cos(deg2rad($deg)),
		$height / 2 + $len * sin(deg2rad($deg)),
		$color
	);
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
	"pi" => "22"
);

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
	"pi" => "12"
);





$zodfile = array(
	"ar" => "horo/ohitsuji.png",
	"ta" => "horo/ousi.png",
	"ge" => "horo/futago.png",
	"cn" => "horo/kani.png",
	"le" => "horo/shishi.png",
	"vi" => "horo/otome.png",
	"li" => "horo/tenbin.png",
	"sc" => "horo/sasori.png",
	"sa" => "horo/ite.png",
	"cp" => "horo/yagi.png",
	"aq" => "horo/mizugame.png",
	"pi" => "horo/uo.png",
);

$zodfile_s = array(
	"ar" => "horo/ohitsuji_small.gif",
	"ta" => "horo/oushi_small.gif",
	"ge" => "horo/futago_small.gif",
	"cn" => "horo/kani_small.gif",
	"le" => "horo/shishi_small.gif",
	"vi" => "horo/otome_small.gif",
	"li" => "horo/tenbin_small.gif",
	"sc" => "horo/sasori_small.gif",
	"sa" => "horo/ite_small.gif",
	"cp" => "horo/yagi_small.gif",
	"aq" => "horo/mizugame_small.gif",
	"pi" => "horo/uo_small.gif"
);

$starfile = array(
	"Sun" => "horo/taiyou12.png",
	"Moon" => "horo/tuki12.png",
	"Mercury" => "horo/suisei12.png",
	"Venus" => "horo/kinsei12.png",
	"Mars" => "horo/kasei12.png",
	"Jupiter" => "horo/mokusei12.png",
	"Saturn" => "horo/dosei12.png",
	"Uranus" => "horo/tennou12.png",
	"Neptune" => "horo/kaiou12.png",
	"Pluto" => "horo/meiou12.png"
);

$starfile_s = array(
	"Sun" => "horo/sun_small.gif",
	"Moon" => "horo/moon_small.gif",
	"Mercury" => "horo/mercury_small.gif",
	"Venus" => "horo/venus_small.gif",
	"Mars" => "horo/mars_small.gif",
	"Jupiter" => "horo/jupiter_small.gif",
	"Saturn" => "horo/saturn_small.gif",
	"Uranus" => "horo/uranus_small.gif",
	"Neptune" => "horo/neptune_small.gif",
	"Pluto" => "horo/pluto_small.gif"
);

$jp = array(
	"ar" => "牡羊座",
	"ta" => "牡牛座",
	"ge" => "双子座",
	"cn" => "蟹座",
	"le" => "獅子座",
	"vi" => "乙女座",
	"li" => "天秤座",
	"sc" => "蠍座",
	"sa" => "射手座",
	"cp" => "山羊座",
	"aq" => "水瓶座",
	"pi" => "魚座",
	"Sun" => "太陽",
	"Moon" => "月",
	"Mercury" => "水星",
	"Venus" => "金星",
	"Mars" => "火星",
	"Jupiter" => "木星",
	"Saturn" => "土星",
	"Uranus" => "天王星",
	"Neptune" => "海王星",
	"Pluto" => "冥王星"
);

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
	"opposition"
);

function zodOrder($start)
{
	$ary = array();
	$str = "ar,ta,ge,cn,le,vi,li,sc,sa,cp,aq,pi,ar,ta,ge,cn,le,vi,li,sc,sa,cp,aq,pi";
	if (preg_match("/($start,[a-z,]{32})/", $str, $ary)) return $ary[1];
}


class Planet
{

	public $relPos;
	public $absPos;
	public $absPosBySign;
	public $sign;
	public $name;
	public $string;

	function __construct($name)
	{
		$this->name = $name;
	}

	function setRelPos($degree)
	{
		$this->relPos = floatval($degree);
	}
	function setAbsPos($degree)
	{
		$this->absPos = floatval($degree);
	}
	function setSign($sign)
	{
		$this->sign = $sign;
	}
	function getRelPos()
	{
		return $this->relPos;
	}
	function getAbsPos()
	{
		return $this->absPos;
	}
	function getSign()
	{
		return $this->sign;
	}
	function getName()
	{
		return $this->name;
	}
}

class HoroScope
{

	public $year;
	public $month;
	public $day;
	public $hour;
	public $minute;
	public $lon;
	public $lat;
	public $method;
	public $time_local;
	public $time_utc;
	public $tz;
	public $offset;
	public $now;

	public $sweret = array();	## swetestコマンドの出力を格納する配列
	##
	## 実行コマンドサンプル
	## /srv/phplib_test/SwissEphemeris.com
	## SwissEphemerisの出力結果サンプル
	## /srv/phplib_test/SwissEphemeris.out
	## この出力結果をパースして星の角度を得ます
	##

	public $sign = array();
	public $horoarg = "";	## arguments for horoscope drawing
	public $orderedSign = array();
	public $planets = array();	## planets objects
	public $debug = False;
	public $logfp;

	function __construct(
		$year,
		$month,
		$day,
		$hour,
		$minute,
		$lon,
		$lat,
		$tz
	) {
		$this->year = intval($year);
		$this->month = intval($month);
		$this->day = intval($day);
		$this->hour = intval($hour);
		$this->minute = intval($minute);
		$this->lon = $lon;
		$this->lat = $lat;
		$this->tz = $tz;
		$this->getTzOffset();
		$this->time_local = smktime(
			$this->hour,
			$this->minute,
			0,
			$this->month,
			$this->day,
			$this->year
		);
		$this->setDateTime($this->time_local);
		//$this->debug = True;
		if ($this->debug) {
			$this->logfp = fopen("/tmp/astro.log", "a");
			#print_r($this);
		}
	}

	function makeUTC($unixtime)
	{
		## unix timestampをUTCに変換する
		$utc = $unixtime - $this->offset;
		return $utc;
	}

	function setDateTime($unixtime)
	{
		## makeUTC()で得たUTC時刻をセットする
		$this->time_utc = $this->makeUTC($unixtime);
	}

	public function invokeSWE()
	{
		global $jp;
		global $starfile;
		global $zodfile;
		global $starfile_s;
		global $zodfile_s;

		## コマンドラインを組み立てる
		//$arg = date("\-bj.n.Y \-u\\tH:i", $this->time_utc);
		$arg = date("\-bj.n.Y ", $this->time_utc);
		$arg .= "-ut";
		$arg .= date("H:i", $this->time_utc);
		$cmd  = "/home/saimitsucom/se1/swetest -pd -head -edir/home/saimitsucom/se1 ";
		$cmd .= $arg;
		$cmd .= " -house";
		$cmd .= $this->lon . "," . $this->lat . ",R";
		$cmd .= " -fPZ";
		if ($this->debug) fwrite($this->logfp, "$cmd\n");

		## 実行する．結果はsweret配列に格納される
		exec($cmd, $this->sweret, $retval);
		foreach ($this->sweret as $line) {
			## １行単位で処理する
			## 例）太陽の位置情報
			## Sun              7 pi 37'52.2061
			## ↑太陽は魚座(pi)の7度37分52.2061秒の位置にある
			##
			## $key: Sun
			## $dd: 7
			## $sign: pi
			## $mmss: 37'52.2061
			## $mmssを ' で分割し，$min,$sec を得る
			## $min: 37
			## $sec: 52.2061
			## $minをx60して秒に換算．
			## $secと合計して$sec_totalを得る
			## $sec_total: 37x60 + 52.2061
			## $sec_totalを3600で割って度に換算．$rightを得る
			## $ddと$rightを合計して，角度を得る
			##
			##

			$key = preg_replace("/ /", "", substr($line, 0, 16));
			$dd = preg_replace("/ /", "", substr($line, 16, 2));
			$sign = substr($line, 19, 2);
			$mmss = preg_replace("/ /", "", substr($line, 22, 10));

			$this->sign[$key] = $sign;

			list($min, $sec) = explode("'", $mmss);
			$sec_total = $min * 60 + $sec;
			$right = $sec_total / 3600;
			$this->keys[] = $key;
			$this->planets[$key] = new Planet($key);
			$this->planets[$key]->setRelPos($dd + $right);
			$this->planets[$key]->setSign($sign);
			$this->planets[$key]->string =
				$jp[$key]
				. "<img src=images/" . $starfile[$key] . ">"
				. ": "
				. $jp[$sign]
				. "<img src=images/" . $zodfile_s[$sign] . "> "
				. $dd
				. "゜"
				. $min
				. "’<br>";
		}
		$this->genHoroArg();
		return $retval;
	}


	function calcHousePlanet($houseNum1)
	{

		##
		## 第N室を何の星が，あるいは星座が支配しているかを求める
		## 「ホロスコープが語るあなたの運勢」で多く利用しています．
		##

		$house1Name = "house" . $houseNum1;
		$house1Obj = $this->planets[$house1Name];
		$house1Pos = $house1Obj->absPos;

		$housePlanet = "";

		$houseNum2 = $houseNum1 + 1;
		if ($houseNum2 == 13) $houseNum2 = 1;

		$house2Name = "house" . $houseNum2;
		$house2Obj = $this->planets[$house2Name];
		$house2Pos = $house2Obj->absPos;


		##
		## 第N室の一番大部分を占める星座を求める
		## 支配星座
		##
		if ($this->getNextSign($house1Obj->sign) == $house2Obj->sign) {
			if ((30 - $house1Obj->relPos) < $house2Obj->relPos) {
				$houseSign = $house2Obj->sign;
			} else {
				$houseSign = $house1Obj->sign;
			}
		} else if ($house1Obj->sign == $house2Obj->sign) {
			$houseSign = $house1Obj->sign;
		} else if ($this->getNextSign($house1Obj->sign) == $this->getPrevSign($house2Obj->sign)) {
			$houseSign = $this->getNextSign($house1Obj->sign);
		} else {
			$houseSign = $house1Obj->sign;
		}

		$pls = array(
			"Sun", "Jupiter", "Mars", "Venus", "Mercury",
			"Saturn", "Uranus", "Neptune", "Pluto"
		);

		##
		## 上の$pls配列の順番で評価する
		## たとえば，第一室に太陽と木星がある場合，太陽の方が優先度が
		## 高いため，第一室の支配星は太陽である．
		##

		if ($house1Pos < 0) $house1Pos += 360;
		if ($house2Pos < 0) $house2Pos += 360;
		foreach ($pls as $pl) {
			$plObj = $this->planets[$pl];
			$pos = $plObj->absPos;
			if ($pos < 0) $pos += 360;
			#if(($house1Pos<$pos) && ($pos<$house2Pos))
			if (ri($house1Pos, $house2Pos, $pos)) {
				### 見つかりしだい return してしまう
				$housePlanet = $pl;
				return $housePlanet;
			}
		}

		## 第一室に惑星がなかったばあい，最初に求めた支配星座を返す
		return $houseSign;
	}

	function getNextSign($sn)
	{
		##
		## 星座の順番は決まっており，次の星座名を返すUtility
		##
		$ret = "";
		switch ($sn) {
			case "ar":
				$ret = "ta";
				break;
			case "ta":
				$ret = "ge";
				break;
			case "ge":
				$ret = "cn";
				break;
			case "cn":
				$ret = "le";
				break;
			case "le":
				$ret = "vi";
				break;
			case "vi":
				$ret = "li";
				break;
			case "li":
				$ret = "sc";
				break;
			case "sc":
				$ret = "sa";
				break;
			case "sa":
				$ret = "cp";
				break;
			case "cp":
				$ret = "aq";
				break;
			case "aq":
				$ret = "pi";
				break;
			case "pi":
				$ret = "ar";
				break;
			default:
				break;
		}
		return $ret;
	}

	function getPrevSign($sn)
	{
		##
		## 星座の順番は決まっており，ひとつ前の星座名を返すUtility
		##
		$ret = "";
		switch ($sn) {
			case "ar":
				$ret = "pi";
				break;
			case "ta":
				$ret = "ar";
				break;
			case "ge":
				$ret = "ta";
				break;
			case "cn":
				$ret = "ge";
				break;
			case "le":
				$ret = "cn";
				break;
			case "vi":
				$ret = "le";
				break;
			case "li":
				$ret = "vi";
				break;
			case "sc":
				$ret = "li";
				break;
			case "sa":
				$ret = "sc";
				break;
			case "cp":
				$ret = "sa";
				break;
			case "aq":
				$ret = "cp";
				break;
			case "pi":
				$ret = "aq";
				break;
			default:
				break;
		}
		return $ret;
	}

	function getSignOrder()
	{
		$ary = array();
		$asc = $this->sign["Ascendant"];

		$str = "ar,ta,ge,cn,le,vi,li,sc,sa,cp,aq,pi,ar,ta,ge,cn,le,vi,li,sc,sa,cp,aq,pi";
		if (preg_match("/($asc,[a-z,]{32})/", $str, $ary))
			$signOrder = $ary[1];

		$this->orderedSign = explode(",", $signOrder);
	}

	function getAbsDegree()
	{
		$this->getSignOrder();
		for ($i = 0; $i < 12; $i++) {
			$signName = $this->orderedSign[$i];
			$this->planets[$signName] = new Planet($signName);
			$this->planets[$signName]->setSign($signName);
			$this->planets[$signName]->setRelPos(0);
			$this->planets[$signName]->setAbsPos(
				$i * 30 -
					$this->planets["Ascendant"]->getRelPos()
			);
		}
		foreach ($this->keys as $key) {
			$sign = $this->sign[$key];
			$this->planets[$key]->setAbsPos(
				$this->planets[$sign]->getAbsPos()
					+ $this->planets[$key]->getRelPos()
			);
		}
		$this->getAbsDegreeBySign();
	}

	function getAbsDegreeBySign()
	{
		$baseDeg["ar"] = 0;
		$baseDeg["ta"] = 30;
		$baseDeg["ge"] = 60;
		$baseDeg["cn"] = 90;
		$baseDeg["le"] = 120;
		$baseDeg["vi"] = 150;
		$baseDeg["li"] = 180;
		$baseDeg["sc"] = 210;
		$baseDeg["sa"] = 240;
		$baseDeg["cp"] = 270;
		$baseDeg["aq"] = 300;
		$baseDeg["pi"] = 330;
		foreach ($this->keys as $key) {
			$keySign = $this->planets[$key]->sign;
			$relPos = $this->planets[$key]->relPos;
			$this->planets[$key]->absPosBySign = $baseDeg[$keySign] + $relPos;
		}
	}

	function shiftAllPlanet($deg)
	{
		foreach ($this->keys as $key) {
			$c_absPos = $this->planets[$key]->getAbsPos();
			$s_absPos = $c_absPos + $deg;
			if ($s_absPos < 0) $s_absPos += 360;
			$this->planets[$key]->setAbsPos($s_absPos);
		}
	}

	function getTzOffset()
	{
		$this->setTzOffset($this->tz);
		return;
	}

	function setTzOffset($offset)
	{
		$this->offset = 3600 * floatval($offset) * -1;
	}

	function genHoroArg()
	{
		## ホロスコープ画像生成のためのURL文字列を生成する
		foreach ($this->planets as $planet) {
			$this->horoarg .= $planet->getName();
			$this->horoarg .= "=";
			$this->horoarg .= $planet->getSign();
			$this->horoarg .= substr($planet->getRelPos(), 0, 6);
			$this->horoarg .= '&';
		}
	}
}




## 与えられたHoroscopeオブジェクトの各惑星が，0,180,120,90.60の角度をとるかどうかを
## チェックする．
##
function aspect($horo)
{

	$plnts = array(
		"Moon", "Sun", "Mercury", "Venus", "Mars", "Jupiter",
		"Saturn", "Uranus", "Neptune", "Pluto"
	);

	$degs = array(0, 180, 120, 90, 60);
	$errs = array(3, 3, 3, 3, 3);
	$cnt = 1;
	$result = array();
	for ($i = 0; $i < 10; $i++) {
		for ($j = 0; $j < 5; $j++) {
			for ($k = $i + 1; $k < 10; $k++) {
				$i_pos = $horo->planets["$plnts[$i]"]->absPos;
				$k_pos = $horo->planets["$plnts[$k]"]->absPos;
				$diff = abs($i_pos - $k_pos);
				if ($diff > 180) $diff = 360 - $diff;

				$low = $degs[$j] - $errs[$j];
				$high = $degs[$j] + $errs[$j];

				if (($low < $diff) && ($diff < $high)) {
					$result[] = array($plnts[$i], $plnts[$k], $degs[$j], $cnt);
				}
				$cnt++;
			}
		}
	}
	return $result;
}
