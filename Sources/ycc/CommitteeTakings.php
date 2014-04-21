<?php
if (!defined('SMF'))
die('Hacking attempt...');

require_once(dirname(__FILE__) . '/../../Settings.php');

global $db_persist, $db_connection, $db_server, $db_user, $db_passwd;
global $db_type, $db_name, $ssi_db_user, $ssi_db_passwd, $sourcedir, $db_prefix;
global $boarddir;

require_once($boarddir."/helper/functions.php");

function CommitteeTakings() {

    global $context;

    isAllowedTo(array('committee'));
    $manager = allowedTo('committee_manage');
    
    loadTemplate('ycc/CommitteeTakings');

}

function CommitteeTakingsSubmit() {
    
    global $context;

    isAllowedTo(array('committee'));
    $manager = allowedTo('committee_manage');
    
    $sessionType = $_REQUEST["sessionType"];
    $sessionDate = $_REQUEST["sessionDate"];
    $sessionDateMySQL = "";
    $sessionDateArray = explode('/', $sessionDate);
    if (count($sessionDateArray) == 3) {
        if (checkdate($sessionDateArray[1], $sessionDateArray[0], $sessionDateArray[2])) {
            $sessionDateMySQL = mysqlDate($sessionDate);
        } else {
            print("*VALIDATE");
            exit(0);
        }
    } else {
        print("*VALIDATE");
        exit(0);
    }
    
    $noNewStarters = intval($_REQUEST["sessionNewStarters"],10);
    $noMembers = intval($_REQUEST["sessionMembers"],10);
    $noNonMembers = intval($_REQUEST["sessionNonMembers"],10);
    $noKit = intval($_REQUEST["sessionKit"],10);
    
    $other = $_REQUEST["sessionOther"];
    
    $auditDate = date("Y-m-d H:i:s");
    
    $memberConnection = mysql_connect($db_server, $db_user, $db_passwd, true) or die("Could not connect: " . mysql_error());
    mysql_select_db($db_name, $memberConnection) or die ('Cannot Connect to DB: ' . mysql_error());

    // Check for Duplicates
    $duplicateQuery = "select * from ycc_takings where takingsDate = '$sessionDateMySQL'";
    $duplicateResult = mysql_query($duplicateQuery, $memberConnection);

    if (mysql_num_rows($duplicateResult) != 0) {
        print("*DUPLICATE");
        exit(0);
    }
    
    // Write Record
    $query = "insert into ycc_takings values (";
    $query .= "'$sessionDateMySQL', ";
    $query .= "'$sessionType', ";
    $query .= "$noNewStarters, ";
    $query .= "$noMembers, ";
    $query .= "$noNonMembers, ";
    $query .= "$noKit, ";
    $query .= "'$other', ";
    $query .= "'$auditDate', ";
    $query .= "'" . $context["user"]["id"] . "', ";
    $query .= "'$auditDate', ";
    $query .= "'" . $context["user"]["id"] . "', ";
    $query .= "0 )";
    
    mysql_query($query, $memberConnection);

    mysql_close($memberConnection);
    
    print("*OK");
    
    exit(0);
    
}

?>
