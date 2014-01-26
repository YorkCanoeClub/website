<?php

require_once("/home/yorkcano/public_html/SSI.php");
require_once("/home/yorkcano/public_html/helper/functions.php");

global $context;

if (allowedTo(array('view_ylist', 'manage_ylist'))) {

    $memberDB = array("localhost", "yorkcano_web", "web", "yorkcano_smf");

    $memberConnection = mysql_connect($memberDB[0], $memberDB[1], $memberDB[2], true) or die("Could not connect: " . mysql_error());
    mysql_select_db($memberDB[3], $memberConnection) or die ('Cannot Connect to DB: ' . mysql_error());

    $query = "select membershipStatus as status, count(membershipStatus) as count from ycc_members group by membershipStatus";
   
    $result = mysql_query($query, $memberConnection);
    
    while ($row = mysql_fetch_assoc($result)) {
    
        print("<b>" . getStatusDesc($row["status"]) . ":</b> " . $row["count"] . "<br />");
            
    }
    
    mysql_close($memberConnection);
    
}

?>
 