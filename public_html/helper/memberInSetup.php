<?php

require_once(dirname(__FILE__) . '/../Settings.php');

global $db_persist, $db_connection, $db_server, $db_user, $db_passwd;
global $db_type, $db_name, $ssi_db_user, $ssi_db_passwd, $sourcedir, $db_prefix;
global $boarddir;

require_once($boarddir."/helper/functions.php");

$memberConnection = mysql_connect($db_server, $db_user, $db_passwd, true) or die("Could not connect: " . mysql_error());
mysql_select_db($db_name, $memberConnection) or die ('Cannot Connect to DB: ' . mysql_error());

for ($i = 1; $i <= 128; $i++) {

    $query = "select * from ycc_members where membershipNumber = $i";
    $result = mysql_query($query, $memberConnection);

    if ($row = mysql_fetch_assoc($result)) {

        if ($row["membershipStatus"] != "550" &&$row["membershipStatus"] != "900" &&$row["membershipStatus"] != "999") {

            // Calculate Age on 30th September in Membership Year
            $age = ageFromDobOnDate($row["memberDob"], date("Y-m-d", mktime(0, 0, 0, 9, 30, 2012)));
            $ageGroup = 3;
            if ($age < 16) {
                $ageGroup = 1;    
            } else if ($age >= 16 && $age <= 18) {
                $ageGroup = 2;    
            } else if ($age >= 19 && $age <= 45) {
                $ageGroup = 3;    
            } else if ($age > 45) {
                $ageGroup = 4;    
            }
            
            $bcu = 0;
            if ($row["memberBcuNumber"] != "") {
                $bcu = 1;
            }
            
            $query = "insert into ycc_memberin values ('2011/12', $i, '" . $row["memberGender"] . "', $ageGroup, $bcu)";
            $result = mysql_query($query, $memberConnection);


        }

    }

}

for ($i = 1; $i <= 160; $i++) {

    $query = "select * from ycc_members where membershipNumber = $i";
    $result = mysql_query($query, $memberConnection);

    if ($row = mysql_fetch_assoc($result)) {

        if ($row["membershipStatus"] == "500") {

            // Calculate Age on 30th September in Membership Year
            $age = ageFromDobOnDate($row["memberDob"], date("Y-m-d", mktime(0, 0, 0, 9, 30, 2013)));
            $ageGroup = 3;
            if ($age < 16) {
                $ageGroup = 1;    
            } else if ($age >= 16 && $age <= 18) {
                $ageGroup = 2;    
            } else if ($age >= 19 && $age <= 45) {
                $ageGroup = 3;    
            } else if ($age > 45) {
                $ageGroup = 4;    
            }
            
            $bcu = 0;
            if ($row["memberBcuNumber"] != "") {
                $bcu = 1;
            }
            
            $query = "insert into ycc_memberin values ('2012/13', $i, '" . $row["memberGender"] . "', $ageGroup, $bcu)";
            $result = mysql_query($query, $memberConnection);


        }

    }

}

mysql_close($memberConnection);

?>
 
