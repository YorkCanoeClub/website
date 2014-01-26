<?php

require_once("/home/yorkcano/public_html/SSI.php");
require_once("/home/yorkcano/public_html/helper/functions.php");

global $context;

if (allowedTo(array('manage_ylist'))) {

    $memberDB = array("localhost", "yorkcano_web", "web", "yorkcano_smf");

    $memberConnection = mysql_connect($memberDB[0], $memberDB[1], $memberDB[2], true) or die("Could not connect: " . mysql_error());
    mysql_select_db($memberDB[3], $memberConnection) or die ('Cannot Connect to DB: ' . mysql_error());

    $query = "select membershipStatus, count(*) as count from ycc_members where membershipStatus = '500' or membershipStatus = '350' group by membershipStatus";
   
    $result = mysql_query($query, $memberConnection);
    
    $inProgress = 0;
    $current = 0;
    
    while ($row = mysql_fetch_assoc($result)) {
    
        if ($row["membershipStatus"] == "350") {
            $inProgress = $row["count"];
        } else if ($row["membershipStatus"] == "500") {
            $current = $row["count"];
        }
            
    }
    
    
    print("<p style=\"text-align: center\"><b>$current / $inProgress</b></p>");
    mysql_close($memberConnection);
    
}

?>
 