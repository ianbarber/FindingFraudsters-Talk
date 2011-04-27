<?php
/*
 * Output data for two variances of normal distribution
 * with same mean. 
 * Output a poisson distribution
 */

foreach(range(1, 20) as $num) {
    echo normal(10, 2, $num), "\t", normal(10, 3, $num), PHP_EOL;
}

foreach(range(1, 10) as $num) {
    echo poisson(5, $num), PHP_EOL;
}

function normal($mean, $stddev, $n) {
    return (1/($stddev * sqrt(2 * 3.14159))) * exp(-pow($n-$mean, 2)/pow(2 * $stddev,2));
}

function poisson($mean, $n) {
    $poisson = exp(-$mean);
    foreach(range(1, $n) as $i) {
        $poisson *= $mean;
        $poisson /= $i;
    }
    return $poisson;
}