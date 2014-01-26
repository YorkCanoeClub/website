<?php

function template_main() {
    
    global $context, $settings, $options, $txt, $scripturl;
    
    $members = $context["memberArray"];

    ?>

<h1>Renew YCC Memberships</h1>
<div id="renewContainer">
    
    <p>Please ensure all the details below are correct. The system will automatically calculate the cheapest way for you to join if there is more than one membership and indicate if 'family' or 'family plus extras' membership is being used. Once you are happy with the details please click confirm.</p>
    <p>As a member of York Canoe Club you agree to the following:</p>
    <ul>
        <li>I understand that canoeing and kayaking are adventurous and potentially dangerous sports, and that I undertake this activity at my own risk.</li>
        <li>I understand that York Canoe Club will hold my personal information on a computer system for the duration of my membership</li>
        <li>I understand that my emergency contact information will be held by trip organisers and river leads whilst I attend organised club trips. This information will only be used in the case of an emergency</li>
        <li>I understand that it is my responsibility to notify a committee member and appropriate river leads of any medical condition which may affect my participation in YCC activities.</li>
    </ul>

    <table id="renewList" class="membershipTable">
        <thead>
            <tr>
                <th>Member</th>
                <th class="number">Individual Cost</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
        </tfoot>
        <tbody>

            <?php

            foreach ($members as $member) {

                ?>

                <tr class="member" id="<?php print($member["membershipNumber"]); ?>">
                    <td>
                        <?php 
                        print($member["membershipNumber"] . "/" . $member["membershipType"] . " - " . $member["memberFirstName"] . " " . $member["memberLastName"] . ", Age: " . $member["calculatedAge"]); 
                        if ($member["memberBcuNumber"] != "") { 
                            print(", BCU Number: " . $member["memberBcuNumber"]);
                        } ?>
                    </td>
                    <td class="number">&pound;<?php print($member["membershipCost"]); ?></td>
                </tr>

                <?php 


            }

            ?>

            <tr>
                <td class="totals">Total<?php if ($context["family"]) { print(" - Family Membership"); } ?><?php if ($context["familyplus"]) { print(" - Family Membership + Extra Adults"); } ?></td>
                <td class="number totals">&pound;<?php print($context["totalCost"]); ?></td>
            </tr>
        </tbody>
    </table>

    <button id="confirm">Confirm</button>
    <button id="cancel">Cancel</button>

</div>

<script type="text/javascript">
    
    var tableLayout = "<'clear'>rt<'bottom'p><'clear'>";
    
    $(document).ready(function() {
        
        $("#renewList").dataTable({
                aaSorting: [],
                aoColumnDefs: [],
                bSortCellsTop: true,
                bProcessing: true,
                bPaginate: false,
                bLengthChange: false,
                bFilter: false,
                bSort: false,
                bInfo: false,
                bAutoWidth: false,
                bStateSave: true,
                bSortClasses: false,
                iDisplayLength: 25,
                iCookieDuration: 60*60*24, /* 1 day */
                sCookiePrefix: "YCC_",
                oLanguage: {
                        sZeroRecords: "No Members Found",
                        oPaginate: {
                                sFirst: "&lt;&lt;",
                                sLast: "&gt;&gt;",
                                sPrevious: "&lt;",
                                sNext: "&gt;"
                        }
                },
                fnDrawCallback: function() {

                },
                sPaginationType: "full_numbers",
                sDom: tableLayout,
                fnInitComplete: function() {
                        $("#memberList").show();
                }
        });
        
        $("#cancel").click(function(event) {
       
            event.preventDefault();
            location.href = "index.php?action=ymembership";
            return false;

        });

        $("#confirm").click(function(event) {
            
            event.preventDefault();
        
            var url = "action=ymembershiprenewsubmit";
            var seqn = 1;

            $("tr.member").each(function() {

                var memberId = $(this).attr("id");
                url += "&member" + seqn + "=" + memberId;
                seqn++;

            });

            url = url + "&count=" + (seqn - 1);

            $.ajax({
                type:    "POST",
                url:     "index.php",
                data:    url,
                cache:   false,
                success: function(data) {
                    if (data == "*ERROR") {
                        alert("Update Failed ... Please try again later");
                    } else {
                        $("#renewContainer").html(data);
                    }
                }
            });

            return false;  

        });

    });

</script>

<?php 
    
}

?>