<?php
if (!defined('SMF'))
die('Hacking attempt...');

require_once(dirname(__FILE__) . '/../../Settings.php');

global $db_persist, $db_connection, $db_server, $db_user, $db_passwd;
global $db_type, $db_name, $ssi_db_user, $ssi_db_passwd, $sourcedir, $db_prefix;
global $boarddir;

require_once($boarddir."/helper/functions.php");

function CommitteeMoney() {

    global $context;

    isAllowedTo(array('committee'));
    $manager = allowedTo('committee_manage');
    
    $memberConnection = mysql_connect($db_server, $db_user, $db_passwd, true) or die("Could not connect: " . mysql_error());
    mysql_select_db($db_name, $memberConnection) or die ('Cannot Connect to DB: ' . mysql_error());

    $users = Array();
    
    $query = "select id_member, member_name, real_name from smf_members order by member_name";
    $result = mysql_query($query, $memberConnection);
    
    while ($row = mysql_fetch_assoc($result)) {

        array_push($users, $row);

    } 
    
    $payments = Array();
    
    $query = "select 
                *, 
                DATE_FORMAT(paymentDate, '%d/%m/%Y %H:%i:%s') as paymentDateF 
               from ycc_payments 
               where paymentDate >= date_sub(now(), interval 6 month)
               or paymentStatus = 100
               order by paymentId desc";
    $result = mysql_query($query, $memberConnection);
    
    while ($row = mysql_fetch_assoc($result)) {

        array_push($payments, $row);

    }    

    mysql_close($memberConnection);
    
    // Payments
    $context['payments'] = $payments;
    $context['manager'] = $manager;
    $context['users'] = $users;
    
    loadTemplate('ycc/CommitteeMoney');

}

function CommitteeMoneySubmit() {

    global $context;
    
    isAllowedTo(array('committee'));
    $manager = allowedTo('committee_manage');
    
    // Audit Fields
    $auditDate = date("Y-m-d H:i:s");
    $auditUser = $context["user"]["username"]; 
    
    $memberConnection = mysql_connect($db_server, $db_user, $db_passwd, true) or die("Could not connect: " . mysql_error());
    mysql_select_db($db_name, $memberConnection) or die ('Cannot Connect to DB: ' . mysql_error());

    $mode = $_REQUEST["mode"];
    
    if ($mode == "*CREATE") {
        
        $userId = $_REQUEST["userId"];
        $userName = $_REQUEST["userName"];
        $paymentReason = $_REQUEST["paymentReason"];
        $paymentAmount = $_REQUEST["paymentAmount"];
        
        if ($userId != "" && $userName != "" && $paymentReason != "" && !is_nan($paymentAmount)) {
        
            $query = "insert into ycc_payments values(
                null, 
                '" . alphaId() . "', 
                '$paymentReason', 
                '$auditDate', 
                $userId, 
                '$userName', 
                $paymentAmount, 
                100
            )";
            mysql_query($query, $memberConnection);
            
        } else {
            
            print("*VALIDATE");
            mysql_close($memberConnection);
            exit(0);
            
        }
        
    } else if ($mode == "*PAID") {
        
        $paymentId = $_REQUEST["paymentId"];
    
        if ($paymentId != "") {
            
            $query = "update ycc_payments set paymentStatus = 500 where paymentId = $paymentId";
            mysql_query($query, $memberConnection);

        } else {
            
            print("*VALIDATE");
            mysql_close($memberConnection);
            exit(0);
            
        }
        
    } else {
        
        
    }
    
    print("*OK");
    mysql_close($memberConnection);
    exit(0);
    
}

?>
