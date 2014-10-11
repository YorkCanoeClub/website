<?php
if (!defined('SMF'))
die('Hacking attempt...');

require_once(dirname(__FILE__) . '/../../Settings.php');
global $boarddir;
require_once($boarddir."/helper/functions.php");

function YCCWaiting() {

    global $context;
    global $db_server, $db_name, $db_user, $db_passwd;

    $context['page_title'] = "Waiting List";

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
              and w.deleteFlag=0
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
              where w.membershipStatus IN ('W200', 'W201', 'W202')
              and w.deleteFlag=0
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
              where w.membershipStatus IN ('W300', 'W301', 'W350', 'W351', 'W400', 'W401', 'W299')
              and w.deleteFlag=0
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
