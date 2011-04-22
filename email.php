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
    $email = strtolower($email);
    $parts = explode(" ", $name);
    $percent = 0;
    
    list($user, $domain) = explode("@", $email);
    $user = str_replace(array(".", "+"), " ", $user);
    $domain = preg_replace("/\..*/", "", $domain);
    
    similar_text($name, $user, $percent);
    if($percent > 80) {
        return 1.0;
    }
    similar_text($name, $domain, $percent);
    if($percent > 80) {
        return 0.8;
    }
    
    if(count($parts)) {
        $highest = 0;
        foreach($parts as $part) {
            similar_text($user, $part, $percent);
            if($percent > 50 && $percent > $highest) {
                $highest = $percent;
            } 
            similar_text($domain, $part, $percent);
            if($percent > 50 && $percent > $highest) {
                $highest = $percent;
            }
        }
        return (1.7 * ($highest/100)) - 1;
    }
    
    return -1;
}

var_dump(compareEmailToName("ian barber", "ian@mydomain.com"));
var_dump(compareEmailToName("ian barber", "ian.barber@email.com"));
var_dump(compareEmailToName("ian barber", "me@ianbarber.com"));
var_dump(compareEmailToName("ian barber", "gonzo@mydomain.com"));
var_dump(compareEmailToName("johnny two times", "gonzo@mydomain.com"));