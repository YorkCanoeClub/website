<?php
if (!defined('SMF'))
die('Hacking attempt...');

require_once(dirname(__FILE__) . '/../../Settings.php');

global $db_persist, $db_connection, $db_server, $db_user, $db_passwd;
global $db_type, $db_name, $ssi_db_user, $ssi_db_passwd, $sourcedir, $db_prefix;
global $boarddir;

require_once($boarddir."/helper/functions.php");

function CommitteeWaiting() {

    global $context;

    isAllowedTo(array('committee'));
    $manager = allowedTo('committee_manage');
    
    $memberConnection = mysql_connect($db_server, $db_user, $db_passwd, true) or die("Could not connect: " . mysql_error());
    mysql_select_db($db_name, $memberConnection) or die ('Cannot Connect to DB: ' . mysql_error());

    $new = Array();
    
    $query = "select 
                w.*,
                m.*,
                DATE_FORMAT(w.memberDob, '%d/%m/%Y') as memberDobF,  
                DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(w.memberDob)), '%Y')+0 AS age,
                DATE_FORMAT(w.createdDate, '%d/%m/%Y') as createdDateF  
                from ycc_waiting as w 
                left join smf_members as m
                on w.forumid = m.id_member
              order by w.membershipStatus, w.createdDate";
    $result = mysql_query($query, $memberConnection);
    
    while ($row = mysql_fetch_assoc($result)) {

        array_push($new, $row);

    }
    
    mysql_close($memberConnection);
    
    // Payments
    $context['new'] = $new;
    $context['manager'] = $manager;
    
    loadTemplate('ycc/CommitteeWaiting');

}

function CommitteeWaitingDetail() {

    global $context;

    isAllowedTo(array('committee'));
    $manager = allowedTo('committee_manage');
    
    $memberConnection = mysql_connect($db_server, $db_user, $db_passwd, true) or die("Could not connect: " . mysql_error());
    mysql_select_db($db_name, $memberConnection) or die ('Cannot Connect to DB: ' . mysql_error());

    $query = "select 
                w.*,
                m.*,
                DATE_FORMAT(w.memberDob, '%d/%m/%Y') as memberDobF,  
                DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(w.memberDob)), '%Y')+0 AS age,
                DATE_FORMAT(w.createdDate, '%d/%m/%Y') as createdDateF  
                from ycc_waiting as w 
                left join smf_members as m
                on w.forumid = m.id_member
              where w.membershipNumber = " . $_REQUEST["membershipNumber"];
    $result = mysql_query($query, $memberConnection);
    
    if ($row = mysql_fetch_assoc($result)) {

        $membersU = Array();
    
        $queryA = "select 
                    forum.id_member,
                    forum.real_name,
                    members.membershipNumber
                   from smf_members as forum
                   left join ycc_members as members
                   on forum.id_member = members.forumId
                   where members.membershipNumber is null
                   or members.forumId = " . $row["forumId"] . "
                   order by real_name";
        $resultA = mysql_query($queryA, $memberConnection);

        while ($row2 = mysql_fetch_assoc($resultA)) {

            array_push($membersU, $row2);

        } 

        $context['membersU'] = $membersU;
        
        $context['waitingDetails'] = $row;

    }
    
    $contacts = Array();
    $contactsQuery = "select * from ycc_waitingcontacts where membershipNumber = " . $_REQUEST["membershipNumber"] . " order by contactsSequence";
    $contactsResult = mysql_query($contactsQuery, $memberConnection);
    while ($contactsRow = mysql_fetch_assoc($contactsResult)) {
        array_push($contacts, $contactsRow);
    }
    
    $context['memberContacts'] = $contacts;
    
    mysql_close($memberConnection);
    
    // Payments
    $context['manager'] = $manager;
    
    loadTemplate('ycc/CommitteeWaitingDetail');

}

function CommitteeWaitingDetailSubmit() {

    global $context;
    $errors = array();
    
    isAllowedTo(array('committee'));
    $manager = allowedTo('committee_manage');
    
    $memberConnection = mysql_connect($db_server, $db_user, $db_passwd, true) or die("Could not connect: " . mysql_error());
    mysql_select_db($db_name, $memberConnection) or die ('Cannot Connect to DB: ' . mysql_error());

    $memberQuery = "select * from smf_members where id_member = " . $context["user"]["id"];
    $memberResult = mysql_query($memberQuery, $memberConnection);

    $memberRow = mysql_fetch_assoc($memberResult);
    $context["forumDetails"] = $memberRow;
    
    $waitingQuery = "select * from ycc_waiting where forumId = " . $context["user"]["id"];
    $waitingResult = mysql_query($waitingQuery, $memberConnection);

    // Main Record
    $memberFirstName = mysql_real_escape_string($_REQUEST["memberFirstName"]);
    $memberLastName = mysql_real_escape_string($_REQUEST["memberLastName"]);
    $memberKnownAs = mysql_real_escape_string($_REQUEST["memberKnownAs"]);
    $memberGender = $_REQUEST["memberGender"];
    $memberDob = $_REQUEST["memberDob"];
    $memberDobMySQL = "";
    $memberDobArray = explode('/', $memberDob);
    if (count($memberDobArray) == 3) {
        if (checkdate($memberDobArray[1], $memberDobArray[0], $memberDobArray[2])) {
            $memberDobMySQL = mysqlDate($memberDob);
        } else {
            array_push($errors, array("memberDob", "Date of Birth must be entered and in format dd/mm/yyyy"));
        }
    } else {
        array_push($errors, array("memberDob", "Date of Birth must be entered and in format dd/mm/yyyy"));
    }
    
    $type = "A";
    $age = ageFromDob($memberDobMySQL);
    if ($age < 11) {
        array_push($errors, array("memberDob", "You must be over 11 years of age to join York Canoe Club and the York Canoe Club waiting list"));
    }
    if ($age < 18) {
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
    
    // Additional Information Questions
    $kitQ = $_REQUEST["kitQ"];
    $swimQ = $_REQUEST["swimQ"];
    $experienceQ = $_REQUEST["experienceQ"];
    $qualificationsQ = $_REQUEST["qualificationsQ"];
    $medicalQ = $_REQUEST["medicalQ"];
    
    // Addtional Information
    $experience= $_REQUEST["experience"];
    $qualifications = $_REQUEST["qualifications"];
    $medical = $_REQUEST["medical"];
    
    // Audit Fields
    $auditDate = date("Y-m-d H:i:s");
    $auditUser = $context["user"]["username"]; 
   
    // Other Validation
    if ($memberFirstName == "") { array_push($errors, array("memberFirstName", "'First Name(s)' must be entered")); }
    if ($memberLastName == "") { array_push($errors, array("memberLastName", "'Last Name' must be entered")); } 
    if ($memberGender == "") { array_push($errors, array("memberGender", "'Gender' must be selected")); }
    if ($memberAddress == "") { array_push($errors, array("memberAddress", "'Address' must be entered")); }
    if ($memberPostcode == "") { array_push($errors, array("memberPostcode", "'Postcode' must be entered")); }
    if ($memberPhone1 == "") { array_push($errors, array("memberPhone1", "'Primary Phone' must be entered")); }
    if ($memberEmail == "") { array_push($errors, array("memberEmail", "'Email' must be entered")); }
    if ($memberContact == "") { array_push($errors, array("memberContact", "")); }
    if ($contactsName1 == "") { array_push($errors, array("contactsName1", "'Primary Emergency Contact Name' must be entered")); }
    if ($contactsRelationship1 == "") { array_push($errors, array("contactsRelationship1", "'Primary Emergency Contact Relationship' must be entered")); }
    if ($contactsPhone11== "") { array_push($errors, array("contactsPhone11", "'Primary Emergency Contact Primary Phone' must be entered")); }
    if ($swimQ== "") { array_push($errors, array("swimQ", "You must answer 'Can you swim 25 metres unaided?'")); }
    if ($swimQ== "*NO") { array_push($errors, array("swimQ", "Although not being able to swim will not stop you from joining York Canoe Club we would prefer speak to you first. Please come and see us on a Wednesday night at the pool during winter or the river during summer.")); }
    if ($kitQ== "") { array_push($errors, array("kitQ", "You must answer 'Do you own all your own kit?'")); }
    if ($experienceQ== "") { array_push($errors, array("experienceQ", "You must answer 'Do you have any prior experience in a kayak?'")); }
    if ($qualificationsQ== "") { array_push($errors, array("qualificationsQ", "You must answer 'Do you have any BCU qualifications in kayak?'")); }
    if ($medicalQ== "") { array_push($errors, array("medicalQ", "You must answer 'Do you have any medical conditions that could affect your ability to kayak or swim?'")); }
    
    if (sizeof($errors) == 0) {

        // Blank out text entries if not relevant
        if ($experienceQ != "*YES") { $experience = ""; } 
        if ($qualificationsQ != "*YES") { $qualifications = ""; } 
        if ($medicalQ != "*YES") { $medical = ""; } 
            
        // Try and work out what level we need to come in at
        $status = "W100";
        if ($experienceQ == "*NO") {
            
            // We are a beginner
            $status = "W200";
            
        } else {
            
            // We need to check all experienced ones. 
            
        }
        
        // Update Main Record

        if (false) {

            array_push($errors, array("memberFirstName", "Something, and if we're being honest we have no idea what, has gone wrong. Please try again. If this is not the first time this has happened please <a href=\"https://www.yorkcanoeclub.co.uk/index.php?action=pm;sa=send;u=2\">PM Jon</a>!"));

        } else {

//            // Remove existing emergency contact records
//            $query2 = "delete from ycc_waitingcontacts where membershipNumber = '$membershipNumber'";
//            mysql_query($query2, $memberConnection);
//
//            // Update Primary Emergency Contact Record
//            $query3 = "insert into ycc_waitingcontacts (membershipNumber, contactsSequence, contactsName, contactsRelationship, contactsPhone1, contactsPhone2) values ($membershipNumber, 10, '$contactsName1', '$contactsRelationship1', '$contactsPhone11', '$contactsPhone21')";
//            mysql_query($query3, $memberConnection);
//
//            // Update Secondary Emergency Contact Record
//            $query4 = "insert into ycc_waitingcontacts (membershipNumber, contactsSequence, contactsName, contactsRelationship, contactsPhone1, contactsPhone2) values ($membershipNumber, 20, '$contactsName2', '$contactsRelationship2', '$contactsPhone12', '$contactsPhone22')";
//            mysql_query($query4, $memberConnection);

        }
        
        print("*OK");

    } else {

        print("*ERROR");

    }      
    
    mysql_close($memberConnection);
    exit(0);
    
}

function CommitteeWaitingInvite() {

    global $context;

    isAllowedTo(array('committee', 'committee_manage'));
    
    $count = intval($_REQUEST["count"], 10);
    $membersIn = Array();
    for ($i = 1; $i <= $count; $i++) {
        array_push($membersIn, $_REQUEST["member" . $i]);
    }
    
    $memberConnection = mysql_connect($db_server, $db_user, $db_passwd, true) or die("Could not connect: " . mysql_error());
    mysql_select_db($db_name, $memberConnection) or die ('Cannot Connect to DB: ' . mysql_error());

    $memberQuery = "select 
                    *
                from ycc_waiting as waiting
                where waiting.membershipNumber IN (";
    $memberQuery .= implode(",", $membersIn);            
    $memberQuery .= ") order by waiting.membershipNumber";
    
    $memberResult = mysql_query($memberQuery, $memberConnection);
    
    $toAddresses = "";
    $members = array();
    while ($row = mysql_fetch_assoc($memberResult)) {
        
        // Check waiting member is eligible to be made a member 
        if ($row["membershipStatus"] == "W200") {
          
            $toAddresses .= $row["memberFirstName"] . " " . $row["memberLastName"] . "&lt;" . $row["memberEmail"] . "&gt;,<br />";
            array_push($members, $row);

        }
        
    }
    
    $toAddresses = substr($toAddresses, 0, -7) . "<br />";
    
    mysql_close($memberConnection);
    
    $context["toAddresses"] = $toAddresses;
    $context["members"] = $members;
    
    loadTemplate('ycc/CommitteeWaitingInvite');

}

function CommitteeWaitingConfirm() {

    global $context;

    isAllowedTo(array('committee', 'committee_manage'));
    
    $count = intval($_REQUEST["count"], 10);
    $membersIn = Array();
    for ($i = 1; $i <= $count; $i++) {
        array_push($membersIn, $_REQUEST["member" . $i]);
    }
    
    $memberConnection = mysql_connect($db_server, $db_user, $db_passwd, true) or die("Could not connect: " . mysql_error());
    mysql_select_db($db_name, $memberConnection) or die ('Cannot Connect to DB: ' . mysql_error());

    $memberQuery = "select 
                    *
                from ycc_waiting as waiting
                where waiting.membershipNumber IN (";
    $memberQuery .= implode(",", $membersIn);            
    $memberQuery .= ") order by waiting.membershipNumber";
    
    $memberResult = mysql_query($memberQuery, $memberConnection);
    
    $members = array();
    while ($row = mysql_fetch_assoc($memberResult)) {
        
        // Check waiting member is eligible to be made a member 
        if ($row["membershipStatus"] == "W300") {
          
            array_push($members, $row);
            
        }
        
    }
    
    print_r($members);
    
    mysql_close($memberConnection);
    
    print("*OK");
    
    exit(0);
    
}

?>
