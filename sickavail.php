<?php

define("DAY_WINDOW", 7);
define("WEEK_WINDOW", 10);
$sensitivity = 0.2;

/*
// Try different sensitivies - we can only do this because we know the data!
do {
	list($alarms, $daysToDetect) = detect($sensitivity);
	echo $sensitivity, "\t", $alarms, "\t", $daysToDetect, PHP_EOL;
	$sensitivity -= 0.001;
} while($alarms >= 0 && $sensitivity > 0);
*/
$d = detect(0.011, false);
echo "Alarms: ", $d[0], " Days To Detect: ", $d[1], PHP_EOL;

/**
 * Detect a fraudulent click set based on a 
 * sickness/availability model.
 *
 * @param float $sensitivity 
 * @return array (falseAlarms (int), $daysToDetect (int))
 * @author Ian Barber
 */
function detect($sensitivity, $output = false) {
	$i = 0;
	$alarmCount = 0;
	$daysToDetect = 0;
	$fraud = fopen("fraudclicks.csv", 'r');
	$last = array();
	$availability = array();
	$estimate = 0;
	$poisson = 0;
	$i = 0;
	while($data = fgetcsv($fraud)) {
	    $i++;
	    $dow = date("w", strtotime($data[0]));
		if(count($last) >= DAY_WINDOW) {
		    if(count($last) && isset($availability[$dow])) {
    		    $sickness = 0;
    		    foreach($last as $day => $value) {
    		        $davail = array_sum($availability[$day]) / count($availability[$day]);
    	            $sickness += $value / $davail;
    		    }
    		    $sickness /= count($last);
    		    $avail = array_sum($availability[$dow]) / count($availability[$dow]);
    		    $estimate = $sickness * $avail;
		    
    			$fac = fac($data[1]);
    			$poisson = exp(-$estimate) * pow($estimate,$data[1]) / $fac;
    			if($poisson < $sensitivity && $data[1] > $estimate) {
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
	    }
	    if(!isset($availability[$dow])) {
		    $availability[$dow] = array();
		}
		$availability[$dow][] = $data[1];
		if(count($availability[$dow]) > WEEK_WINDOW) {
		    array_shift($availability[$dow]);
		}
		$last[$dow] = $data[1];
		if($output) {
		    echo $estimate, PHP_EOL;
		}
	}
	return array($alarmCount-1, $daysToDetect);
}

// Dumb factorial function, do not use with big numbers!
function fac($num) {
    if($num == 1) {
        return 1;
    }
    return $num * fac($num-1);
}