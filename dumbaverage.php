<?php

$train = fopen("data.csv", 'r');

$count = $total = 0;
while($data = fgetcsv($train)) {
	$count++;
	$total += $data[5];
}

$average = $total/$count;
echo "Average: " . $average . "\n";

rewind($train);
$stddev = 0;
while($data = fgetcsv($train)) {
	$stddev += pow($data[5] - $average, 2);
}
$stddev = sqrt($stddev/$count);
echo "Stddev: " . $stddev . "\n";

$tp = $tn = $fp = $fn = 0;
$total = 0;

$clean = fopen("clean.csv", 'r');
while($data = fgetcsv($clean)) {
	if($data[5] > ($average + $stddev)) {
		$fp++;
	} else {
		$tn++;
	}
	$total++;
}

$fraud = fopen("fraud.csv", 'r');
while($data = fgetcsv($fraud)) {
	if($data[5] > ($average + $stddev)) {
		$tp++;
	} else {
		$fn++;
	}
	$total++;
}

echo "False Positives: " . $fp/$total . PHP_EOL;
echo "False Negatives: " . $fn/$total . PHP_EOL;
echo "True Positives: " . $tp/$total . PHP_EOL;
echo "True Negatives: " . $tn/$total . PHP_EOL;
echo "Accuracy: " . (($tp+$tn) / $total) . PHP_EOL;
