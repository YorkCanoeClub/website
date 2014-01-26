<?php
if (!defined("SMF"))
die("Hacking attempt...");

require_once("/home/yorkcano/public_html/helper/functions.php");

function CommitteeEmergencyContacts() {

    global $context;

    isAllowedTo(array("committee"));
    $manager = allowedTo("committee_manage");
    
    $extraDb = array("localhost", "yorkcano_web", "web", "yorkcano_smf");

    $extraConnection = mysql_connect($extraDb[0], $extraDb[1], $extraDb[2], true) or die("Could not connect: " . mysql_error());
    mysql_select_db($extraDb[3], $extraConnection) or die ("Cannot Connect to DB: " . mysql_error());

    // Has a trip been selected
    $trip = "";
    if (isset($_REQUEST["trip"])) {
        
        $trip = $_REQUEST["trip"];
        
    }
    
    $tripsQuery = "select * from ycc_trips";
    
    $tripsResult = mysql_query($tripsQuery, $extraConnection);
    
    $trips = Array();
    
    while ($tripsRow = mysql_fetch_assoc($tripsResult)) {

        array_push($trips, $tripsRow);

    } 

    $contactsQuery = "select 
                                concat(m.membershipNumber, concat('/', m.membershipType)) as membershipNumber,
                                concat(m.memberFirstName, concat(' ' , m.memberLastName)) as memberName, 
                                c1.contactsName as primaryContactName,
                                c1.contactsRelationship as primaryContactRelationship,
                                c1.contactsPhone1 as primaryContactPhone1,
                                c1.contactsPhone2 as primaryContactPhone2, 
                                c2.contactsName as secondaryContactName,
                                c2.contactsRelationship as secondaryContactRelationship,
                                c2.contactsPhone1 as secondaryContactPhone1,
                                c2.contactsPhone2 as secondaryContactPhone2
                        from ycc_members as m 
                        left join ycc_memberscontacts as c1 
                        on m.membershipNumber = c1.membershipNumber 
                        and c1.contactsSequence = 10
                        left join ycc_memberscontacts as c2 
                        on m.membershipNumber = c2.membershipNumber 
                        and c2.contactsSequence = 20
                        where m.membershipStatus = '500'
                        and (c1.contactsName != ''
                        or c2.contactsName != '')
                        order by m.membershipNumber";
    
    $contactsResult = mysql_query($contactsQuery, $extraConnection);
    
    $contacts = Array();
    
    while ($contactsRow = mysql_fetch_assoc($contactsResult)) {

        array_push($contacts, $contactsRow);

    } 
    
    mysql_close($extraConnection);
    
    $context["pageTitle"] = "Generate Emergency Contact List";
    $context["manager"] = $manager;
    $context["contacts"] = $contacts;
    
    loadTemplate("ycc/CommitteeEmergencyContacts");

}
?>