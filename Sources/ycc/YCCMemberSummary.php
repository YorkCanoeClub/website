<?php
if (!defined('SMF'))
die('Hacking attempt...');

require_once(dirname(__FILE__) . '/../../Settings.php');

global $db_persist, $db_connection, $db_server, $db_user, $db_passwd;
global $db_type, $db_name, $ssi_db_user, $ssi_db_passwd, $sourcedir, $db_prefix;
global $boarddir;

require_once($boarddir."/helper/functions.php");

function YCCMemberSummary() {

    global $context;

    isAllowedTo(array('committee'));
    $manager = allowedTo('committee_manage');
    
    $memberConnection = mysql_connect($db_server, $db_user, $db_passwd, true) or die("Could not connect: " . mysql_error());
    mysql_select_db($db_name, $memberConnection) or die ('Cannot Connect to DB: ' . mysql_error());

    if (isset($_REQUEST["currentSeason"]) && $_REQUEST["currentSeason"] != "") {
        $currentSeason = $_REQUEST["currentSeason"];
    } else {
        if (intval(date("m")) >= 10) {
            $currentSeason = date("Y") . "/" . (date("y") + 1);
        } else {
            $currentSeason = (date("Y") - 1) . "/" . date("y");
        }
    }
    
    $summaryQuery = "select 
                        count(*) as members, 
                        memberInGender, 
                        memberInAgeGroup, 
                        memberInBcu 
                     from ycc_memberin 
                     where memberInSeason = '$currentSeason' 
                     group by memberInGender, memberInAgeGroup, memberInBcu order by memberInGender, memberInAgeGroup, memberInBcu";
    
    $summaryResult = mysql_query($summaryQuery, $memberConnection);

    // Male BCU
    $context["M1B"] = 0;
    $context["M2B"] = 0;
    $context["M3B"] = 0;
    $context["M4B"] = 0;
    // Female BCU
    $context["F1B"] = 0;
    $context["F2B"] = 0;
    $context["F3B"] = 0;
    $context["F4B"] = 0;
    // Male Non-BCU
    $context["M1N"] = 0;
    $context["M2N"] = 0;
    $context["M3N"] = 0;
    $context["M4N"] = 0;
    // Female Non-BCU
    $context["F1N"] = 0;
    $context["F2N"] = 0;
    $context["F3N"] = 0;
    $context["F4N"] = 0;
    // Totals CE
    $context["TMB"] = 0;
    $context["TFB"] = 0;
    $context["TMN"] = 0;
    $context["TFN"] = 0;
    
    while ($summaryRow = mysql_fetch_assoc($summaryResult)) {
        
        if ($summaryRow["memberInGender"] == "M") {
           
            if($summaryRow["memberInBcu"] == 1) {
                
                if ($summaryRow["memberInAgeGroup"] == 1) {
                    $context["M1B"] = $summaryRow["members"];
                    $context["TMB"] += $summaryRow["members"];
                } else if ($summaryRow["memberInAgeGroup"] == 2) {
                    $context["M2B"] = $summaryRow["members"];
                    $context["TMB"] += $summaryRow["members"];
                } else if ($summaryRow["memberInAgeGroup"] == 3) {
                    $context["M3B"] = $summaryRow["members"];
                    $context["TMB"] += $summaryRow["members"];
                } else if ($summaryRow["memberInAgeGroup"] == 4) {
                    $context["M4B"] = $summaryRow["members"];
                    $context["TMB"] += $summaryRow["members"];
                }
                
            } else {
                
                if ($summaryRow["memberInAgeGroup"] == 1) {
                    $context["M1N"] = $summaryRow["members"];
                    $context["TMN"] += $summaryRow["members"];
                } else if ($summaryRow["memberInAgeGroup"] == 2) {
                    $context["M2N"] = $summaryRow["members"];
                    $context["TMN"] += $summaryRow["members"];
                } else if ($summaryRow["memberInAgeGroup"] == 3) {
                    $context["M3N"] = $summaryRow["members"];
                    $context["TMN"] += $summaryRow["members"];
                } else if ($summaryRow["memberInAgeGroup"] == 4) {
                    $context["M4N"] = $summaryRow["members"];
                    $context["TMN"] += $summaryRow["members"];
                }
                
            }
            
        } else {
            
            if($summaryRow["memberInBcu"] == 1) {
                
                if ($summaryRow["memberInAgeGroup"] == 1) {
                    $context["F1B"] = $summaryRow["members"];
                    $context["TFB"] += $summaryRow["members"];
                } else if ($summaryRow["memberInAgeGroup"] == 2) {
                    $context["F2B"] = $summaryRow["members"];
                    $context["TFB"] += $summaryRow["members"];
                } else if ($summaryRow["memberInAgeGroup"] == 3) {
                    $context["F3B"] = $summaryRow["members"];
                    $context["TFB"] += $summaryRow["members"];
                } else if ($summaryRow["memberInAgeGroup"] == 4) {
                    $context["F4B"] = $summaryRow["members"];
                    $context["TFB"] += $summaryRow["members"];
                }
                
            } else {
                
                if ($summaryRow["memberInAgeGroup"] == 1) {
                    $context["F1N"] = $summaryRow["members"];
                    $context["TFN"] += $summaryRow["members"];
                } else if ($summaryRow["memberInAgeGroup"] == 2) {
                    $context["F2N"] = $summaryRow["members"];
                    $context["TFN"] += $summaryRow["members"];
                } else if ($summaryRow["memberInAgeGroup"] == 3) {
                    $context["F3N"] = $summaryRow["members"];
                    $context["TFN"] += $summaryRow["members"];
                } else if ($summaryRow["memberInAgeGroup"] == 4) {
                    $context["F4N"] = $summaryRow["members"];
                    $context["TFN"] += $summaryRow["members"];
                }
                
            }
            
        }
    
    }
    
    // Totals Age / Gender
    $context["AM1"] = $context["M1B"] + $context["M1N"];
    $context["AM2"] = $context["M2B"] + $context["M2N"];
    $context["AM3"] = $context["M3B"] + $context["M3N"];
    $context["AM4"] = $context["M4B"] + $context["M4N"];
    $context["TM"] = $context["AM1"] + $context["AM2"] + $context["AM3"] + $context["AM4"];
    $context["AF1"] = $context["F1B"] + $context["F1N"];
    $context["AF2"] = $context["F2B"] + $context["F2N"];
    $context["AF3"] = $context["F3B"] + $context["F3N"];
    $context["AF4"] = $context["F4B"] + $context["F4N"];
    $context["TF"] = $context["AF1"] + $context["AF2"] + $context["AF3"] + $context["AF4"];
    // Totals Other
    $context["A1"] = $context["M1B"] + $context["F1B"] + $context["M1N"] + $context["F1N"];
    $context["A2"] = $context["M2B"] + $context["F2B"] + $context["M2N"] + $context["F2N"];
    $context["A3"] = $context["M3B"] + $context["F3B"] + $context["M3N"] + $context["F3N"];
    $context["A4"] = $context["M4B"] + $context["F4B"] + $context["M4N"] + $context["F4N"];
    $context["TB"] = $context["TMB"] + $context["TFB"];
    $context["TN"] = $context["TMN"] + $context["TFN"];
    // Grand Totals
    $context["GT"] = $context["A1"] + $context["A2"] + $context["A3"] + $context["A4"];
    $context["GTP"] = 100;
    // Totals Other Percent
    if ($context["GT"] != 0) {
        $context["TMBP"] = round($context["TMB"] / $context["GT"] * 100);
        $context["TFBP"] = round($context["TFB"] / $context["GT"] * 100);
        $context["TMNP"] = round($context["TMN"] / $context["GT"] * 100);
        $context["TFNP"] = round($context["TFN"] / $context["GT"] * 100);
        $context["TBP"] = round($context["TB"] / $context["GT"] * 100);
        $context["TNP"] = round($context["TN"] / $context["GT"] * 100);
        $context["A1P"] = round($context["A1"] / $context["GT"] * 100);
        $context["A2P"] = round($context["A2"] / $context["GT"] * 100);
        $context["A3P"] = round($context["A3"] / $context["GT"] * 100);
        $context["A4P"] = round($context["A4"] / $context["GT"] * 100);
        $context["TMP"] = round($context["TM"] / $context["GT"] * 100);
        $context["TFP"] = round($context["TF"] / $context["GT"] * 100);       
    }
    
    $seasonsQuery = "select distinct memberInSeason from ycc_memberin";
    $seasonsResult = mysql_query($seasonsQuery, $memberConnection);
    
    $context["seasons"] = array();
    
    while ($seasonsRow = mysql_fetch_assoc($seasonsResult)) {
    
        array_push($context["seasons"], $seasonsRow["memberInSeason"]);
        
    }
    
    $context["currentSeason"] = $currentSeason;
    $context['pageTitle'] = 'YCC Member Summary';

    mysql_close($memberConnection);

    loadTemplate('ycc/YCCMemberSummary');

}
?>
