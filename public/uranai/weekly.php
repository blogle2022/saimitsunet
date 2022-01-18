<?php

use App\Services\Model;

require_once __DIR__ . '/../../bootstrap/uranai.php';

$horo->invokeSWE();
$horo->getAbsDegree();
$tomorrow = time() + 86400;
$year2 = date("Y", $tomorrow);
$month2 = date("n", $tomorrow);
$day2 = date("j", $tomorrow);

$weeklater = $tomorrow + 86400 * 6;
$year3 = date("Y", $weeklater);
$month3 = date("n", $weeklater);
$day3 = date("j", $weeklater);

$horo2 = new HoroScope(
	$year2,
	$month2,
	$day2,
	0,
	0,
	$user->lon,
	$user->lat,
	$user->tz
);
$horo2->invokeSWE();
$horo2->getAbsDegree();

$horo3 = new HoroScope(
	$year3,
	$month3,
	$day3,
	0,
	0,
	$user->lon,
	$user->lat,
	$user->tz
);
$horo3->invokeSWE();
$horo3->getAbsDegree();

$mess = transit($horo, $horo2, $horo3);

$count = 0;
for ($i = 0; $i < count($mess); $i++) {
	list($a, $b, $c, $d, $e, $f, $g) = explode(",", $mess[$i]);
	$cpl[$i] = $a;
	$degl[$i] = $b;
	$degu[$i] = $c;
	$bpl[$i] = $d;
	$deg[$i] = $e;
	$asp[$i] = $f;
	$msgid[$i] = $g;

	$done = 0;

	$offset = 13 * ($g - 1) * 2;

	if (($year2 - $user->year) >= 18) {
		$file = public_path() . "/libs/weekly/today_1_adult.txt";
	} else {
		$file = public_path() . "/libs/weekly/today_1_young.txt";
	}

	$allText = file_get_contents($file);
	$messageArr = explode("\n", $allText);

	##
	## begin building mixed message
	##
	$line_len = 50;
	$line_cnt = 3;
	$msg_line = 0;
	for ($j = 0; $j < 4; $j++) {
		$ofs = 1 + $j * $line_cnt;
		if ($hash & (2 ^ $j)) {
			$ofs += 1 + 4 * $line_cnt;
		}
		$currentLine = $offset + $ofs;

		$txt = "";
		for ($m = $currentLine; $m < $line_cnt + $currentLine; $m++) {
			$msg[$i][$msg_line] = stripLine($messageArr[$m]);
			$msg_line++;
		}
	}
	##
	## end building mixed message
	##

	$count++;
}

$text = "<h4>" . $month2 . "/" . $day2 . "～" . $month3 . "/" . $day3 . "までの運勢</h4><br><br>";
for ($i = 0; $i < $count; $i++) {
	$text .= "<b>" . $jp["$cpl[$i]"] . "と";
	$text .= "誕生時の" . $jp["$bpl[$i]"] . "が";
	$text .= $asp[$i] . "度をとっています。</b><br>";

	for ($l = 0; $l < 13; $l++) {
		$text .= $msg[$i][$l];
	}
	$text .= "<br><br>";
}
if ($count < 1) {
	$text .= "この期間に意味をもつ角度をとる惑星はありません。<br><br>";
}

## 非課金ユーザー向け
##if(!$auth) {
##       $text = $payment_needed;
##}

$partner = new Model('partnerinfo');
$partners = $partner->find('mailaddr', '=', $_SESSION['user']['mail']);

$person = $request->post('person');
$params = [
	'select' => $partners,
	'text' => $text,
	'partners' => $partners,
	'person' => $person,
];
$_SESSION['token'] = csrf();
$params['token'] = $_SESSION['token'];

echo $response->view('uranai.weekly', $params);
