<?php

require_once(dirname(__FILE__) . '/../Settings.php');

global $db_persist, $db_connection, $db_server, $db_user, $db_passwd;
global $db_type, $db_name, $ssi_db_user, $ssi_db_passwd, $sourcedir, $db_prefix;
global $boarddir;

require_once($boarddir."/SSI.php");
require_once($boarddir."/helper/functions.php");

global $context;

if (allowedTo(array('manage_ylist'))) {

    $memberConnection = mysql_connect($db_server, $db_user, $db_passwd, true) or die("Could not connect: " . mysql_error());
    mysql_select_db($db_name, $memberConnection) or die ('Cannot Connect to DB: ' . mysql_error());

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
 
