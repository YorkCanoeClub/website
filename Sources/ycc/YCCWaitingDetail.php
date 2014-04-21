<?php
if (!defined('SMF'))
die('Hacking attempt...');

require_once(dirname(__FILE__) . '/../../Settings.php');

global $db_persist, $db_connection, $db_server, $db_user, $db_passwd;
global $db_type, $db_name, $ssi_db_user, $ssi_db_passwd, $sourcedir, $db_prefix;
global $boarddir;

require_once($boarddir."/helper/functions.php");

function YCCWaitingDetail() {

    global $context;

    isAllowedTo(array('view_ownylist', 'view_ylist', 'manage_ylist'));
    $manager = allowedTo('manage_ylist');
    
    $memberConnection = mysql_connect($db_server, $db_user, $db_passwd, true) or die("Could not connect: " . mysql_error());
    mysql_select_db($db_name, $memberConnection) or die ('Cannot Connect to DB: ' . mysql_error());

    $membershipNumber = $_REQUEST["yno"];
    $fromFunction = $_REQUEST["from"];
    
    if ($manager) {
        $memberQuery = "select 
                        *, 
                        DATE_FORMAT(membershipStarted, '%d/%m/%Y') as membershipStartedF, 
                        DATE_FORMAT(membershipExpires, '%d/%m/%Y') as membershipExpiresF,
                        DATE_FORMAT(memberDob, '%d/%m/%Y') as memberDobF,  
                        DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(memberDob)), '%Y')+0 AS age
                    from ycc_members as members 
                    where members.membershipNumber = $membershipNumber
                    order by members.membershipNumber";
    } else {
        $memberQuery = "select 
                        *, 
                        DATE_FORMAT(membershipStarted, '%d/%m/%Y') as membershipStartedF, 
                        DATE_FORMAT(membershipExpires, '%d/%m/%Y') as membershipExpiresF,
                        DATE_FORMAT(memberDob, '%d/%m/%Y') as memberDobF,  
                        DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(memberDob)), '%Y')+0 AS age
                    from ycc_members as members 
                    left join ycc_memberForumCR as crossref
                    on members.membershipNumber = crossref.membershipNumber
                    where crossref.forumId = '" . $context["user"]["id"] . "'
                    and members.membershipNumber = $membershipNumber
                    order by members.membershipNumber";
    }
    $memberResult = mysql_query($memberQuery, $memberConnection);

    $context['pageTitle'] = 'YCC Membership Detail';
        
    if ($memberRow = mysql_fetch_assoc($memberResult)) {
        
        $context['member'] = true;
        $context['owner'] = false;
        $context['memberArray'] = $memberRow;
        $context['memberManage'] = $manager;

        $contacts = Array();
        $contactsQuery = "select * from ycc_memberscontacts where membershipNumber = $membershipNumber order by contactsSequence";
        $contactsResult = mysql_query($contactsQuery, $memberConnection);
        while ($contactsRow = mysql_fetch_assoc($contactsResult)) {
            array_push($contacts, $contactsRow);
        }
        
        $context['memberContacts'] = $contacts;

        $notes = Array();
        $notesQuery = "select *, DATE_FORMAT(notesTime, '%d/%m/%Y %H:%i') as notesTimeF from ycc_membersnotes where membershipNumber = $membershipNumber order by notesTime desc";
        $notesResult = mysql_query($notesQuery, $memberConnection);
        while ($notesRow = mysql_fetch_assoc($notesResult)) {
            array_push($notes, $notesRow);
        }
        
        $context['memberNotes'] = $notes;
        
        $crb = Array();
        $crbQuery = "select * from ycc_memberscrb where membershipNumber = $membershipNumber order by crbDate, crbType";
        $crbResult = mysql_query($crbQuery, $memberConnection);
        while ($crbRow = mysql_fetch_assoc($crbResult)) {
            array_push($crb, $crbRow);
        }
        
        $context['memberCrb'] = $crb;
        
        $courses = Array();
        $coursesQuery = "select * from ycc_memberscourses where membershipNumber = $membershipNumber order by courseDate, courseName";
        $coursesResult = mysql_query($coursesQuery, $memberConnection);
        while ($coursesRow = mysql_fetch_assoc($coursesResult)) {
            array_push($courses, $coursesRow);
        }
        
        $context['memberCourses'] = $courses;
        
        $context['memberCrb'] = $crb;
        
        $medical = Array();
        $medicalQuery = "select * from ycc_membersmedical where membershipNumber = $membershipNumber order by medicalType, medicalId";
        $medicalResult = mysql_query($medicalQuery, $memberConnection);
        while ($medicalRow = mysql_fetch_assoc($medicalResult)) {
            array_push($medical, $medicalRow);
        }
        
        $context['memberMedical'] = $medical;
        
        $boats = Array();
        $boatsQuery = "select * from ycc_membersboats where membershipNumber = $membershipNumber order by boatsMake, boatsModel";
        $boatsResult = mysql_query($boatsQuery, $memberConnection);
        while ($boatsRow = mysql_fetch_assoc($boatsResult)) {
            array_push($boats, $boatsRow);
        }
        
        $context['memberBoats'] = $boats;
        
        $committees = Array();
        $committeesQuery = "select * from ycc_memberscommittees where membershipNumber = $membershipNumber order by committeesYear, committeesName";
        $committeesResult = mysql_query($committeesQuery, $memberConnection);
        while ($committeesRow = mysql_fetch_assoc($committeesResult)) {
            array_push($committees, $committeesRow);
        }
        
        $context['memberCommittees'] = $committees;

        $context['fromFunction'] = $fromFunction;

    } else {
        
        $context['member'] = false;
        $context['memberManage'] = false;
        
        
    }        

    mysql_close($memberConnection);

    loadTemplate('ycc/YCCMembershipDetail');

}

function YCCWaitingDetailSubmit() {

    global $context;

    isAllowedTo(array('view_ownylist', 'view_ylist', 'manage_ylist'));
    $manager = allowedTo('manage_ylist');
    
    $memberConnection = mysql_connect($db_server, $db_user, $db_passwd, true) or die("Could not connect: " . mysql_error());
    mysql_select_db($db_name, $memberConnection) or die ('Cannot Connect to DB: ' . mysql_error());

    // Main Record
    $membershipNumber = $_REQUEST["membershipNumber"];
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
        print("*VALIDATEDATE");
        mysql_close($memberConnection);
        exit(0);
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
    
    if ($membershipNumber == ""
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
        ) {

        print("*VALIDATE");

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
            $query .= "membershipType = '$type', ";
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

            print("*OK");

        } else {

            print("*ERROR");

        }        

    }
            
    mysql_close($memberConnection);
    exit(0);
    
}

?>
