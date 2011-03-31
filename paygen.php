<?php

$countries = array(
	'UK' => 'UK',
	'US' => 'US',
	'ZN' => 'ZN',
	'AUS' => 'AUS',
	'DE' => 'DE',
	'IN' => 'IN',
	'IT' => 'IT',
);

// Model
$data = array(
	'purchase_value' => 1299, // pence
	'geo_country' => 'UK',
	'time' => '11:45',
	'delivery_country' => 'UK',
	'delivery_matches_card' => true,
	'previous_orders' => true,
	'difference_from_last_trans' => 100,
	'timegap' => 360000,
	'product_category' => 'media',
	'is_fraud' => false,
);


$train = fopen("paydata.csv", 'w');
for($i = 0; $i < 10000, $i++) {
	$data = generate_payment($countries);
	fputcsv($train, $data);
}


$classify = fopen("paytest.csv", 'w');
for($i = 0; $i < 1000, $i++) {
	$data = generate_payment($countries);
	fputcsv($classify, $data);
}

function generate_payment($countries) {
	$data = array();
	$data['purchase_value'] = rand(500, 39900);
	$data['geo_country'] = array_rand($countries);
}