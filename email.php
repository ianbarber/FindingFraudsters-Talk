<?php

function compareEmailToName($name, $email) {
    $name = strtolower($name);
    $parts = explode(" ", $name);
    
    list($user, $domain) = explode("@", $email);
    
    $user = str_replace(".+", " ", $user);
    
    if(levenshtein($name, $user) < 3) {
        return 1.0;
    } else if(levenshtein($name, $domain) < 6) {
        return 0.8;
    }
    
    if(count($parts)) {
        $lowest = 10;
        foreach($parts as $part) {
            var_dump('------------');
            var_dump($part);
            var_dump($user);
            $l = levenshtein($user, $part);
            var_dump($l);
            if($l < strlen($user) && $l < $lowest) {
                $lowest = $l;
            } 
            var_dump($domain);
            $l = levenshtein($domain, $part);
            var_dump($l);
            if($l < strlen($domain) && $l < $lowest) {
                $lowest = $l;
            }
        }
        return 0.8 - (0.1*$lowest);
    }
}

var_dump(compareEmailToName("ian barber", "ian@raegunne.com"));
var_dump(compareEmailToName("ian barber", "ian.barber@gmail.com"));
var_dump(compareEmailToName("ian barber", "me@ianbarber.com"));
var_dump(compareEmailToName("ian barber", "gonzo@raegunne.com"));