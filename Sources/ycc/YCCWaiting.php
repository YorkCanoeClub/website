<?php
if (!defined('SMF'))
die('Hacking attempt...');

require_once(dirname(__FILE__) . '/../../Settings.php');

global $db_persist, $db_connection, $db_server, $db_user, $db_passwd;
global $db_type, $db_name, $ssi_db_user, $ssi_db_passwd, $sourcedir, $db_prefix;
global $boarddir;

require_once($boarddir."/helper/functions.php");

function YCCWaiting() {

    global $context;

    isAllowedTo(array('view_ownylist', 'view_ylist', 'manage_ylist'));
    
    $memberConnection = mysql_connect($db_server, $db_user, $db_passwd, true) or die("Could not connect: " . mysql_error());
    mysql_select_db($db_name, $memberConnection) or die ('Cannot Connect to DB: ' . mysql_error());

    $new = Array();
    $waiting = Array();
    $invited = Array();
    
    $query = "select 
                w.*, 
                m.member_name as fName, 
                DATE_FORMAT(w.memberDob, '%d/%m/%Y') as memberDobF,  
                DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(w.memberDob)), '%Y')+0 AS age,
                DATE_FORMAT(w.createdDate, '%d/%m/%Y') as createdDateF  
                from ycc_waiting as w 
                left join smf_members as m
                on w.forumid = m.id_member
              where w.membershipStatus = 'W100'
              order by w.membershipStatus desc, w.createdDate";
    $result = mysql_query($query, $memberConnection);
    
    while ($row = mysql_fetch_assoc($result)) {

        array_push($new, $row);

    }
    
    $query2 = "select 
                w.*, 
                m.member_name as fName, 
                DATE_FORMAT(w.memberDob, '%d/%m/%Y') as memberDobF,  
                DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(w.memberDob)), '%Y')+0 AS age,
                DATE_FORMAT(w.createdDate, '%d/%m/%Y') as createdDateF  
                from ycc_waiting as w 
                left join smf_members as m
                on w.forumid = m.id_member
              where w.membershipStatus = 'W200'
              or w.membershipStatus = 'W201'
              or w.membershipStatus = 'W202'
              order by w.membershipStatus desc, w.createdDate";
    $result2 = mysql_query($query2, $memberConnection);
    
    while ($row2 = mysql_fetch_assoc($result2)) {

        array_push($waiting, $row2);

    }
    
    $query3 = "select 
                w.*, 
                m.member_name as fName, 
                DATE_FORMAT(w.memberDob, '%d/%m/%Y') as memberDobF,  
                DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(w.memberDob)), '%Y')+0 AS age,
                DATE_FORMAT(w.createdDate, '%d/%m/%Y') as createdDateF  
                from ycc_waiting as w 
                left join smf_members as m
                on w.forumid = m.id_member
              where w.membershipStatus = 'W300'
              or w.membershipStatus = 'W301'
              or w.membershipStatus = 'W350'
              or w.membershipStatus = 'W351'
              or w.membershipStatus = 'W400'
              or w.membershipStatus = 'W401'
              or w.membershipStatus = 'W299'
              order by w.membershipStatus desc, w.createdDate";
    $result3 = mysql_query($query3, $memberConnection);
    
    while ($row3 = mysql_fetch_assoc($result3)) {

        array_push($invited, $row3);

    }
    
    mysql_close($memberConnection);
    
    // Payments
    $context['new'] = $new;
    $context['waiting'] = $waiting;
    $context['invited'] = $invited;
    
    loadTemplate('ycc/YCCWaiting');

}

?>
