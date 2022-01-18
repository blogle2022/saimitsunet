<?php

require_once __DIR__ . '/../../bootstrap/uranai.php';

$horo->invokeSWE();
$horo->getAbsDegree();

$p = intval(httpValue("p"));
$command = httpValue("command");

if ($p == 0) {
	$p = 1;
}

$point["vi"] = 6;
$point["li"] = 7;
$point["sc"] = 8;
$point["sa"] = 9;
$point["cp"] = 10;
$point["aq"] = 11;
$point["pi"] = 12;
$point["ar"] = 1;
$point["ta"] = 2;
$point["ge"] = 3;
$point["cn"] = 4;
$point["le"] = 5;

$lank["4"] =  "○";
$lank["10"] = "○";
$lank["1"] =  "△";
$lank["2"] =  "△";
$lank["5"] =  "△";
$lank["7"] =  "△";
$lank["8"] =  "△";
$lank["11"] = "△";
$lank["0"] = "△";
$lank["3"] =  "×";
$lank["6"] =  "×";
$lank["9"] =  "×";

$company_year = httpValue("company_year");
$company_month = httpValue("company_month");
$company_day = httpValue("company_day");

$ceo_year = httpValue("ceo_year");
$ceo_month = httpValue("ceo_month");
$ceo_date = httpValue("ceo_date");

$boss_year = httpValue("boss_year");
$boss_month = httpValue("boss_month");
$boss_date = httpValue("boss_date");

$assign_year = httpValue("assign_year");
$assign_month = httpValue("assign_month");
$assign_date = httpValue("assign_date");

$future_year = httpValue("future_year");
$future_month = httpValue("future_month");

$company = new HoroScope(
	$company_year,
	$company_month,
	$company_day,
	0, ## hour
	0, ## min
	139.75, ## lon
	35.68, ## lat
	-9
); ## tz
$ceo = new HoroScope(
	$ceo_year,
	$ceo_month,
	$ceo_date,
	0, ## hour
	0, ## min
	139.75, ## lon
	35.68, ## lat
	-9
); ## tz

$boss = new HoroScope(
	$boss_year,
	$boss_month,
	$boss_date,
	0, ## hour
	0, ## min
	139.75, ## lon
	35.68, ## lat
	-9
); ## tz

$assign = new HoroScope(
	$assign_year,
	$assign_month,
	$assign_date,
	0, ## hour
	0, ## min
	139.75, ## lon
	35.68, ## lat
	-9
); ## tz

$future = new HoroScope(
	$future_year,
	$future_month,
	1,
	0, ## hour
	0, ## min
	139.75, ## lon
	35.68, ## lat
	-9
); ## tz

$company->invokeSWE();
$company->getAbsDegree();
if ($p == 1) {
	$ceo->invokeSWE();
	$ceo->getAbsDegree();
}

if ($p == 3 || $p == 4) {
	$boss->invokeSWE();
	$boss->getAbsDegree();
}

if ($p == 5) {
	$assign->invokeSWE();
	$assign->getAbsDegree();
}
if ($p == 6) {
	$future->invokeSWE();
	$future->getAbsDegree();
}

switch ($p) {
	case 1:
		$sign_1_1 = $company->sign["Sun"];
		$sign_1_2 = $ceo->sign["Sun"];

		$sign_2_1 = $company->sign["Moon"];
		$sign_2_2 = $ceo->sign["Moon"];

		$a_company = $point[$sign_1_1];
		$a_ceo = $point[$sign_1_2];
		$b_company = $point[$sing_2_1];
		$b_ceo = $point[$sign_2_2];

		$score_1 = abs($a_company - $a_ceo);
		$score_2 = abs($a_company - $b_ceo);
		$score_3 = abs($b_company - $b_ceo);

		$lank_1 = $lank[$score_1];
		$lank_2 = $lank[$score_2];
		$lank_3 = $lank[$score_3];

		$search = "/" . $lank_1 . $lank_2 . $lank_3 . "/";
		$buf = "";
		$text = "";
		$fp = fopen(public_path() . "/libs/business/business_1", "r");
		if ($fp) {
			$found = false;
			while (($buf = fgets($fp, 4096)) !== false) {
				if (preg_match("/^○/", $buf)) {
					$found = false;
				}
				if (preg_match($search, $buf)) {
					$found = true;
					continue;
				}
				if ($found) {
					$text .= $buf;
				}
			}
			fclose($fp);
		}
		$text = utf2euc($text);

		$title = "会社と経営者の調和";
		# if (!$auth) {
		if (false) {
			$text = utf2euc($payment_needed);
		}
		break;

	case 2:
		$sign_1_1 = $company->sign["Sun"];
		$sign_1_2 = $horo->sign["Sun"];

		$sign_2_1 = $company->sign["Jupiter"];
		$sign_2_2 = $horo->sign["Jupiter"];


		$param_1_1 = $point[$sign_1_1];
		$param_1_2 = $point[$sign_1_2];
		$param_2_1 = $point[$sing_2_1];
		$param_2_2 = $point[$sign_2_2];

		$score_1 = abs($param_1_1 - $param_2_2);
		$score_2 = abs($param_2_1 - $paran_1_2);

		$lank_1 = $lank[$score_1];
		$lank_2 = $lank[$score_2];

		$search = "/" . $lank_1 . $lank_2 . $lank_3 . "/";
		$buf = "";
		$text = "";
		$fp = fopen(public_path() . "/libs/business/business_2", "r");
		if ($fp) {
			$found = false;
			while (($buf = fgets($fp, 4096)) !== false) {
				if (preg_match("/^○/", $buf)) {
					$found = false;
				}
				if (preg_match($search, $buf)) {
					$found = true;
					continue;
				}
				if ($found) {
					$text .= $buf;
				}
			}
			fclose($fp);
		}
		$text = utf2euc($text);

		$title = "あなたと会社のマッチング";
		# if (!$auth) {
		if (false) {
			$text = utf2euc($payment_needed);
		}
		break;

	case 3:
		$sign_1_1 = $boss->sign["Sun"];
		$sign_1_2 = $horo->sign["Sun"];

		$sign_2_1 = $boss->sign["Saturn"];
		$sign_2_2 = $horo->sign["Saturn"];


		$param_1_1 = $point[$sign_1_1];
		$param_1_2 = $point[$sign_1_2];
		$param_2_1 = $point[$sing_2_1];
		$param_2_2 = $point[$sign_2_2];

		$score_1 = abs($param_1_1 - $param_2_2);
		$score_2 = abs($param_2_1 - $paran_1_2);

		$lank_1 = $lank[$score_1];
		$lank_2 = $lank[$score_2];

		$search = "/" . $lank_1 . $lank_2 . "/";
		$buf = "";
		$text = "<br><b>信頼関係</b><br>";
		if ($targetObject->base_sex == "m") {
			$fp = fopen(public_path() . "/libs/business/business_3_male_1", "r");
		} else {
			$fp = fopen(public_path() . "/libs/business/business_3_female_1", "r");
		}
		if ($fp) {
			$found = false;
			while (($buf = fgets($fp, 4096)) !== false) {
				if (preg_match("/^太陽と土星/", $buf)) {
					$found = false;
				}
				if (preg_match($search, $buf)) {
					$found = true;
					continue;
				}
				if ($found) {
					$text .= $buf;
				}
			}
			fclose($fp);
		}

		$text .= "<br>";
		$text .= "<br>";
		$text .= "<b>目的・欲望の関係</b><br>";

		$sign_1_1 = $boss->sign["Sun"];
		$sign_1_2 = $horo->sign["Sun"];

		$sign_2_1 = $boss->sign["Mars"];
		$sign_2_2 = $horo->sign["Mars"];


		$param_1_1 = $point[$sign_1_1];
		$param_1_2 = $point[$sign_1_2];
		$param_2_1 = $point[$sing_2_1];
		$param_2_2 = $point[$sign_2_2];

		$score_1 = abs($param_1_1 - $param_1_2);
		$score_2 = abs($param_1_1 - $paran_2_2);
		$score_3 = abs($param_2_1 - $paran_2_2);

		$lank_1 = $lank[$score_1];
		$lank_2 = $lank[$score_2];
		$lank_3 = $lank[$score_3];

		$search = "/" . $lank_1 . $lank_2 . $lank_3 . "/";
		$buf = "";
		if ($targetObject->base_sex == "m") {
			$fp = fopen(public_path() . "/libs/business/business_3_male_2", "r");
		} else {
			$fp = fopen(public_path() . "/libs/business/business_3_female_2", "r");
		}
		if ($fp) {
			$found = false;
			while (($buf = fgets($fp, 4096)) !== false) {
				if (preg_match("/^太陽と火星/", $buf)) {
					$found = false;
				}
				if (preg_match($search, $buf)) {
					$found = true;
					continue;
				}
				if ($found) {
					$text .= $buf;
				}
			}
			fclose($fp);
		}


		$text = utf2euc($text);


		$title = "上司とのwin-win度";
		# if (!$auth) {
		if (false) {
			$text = utf2euc($payment_needed);
		}
		break;

	case 4:
		break;

	case 5:
		$sign_1_1 = $assign->sign["Moon"];
		$sign_1_2 = $horo->sign["Moon"];

		$sign_2_1 = $assign->sign["Mars"];
		$sign_2_2 = $horo->sign["Mars"];


		$param_1_1 = $point[$sign_1_1];
		$param_1_2 = $point[$sign_1_2];
		$param_2_1 = $point[$sing_2_1];
		$param_2_2 = $point[$sign_2_2];

		$score_1 = abs($param_2_1 - $param_1_2);
		$score_2 = abs($param_1_1 - $paran_2_2);

		$lank_1 = $lank[$score_1];
		$lank_2 = $lank[$score_2];

		$search = "/" . $lank_1 . $lank_2 . "/";
		$buf = "";
		$text = "<br><b>仕事（業務）内容のマッチング</b><br>";
		$fp = fopen(public_path() . "/libs/business/business_5_1", "r");
		if ($fp) {
			$found = false;
			while (($buf = fgets($fp, 4096)) !== false) {
				if (preg_match("/^月と火星/", $buf)) {
					$found = false;
				}
				if (preg_match($search, $buf)) {
					$found = true;
					continue;
				}
				if ($found) {
					$text .= $buf;
				}
			}
			fclose($fp);
		}

		$text .= "<br>";
		$text .= "<br>";
		$text .= "<b>配属先の人間関係</b><br>";

		$sign_1_1 = $assign->sign["Moon"];
		$sign_1_2 = $horo->sign["Moon"];

		$sign_2_1 = $assign->sign["Venus"];
		$sign_2_2 = $horo->sign["Venus"];


		$param_1_1 = $point[$sign_1_1];
		$param_1_2 = $point[$sign_1_2];
		$param_2_1 = $point[$sing_2_1];
		$param_2_2 = $point[$sign_2_2];

		$score_1 = abs($param_2_1 - $param_1_2);
		$score_2 = abs($param_1_1 - $paran_2_2);

		$lank_1 = $lank[$score_1];
		$lank_2 = $lank[$score_2];

		$search = "/" . $lank_1 . $lank_2  . "/";
		$buf = "";
		$fp = fopen(public_path() . "/libs/business/business_5_2", "r");
		if ($fp) {
			$found = false;
			while (($buf = fgets($fp, 4096)) !== false) {
				if (preg_match("/^月と金星/", $buf)) {
					$found = false;
				}
				if (preg_match($search, $buf)) {
					$found = true;
					continue;
				}
				if ($found) {
					$text .= $buf;
				}
			}
			fclose($fp);
		}


		$text = utf2euc($text);


		$title = "あなたと仕事のマッチング";
		# if (!$auth) {
		if (false) {
			$text = utf2euc($payment_needed);
		}
		break;


	case 6:




		$defSun = array(
			'Sun'	 => array(0 => 1,	-3 => 2,	9 => 2,	3 => 2,	-9 => 2,	6 => 2,	-6 => 2),
			'Mercury' => array(0 => 3,	-3 => 4,	9 => 4,	3 => 4,	-9 => 4,	6 => 4,	-6 => 4),
			'Venus'	 => array(0 => 5,	-3 => 6,	9 => 6,	3 => 6,	-9 => 6,	6 => 6,	-6 => 6),
			'Mars'	 => array(0 => 7,	-3 => 8,	9 => 8,	3 => 9,	-9 => 9,	6 => 10,	-6 => 10),
			'Juputer' => array(0 => 11,	-3 => 12,	9 => 12,	3 => 13,	-9 => 13,	6 => 14,	-6 => 14),
			'Saturn' => array(0 => 15,	-3 => 16,	9 => 16,	3 => 17,	-9 => 17,	6 => 18,	-6 => 18),
			'Uranus' => array(0 => 19,	-3 => 20,	9 => 20,	3 => 21,	-9 => 21,	6 => 22,	-6 => 22),
			'Neptune' => array(0 => 23,	-3 => 24,	9 => 24,	3 => 25,	-9 => 25,	6 => 26,	-6 => 26),
			'Pluto' => array(0 => 27,	-3 => 28,	9 => 28,	3 => 28,	-9 => 28,	6 => 28,	-6 => 28)
		);

		$defMoon = array(
			'Sun'	 => array(0 => 29,	-3 => 30,	9 => 30,	3 => 30,	-9 => 30,	6 => 30,	-6 => 30),
			'Mercury' => array(0 => 35,	-3 => 36,	9 => 36,	3 => 36,	-9 => 36,	6 => 36,	-6 => 36),
			'Venus'	 => array(0 => 31,	-3 => 32,	9 => 32,	3 => 32,	-9 => 32,	6 => 32,	-6 => 32),
			'Mars'	 => array(0 => 33,	-3 => 34,	9 => 34,	3 => 34,	-9 => 34,	6 => 34,	-6 => 34),
			'Juputer' => array(0 => 37,	-3 => 38,	9 => 38,	3 => 38,	-9 => 38,	6 => 38,	-6 => 38),
			'Saturn' => array(0 => 39,	-3 => 40,	9 => 40,	3 => 40,	-9 => 40,	6 => 41,	-6 => 41),
			'Uranus' => array(0 => 42,	-3 => 43,	9 => 43,	3 => 43,	-9 => 43,	6 => 44,	-6 => 44),
			'Neptune' => array(0 => 45,	-3 => 46,	9 => 46,	3 => 46,	-9 => 46,	6 => 47,	-6 => 47),
			'Pluto' => array(0 => 48,	-3 => 49,	9 => 49,	3 => 49,	-9 => 49,	6 => 50,	-6 => 50)
		);

		$ary = array('Sun', 'Mercury', 'Venus', 'Mars', 'Jupiter', 'Saturn', 'Uranus', 'Neptune', 'Pluto');

		$signSun = $horo->sign["Sun"];
		$signMoon = $horo->sign["Moon"];
		$paramSignSun = $point[$signSun];
		$paramSignMoon = $point[$signMoon];

		for ($i = 0; $i < 9; $i++) {
			$targetPlanet = $ary[$i];
			$futureSign = $future->sign["$targetPlanet"];
			$def = $paramSignSun - $point["$futureSign"];
			if ($defSun["$targetPlanet"][$def] > 0) {

				$search = "/^" . $defSun["$targetPlanet"][$def] . "\$/";
				$buf = "";
				if ($targetObject->base_sex == "m") {
					$fp = fopen(public_path() . "/libs/business/business_6_male", "r");
				} else {
					$fp = fopen(public_path() . "/libs/business/business_6_female", "r");
				}
				if ($fp) {
					$found = false;
					while (($buf = fgets($fp, 4096)) !== false) {
						if (preg_match("/^[0-9]+$/", $buf)) {
							$found = false;
						}
						if (preg_match($search, $buf)) {
							$found = true;
							continue;
						}
						if ($found) {
							$text .= $buf;
						}
					}
					fclose($fp);
					$text .= "<br><br>";
				}
			}
		}





		for ($i = 0; $i < 9; $i++) {
			$targetPlanet = $ary[$i];
			$futureSign = $future->sign["$targetPlanet"];
			$def = $paramSignSun - $point["$futureSign"];
			if ($defMoon["$targetPlanet"][$def] > 0) {

				$search = "/^" . $defMoon["$targetPlanet"][$def] . "\$/";
				$buf = "";
				if ($targetObject->base_sex == "m") {
					$fp = fopen(public_path() . "/libs/business/business_6_male", "r");
				} else {
					$fp = fopen(public_path() . "/libs/business/business_6_female", "r");
				}
				if ($fp) {
					$found = false;
					while (($buf = fgets($fp, 4096)) !== false) {
						if (preg_match("/^[0-9]+$/", $buf)) {
							$found = false;
						}
						if (preg_match($search, $buf)) {
							$found = true;
							continue;
						}
						if ($found) {
							$text .= $buf;
						}
					}
					fclose($fp);
					$text .= "<br><br>";
				}
			}
		}







		$text = utf2euc($text);
		$title = "あなたの出世・昇給度は";
		break;

	default:
}

$text = space2br(euc2utf($text));
## 非課金ユーザー向け
#if (!$auth) {
if (false) {
	$text = $payment_needed;
}
$params = [
	'title' => $title,
	'text' => $text,
	'p' => $p,
	'command' => $command,
];
$_SESSION['token'] = csrf();
$params['token'] = $_SESSION['token'];

echo $response->view('uranai.business', $params);
