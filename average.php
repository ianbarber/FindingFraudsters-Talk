<?php
define("DAY_WINDOW", 7);

/*
// Try different sensitivities - obviously we can only do this as we know the data!
$sensitivity = 0;
do {
	list($alarms, $daysToDetect) = detect($sensitivity);
	echo $sensitivity, "\t", $alarms, "\t", $daysToDetect, PHP_EOL;
	$sensitivity += 0.1;
} while($alarms >= 0);
*/

//$d = detect(1.6, true);
$d = detect(2.7, false);
echo "Alarms: ", $d[0], " Days To Detect: ", $d[1], PHP_EOL;

/**
 * Return the false alarms and days to detect
 *
 * @param float $sensitivity 
 * @return array (falseAlarms (int), $daysToDetect (int))
 * @author Ian Barber
 */
function detect($sensitivity, $output = false) {
    $window = array();
	$i = 0;
	$alarmCount = 0;
	$daysToDetect = 0;
	$fraud = fopen("fraudclicks.csv", 'r');
	$average = $stddev = 0;
	while($data = fgetcsv($fraud)) {
	    $i++;
	    if(count($window) > DAY_WINDOW) {
			array_shift($window);
			$average = array_sum($window) / DAY_WINDOW;
			foreach($window as $val) {
			    $stddev += pow($val - $average, 2);
			}
			$stddev = sqrt($stddev/DAY_WINDOW);
			
    		if($data[1] > ($average + ($sensitivity * $stddev))) {
    			$alarmCount++;
    			if($i > 201) { 
    				break;
    			}
    		} else {
    			if($i > 201) {
    				$daysToDetect++;
    			}
    		}
	    }
		array_push($window, $data[1]);
		if($output) {
		    echo ($average + ($sensitivity * $stddev)), PHP_EOL;
		}
	}
	return array($alarmCount-1, $daysToDetect);
}