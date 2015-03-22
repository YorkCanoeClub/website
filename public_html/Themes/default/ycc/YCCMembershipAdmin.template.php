<?php

function template_main() {
    
    global $context, $settings, $options, $txt, $scripturl;
    
    $forumId = $context["forumId"];
    $membersU = $context["membersU"];
    $members = $context["memberArray"];
    
    ?>


<h1>Your YCC Memberships (Admin)</h1>

<label>Forum Account <?php print($forumId); ?></label>
<span class="inputContainer"><select name="forumId" id="forumId">
    <option value="">Select Forum Account</option>
    <?php 

    foreach ($membersU as $member) {

        print("<option value=\"" . $member["id_member"] . "\"");
        if ($forumId == $member["id_member"]) {
            print(" selected");
        }
        print(">" . $member["real_name"] . "</option>");

    }

    ?> 

</select></span><br />

<table id="memberList" class="membershipTable">
    <thead>
        <tr>
            <th class="ignoreSize">&nbsp;</th>
            <th class="ignoreSize">No.</th>
            <th class="ignoreSize">Primary</th>
            <th>Name</th>
            <th class="ignoreSize">Expires</th>
            <th class="ignoreSize">Status</th>
            <th class="ignoreSize">Complete</th>
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
        </tr>
    </tfoot>
    <tbody>
    
        <?php
        
        foreach ($members as $member) {
    
            ?>
        
            <tr id="<?php print($member["membershipNumber"]); ?>" class="clickableRow">
                <td>
                    <?php if ($member["membershipStatus"] != 350 && $member["membershipStatus"] != 500 && $member["complete"] == "1") { ?>
                    <input class="selectedMember" name="selectedMember" type="checkbox" value="<?php print($member["membershipNumber"]); ?>" />
                    <?php } ?>
                </td>
                <td><?php print($member["membershipNumber"] . "/" . $member["membershipType"]); ?></td>
                <td class="centre"><span class="tick<?php print($member["primary"]); ?>">&nbsp;</span></td>
                <td><?php print($member["memberFirstName"] . " " . $member["memberLastName"]); ?></td>
                <td><?php print($member["membershipExpiresF"]); ?></td>
                <td><?php print(getStatusDesc($member["membershipStatus"])); ?></td>
                <td class="centre"><span class="tick<?php print($member["complete"]); ?>">&nbsp;</span></td>
            </tr>

            <?php 
     
            
        }
     
        ?>
    
    </tbody>
</table>

<button id="renewMembers">Renew Selected <span class="selectedMemberCount">0</span> Members</button>

<script type="text/javascript">
    
    $(document).ready(function() {
        
        var memberListTable = defineTableNew("#memberList", [], [0,1,2,3,4,5,6], [], "YCC_MEMBERSLIST", "No Members Found", tableLayout, true, 25);
        memberListTable.fnRestoreAllFilters();

        $("#forumId").change(function() {
            
            location.href = "index.php?action=ymembershipadmin&forumid=" + $(this).val();    
        
        });
        
        $(".selectedMember").click(function(event) {

            $(".selectedMemberCount").html($("input.selectedMember:checked").length);

        });
        
        $("#memberList tbody").click(function(event) {
      
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
                
            } else {
             
                if (!row.find("td:first").hasClass("dataTables_empty")) {
                    location.href = "index.php?action=ymembershipdetail&yno=" + key + "&from=ymembership";
                }
                
            }
            
            return false;

        });

        $("#renewMembers").click(function(event) {

            event.preventDefault();

            var url = "index.php?action=ymembershiprenew";
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
                alert("No Memberships Selected");
            }
            return false;

        });

    });

</script>

<?php 
    
}

?>