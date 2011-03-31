<?php

// Click Fraud Model Parameters
$train = fopen("data.csv", 'w');
foreach(getClicks() as $data) {
	fputcsv($train, $data);
}
$clean = fopen("clean.csv", 'w');
foreach(getClicks() as $data) {
	fputcsv($clean, $data);
}
$fraud = fopen("fraud.csv", 'w');
foreach(getClicks(10, 50) as $data) {
	fputcsv($fraud, $data);
}


function getClicks($minFraud = 0, $maxFraud = 0, $iterations = 10000) {
	$return = array();
	for($i = 0; $i < $iterations; $i++) {
		$data = array();
		$data['season'] = rand(1, 4);
		$data['dayOfWeek'] = rand(1,7);
		$data['onSite'] = rand(0, 1);
		$data['exactSearch'] = $data['onSite'] ? rand(0, 1) : 0;
		$data['broadSearch'] = $data['onSite'] || $data['exactSearch'] ? 0 : 1;
		$data['clicks'] = 	intval(($data['season'] * 0.1) *
		 					($data['dayOfWeek'] * 0.2) * 
							($data['onSite'] == 1 ? 0.5 : 1) *
							($data['exactSearch'] == 1 ? 1.2 : 1) *
							($data['broadSearch'] == 1 ? 1.1 : 1) *
							rand(100, 200));
		if($minFraud) {
			$data['clicks'] += rand($minFraud, $maxFraud);
		}
		$return[] = $data;
	}
	return $return;	
}
