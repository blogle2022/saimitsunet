<?
class Aspect {

	var $planets = array();
	var $aspect;

}



class calcAspect {

	var $names = array(
		"Sun",
		"Moon",
		"Mercury",
		"Venus",
		"Mars",
		"Jupiter",
		"Saturn",
		"Uranus",
		"Neptune",
		"Pluto");
	var $result = array();	
	var $tmp_result = array();
	var $hs;

	function calcAspect($hs) {
		$this->hs = $hs;
		$this->makeAspect();
	}

	function makeAspect() {
		global $aspect_def;
		for($i=0; $i<9; $i++) {
			$p1 = $this->names[$i];
			for($j=$i+1; $j<10; $j++) {
				$p2 = $this->names[$j];
				$ret = $this->compare($p1, $p2);
				if($ret >= 0) {
					$str = "$p1,$p2,$ret, $aspect_def[$ret]";
					$this->result[] = $str;
				}
			}
		}
	}

	function compare($p1, $p2) {
		$abs1 = $this->hs->planets[$p1]->getAbsPos();
		$abs2 = $this->hs->planets[$p2]->getAbsPos();

		$asps = array(	
			0,	30,	45,	60,
			72,	90,	120,	135,
			150,	180
		);

		$orbs = array(
			3.0,	3.0,	3.0,	3.0,
			3.0,	3.0,	3.0,	3.0,
			3.0,	3.0
		);

		$mm = abs($abs1 - $abs2);
		if($mm > 180) $mm = 360 - $mm;


		for($i=0; $i<10; $i++) {

			$def = abs($mm - $asps[$i]);
			$log = "$p1:$abs1, $p2:$abs2, $mm, $def, cmpwith:$asps[$i]";
			$this->tmp_result[] = $log;

			if($def <= $orbs[$i]) {
				return $i;
			}
		}
		return -1;
	}

}

?>


