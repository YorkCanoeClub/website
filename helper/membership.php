<?php

require_once(dirname(__FILE__) . '/../Settings.php');

global $db_persist, $db_connection, $db_server, $db_user, $db_passwd;
global $db_type, $db_name, $ssi_db_user, $ssi_db_passwd, $sourcedir, $db_prefix;
global $boarddir;

require_once($boarddir."/SSI.php");
require_once($boarddir."/helper/functions.php");

global $context;

if (allowedTo(array('view_ylist', 'manage_ylist'))) {

    $memberConnection = mysql_connect($db_server, $db_user, $db_passwd, true) or die("Could not connect: " . mysql_error());
    mysql_select_db($db_name, $memberConnection) or die ('Cannot Connect to DB: ' . mysql_error());

    $query = "select membershipStatus as status, count(membershipStatus) as count from ycc_members group by membershipStatus";
   
    $result = mysql_query($query, $memberConnection);
    
    while ($row = mysql_fetch_assoc($result)) {
    
        print("<b>" . getStatusDesc($row["status"]) . ":</b> " . $row["count"] . "<br />");
            
    }
    
    mysql_close($memberConnection);
    
}

?>
 
