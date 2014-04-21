<?php
if (!defined('SMF'))
die('Hacking attempt...');

require_once(dirname(__FILE__) . '/../../Settings.php');

global $db_persist, $db_connection, $db_server, $db_user, $db_passwd;
global $db_type, $db_name, $ssi_db_user, $ssi_db_passwd, $sourcedir, $db_prefix;
global $boarddir;

require_once($boarddir."/helper/functions.php");
require_once($boarddir."/libraries/PHPMailer/class.phpmailer.php");

date_default_timezone_set('Etc/UTC');

function YCCNewMember() {

    global $context;
    
    if (false && $context["user"]["username"] != "allarsj") {
        loadTemplate('ycc/YCCNewMemberOff');
        return;
    }
    
    $memberConnection = mysql_connect($db_server, $db_user, $db_passwd, true) or die("Could not connect: " . mysql_error());
    mysql_select_db($db_name, $memberConnection) or die ('Cannot Connect to DB: ' . mysql_error());

    $memberQuery = "select * from smf_members where id_member = " . $context["user"]["id"];
    $memberResult = mysql_query($memberQuery, $memberConnection);

    $memberRow = mysql_fetch_assoc($memberResult);
    $context["forumDetails"] = $memberRow;
    
    $waitingQuery = "select * from ycc_waiting where forumId = " . $context["user"]["id"];
    $waitingResult = mysql_query($waitingQuery, $memberConnection);

    // Does it look like a duplicate
    $waitingCount = 0;
    while ($waitingRow = mysql_fetch_assoc($waitingResult)) {
        if (   $waitingRow["memberFirstName"] == $memberFirstName
            && $waitingRow["memberLastName"] == $memberLastName 
            && $waitingRow["memberDobString"] == $memberDob) {
            array_push($errors, array("", "A waiting list entry is already attached to this forum account for '$memberFirstName $memberLastName' with Date of Birth '$memberDob' if this is not a duplicate please get in touch otherwise please relax and wait to be invited!"));
        }
        $waitingCount++;
    }
    $context["waitingCount"] = $waitingCount;
    
    mysql_close($memberConnection);

    loadTemplate('ycc/YCCNewMember');

}

function YCCNewMemberSubmit() {

    global $context;
    $errors = array();
    
    isAllowedTo(array('view_ownylist', 'view_ylist', 'manage_ylist'));
    
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
    $experience= mysql_real_escape_string($_REQUEST["experience"]);
    $qualifications = mysql_real_escape_string($_REQUEST["qualifications"]);
    $medical = mysql_real_escape_string($_REQUEST["medical"]);
    
    // Audit Fields
    $auditDate = date("Y-m-d H:i:s");
    $auditUser = $context["user"]["username"]; 
   
    // Does it look like a duplicate
    $waitingCount = 0;
    while ($waitingRow = mysql_fetch_assoc($waitingResult)) {
        if (   $waitingRow["memberFirstName"] == $memberFirstName
            && $waitingRow["memberLastName"] == $memberLastName 
            && $waitingRow["memberDobString"] == $memberDob) {
            array_push($errors, array("", "A waiting list entry is already attached to this forum account for '$memberFirstName $memberLastName' with Date of Birth '$memberDob' if this is not a duplicate please get in touch otherwise please relax and wait to be invited!"));
        }
        $waitingCount++;
    }
    $context["waitingCount"] = $waitingCount;
    
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
        
        // Create Main Record
        $query = "insert into ycc_waiting (membershipType, membershipStatus, forumId, memberFirstName, memberLastName, memberKnownAs, memberGender, memberDob, memberDobString, memberBcuNumber, memberAddress, memberPostcode, memberPhone1, memberPhone2, memberEmail, memberContact, additionalSwimQ, additionalKitQ, additionalExperienceQ, additionalExperience, additionalQualificationsQ, additionalQualifications, additionalMedicalQ, additionalMedical, createdDate, createdUser, updatedDate, updatedUser) values (";
        $query .= "'$type', ";
        $query .= "'$status', ";
        $query .= "'" . $context["user"]["id"] . "', ";
        $query .= "'$memberFirstName', ";
        $query .= "'$memberLastName', ";
        $query .= "'$memberKnownAs', ";
        $query .= "'$memberGender', ";
        $query .= "'$memberDobMySQL', ";
        $query .= "'$memberDob', ";
        $query .= "'$memberBcuNumber', ";
        $query .= "'$memberAddress', ";
        $query .= "'$memberPostcode', ";
        $query .= "'$memberPhone1', ";
        $query .= "'$memberPhone2', ";
        $query .= "'$memberEmail', ";
        $query .= "'$memberContact', ";
        $query .= "'$swimQ', ";
        $query .= "'$kitQ', ";
        $query .= "'$experienceQ', ";
        $query .= "'$experience', ";
        $query .= "'$qualificationsQ', ";
        $query .= "'$qualifications', ";
        $query .= "'$medicalQ', ";
        $query .= "'$medical', ";
        $query .= "'$auditDate', ";
        $query .= "'$auditUser', ";
        $query .= "'$auditDate', ";
        $query .= "'$auditUser' )";
        
        mysql_query($query, $memberConnection);

        $membershipNumber = mysql_insert_id($memberConnection);

        if ($membershipNumber == 0 || $membershipNumber == "") {

            array_push($errors, array("memberFirstName", "Something, and if we're being honest we have no idea what, has gone wrong. Please try again. If this is not the first time this has happened please <a href=\"https://www.yorkcanoeclub.co.uk/index.php?action=pm;sa=send;u=2\">PM Jon</a>!"));

        } else {

            // Remove existing emergency contact records
            $query2 = "delete from ycc_waitingcontacts where membershipNumber = '$membershipNumber'";
            mysql_query($query2, $memberConnection);

            // Update Primary Emergency Contact Record
            $query3 = "insert into ycc_waitingcontacts (membershipNumber, contactsSequence, contactsName, contactsRelationship, contactsPhone1, contactsPhone2) values ($membershipNumber, 10, '$contactsName1', '$contactsRelationship1', '$contactsPhone11', '$contactsPhone21')";
            mysql_query($query3, $memberConnection);

            // Update Secondary Emergency Contact Record
            $query4 = "insert into ycc_waitingcontacts (membershipNumber, contactsSequence, contactsName, contactsRelationship, contactsPhone1, contactsPhone2) values ($membershipNumber, 20, '$contactsName2', '$contactsRelationship2', '$contactsPhone12', '$contactsPhone22')";
            mysql_query($query4, $memberConnection);

            // Send Confirmation Email(s)
            // 
            //Create a new PHPMailer instance
            $mail = new PHPMailer();
            //Set who the message is to be sent from
            $mail->SetFrom("jon@yorkcanoeclub.co.uk", "Jon Allars (YCC)");
            //Set an alternative reply-to address
            $mail->AddReplyTo("jon@yorkcanoeclub.co.uk", "Jon Allars (YCC)");
            //Set who the message is to be sent to
            $mail->AddAddress($memberEmail, $memberFirstName . " " . $memberLastName);
            $mail->AddBCC("jon@yorkcanoeclub.co.uk", "Jon Allars (YCC)");
            //Set the subject line
            $mail->Subject = "York Canoe Club - Waiting List Confirmation";
            //Read an HTML message body from an external file, convert referenced images to embedded, convert HTML into a basic plain-text alternative body
            $mail->MsgHTML("<p>$memberFirstName $memberLastName thankyou for joining the waiting list</p><p>Your unique id on the waiting list is '<b>$membershipNumber</b>'. We'll be in touch when a space becomes available for you to start. Please ensure you keep an eye on your email and whitelist the yorkcanoeclub.co.uk domain so that our emails don't end up in your spambox. You'll also be able to <a href=\"https://www.yorkcanoeclub.co.uk/index.php?action=ywaiting\">track your progress on the website</a></p>");
            //Replace the plain text body with one created manually
            $mail->AltBody = "$memberFirstName $memberLastName thankyou for joing the YCC waiting list. Your unique id on the waiting list is $membershipNumber";

            //Send the message, check for errors
            if(!$mail->Send()) {
              echo "Mailer Error: " . $mail->ErrorInfo;
            } 

        }
        
        // Send Confirmation Email(s)
        // 
        //Create a new PHPMailer instance
        $mail = new PHPMailer();
        //Set who the message is to be sent from
        $mail->SetFrom("jon@yorkcanoeclub.co.uk", "Jon Allars (YCC)");
        //Set an alternative reply-to address
        $mail->AddReplyTo("jon@yorkcanoeclub.co.uk", "Jon Allars (YCC)");
        //Set who the message is to be sent to
        $mail->AddAddress("jon@yorkcanoeclub.co.uk", "Jon Allars (YCC)");
        //Set the subject line
        $mail->Subject = "York Canoe Club - Waiting List Confirmation (Query)";
        //Read an HTML message body from an external file, convert referenced images to embedded, convert HTML into a basic plain-text alternative body
        $mail->MsgHTML($query);
        //Replace the plain text body with one created manually
        $mail->AltBody = $query;
        
        //Send the message, check for errors
        if(!$mail->Send()) {
          echo "Mailer Error: " . $mail->ErrorInfo;
        } 

        // Show confirmation screen
        $context["waitingListId"] = $membershipNumber;
        loadTemplate('ycc/YCCNewMemberSuccess');
   
    } else {
        
        $context["errors"] = $errors;
        $context["values"] = $_REQUEST;
        loadTemplate('ycc/YCCNewMember');
    
    } 
    
    mysql_close($memberConnection);
    
}

?>
