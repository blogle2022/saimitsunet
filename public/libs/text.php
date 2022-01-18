<?php

class MixMsg
{
	var $line_len;
	var $lines_par;
	var $total_lines;

	var $filename;
	var $hash;

	function __construct($filename)
	{
		$this->filename = $filename;
	}

	function setLineLen($line_len)
	{
		$this->line_len = $line_len;
	}
	function setLinesPar($lines_par)
	{
		$this->lines_par = $lines_par;
	}
	function setTotalLines($total_lines)
	{
		$this->total_lines = $total_lines;
	}
	function setHash($hash)
	{
		$this->hash = $hash;
	}
	function getMessage($idx)
	{
		if (!$idx) {
			$txt = "ご入力いただいているデータでの星のめぐり合わせが無い状態にあります(詳細は<a href=about_non.php target=_blank>こちら</a>)";
			return $txt;
		}

		$allText = file_get_contents($this->filename);
		$messageArr = explode("\n", $allText);

		$txt = "";
		$begin_ofs = $this->total_lines * ($idx - 1) * 2;
		for ($j = 0; $j < 4; $j++) {
			$ofs = (1 + $j * $this->lines_par);
			if (($this->hash) & (2 ^ $j)) {
				$ofs += 1 + 4 * $this->lines_par;
			}

			$startLine = $begin_ofs + $ofs;

			for ($l = $startLine; $l < ($this->lines_par + $startLine); $l++) {
				$txt .= stripLine($messageArr[$l]);
			}
		}
		return ($txt);
	}
}
