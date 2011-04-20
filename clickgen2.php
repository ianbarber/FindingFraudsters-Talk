<?php

// Click Fraud Model Parameters
$fraud = fopen("fraud.csv", 'w');
foreach(getClicks(200) as $data) {
	fputcsv($fraud, $data);
}


function getClicks($fraud = false, $iterations = 365) {
	$return = array();
	$time = strtotime("2010-03-23 12:00");
	for($i = 0; $i < $iterations; $i++) {
		$time = strtotime("+1 day", $time);
		$fraudScore = $fraud && $fraud < $i ? rand(1, 5) : 0;
		$dateScore = (date("w", $time) + 1);
		$score = rand(1,10);
		$return[]  = array(date("Y-m-d", $time), $score + $dateScore + $fraudScore);
	}
	return $return;	
}
