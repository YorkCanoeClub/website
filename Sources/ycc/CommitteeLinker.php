<?php
if (!defined('SMF'))
die('Hacking attempt...');

require_once(dirname(__FILE__) . '/../../Settings.php');

global $db_persist, $db_connection, $db_server, $db_user, $db_passwd;
global $db_type, $db_name, $ssi_db_user, $ssi_db_passwd, $sourcedir, $db_prefix;
global $boarddir;

require_once($boarddir."/helper/functions.php");

function CommitteeLinker() {

    global $context;

    isAllowedTo(array('committee'));
    $manager = allowedTo('committee_manage');
    
    $memberConnection = mysql_connect($db_server, $db_user, $db_passwd, true) or die("Could not connect: " . mysql_error());
    mysql_select_db($db_name, $memberConnection) or die ('Cannot Connect to DB: ' . mysql_error());

    $membersU = Array();
    $membersL = Array();
    $accountsU = Array();
    $accountsL = Array();
    
    $query = "select membershipNumber, concat(memberFirstName, concat(' ', memberLastName)) as memberName from ycc_members where membershipNumber not in (select membershipNumber from ycc_memberForumCR) order by memberName";
    $result = mysql_query($query, $memberConnection);
    
    while ($row = mysql_fetch_assoc($result)) {

        array_push($membersU, $row);

    } 
    $query = "select membershipNumber, concat(memberFirstName, concat(' ', memberLastName)) as memberName from ycc_members where membershipNumber in (select membershipNumber from ycc_memberForumCR) order by memberName";
    $result = mysql_query($query, $memberConnection);
    
    while ($row = mysql_fetch_assoc($result)) {

        array_push($membersL, $row);

    } 
    
    $query = "select id_member, real_name from smf_members where id_member not in (select forumId from ycc_memberForumCR) order by real_name";
    $result = mysql_query($query, $memberConnection);
    
    while ($row = mysql_fetch_assoc($result)) {

        array_push($accountsU, $row);

    }       
    
    $query = "select id_member, real_name from smf_members where id_member in (select forumId from ycc_memberForumCR) order by real_name";
    $result = mysql_query($query, $memberConnection);
    
    while ($row = mysql_fetch_assoc($result)) {

        array_push($accountsL, $row);

    }       

    mysql_close($memberConnection);
    
    // Unlinked Members
    $context['membersU'] = $membersU;
    // Linked Members
    $context['membersL'] = $membersL;
    // Unlinked Forum Accounts
    $context['accountsU'] = $accountsU;
    // Linked Forum Accounts
    $context['accountsL'] = $accountsL;
    
    loadTemplate('ycc/CommitteeLinker');

}

function CommitteeLinkerSubmit() {

    global $context;

    isAllowedTo(array('committee'));
    $manager = allowedTo('committee_manage');
    
    $memberConnection = mysql_connect($db_server, $db_user, $db_passwd, true) or die("Could not connect: " . mysql_error());
    mysql_select_db($db_name, $memberConnection) or die ('Cannot Connect to DB: ' . mysql_error());

    $membershipNumber = $_REQUEST["membershipNumber"];
    $forumId = $_REQUEST["forumId"];
    $primary = intVal($_REQUEST["primary"], 10);
    
    $query = "insert into ycc_memberForumCR values($membershipNumber, $forumId, $primary)";
    mysql_query($query, $memberConnection);
 
    print("*OK");
    
    exit(0);
    
}

?>
