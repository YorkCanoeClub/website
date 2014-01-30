<?php
if (!defined('SMF'))
die('Hacking attempt...');

require_once("/home/yorkcano/public_html/helper/functions.php");

function YCCMemberList() {

    global $context;

    isAllowedTo(array('committee'));
    $manager = allowedTo('committee_manage');

    $memberDB = array("localhost", "yorkcano_web", "web", "yorkcano_smf", true);

    $memberConnection = mysql_connect($memberDB[0], $memberDB[1], $memberDB[2], true) or die("Could not connect: " . mysql_error());
    mysql_select_db($memberDB[3], $memberConnection) or die ('Cannot Connect to DB: ' . mysql_error());

    $memberQuery = "select
                    members.*,
                    forum.*,
                    DATE_FORMAT(members.membershipStarted, '%d/%m/%Y') as membershipStartedF,
                    DATE_FORMAT(members.membershipExpires, '%d/%m/%Y') as membershipExpiresF
                   from ycc_members as members
                   left join smf_members as forum
                   on members.forumId = forum.id_member
                   order by members.membershipNumber";

    $result = mysql_query($memberQuery, $memberConnection);

    $memberCount = mysql_num_rows($result);
    $members = array();

    while ($row = mysql_fetch_assoc($result)) {

        $contactsQuery = "select * from ycc_memberscontacts where membershipNumber = " . $row["membershipNumber"] . " and contactsSequence = 10";
        $contactsResult = mysql_query($contactsQuery, $memberConnection);
        $contactsRow = mysql_fetch_assoc($contactsResult);

        if ($row["memberFirstName"] == ""
         || $row["memberLastName"] == ""
         || $row["memberGender"] == ""
         || $row["memberDob"] == ""
         || $row["memberAddress"] == ""
         || $row["memberPostcode"] == ""
         || $row["memberPhone1"] == ""
         || $row["memberEmail"] == ""
         || $row["memberContact"] == ""
         || $contactsRow["contactsName"] == ""
         || $contactsRow["contactsRelationship"] == ""
         || $contactsRow["contactsPhone1"] == ""
        ) {
            $row["complete"] = "Details Missing";
        } else {
            $row["complete"] = "&nbsp;";
        }

        array_push($members, $row);

    }

    $context['pageTitle'] = 'YCC Members';
    $context['memberCount'] = $memberCount;
    $context['memberArray'] = $members;
    $context['memberManage'] = $manager;

    mysql_close($memberConnection);

    loadTemplate('ycc/YCCMemberList');

}
?>