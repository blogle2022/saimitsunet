<?

class FortuneMessage {

	var $filename;

	function setFileName($fn) {
		if(is_array($fn)) {
			$fileCnt = count($fn);
			$today = intval(date("Ymd"));
			$mod = $today % $fileCnt;
			$this->filename = $fn[$mod];
		} else if(is_string($fn)) {
			$this->filename = $fn;
		}
	}

}

class 


?>
