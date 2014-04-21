<?php
if (!defined('SMF'))
die('Hacking attempt...');

require_once(dirname(__FILE__) . '/../../Settings.php');

global $db_persist, $db_connection, $db_server, $db_user, $db_passwd;
global $db_type, $db_name, $ssi_db_user, $ssi_db_passwd, $sourcedir, $db_prefix;
global $boarddir;

require_once($boarddir."/helper/functions.php");
require_once($boarddir."/libraries/PHPMailer/class.phpmailer.php");
    
function YCCMembershipRenew() {

    global $context;

    isAllowedTo(array('view_ownylist'));
    
    $memberConnection = mysql_connect($db_server, $db_user, $db_passwd, true) or die("Could not connect: " . mysql_error());
    mysql_select_db($db_name, $memberConnection) or die ('Cannot Connect to DB: ' . mysql_error());

    $count = intval($_REQUEST["count"], 10);
    $membersIn = Array();
    for ($i = 1; $i <= $count; $i++) {
        array_push($membersIn, $_REQUEST["member" . $i]);
    }
    
    $context["family"] = false;
    $context["familyplus"] = false;
    
    $memberQuery = "select 
                    *, 
                    DATE_FORMAT(membershipStarted, '%d/%m/%Y') as membershipStartedF, 
                    DATE_FORMAT(membershipExpires, '%d/%m/%Y') as membershipExpiresF,
                    DATE_FORMAT(memberDob, '%d/%m/%Y') as memberDobF 
                from ycc_members as members 
                left join ycc_memberForumCR as crossref
                on members.membershipNumber = crossref.membershipNumber
                where crossref.forumId = '" . $context["user"]["id"] . "'
                and members.membershipNumber IN (";
    $memberQuery .= implode(",", $membersIn);            
    $memberQuery .= ") and members.membershipStatus != '500'";
    $memberQuery .= " order by members.membershipNumber";
    
    $memberResult = mysql_query($memberQuery, $memberConnection);
    
    $members = array();
    $adults = 0;
    $juniors = 0;
    $totalCost = 0;
    
    while ($row = mysql_fetch_assoc($memberResult)) {

        // Calculate Age
        $row["calculatedAge"] = ageFromDob($row["memberDob"]);
        
        if ($row["calculatedAge"] >= 18) {
            $row["membershipType"] = "A";
            $adults++;
            if ($row["memberBcuNumber"] == "") {
                $row["membershipCost"] = 40;
            } else {
                $row["membershipCost"] = 39;
            }   
        } else {
            $row["membershipType"] = "J";
            $juniors++;
            if ($row["memberBcuNumber"] == "") {
                $row["membershipCost"] = 25;
            } else {
                $row["membershipCost"] = 24;
            }
        }
        
        $totalCost = $totalCost + $row["membershipCost"];
        
        array_push($members, $row);

    }        

    if ($totalCost > 80) {
    
        // Check Viability of Family Membership
        if ($adults <= 2) {
            $totalCost = 80;
            $context["family"] = true;
        } else if ($adults > 2) {
            $totalCost = 80 + ($adults - 2 * 40);
            $context["familyplus"] = true;
        }
        
    }
    
    mysql_close($memberConnection);

    $context['pageTitle'] = 'Renew YCC Memberships';
    $context['memberCount'] = sizeof($members);
    $context['memberArray'] = $members;
    $context['totalCost'] = $totalCost;

    loadTemplate('ycc/YCCMembershipRenew');

}

function YCCMembershipRenewSubmit() {

    global $context;

    isAllowedTo(array('view_ownylist'));
    
    $memberConnection = mysql_connect($db_server, $db_user, $db_passwd, true) or die("Could not connect: " . mysql_error());
    mysql_select_db($db_name, $memberConnection) or die ('Cannot Connect to DB: ' . mysql_error());

    $count = intval($_REQUEST["count"], 10);
    $membersIn = Array();
    for ($i = 1; $i <= $count; $i++) {
        array_push($membersIn, $_REQUEST["member" . $i]);
    }
    // Audit Fields
    $auditDate = date("Y-m-d H:i:s");
    $auditUser = $context["user"]["username"]; 
    
    $memberQuery = "select 
                    *, 
                    DATE_FORMAT(membershipStarted, '%d/%m/%Y') as membershipStartedF, 
                    DATE_FORMAT(membershipExpires, '%d/%m/%Y') as membershipExpiresF,
                    DATE_FORMAT(memberDob, '%d/%m/%Y') as memberDobF 
                from ycc_members as members 
                left join ycc_memberForumCR as crossref
                on members.membershipNumber = crossref.membershipNumber
                where crossref.forumId = '" . $context["user"]["id"] . "'
                and members.membershipNumber IN (";
    $memberQuery .= implode(",", $membersIn);            
    $memberQuery .= ") and members.membershipStatus != '500'";
    $memberQuery .= " order by members.membershipNumber";
    
    $memberResult = mysql_query($memberQuery, $memberConnection);
    
    $members = array();
    $adults = 0;
    $juniors = 0;
    $totalCost = 0;
    
    while ($row = mysql_fetch_assoc($memberResult)) {

        // Calculate Age
        $row["calculatedAge"] = ageFromDob($row["memberDob"]);
        
        if ($row["calculatedAge"] >= 18) {
            $row["membershipType"] = "A";
            $adults++;
            if ($row["memberBcuNumber"] == "") {
                $row["membershipCost"] = 40;
            } else {
                $row["membershipCost"] = 39;
            }   
        } else {
            $row["membershipType"] = "J";
            $juniors++;
            if ($row["memberBcuNumber"] == "") {
                $row["membershipCost"] = 25;
            } else {
                $row["membershipCost"] = 24;
            }
        }
        
        $totalCost = $totalCost + $row["membershipCost"];
        
        array_push($members, $row);

    }        

    if ($totalCost > 80) {
    
        // Check Viability of Family Membership
        if ($adults <= 2) {
            $totalCost = 80;
            $context["family"] = true;
        } else if ($adults > 2) {
            $totalCost = 80 + ($adults - 2 * 40);
            $context["familyplus"] = true;
        }
        
    }
    
    // Work out the current season
    if (intval(date("m")) >= 10) {
        $currentYear = date("Y") + 1;
        $currentSeason = date("Y") . "/" . (date("y") + 1);
    } else {
        $currentYear = date("Y");
        $currentSeason = (date("Y")) . "/" . date("y");
    }

    // Update Status, Renewed Date and Expiry Date on Membership record
    $memberQuery = "update ycc_members set ";
    $memberQuery .= "membershipStatus = 350, "; 
    $memberQuery .= "membershipStarted = '$auditDate', "; 
    $memberQuery .= "membershipExpires = '$currentYear-09-30', "; 
    $memberQuery .= "updatedDate = '$auditDate', ";
    $memberQuery .= "updatedUser = '$auditUser' "; 
    $memberQuery .= "where membershipNumber IN (";
    $memberQuery .= implode(",", $membersIn);            
    $memberQuery .= ")";
    mysql_query($memberQuery, $memberConnection);
    
    // Insert Reference into payments file
    //$query = "insert into ycc_payments values(null, '" . implode(",", $membersIn) . "', 'Membership $currentSeason', '$auditDate', ". $context["user"]["id"] . ", '" . $context["user"]["username"] . "', $totalCost, 100)";
    //mysql_query($query, $memberConnection);
    //$paymentId = mysql_insert_id($memberConnection);
    
    //$context["reference"] = alphaId();
    
    //$query = "update ycc_payments set paymentReference = '" . $context["reference"] . "' where paymentId = $paymentId";
    //mysql_query($query, $memberConnection);
    
    mysql_close($memberConnection);

    $context['pageTitle'] = 'Renew YCC Memberships - Complete';
    $context['memberCount'] = sizeof($members);
    $context['memberArray'] = $members;
    $context['totalCost'] = $totalCost;

    $message = "<p>Thank you for renewing your membership(s). The process will be complete once we have received payment of <b>&pound;" . $context["totalCost"] . "</b>.</p>";
    $message .= "<p>Your unique reference is <b>" . implode(",", $membersIn) . "</b></p>";
    $message .= "<p>You can pay by internet / telephone banking. <b>This is the preferred method of payment</b>. Our bank details are: <br /><br /><b>Bank</b>: Barclays<br /><b>Sort Code</b>: 20-99-56<br /><b>Account No.</b>: 00666165<br /><br />Please ensure you quote your unique reference, or as much of it as possible, when paying otherwise we will not know who the money is from or what it is for. The unique reference is your membership number(s).</p>";
    $message .= "<p>You can also pay by cheque made payable to 'York Canoe Club'. Please ensure you write your unique reference on the back of the cheque as we will not be able to accept a cheque without a reference.</p>";
    $message .= "<p>You can also pay with cash. Please ensure you have your unique reference available when paying and make sure that the person you pay makes a note of your reference.</p>";
   
    print($message);
    
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
    $mail->Subject = "York Canoe Club - Membership Renewal";
    //Read an HTML message body from an external file, convert referenced images to embedded, convert HTML into a basic plain-text alternative body
    $mail->MsgHTML($message);
    //Replace the plain text body with one created manually
    $mail->AltBody = "You've renewed your YCC membership(s) your reference is " . implode(",", $membersIn);

    //Send the message, check for errors
    if(!$mail->Send()) {
      echo "Mailer Error: " . $mail->ErrorInfo;
    } 

    exit(0);
}

?>
