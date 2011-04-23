<?php
/*
 * Example of looking at poisson data in different ways. 
 * The first is the traditional poisson with a factorial.
 * The second is the normal distribution example - better for bigger numbers
 * The thing is an iterative poisson calculation that avoids the factorial
 */

$avg = 15;
$stddev = sqrt(15);

foreach(range(1, 25) as $val) {
    // Classic poisson
    $fac = fac($val);
    $p = (exp(-$avg) * pow($avg,$val)) / $fac;
    
    // Normal approximation
    $n = (1/($stddev * sqrt(2 * 3.14159))) * exp(-pow($val-$avg, 2)/pow(2 * $stddev,2));
    
    // Online poisson
    $p2 = exp(-$avg);
    foreach(range(1, $val) as $i) {
        $p2 *= $avg;
        $p2 /= $i;
    }
    
    echo $n, "\t", $p, "\t", $p2, PHP_EOL;
    
}

function fac($num) {
    if($num == 1) {
        return 1;
    }
    return $num * fac($num-1);
}