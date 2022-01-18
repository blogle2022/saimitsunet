<?

$name2 = httpValue("name2");
$year2 = httpValue("year2");
$month2 = httpValue("month2");
$day2 = httpValue("day2");
$ampm2 = httpValue("ampm2");
if($ampm2 == "unknown") {
	$hour2 = 0;
	$minute2 = 0;
	$am_pm2 = 0;
} else if ($ampm2 == "am") {
	$hour2 = httpValue("hour122");
	$minute2 = httpValue("min2");
	$am_pm2 = 1;
} else if ($ampm2 == "pm") {
	$hour2 = 12 + httpValue("hour122");
	$minute2 = httpValue("min2");
	$am_pm2 = 2; 
}

$where2 = httpValue("where2");
list($lon2, $lat2, $tz2) = location($where2);
if($lon2=="" || $lat2=="") {
        $lon2 = 139.75;
        $lat2 = 35.68;
}      





#$lon = httpValue("lon");
#$lon2 = 139.75;
#$lat = httpValue("lat");
#$lat2 = 35.68;
#$tz = httpValue("tz");
#$tz2 = "JST";
$submit2 = httpValue("submit2");

$horo2 = new HoroScope($year2,$month2,$day2,$hour2,$minute2,$lon2,$lat2,$tz2);
$horo2->invokeSWE();
$horo2->getAbsDegree();

?>
