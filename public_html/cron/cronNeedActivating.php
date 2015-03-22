<?php

$hostname = "yorkcanoeclub.co.uk";
$username = "yorkcano_web";
$password = "web";
$db = "yorkcano_smf";

try {
    
    $dbh = new PDO("mysql:host=$hostname;dbname=$db", $username, $password);
    
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $sql = "select count(*) as count from smf_members where is_activated = 3";
    foreach ($dbh->query($sql) as $row) {
        
        print("There are currently " . $row["count"] . " users that need activating.");
        if ($row["count"] > 0) {
           print(" Please go to <a href=\"https://www.yorkcanoeclub.co.uk/index.php?action=admin;area=viewmembers;sa=browse;type=approve\">the forum approvals page</a> to approve or reject them.");
        }
        
    }

    $dbh = null;
    
} catch(PDOException $e) {
    
    print($e->getMessage());

}

?>