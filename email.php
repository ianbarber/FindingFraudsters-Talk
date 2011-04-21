<?php

/**
 * Compare an email address to a name using edit distance
 * Returns a float for quality of match, anything over 0.5 can be considered a good match
 * 
 * @param string $name 
 * @param string $email 
 * @return float -1 to 1
 * @author Ian Barber
 */
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
        $lowest = 8;
        foreach($parts as $part) {
            $l = levenshtein($user, $part);
            if($l < strlen($part) && $l < $lowest) {
                $lowest = $l;
            } 
            $l = levenshtein($domain, $part);
            if($l < strlen($part) && $l < $lowest) {
                $lowest = $l;
            }
        }
        return 0.7 - (0.2*$lowest);
    }
}

var_dump(compareEmailToName("ian barber", "ian@mydomain.com"));
var_dump(compareEmailToName("ian barber", "ian.barber@email.com"));
var_dump(compareEmailToName("ian barber", "me@ianbarber.com"));
var_dump(compareEmailToName("ian barber", "gonzo@mydomain.com"));
var_dump(compareEmailToName("johnny two times", "gonzo@mydomain.com"));