<?php

function template_main() {
    
    global $context, $settings, $options, $txt, $scripturl;
    
    $new = $context["new"];
    $manager = $context["manager"];
    
    ?>

    <link href="../../libraries/tooltipster/css/tooltipster.css" rel="stylesheet" type="text/css" />
    <script src="../../libraries/tooltipster/js/jquery.tooltipster.min.js" type="text/javascript"></script>
    
   <h1>New</h1>
    
   <table id="waitingList" class="membershipTable">
    <thead>
        <tr>
            <th class="ignoreSize">Id</th>
            <th>Forum Id</th>
            <th>Name</th>
            <th>Email</th>
            <th class="ignoreSize" title="Gender">G</th>
            <th class="ignoreSize" title="Age">A</th>
            <th>Status</th>
            <th class="ignoreSize">Added</th>
            <th class="actions">&nbsp;</th>
        </tr>
        <tr class="search">
            <th><input type="text" name="search0" id="search0" /></th>
            <th><input type="text" name="search1" id="search1" /></th>
            <th><input type="text" name="search2" id="search2" /></th>
            <th><input type="text" name="search3" id="search3" /></th>
            <th>&nbsp;</th>
            <th>&nbsp;</th>
            <th><span id="search11"></span></th>
            <th>&nbsp;</th>
            <th>&nbsp;</th>
        </tr>
    </thead>
    <tfoot>
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
    </tfoot>
    <tbody>
    
        <?php
        
        foreach ($new as $waiting) {
    
            ?>
        
            <tr id="<?php print($waiting["membershipNumber"]); ?>" class="clickableRow"> 
                <td><?php print($waiting["membershipNumber"] . "/" . $waiting["membershipType"]); ?></td>
                <td><?php print("<a class=\"pmUser\" href=\"https://www.yorkcanoeclub.co.uk/index.php?action=pm;sa=send;u=" . $waiting["forumId"] . "\">" . $waiting["member_name"] . "</a>"); ?></td>
                <td><?php print($waiting["memberFirstName"] . " " . $waiting["memberLastName"]); ?></td>
                <td><?php print("<a class=\"pmUser\" href=\"mailto:" . $waiting["memberEmail"] . "\">" . $waiting["memberEmail"] . "</a>"); ?></td>
                <td><?php print($waiting["memberGender"]); ?></td>
                <td><?php if ($waiting["age"] < 11) { print("<span style=\"color: #cc0000; font-weight: bold;\">"); } print($waiting["age"]); if ($waiting["age"] < 11) { print("</span>"); } ?></td>
                <td><?php print(getStatusDesc($waiting["membershipStatus"])); ?></td>
                <td><?php print($waiting["createdDate"]); ?>:::<?php print($waiting["createdDateF"]); ?>:::<?php print($waiting["createdDate"]); ?></td>
                <td>
                    <input class="selectedMember type<?php print($waiting["membershipStatus"]); ?>" type="checkbox" name="waiting" id="waiting<?php print($waiting["membershipNumber"]); ?>" value="<?php print($waiting["membershipNumber"]); ?>" />
                </td>
            </tr>

            <?php 
            
        }
     
        ?>
    
    </tbody>
</table>

<button id="setBeginner" class="jQueryHidden">Set Beginner for <span class="selectedMemberCount">0</span> Members</button>
<button id="setExperienced" class="jQueryHidden">Set Experienced for <span class="selectedMemberCount">0</span> Members</button>
<button id="inviteWaiting" class="jQueryHidden">Invite <span class="selectedMemberCount">0</span> Members</button>
<button id="replied" class="jQueryHidden">Mark <span class="selectedMemberCount">0</span> Members Replied</button>
<button id="confirmed" class="jQueryHidden">Mark <span class="selectedMemberCount">0</span> Members Confirmed</button>
<button id="complete" class="jQueryHidden">Mark <span class="selectedMemberCount">0</span> Members Complete</button>

<script type="text/javascript">
    
    var pagesize = 15; 
    
    $(document).ready(function() {
        
        $(".selectedMember").click(function(event) {

            $(".selectedMemberCount").html($("input.selectedMember:checked").length);
            
            var theClass = $(this).attr("class");
            var status = theClass.replace("selectedMember type", "");
                        
            // We should only be allowing members at the same status to be checked
            if ($(this).prop("checked") && $("input.selectedMember:checked").length == 1) {
            
                $("input[class!='" + theClass + "']").hide();
              
                $("#setBeginner").hide();
                $("#setExperienced").hide();
                $("#inviteWaiting").hide();
                $("#replied").hide();
                $("#confirmed").hide();
                $("#complete").hide();     

                if (status == "W100") {
                    $("#setBeginner").show();
                    $("#setExperienced").show();
                } else if (status == "W200" || status == "W201") {
                    $("#inviteWaiting").show();
                } else if (status == "W300" || status == "W301") {
                    $("#replied").show();
                    $("#confirmed").show();
                    $("#complete").show();
                } else if (status == "W350" || status == "W351") {
                    $("#confirmed").show();
                    $("#complete").show();
                } else if (status == "W400" || status == "W401") {
                    $("#complete").show();
                } else if (status == "W500" || status == "W501") {
                    
                }    

            } else if ($("input.selectedMember:checked").length == 0) {
                
                $("input.selectedMember").show();

                $("#setBeginner").hide();
                $("#setExperienced").hide();
                $("#inviteWaiting").hide();
                $("#replied").hide();
                $("#confirmed").hide();
                $("#complete").hide();

            }

        });
        
        $("#inviteWaiting").click(function(event) {

            event.preventDefault();

            var url = "index.php?action=cwaitinginvitebeginners";
            var seqn = 1;

            $("input[type='checkbox']").each(function() {

                var memberId = $(this).val();
                if ($(this).prop("checked")) {
                    url += "&member" + seqn + "=" + memberId;
                    seqn++;
                }

            });

            url = url + "&count=" + (seqn - 1);

            if (seqn > 1) {
                location.href = url;
            } else {
                alert("No Members Selected");
            }
            return false;

        });
        
        $("#changeStatus").click(function(event) {

            event.preventDefault();

            var url = "index.php?action=cwaitingchangestatus";
            var seqn = 1;

            $("input[type='checkbox']").each(function() {

                var memberId = $(this).val();
                if ($(this).prop("checked")) {
                    url += "&member" + seqn + "=" + memberId;
                    seqn++;
                }

            });

            url = url + "&count=" + (seqn - 1);

            if (seqn > 1) {
                location.href = url;
            } else {
                alert("No Members Selected");
            }
            return false;

        });
        
        $("#waitingList tbody").click(function(event) {
      
            var target = event.target;
            var jTarget = $(target);
            var className = target.className;
            var row = jTarget.closest("tr");
            var cell = jTarget.closest("td");
            var key = row.attr("id");

            bConsole(target);
            bConsole(jTarget);
            bConsole(className);
            bConsole(row);
            bConsole(cell);
            bConsole(key);

            if (jTarget.is("input")) {

                return true;
                
            } else if (jTarget.hasClass("pmUser")) {
                
                return true;
                
            } else {
                
                if (!row.find("td:first").hasClass("dataTables_empty")) {
                    location.href = "index.php?action=cwaitingdetail&membershipNumber=" + key + "&from=ymembership";
                }
                
            }
            
            return false;

        });
        
        var waitingListTable = defineTableNew(
                                    "#waitingList", 
                                    [[7,"asc"]], 
                                    [8], 
                                    [7], 
                                    "YCC_COMMITTEEWAITING", 
                                    "No Entries Found", 
                                    tableLayout, 
                                    true,
                                    30,
                                    function () {
                                        $("#waitingList th[title][title!=''], #waitingList td[title][title!='']").tooltipster({
                                            animation: 'grow'
                                        }).addClass("hoverable");
                                    }
                                );
        
        $("#search11").html(fnCreateSelect( waitingListTable.fnGetColumnData(6, true, false, false)));

	$("#search11 select").change(function() {
            if ($(this).val() != "") {
                waitingListTable.fnFilter("^" + $(this).val() + "$", 6, true);
            } else {
                waitingListTable.fnFilter($(this).val(), 6, true);
            }
        });
        
        waitingListTable.fnRestoreAllFilters();
        
    });

</script>

<?php 
    
}

?>