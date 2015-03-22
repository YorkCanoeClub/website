<?php
if (!defined('SMF'))
die('Hacking attempt...');

require_once(dirname(__FILE__) . '/../../Settings.php');
global $boarddir;
require_once($boarddir."/helper/functions.php");

function YCCMembership() {

    global $context;
    global $db_server, $db_name, $db_user, $db_passwd;

    isAllowedTo(array('committee'));
    $forumId = $_REQUEST["forumid"];
    
    $memberConnection = mysql_connect($db_server, $db_user, $db_passwd, true) or die("Could not connect: " . mysql_error());
    mysql_select_db($db_name, $memberConnection) or die ('Cannot Connect to DB: ' . mysql_error());

    $membersU = Array();
    
    $queryA = "select 
                forum.id_member,
                forum.real_name
               from smf_members as forum
               order by real_name";
    $resultA = mysql_query($queryA, $memberConnection);
    
    while ($row = mysql_fetch_assoc($resultA)) {

        array_push($membersU, $row);

    } 
    
    $context['membersU'] = $membersU;
    
    $members = array();
    
    // Get Primary Members
    $memberQuery = "select 
                            members.*, 
                            DATE_FORMAT(membershipStarted, '%d/%m/%Y') as membershipStartedF, 
                            DATE_FORMAT(membershipExpires, '%d/%m/%Y') as membershipExpiresF 
                    from ycc_members as members 
                    where members.forumId = '" . $forumId . "'
                    order by members.membershipNumber";
    $result = mysql_query($memberQuery, $memberConnection);

    $memberCount = mysql_num_rows($result);
    
    if ($row = mysql_fetch_assoc($result)) {

        $contactsQuery = "select * from ycc_memberscontacts where membershipNumber = " . $row["membershipNumber"] . " and contactsSequence = 10";
        $contactsResult = mysql_query($contactsQuery, $memberConnection);
        $contactsRow = mysql_fetch_assoc($contactsResult);

        if ($row["memberFirstName"] == ""
         || $row["memberLastName"] == ""
         || $row["memberGender"] == ""
         || $row["memberDob"] == ""
         || $row["memberAddress"] == ""
         || $row["memberPostcode"] == ""
         || $row["memberPhone1"] == ""
         || $row["memberEmail"] == ""
         || $row["memberContact"] == ""
         || $contactsRow["contactsName"] == ""
         || $contactsRow["contactsRelationship"] == ""
         || $contactsRow["contactsPhone1"] == ""       
        ) {
            $row["complete"] = "";
        } else {
            $row["complete"] = "1";
        }
        
        if ($row["forumId"] == $forumId) {
            $row["primary"] = "1";
        } else {
            $row["primary"] = "";
        }
        
        array_push($members, $row);

    }  
    
    // Get Linked Members
    $memberQuery = "select 
                            members.*, 
                            DATE_FORMAT(membershipStarted, '%d/%m/%Y') as membershipStartedF, 
                            DATE_FORMAT(membershipExpires, '%d/%m/%Y') as membershipExpiresF 
                    from ycc_membersLinks as links
                    left join ycc_members as members
                    on links.linkedMembershipNumber = members.membershipNumber
                    where links.mainMembershipNumber = '" . $row["membershipNumber"] . "'
                    order by members.membershipNumber";
    $result = mysql_query($memberQuery, $memberConnection);

    $memberCount += mysql_num_rows($result);
    
    while ($row = mysql_fetch_assoc($result)) {

        $contactsQuery = "select * from ycc_memberscontacts where membershipNumber = " . $row["membershipNumber"] . " and contactsSequence = 10";
        $contactsResult = mysql_query($contactsQuery, $memberConnection);
        $contactsRow = mysql_fetch_assoc($contactsResult);

        if ($row["memberFirstName"] == ""
         || $row["memberLastName"] == ""
         || $row["memberGender"] == ""
         || $row["memberDob"] == ""
         || $row["memberAddress"] == ""
         || $row["memberPostcode"] == ""
         || $row["memberPhone1"] == ""
         || $row["memberEmail"] == ""
         || $row["memberContact"] == ""
         || $contactsRow["contactsName"] == ""
         || $contactsRow["contactsRelationship"] == ""
         || $contactsRow["contactsPhone1"] == ""       
        ) {
            $row["complete"] = "";
        } else {
            $row["complete"] = "1";
        }
        
        if ($row["forumId"] == $forumId) {
            $row["primary"] = "1";
        } else {
            $row["primary"] = "";
        }
        
        array_push($members, $row);

    }        

    mysql_close($memberConnection);

    $context['pageTitle'] = 'YCC Memberships';
    $context['memberCount'] = $memberCount;
    $context['memberArray'] = $members;
    $context['forumId'] = $forumId;

    loadTemplate('ycc/YCCMembershipAdmin');

}
?>
