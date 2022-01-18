<?

class HoroScope2 {

	var $year;
	var $month;
	var $day;
	var $hour;
	var $minute;
	var $lon;
	var $lat;
	var $method;
	var $time_local;
	var $time_utc;
	var $tz;
	var $offset;
	var $now;

	var $sweret = array();	## swetestコマンドの出力

	var $sign = array();
	var $horoarg = "";	## arguments for horoscope drawing
	var $orderedSign = array();
	var $planets = array();	## planets objects
	var $debug = False;
	var $logfp;

	function HoroScope(
		$year, 
		$month, 
		$day, 
		$hour, 
		$minute, 
		$lon, 
		$lat,
		$tz) 
	{

		global $user;

		$this->year = $year;
		$this->month = $month;
		$this->day = $day;
		$this->hour = $hour;
		$this->minute = $minute;
		$this->lon = $lon;
		$this->lat = $lat;
		$this->tz = $tz;
		$this->getTzOffset();
		$this->time_local = mktime(
			$this->hour,
			$this->minute,
			0,
			$this->month,
			$this->day,
			$this->year);
		$this->setDateTime($this->time_local);

		if($user->base_id=='117758') $this->debug = True;
		if($this->debug) {
			$this->logfp = fopen("/tmp/astro.log", "w");
		}
	}

	function makeUTC($unixtime) {
		$utc = $unixtime - $this->offset;

		return $utc;
	}
	function setDateTime($unixtime) {
		$this->time_utc = $this->makeUTC($unixtime);
	}

	function invokeSWE() {
		$arg = date("\-bj.n.Y \-u\\tH:i", $this->time_utc);
		$cmd  = "/srv/se1/swetest -pa -head -edir/srv/se1 ";
		$cmd .= $arg;
		$cmd .= " -house";
		$cmd .= $this->lon . "," . $this->lat . ",R";
		$cmd .= " -fPZ";
		if($this->debug) fwrite($this->logfp, "$cmd\n");

		exec($cmd, &$this->sweret, $retval);

		foreach ($this->sweret as $line) {
			$key = ereg_replace(" ","",substr($line,0,16));
			$dd = ereg_replace(" ","",substr($line,16,2));
			$sign = substr($line,19,2);
			$mmss = ereg_replace(" ","",substr($line,22,10));
			##$recta = ereg_replace(" ","",substr($line,34,16));

			$this->sign[$key] = $sign;

			list($min, $sec) = explode("'", $mmss);
			$sec_total = $min*60 + $sec;
			$right = $sec_total / 3600;
			$this->keys[] = $key;

			$this->planets[$key] = new Planet($key);
			$this->planets[$key]->setRelPos($dd + $right);
			$this->planets[$key]->setSign($sign);
		}
		$this->genHoroArg();
		return $retval;
	}

	function calcHousePlanet($houseNum1) {
		$house1Name = "house" . $houseNum1;
		$house1Obj = $this->planets[$house1Name];
		$house1Pos = $house1Obj->absPos;

		$housePlanet = "";

		$houseNum2 = $houseNum1 + 1;
		if($houseNum2 == 13) $houseNum2 = 1;

		$house2Name = "house" . $houseNum2;
		$house2Obj = $this->planets[$house2Name];
		$house2Pos = $house2Obj->absPos;


		if( $this->getNextSign($house1Obj->sign) == $house2Obj->sign ) {
			if( (30 - $house1Obj->relPos) < $house2Obj->relPos ) {
				$houseSign = $house2Obj->sign;
			} else {
				$houseSign = $house1Obj->sign;
			}
		} else if($house1Obj->sign == $house2Obj->sign) {
			$houseSign = $house1Obj->sign;
		} else if($this->getNextSign($house1Obj->sign)==$this->getPrevSign($house2Obj->sign)) {
			$houseSign = $this->getNextSign($house1Obj->sign);
		} else {
			$houseSign = $house1Obj->sign;
		}

		$pls = array("Sun","Jupiter","Mars","Venus","Mercury",
			"Saturn","Uranus","Neptune","Pluto");

		if($house1Pos < 0) $house1Pos += 360;
		if($house2Pos < 0) $house2Pos += 360;
		foreach ($pls as $pl) {
			$plObj = $this->planets[$pl];
			$pos = $plObj->absPos;
			if($pos < 0) $pos += 360;
			#if(($house1Pos<$pos) && ($pos<$house2Pos)) 
			if(ri($house1Pos, $house2Pos, $pos))
			{
				$housePlanet = $pl;
				return $housePlanet;
				##break;
			}
		}
		return $houseSign;
	}		

	function getNextSign($sn) {
		$ret = "";
		switch($sn) {
			case "ar": $ret = "ta"; break;
			case "ta": $ret = "ge"; break;
			case "ge": $ret = "cn"; break;
			case "cn": $ret = "le"; break;
			case "le": $ret = "vi"; break;
			case "vi": $ret = "li"; break;
			case "li": $ret = "sc"; break;
			case "sc": $ret = "sa"; break;
			case "sa": $ret = "cp"; break;
			case "cp": $ret = "aq"; break;
			case "aq": $ret = "pi"; break;
			case "pi": $ret = "ar"; break;
			default: break;
		}
		return $ret;
	}

	function getPrevSign($sn) {
		$ret = "";
		switch($sn) {
			case "ar": $ret = "pi"; break;
			case "ta": $ret = "ar"; break;
			case "ge": $ret = "ta"; break;
			case "cn": $ret = "ge"; break;
			case "le": $ret = "cn"; break;
			case "vi": $ret = "le"; break;
			case "li": $ret = "vi"; break;
			case "sc": $ret = "li"; break;
			case "sa": $ret = "sc"; break;
			case "cp": $ret = "sa"; break;
			case "aq": $ret = "cp"; break;
			case "pi": $ret = "aq"; break;
			default: break;
		}
		return $ret;
	}

	function getSignOrder() {
		$ary = array();
		$asc = $this->sign["Ascendant"];

		$str = "ar,ta,ge,cn,le,vi,li,sc,sa,cp,aq,pi,ar,ta,ge,cn,le,vi,li,sc,sa,cp,aq,pi";
		if(ereg("($asc,[a-z,]{32})", $str, $ary)) 
			$signOrder = $ary[1];

		$this->orderedSign = explode(",", $signOrder);
	}

	function getAbsDegree() {
		$this->getSignOrder();
		for($i=0; $i<12; $i++) {
			$signName = $this->orderedSign[$i];
			$this->planets[$signName] = new Planet($signName);
			$this->planets[$signName]->setSign($signName);
			$this->planets[$signName]->setRelPos(0);
			$this->planets[$signName]->setAbsPos(
				$i * 30 -
				$this->planets["Ascendant"]->getRelPos() );
			
		}
		foreach ($this->keys as $key) {
			$sign = $this->sign[$key];
			$this->planets[$key]->setAbsPos(
				$this->planets[$sign]->getAbsPos()
				+ $this->planets[$key]->getRelPos());
		}
		$this->getAbsDegreeBySign();
	}

	function getAbsDegreeBySign() {
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

	function shiftAllPlanet($deg) {
		foreach($this->keys as $key) {
			$c_absPos = $this->planets[$key]->getAbsPos();
			$s_absPos = $c_absPos + $deg;
			if($s_absPos < 0) $s_absPos += 360;
			$this->planets[$key]->setAbsPos($s_absPos);
		}
	}

	function getTzOffset() {
		$this->setTzOffset($this->tz);
		return;
	}

	function setTzOffset($offset) {
		$this->offset = 3600 * floatval($offset) * -1;
	}

	function genHoroArg() {
		foreach( $this->planets as $planet) {
			$this->horoarg .= $planet->getName();
			$this->horoarg .= "=";
			$this->horoarg .= $planet->getSign();
			$this->horoarg .= $planet->getRelPos();
			$this->horoarg .= '&';
		}

	}
}
?>
