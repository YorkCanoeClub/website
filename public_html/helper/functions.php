<?php 

function getStatusDesc($statusCode) {
    
    $status = "";
    
    switch ($statusCode) {
        case "100":
        case "W100":
            $status = "Prospective";
            break;
        case "200":
            $status = "Pending";
            break;
        case "300":
            $status = "Renewal Due";
            break;
        case "350":
            $status = "Renewal In Progress";
            break;
        case "400":
            $status = "Lapsed";
            break;
        case "500":
            $status = "Current";
            break;
        case "550":
            $status = "Honorary";
            break;
        case "600":
            $status = "";
            break;
        case "700":
            $status = "";
            break;
        case "800":
            $status = "";
            break;
        case "900":
            $status = "Cancelled";
            break;
        case "998":
            $status = "Not Taken";
            break;
        case "999":
        case "W999":
            $status = "Deleted";
            break;
        case "W200":
            $status = "Waiting - New Starters Program";
            break;
        case "W201":
            $status = "Waiting - Direct Entry";
            break;
        case "W202":
            $status = "Invited - Cannot Attend";
            break;
        case "W298":
            $status = "Invited - Place Lost";
            break;
        case "W300":
            $status = "Invited - New Starters Program";
            break;
        case "W350":
            $status = "Replied - New Starters Program";
            break;
        case "W301":
            $status = "Invited - Direct Entry";
            break;
        case "W351":
            $status = "Replied - Direct Entry";
            break;
        case "W400":
            $status = "Confirmed - New Starters Program";
            break;
        case "W401":
            $status = "Confirmed - Direct Entry";
            break;
        case "W500":
            $status = "Complete - New Starters Program";
            break;
        case "W501":
            $status = "Complete - Direct Entry";
            break;
        case "W900":
            $status = "No Response - New Starters Program";
            break;
        case "W901":
            $status = "No Response - Direct Entry";
            break;
        case "W902":
            $status = "No Show - New Starters Program";
            break;
        case "W903":
            $status = "No Show - Direct Entry";
            break;
        default:
            $status = "";
            break;
    }

    return $status;
    
}

function getPaymentStatusDesc($statusCode) {
    
    $status = "";
    
    switch ($statusCode) {
        case "100":
            $status = "New";
            break;
        case "500":
            $status = "Paid";
            break;
        case "900":
            $status = "Cancelled";
            break;
        case "999":
            $status = "Deleted";
            break;
        default:
            $status = "";
            break;
    }

    return $status;
    
}

function mysqlDate($inDate) {
    
    $inDateArray = explode("/", $inDate);
    return $inDateArray[2] . "-" . $inDateArray[1] . "-" .$inDateArray[0];
    
}

function ageFromDob($dob) {

    list($y,$m,$d) = explode('-', $dob);
   
    if (($m = (date('m') - $m)) < 0) {
        $y++;
    } else if ($m == 0 && date('d') - $d < 0) {
        $y++;
    }
   
    return date('Y') - $y;
   
}

function ageFromDobOnDate($dob, $date) {

    list($dobY,$dobM,$dobD) = explode('-', $dob);
    list($dateY,$dateM,$dateD) = explode('-', $date);
   
    if (($dobM = $dateM - $dobM) < 0) {
        $dobY++;
    } else if ($dobM == 0 && $dateD - $dobD < 0) {
        $dobY++;
    }
   
    return $dateY - $dobY;
   
}

/**
 * Generate and return a random string
 *
 * The default string returned is 8 alphanumeric characters.
 *
 * The type of string returned can be changed with the "seeds" parameter.
 * Four types are - by default - available: alpha, numeric, alphanum and hexidec. 
 *
 * If the "seeds" parameter does not match one of the above, then the string
 * supplied is used.
 *
 * @author      Aidan Lister <aidan@php.net>
 * @version     2.1.0
 * @link        http://aidanlister.com/repos/v/function.str_rand.php
 * @param       int     $length  Length of string to be generated
 * @param       string  $seeds   Seeds string should be generated from
 */
function alphaId($length = 8, $seeds = 'alphanum') {
    
    // Possible seeds
    $seedings['alpha'] = 'abcdefghijklmnopqrstuvwqyz';
    $seedings['numeric'] = '0123456789';
    $seedings['alphanum'] = 'abcdefghijklmnopqrstuvwqyz0123456789';
    $seedings['hexidec'] = '0123456789abcdef';
    
    // Choose seed
    if (isset($seedings[$seeds]))
    {
        $seeds = $seedings[$seeds];
    }
    
    // Seed generator
    list($usec, $sec) = explode(' ', microtime());
    $seed = (float) $sec + ((float) $usec * 100000);
    mt_srand($seed);
    
    // Generate
    $str = '';
    $seeds_count = strlen($seeds);
    
    for ($i = 0; $length > $i; $i++)
    {
        $str .= $seeds{mt_rand(0, $seeds_count - 1)};
    }
    
    return $str;
}

?>
