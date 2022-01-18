<?

$name = httpValue("name");
$year = httpValue("year");
$month = httpValue("month");
$day = httpValue("day");
$ampm = httpValue("ampm");
if($ampm == "unknown") {
	$hour = 0;
	$minute = 0;
	$am_pm = 0;
} else if ($ampm == "am") {
	$hour = httpValue("hour12");
	$minute = httpValue("min");
	$am_pm = 1;
} else if ($ampm == "pm") {
	$hour = 12 + httpValue("hour12");
	$minute = httpValue("min");
	$am_pm = 2; 
}

$hash = intval(($year + $month + $day + $hour + $min) % 16);


$where = httpValue("where");

list($lon, $lat, $tz) = location($where);
if($lon=="" || $lat=="") {
	$lon = 139.75;
	$lat = 35.68;
}
$submit1 = httpValue("submit1");

$horo = new HoroScope($year,$month,$day,$hour,$minute,$lon,$lat,$tz);
$horo->invokeSWE();
$horo->getAbsDegree();

?>
