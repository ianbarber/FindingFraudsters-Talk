<?php

$countries = array(
	'UK' => 'UK',
	'US' => 'US',
	'CN' => 'CN',
	'CA' => 'CA',
	'DE' => 'DE',
	'IN' => 'IN',
	'IT' => 'IT',
	'DN' => 'DN',
	'FI' => 'FI',
);

$products = array(
    'digital' => 'digital',
    'small_item' => 'small_item',
    'large_item' => 'large_item'
);

// Model
$data = array(
	'purchase_value' => 1299, // pence
	'geo_country' => 'UK',
	'time' => '11:45',
	'card_country' => 'UK',
	'delivery_country' => 'UK',
	'delivery_matches_card' => true,
	'geo_matches_card' => true,
	'previous_orders' => true,
	'difference_from_last_trans' => 100,
	'timegap' => 360000,
	'product_category' => 'media',
	'email_like_name' => true,
	'free_email_provider' => true,
	'disposable_email_provider' => false,
	'free_shipping' => true,
	'quantity' => 1,
	'is_fraud' => false,
);
// several of the same thing?
// IP address used for other orders
// Multiple orders for one delivery with different cards

$train = fopen("paydata.csv", 'w');
$fraud = 0; 
$non = 0;
for($i = 0; $i < 10000; $i++) {
	$data = generate_payment($countries, $products);
	if($data['is_fraud']) {
	    $fraud++;
	} else {
	    $non++;
	}
	fputcsv($train, $data);
}
var_dump($fraud);
var_dump($non);
var_dump($fraud/($non+$fraud));


$classify = fopen("paytest.csv", 'w');
$fraud = 0; 
$non = 0;
for($i = 0; $i < 1000; $i++) {
	$data = generate_payment($countries, $products);
	if($data['is_fraud']) {
	    $fraud++;
	} else {
	    $non++;
	}
	fputcsv($classify, $data);
}
var_dump($fraud);
var_dump($non);
var_dump($fraud/($non+$fraud));

function generate_payment($countries, $products) {
	$data = array();
	$data['purchase_value'] = rand(500, 39900);
	$data['geo_country'] = array_rand($countries);
	$data['previous_orders'] = rand(0, 1);
	$data['time'] = rand(0, 23);
	$data['timegap'] = rand(3600, 360000);
	$data['product_category'] = array_rand($products);
	$data['delivery_matches_card'] = rand(0, 1);
	$data['geo_ip_matches_card'] = rand(0,1);
	$data['difference_from_last_trans'] = $data['previous_orders'] ? rand(500, 20000) :  $data['purchase_value'];
	$data['free_shipping'] = rand(0, 1);
	$data['email_like_name'] = rand(0, 1);
	$data['free_email_provider'] = rand(0, 1);
	$data['disposable_email_provider'] = rand(0, 100) == 0 ? 1 : 0;
	$data['quantity'] = rand(0, 5) == 0 ? rand(1, 3) : 1;
	
    if( ($data['purchase_value'] > 10000 && 
	    $data['geo_ip_matches_card'] == 0 && 
	    $data['product_category'] != 'digital' && 
	    $data['free_shipping'] == 0 && 
	    $data['delivery_matches_card'] == 0) ||
	    ($data['geo_country'] == 'CN') 
	){
	    $data['is_fraud'] = 1;
	} else {
	    $data['is_fraud'] = 0;
	}
	return $data;
}