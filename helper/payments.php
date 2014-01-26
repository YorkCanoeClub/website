<?php

require_once("/home/yorkcano/public_html/SSI.php");
require_once("/home/yorkcano/public_html/helper/functions.php");

global $context;

if ($context['user']['is_guest']) {

    print("<p>In order to manage payments you need to <a href=\"http://www.yorkcanoeclub.co.uk/index.php?action=register\">register</a> and <a href=\"http://www.yorkcanoeclub.co.uk/index.php?action=login\">login</a>.</p>");

} else {

    $paymentDB = array("localhost", "yorkcano_web", "web", "yorkcano_smf");

    $paymentConnection = mysql_connect($paymentDB[0], $paymentDB[1], $paymentDB[2], true) or die("Could not connect: " . mysql_error());
    mysql_select_db($paymentDB[3], $paymentConnection) or die ('Cannot Connect to DB: ' . mysql_error());

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
 