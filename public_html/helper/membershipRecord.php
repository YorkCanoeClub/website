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

    $memberQuery = "select *, DATE_FORMAT(membershipStarted, '%d/%m/%Y') as membershipStartedF, DATE_FORMAT(membershipExpires, '%d/%m/%Y') as membershipExpiresF from ycc_memberForumCR as link left join ycc_members as members on members.membershipNumber = link.membershipNumber where forumId = '" . $context["user"]["id"] . "' and membershipStatus < 900";
    $result = mysql_query($memberQuery, $memberConnection);
    
    $memberCount = mysql_num_rows($result);
    
    print("You have " . $memberCount . " active memberships linked to this forum account.</p>");
    
    if ($memberCount > 0) {
        
        while ($row = mysql_fetch_assoc($result)) {

            print("<p><b>" . $row["memberFirstName"] . " " . $row["memberLastName"] . "</b><br />" . $row["membershipNumber"] . "/" .$row["membershipType"] . "<br />" . getStatusDesc($row["membershipStatus"]) . "</p>");
            
        }
    
    }
    
    print("<p>To manage your memberships <a href=\"index.php?action=ymembership\">click here</a></p>");
    
    mysql_close($memberConnection);
    
}

?>
 
