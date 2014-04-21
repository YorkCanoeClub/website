<?php

require_once(dirname(__FILE__) . '/../Settings.php');

global $db_persist, $db_connection, $db_server, $db_user, $db_passwd;
global $db_type, $db_name, $ssi_db_user, $ssi_db_passwd, $sourcedir, $db_prefix;
global $boarddir;

require_once($boarddir."/SSI.php");
require_once($boarddir."/helper/functions.php");

global $context;

if ($context['user']['is_guest']) {

    print("<p>In order to manage payments you need to <a href=\"http://www.yorkcanoeclub.co.uk/index.php?action=register\">register</a> and <a href=\"http://www.yorkcanoeclub.co.uk/index.php?action=login\">login</a>.</p>");

} else {

    $memberConnection = mysql_connect($db_server, $db_user, $db_passwd, true) or die("Could not connect: " . mysql_error());
    mysql_select_db($db_name, $memberConnection) or die ('Cannot Connect to DB: ' . mysql_error());

    $paymentQuery = "select * from ycc_payments where paymentUser = '" . $context["user"]["username"] . "' and paymentStatus = 100";
    $result = mysql_query($paymentQuery, $paymentConnection);
    
    $paymentCount = mysql_num_rows($result);
    
    if ($paymentCount > 0) {
        
        while ($row = mysql_fetch_assoc($result)) {

            print("<p><b>" . $row["paymentReason"] . "</b><br />" . $row["paymentReference"] . ": &pound;" . $row["paymentValue"] .  "</p>");
            
        }
    
    } else {
        
        print("You have no pending payments.");
        
    }
    
    mysql_close($paymentConnection);
    
}

?>
 
