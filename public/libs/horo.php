<?php

if ($_SESSION['user']) {
	$target = httpValue('person');
	if (!$target || $target == 'myself') {
		$horo = new HoroScope(
			$user->year,
			$user->month,
			$user->day,
			$user->hour,
			$user->min,
			$user->lon,
			$user->lat,
			$user->tz
		);
		$targetname = $user->base_nick;
		$targetObject = $user;
	} else {
		$p = $user->partners[$target];
		$horo = new HoroScope(
			$p->b_year,
			$p->b_mon,
			$p->b_day,
			$p->b_hour,
			$p->b_min,
			$p->lon,
			$p->lat,
			$p->tz
		);
		$targetname = $user->partners[$target]->username;
		$targetObject = $p;
	}
}
