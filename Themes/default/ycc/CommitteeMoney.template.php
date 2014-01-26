<?php

function template_main() {
    
    global $context, $settings, $options, $txt, $scripturl;
    
    $payments = $context["payments"];
    $manager = $context["manager"];
    $users = $context["users"];
   
    ?>

    <label>Forum ID</label>
    <span class="inputContainer"><select name="userId" id="userId">
        <option value="">Select Account</option>
        <?php 
        
        foreach ($users as $user) {
            
            print("<option value=\"" . $user["id_member"] . "\">" . $user["member_name"] . "</option>");
            
        }
        
        ?>
    </select></span><br />
    
    <label>Payment Reason</label>
    <span class="inputContainer"><input type="text" name="paymentReason" id="paymentReason" value="" /></span>
   
    <label>Payment Amount</label>
    <span class="inputContainer"><input type="text" name="paymentAmount" id="paymentAmount" value="" /></span><button id="create">Create</button>
   
    <table id="paymentList" class="membershipTable">
    <thead>
        <tr>
            <th>User</th>
            <th>Payment Reference</th>
            <th>Reason</th>
            <th>Date / Time</th>
            <th>Amount</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
        <tr class="search">
            <th><input type="text" name="search0" id="search0" /></th>
            <th><input type="text" name="search1" id="search1" /></th>
            <th><span id="search2">&nbsp;</span></th>
            <th>&nbsp;</th>
            <th>&nbsp;</th>
            <th><span id="search5">&nbsp;</span></th>
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
        </tr>
    </tfoot>
    <tbody>
    
        <?php
        
        foreach ($payments as $payment) {
    
            ?>
        
            <tr id="<?php print($payment["paymentId"]); ?>">
                <td><?php print($payment["paymentUser"]); ?></td>
                <td><?php print($payment["paymentReference"]); ?></td>
                <td><?php print($payment["paymentReason"]); ?></td>
                <td><?php print($payment["paymentDateF"]); ?></td>
                <td class="number">&pound;<?php print($payment["paymentValue"]); ?></td>
                <td><?php print(getPaymentStatusDesc($payment["paymentStatus"])); ?></td>
                <td><?php if ($manager && $payment["paymentStatus"] == 100) { ?><a href="#" class="markPaid">Mark Paid</a><?php } ?></td>
            </tr>

            <?php 
     
            
        }
     
        ?>
    
    </tbody>
</table>

<script type="text/javascript">
    
    var pagesize = 25; 
    
    $(document).ready(function() {
        
        $("#create").click(function(event) {
            
            event.preventDefault();
            
            $.ajax({
                type:    "POST",
                url:     "index.php",
                data:    "action=cmoneysubmit&mode=*CREATE&userId=" + $("#userId").val() + "&userName=" + $("#userId option:selected").html() + "&paymentReason=" + $("#paymentReason").val() + "&paymentAmount=" + $("#paymentAmount").val(),
                cache:   false,
                success: function(data) {
                    location.href = "index.php?action=cmoney";
                }
            });
            
            return false;
            
        });
        
        $(".markPaid").click(function(event) {
            
            event.preventDefault();
            
            var paymentId = $(event.target).parent().parent().prop("id");
            
            $.ajax({
                type:    "POST",
                url:     "index.php",
                data:    "action=cmoneysubmit&mode=*PAID&paymentId=" + paymentId,
                cache:   false,
                success: function(data) {
                    location.href = "index.php?action=cmoney";
                }
            });
            
            return false;
            
        });
        
        var paymentListTable = defineTableNew("#paymentList", [], [6], [], "YCC_COMMITTEEMONEY", "No Payments Found", tableLayout, true, 25);
        paymentListTable.fnRestoreAllFilters();
        
        $("#search2").html(fnCreateSelect( paymentListTable.fnGetColumnData(2, true, false, false)));
	  
	$("#search2 select").change(function() {
            paymentListTable.fnFilter($(this).val(), 2);
        });
        
        $("#search5").html(fnCreateSelect( paymentListTable.fnGetColumnData(5, true, false, false)));
	  
	$("#search5 select").change(function() {
            paymentListTable.fnFilter($(this).val(), 5);
        });
        
        paymentListTable.fnRestoreAllFilters();
        
    });

</script>

<?php 
    
}

?>