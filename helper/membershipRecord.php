<?php

require_once("/home/yorkcano/public_html/SSI.php");
require_once("/home/yorkcano/public_html/helper/functions.php");

global $context;

if ($context['user']['is_guest']) {

    print("<p>In order to join York Canoe Club you need to <a href=\"http://www.yorkcanoeclub.co.uk/index.php?action=register\">register</a> and <a href=\"http://www.yorkcanoeclub.co.uk/index.php?action=login\">login</a>.</p>");

} else {

    $memberDB = array("localhost", "yorkcano_web", "web", "yorkcano_smf");

    $memberConnection = mysql_connect($memberDB[0], $memberDB[1], $memberDB[2], true) or die("Could not connect: " . mysql_error());
    mysql_select_db($memberDB[3], $memberConnection) or die ('Cannot Connect to DB: ' . mysql_error());

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
 