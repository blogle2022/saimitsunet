<?


function calcVirtualAspect($deg1, $deg2) {

	$deg1 = floatval($deg1);
	$deg2 = floatval($deg2);
	if($deg1 < 0) $deg1 += 360;
	if($deg2 < 0) $deg2 += 360;

	$avg = ($deg1 + $deg2) / 2;
	if(abs($avg - $deg1) > 90 || abs($avg - $deg2)) $avg -= 180;
	if($avg < 0) $avg += 360;
	
	return $avg;
}


function virtualHoro($horo1, $horo2) {

	$virtualAspect = array();
	$plnts = array(
		"Sun","Moon","Mercury","Venus",
		"Mars","Jupiter","Saturn","Uranus",
		"Neptune","Pluto");
	$horo1->getAbsDegreeBySign();
	$horo2->getAbsDegreeBySign();
	
	foreach ($plnts as $pl) {
		$virtualAspect[$pl] = calcVirtualAspect($horo1->planets[$pl]->absPosBySign, $horo2->planets[$pl]->absPosBySign);
	}
	return $virtualAspect;
}

?>
