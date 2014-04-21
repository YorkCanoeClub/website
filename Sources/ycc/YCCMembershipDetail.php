<?php
if (!defined('SMF'))
die('Hacking attempt...');

require_once(dirname(__FILE__) . '/../../Settings.php');
global $boarddir;
require_once($boarddir."/helper/functions.php");

function YCCMembershipDetail() {

    global $context;
    global $db_server, $db_name, $db_user, $db_passwd;

    isAllowedTo(array('committee'));
    $manager = allowedTo('committee_manage');
    
    $memberConnection = mysql_connect($db_server, $db_user, $db_passwd, true) or die("Could not connect: " . mysql_error());
    mysql_select_db($db_name, $memberConnection) or die ('Cannot Connect to DB: ' . mysql_error());

    $membershipNumber = $_REQUEST["yno"];
    $fromFunction = $_REQUEST["from"];
    
    $memberQuery = "select 
                        *, 
                        DATE_FORMAT(membershipStarted, '%d/%m/%Y') as membershipStartedF, 
                        DATE_FORMAT(membershipExpires, '%d/%m/%Y') as membershipExpiresF,
                        DATE_FORMAT(memberDob, '%d/%m/%Y') as memberDobF,  
                        DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(memberDob)), '%Y')+0 AS age
                    from ycc_members as members
                    left join smf_members as forum
                    on members.forumid = forum.id_member
                    where members.membershipNumber = $membershipNumber
                    order by members.membershipNumber";
    $memberResult = mysql_query($memberQuery, $memberConnection);
    
    $context['pageTitle'] = 'YCC Membership Detail';
        
    if ($memberRow = mysql_fetch_assoc($memberResult)) {
        
        $membersU = Array();
    
        $queryA = "select 
                    forum.id_member,
                    forum.real_name,
                    members.membershipNumber
                   from smf_members as forum
                   left join ycc_members as members
                   on forum.id_member = members.forumId
                   where members.membershipNumber is null
                   or members.membershipNumber = " . $memberRow["membershipNumber"] . "
                   order by real_name";
        $resultA = mysql_query($queryA, $memberConnection);

        while ($row = mysql_fetch_assoc($resultA)) {

            array_push($membersU, $row);

        } 

        $context['membersU'] = $membersU;

        $context['member'] = true;
        $context['owner'] = false;
        $context['memberArray'] = $memberRow;
        $context['memberManage'] = $manager;

        if ($memberRow["forumId"] == "") {
            $memberRow["forumId"] == $memberRow["crossrefForumId"];
        }
        
        $contacts = Array();
        $contactsQuery = "select * from ycc_memberscontacts where membershipNumber = $membershipNumber order by contactsSequence";
        $contactsResult = mysql_query($contactsQuery, $memberConnection);
        while ($contactsRow = mysql_fetch_assoc($contactsResult)) {
            array_push($contacts, $contactsRow);
        }
        
        $context['memberContacts'] = $contacts;

        $links = Array();
        $linksQuery = "select *, DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(memberDob)), '%Y')+0 AS age from ycc_membersLinks as links left join ycc_members as members on links.linkedMembershipNumber = members.membershipNumber where links.mainMembershipNumber = $membershipNumber";
        $linksResult = mysql_query($linksQuery, $memberConnection);
        while ($linksRow = mysql_fetch_assoc($linksResult)) {
            array_push($links, $linksRow);
        }
        
        $context["memberLinks"] = $links;
        
        $context['fromFunction'] = $fromFunction;

    } else {
        
        $context['member'] = false;
        $context['memberManage'] = false;
        
        
    }        

    mysql_close($memberConnection);

    loadTemplate('ycc/YCCMembershipDetail');

}

function YCCMembershipDetailSubmit() {

    global $context;
    global $db_server, $db_name, $db_user, $db_passwd;

    isAllowedTo(array('view_ownylist', 'view_ylist', 'manage_ylist'));
    $manager = allowedTo('manage_ylist');
    
    $memberConnection = mysql_connect($db_server, $db_user, $db_passwd, true) or die("Could not connect: " . mysql_error());
    mysql_select_db($db_name, $memberConnection) or die ('Cannot Connect to DB: ' . mysql_error());

    // Main Record
    $forumId = $_REQUEST["forumId"];
    $membershipNumber = $_REQUEST["membershipNumber"];
    $membershipStatus = $_REQUEST["membershipStatus"];
    $originalMembershipStatus = $_REQUEST["originalMembershipStatus"];
    $membershipStarted = $_REQUEST["membershipStarted"];
    $membershipStartedMySQL = "";
    list($day, $month, $year) = explode('/', $membershipStarted);
    if (checkdate($month, $day, $year)) {
        $membershipStartedMySQL = mysqlDate($membershipStarted);
    } else {
        $membershipStartedMySQL = null;
    }
    $membershipExpires = $_REQUEST["membershipExpires"];
    $membershipExpiresMySQL = "";
    list($day, $month, $year) = explode('/', $membershipExpires);
    if (checkdate($month, $day, $year)) {
        $membershipExpiresMySQL = mysqlDate($membershipExpires);
    } else {
        $membershipExpiresMySQL = null;
    }
    $memberFirstName = mysql_real_escape_string($_REQUEST["memberFirstName"]);
    $memberLastName = mysql_real_escape_string($_REQUEST["memberLastName"]);
    $memberKnownAs = mysql_real_escape_string($_REQUEST["memberKnownAs"]);
    $memberGender = $_REQUEST["memberGender"];
    $memberDob = $_REQUEST["memberDob"];
    $memberDobMySQL = "";
    list($day, $month, $year) = explode('/', $memberDob);
    if (checkdate($month, $day, $year)) {
        $memberDobMySQL = mysqlDate($memberDob);
    } else {
        if ($manager) {
            // allow invalid date but set to be blank if manager
            $memberDobMySQL = null;
        } else {
            print("*VALIDATEDATE");
            mysql_close($memberConnection);
            exit(0);
        }
    }
        
    $type = "A";
    if (ageFromDob($memberDobMySQL) < 18) {
        $type = "J";
    }
    $memberBcuNumber = $_REQUEST["memberBcuNumber"];
    $memberAddress = mysql_real_escape_string($_REQUEST["memberAddress"]);
    $memberPostcode = $_REQUEST["memberPostcode"];
    $memberPhone1 = $_REQUEST["memberPhone1"];
    $memberPhone2 = $_REQUEST["memberPhone2"];
    $memberEmail = $_REQUEST["memberEmail"];
    $memberContact = $_REQUEST["memberContact"];

    // Primary Emergency Contact
    $contactsName1 = mysql_real_escape_string($_REQUEST["contactsName1"]);
    $contactsRelationship1 = mysql_real_escape_string($_REQUEST["contactsRelationship1"]);
    $contactsPhone11 = $_REQUEST["contactsPhone11"];
    $contactsPhone21 = $_REQUEST["contactsPhone21"];

    // Secondary Emergency contacts
    $contactsName2 = mysql_real_escape_string($_REQUEST["contactsName2"]);
    $contactsRelationship2 = mysql_real_escape_string($_REQUEST["contactsRelationship2"]);
    $contactsPhone12 = $_REQUEST["contactsPhone12"];
    $contactsPhone22 = $_REQUEST["contactsPhone22"];

    // Audit Fields
    $auditDate = date("Y-m-d H:i:s");
    $auditUser = $context["user"]["username"]; 
    
    if (!$manager && (
            $membershipNumber == ""
         || $memberFirstName == ""
         || $memberLastName == ""
         || $memberGender == ""
         || $memberDob == ""
         || $memberAddress == ""
         || $memberPostcode == ""
         || $memberPhone1 == ""
         || $memberEmail == ""
         || $memberContact == ""
         || $contactsName1 == ""
         || $contactsRelationship1 == ""
         || $contactsPhone11== ""
        )) {

        print("*VALIDATE");
        mysql_close($memberConnection);
        exit(0);
    
    } else {

        if ($manager) {
            $memberQuery = "select 
                            *, 
                            DATE_FORMAT(membershipStarted, '%d/%m/%Y') as membershipStartedF, 
                            DATE_FORMAT(membershipExpires, '%d/%m/%Y') as membershipExpiresF,
                            DATE_FORMAT(memberDob, '%d/%m/%Y') as memberDobF 
                        from ycc_members as members 
                        where members.membershipNumber = $membershipNumber
                        order by members.membershipNumber";
        } else {
            $memberQuery = "select 
                            *, 
                            DATE_FORMAT(membershipStarted, '%d/%m/%Y') as membershipStartedF, 
                            DATE_FORMAT(membershipExpires, '%d/%m/%Y') as membershipExpiresF,
                            DATE_FORMAT(memberDob, '%d/%m/%Y') as memberDobF 
                        from ycc_members as members 
                        left join ycc_memberForumCR as crossref
                        on members.membershipNumber = crossref.membershipNumber
                        where crossref.forumId = '" . $context["user"]["id"] . "'
                        and members.membershipNumber = $membershipNumber
                        order by members.membershipNumber";
        }
        $memberResult = mysql_query($memberQuery, $memberConnection);

        if ($memberRow = mysql_fetch_assoc($memberResult)) {

            // Update Main Record
            $query = "update ycc_members set ";
            $query .= "forumId = '$forumId', ";
            $query .= "membershipType = '$type', ";
            $query .= "membershipStatus = '$membershipStatus', ";
            $query .= "membershipStarted = '$membershipStartedMySQL', ";
            $query .= "membershipExpires = '$membershipExpiresMySQL', ";
            $query .= "memberFirstName = '$memberFirstName', ";
            $query .= "memberLastName = '$memberLastName', ";
            $query .= "memberKnownAs = '$memberKnownAs', ";
            $query .= "memberGender = '$memberGender', ";
            $query .= "memberDob = '$memberDobMySQL', ";
            $query .= "memberDobString = '$memberDob', ";
            $query .= "memberBcuNumber = '$memberBcuNumber', ";
            $query .= "memberAddress = '$memberAddress', ";
            $query .= "memberPostcode = '$memberPostcode', ";
            $query .= "memberPhone1 = '$memberPhone1', ";
            $query .= "memberPhone2 = '$memberPhone2', ";
            $query .= "memberEmail = '$memberEmail', ";
            $query .= "memberContact = '$memberContact', ";
            $query .= "updatedDate = '$auditDate', ";
            $query .= "updatedUser = '$auditUser' ";
            $query .= "where membershipNumber = $membershipNumber";
            mysql_query($query, $memberConnection);

            // Remove existing emergency contact records
            $query = "delete from ycc_memberscontacts where membershipNumber = '$membershipNumber'";
            mysql_query($query, $memberConnection);

            // Update Primary Emergency Contact Record
            $query = "insert into ycc_memberscontacts (membershipNumber, contactsSequence, contactsName, contactsRelationship, contactsPhone1, contactsPhone2) values ($membershipNumber, 10, '$contactsName1', '$contactsRelationship1', '$contactsPhone11', '$contactsPhone21')";
            mysql_query($query, $memberConnection);

            // Update Secondary Emergency Contact Record
            $query = "insert into ycc_memberscontacts (membershipNumber, contactsSequence, contactsName, contactsRelationship, contactsPhone1, contactsPhone2) values ($membershipNumber, 20, '$contactsName2', '$contactsRelationship2', '$contactsPhone12', '$contactsPhone22')";
            mysql_query($query, $memberConnection);

            // Update the MemberIn Records
            // Work out the current season
            if (intval(date("m")) >= 10) {
                $currentYear = date("Y");
                $currentSeason = date("Y") . "/" . (date("y") + 1);
            } else {
                $currentYear = date("Y") - 1;
                $currentSeason = (date("Y") - 1) . "/" . date("y");
            }
            
            if ($originalMembershipStatus != $membershipStatus) {
                
                // Remove existing memberin records for this season
                $query = "delete from ycc_memberin where memberInSeason = '$currentSeason' and memberInMember = '$membershipNumber'";
                mysql_query($query, $memberConnection);

                if ($membershipStatus == 500) {
                    // Record is current so add to the memberin file for this season
                    // Calculate Age on 30th September in Membership Year
                    $age = ageFromDobOnDate($memberDobMySQL, date("Y-m-d", mktime(0, 0, 0, 9, 30, $currentYear)));
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
                    if ($memberBcuNumber != "") {
                        $bcu = 1;
                    }

                    $query = "insert into ycc_memberin values ('$currentSeason', $membershipNumber, '$memberGender', $ageGroup, $bcu)";
                    $result = mysql_query($query, $memberConnection);

                } 
                
            }
            
            print("*OK");

        } else {

            print("*ERROR");

        }        

    }
            
    mysql_close($memberConnection);
    exit(0);
    
}

?>
