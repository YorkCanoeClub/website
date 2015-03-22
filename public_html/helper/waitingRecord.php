<?php

require_once(dirname(__FILE__) . '/../Settings.php');

global $db_persist, $db_connection, $db_server, $db_user, $db_passwd;
global $db_type, $db_name, $ssi_db_user, $ssi_db_passwd, $sourcedir, $db_prefix;
global $boarddir;

require_once($boarddir."/SSI.php");
require_once($boarddir."/helper/functions.php");

global $context;

if ($context['user']['is_guest']) {

    print("<p>In order to join York Canoe Club you need to <a href=\"http://www.yorkcanoeclub.co.uk/index.php?action=register\">register</a> and <a href=\"http://www.yorkcanoeclub.co.uk/index.php?action=login\">login</a>.</p>");

} else {

    $memberConnection = mysql_connect($db_server, $db_user, $db_passwd, true) or die("Could not connect: " . mysql_error());
    mysql_select_db($db_name, $memberConnection) or die ('Cannot Connect to DB: ' . mysql_error());

    $memberQuery = "select * from ycc_waiting where forumId = '" . $context["user"]["id"] . "' and membershipStatus != 'W500' and membershipStatus != 'W501' and membershipStatus != 'W900' and membershipStatus != 'W998' and membershipStatus != 'W999'";
    $result2 = mysql_query($memberQuery, $memberConnection);
    
    $memberCount2 = mysql_num_rows($result2);
    
    print("<p>You have " . $memberCount2 . " active waiting list entries linked to this forum account.</p>");
    
    if ($memberCount2 > 0) {
        
        while ($row2 = mysql_fetch_assoc($result2)) {

            print("<p><b>" . $row2["memberFirstName"] . " " . $row2["memberLastName"] . "</b><br />" . $row2["membershipNumber"] . "/" .$row2["membershipType"] . "<br />" . getStatusDesc($row2["membershipStatus"]) . "</p>");
            
        }
    
    }
    
    print("<p>To put your name on the waiting list <a href=\"http://www.yorkcanoeclub.co.uk/index.php?action=ynewmember\">click here</a></p>");
    
    mysql_close($memberConnection);

}

?>
 
