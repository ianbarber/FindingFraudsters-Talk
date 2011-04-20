<?php

/*
 * Function to import the data and turn into a LibSVM data set
 */
 
$fh = fopen('paydata.csv', 'r');
$output = array();
$i = 0;
$fraud = $non = 0;
while($data = fgetcsv($fh)) {
    if($data[14] == 1) { $fraud++; } else { $non++; }
    $output[] = array(
        $data[14] == 1 ? -1 : 1,
        1 => ($data[0]/20000.00) - 1.0, // price
        2 => $data[1] == 'CN' ? 1.0 : -1.0,
        3 => $data[1] == 'US' ? 1.0 : -1.0, 
        8 => $data[5] == 'digital' ? 1.0 : -1.0, // prod type
        10 => $data[7] == 1 ? 1.0 : -1.0, // geo matches card
        11 => $data[6] == 1 ? 1.0 : -1.0, // delivery matches card
        12 => $data[9] == 1 ? 1.0 : -1.0, // free shipping
        13 => ($data[13] / 1.5) - 1.0, // quantity
    );
}
var_dump($fraud);
var_dump($non);
var_dump($fraud/($non+$fraud));


$svm = new svm();
$model = $svm->train($output, array(-1 => 0.65, 1 => 0.5));
$model->save('learn.model');

$fp = $tp = $fn = $tn = 0;
foreach($output as $test) {
    $real = $test[0];
    //$test[0] = 0;
    $res = $model->predict($test);
    if($real > 0) {
        if($res > 0) {
            $tp++;
        } else {
            $fn++;
        } 
    } else {
        if($res > 0) {
            $fp++;
        } else {
            $tn++;
        }
    }
}

var_dump("True Positive " . $tp);
var_dump("True Negative " . $tn);
var_dump("False Positive " . $fp);
var_dump("False Negative " . $fn);
var_dump("Accuracy " . (($tp+$tn)/($tp+$tn+$fp+$fn)));