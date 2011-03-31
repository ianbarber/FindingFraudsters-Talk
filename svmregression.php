<?php

// Load data into arrays
$count = $total = 0;
$output = array();
$train = fopen("data.csv", 'r');
$max = array_fill(0, 6, 0);
$total = 0;
$stddev = 0;
while($data = fgetcsv($train)) {
	foreach($data as $key => $val) {
		if($max[$key] < $val) {
			$max[$key] = $val;
		}
	}
	$count++;
	$total += $data[5];
}
$average = $total/$count;

rewind($train);
while($data = fgetcsv($train)) {
	$output[] = array($data[5], $data[1]/$max[1], $data[2]/$max[2], $data[3]/$max[3], $data[4]/$max[4]);
	$stddev += pow($data[5] - $average, 2);
}
$stddev = sqrt($stddev/$count);

$svm = new svm();
$svm->setOptions(array(
        SVM::OPT_TYPE => SVM::C_SVC,
        SVM::OPT_KERNEL_TYPE => SVM::KERNEL_LINEAR,
        SVM::OPT_P => 0.1,  // epsilon 0.1
));

$total = $tp = $tn = $fp = $fn = 0;
$model = $svm->train($output);
if($model) {
	$clean = fopen("clean.csv", 'r');
	while($data = fgetcsv($clean)) {
		$d = array(0, $data[1], $data[2], $data[3], $data[4]);
		$result = $model->predict($d);
		if($data[5] > ($result + $stddev)) {
			$fp++;
		} else {
			$tn++;
		}
		$total++;
	}

	$fraud = fopen("fraud.csv", 'r');
	while($data = fgetcsv($fraud)) {
		$d = array(0, $data[1], $data[2], $data[3], $data[4]);
		$result = $model->predict($d);
		if($data[5] > ($result + $stddev)) {
			$tp++;
		} else {
			$fn++;
		}
		$total++;
	}
} else {
	echo "Failed to classify";
}

echo "False Positives: " . $fp/$total . PHP_EOL;
echo "False Negatives: " . $fn/$total . PHP_EOL;
echo "True Positives: " . $tp/$total . PHP_EOL;
echo "True Negatives: " . $tn/$total . PHP_EOL;
echo "Accuracy: " . (($tp+$tn) / $total) . PHP_EOL;
