<?php

function template_main() {
    
    global $context, $settings, $options, $txt, $scripturl;
    
    $new = $context["new"];
    $waiting = $context["waiting"];
    $invited = $context["invited"];
    
    ?>

   <h1>New</h1>
    
   <table id="waitingListNew" class="membershipTable">
    <thead>
        <tr>
            <th>Name</th>
            <th>Status</th>
            <th>Date Added</th>
        </tr>
        <tr class="search">
            <th><input type="text" name="search0" id="search0" /></th>
            <th>&nbsp;</th>
            <th>&nbsp;</th>
        </tr>
    </thead>
    <tfoot>
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
    </tfoot>
    <tbody>
    
        <?php
        
        foreach ($new as $person) {
    
            ?>
        
            <tr>
                <td><?php print($person["memberFirstName"] . " " . $person["memberLastName"]); ?></td>
                <td><?php print(getStatusDesc($person["membershipStatus"])); ?></td>
                <td><?php print($person["createdDate"]); ?>:::<?php print($person["createdDateF"]); ?>:::<?php print($person["createdDate"]); ?></td>
            </tr>

            <?php 
     
            
        }
     
        ?>
    
    </tbody>
</table>

   <h1>Waiting</h1>
    
   <table id="waitingListWaiting" class="membershipTable">
    <thead>
        <tr>
            <th>Name</th>
            <th>Status</th>
            <th>Date Added</th>
        </tr>
        <tr class="search">
            <th><input type="text" name="search0" id="search0" /></th>
            <th>&nbsp;</th>
            <th>&nbsp;</th>
        </tr>
    </thead>
    <tfoot>
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
    </tfoot>
    <tbody>
    
        <?php
        
        foreach ($waiting as $person) {
    
            ?>
        
            <tr>
                <td><?php print($person["memberFirstName"] . " " . $person["memberLastName"]); ?></td>
                <td><?php print(getStatusDesc($person["membershipStatus"])); ?></td>
                <td><?php print($person["createdDate"]); ?>:::<?php print($person["createdDateF"]); ?>:::<?php print($person["createdDate"]); ?></td>
            </tr>

            <?php 
     
            
        }
     
        ?>
    
    </tbody>
</table>
   
     <h1>Invited</h1>
    
   <table id="waitingListInvited" class="membershipTable">
    <thead>
        <tr>
            <th>Name</th>
            <th>Status</th>
            <th>Date Added</th>
        </tr>
        <tr class="search">
            <th><input type="text" name="search0" id="search0" /></th>
            <th>&nbsp;</th>
            <th>&nbsp;</th>
        </tr>
    </thead>
    <tfoot>
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
    </tfoot>
    <tbody>
    
        <?php
        
        foreach ($invited as $person) {
    
            ?>
        
            <tr>
                <td><?php print($person["memberFirstName"] . " " . $person["memberLastName"]); ?></td>
                <td><?php print(getStatusDesc($person["membershipStatus"])); ?></td>
                <td><?php print($person["createdDate"]); ?>:::<?php print($person["createdDateF"]); ?>:::<?php print($person["createdDate"]); ?></td>
            </tr>

            <?php 
     
            
        }
     
        ?>
    
    </tbody>
</table>
     
<script type="text/javascript">
    
    var pagesize = 15; 
    
    $(document).ready(function() {
        
        var waitingListTableNew = defineTableNew(
                                    "#waitingListNew", 
                                    [[2,"asc"]], 
                                    [], 
                                    [2], 
                                    "YCC_YCCWAITING_NEW", 
                                    "No Entries Found", 
                                    tableLayout, 
                                    true,
                                    30,
                                    function () {
                                        
                                    }
                                );
        waitingListTableNew.fnRestoreAllFilters();
        
        var waitingListTableWaiting = defineTableNew(
                                    "#waitingListWaiting", 
                                    [[2,"asc"]], 
                                    [], 
                                    [2], 
                                    "YCC_YCCWAITING_WAITING", 
                                    "No Entries Found", 
                                    tableLayout, 
                                    true,
                                    30,
                                    function () {
                                        
                                    }
                                );
        waitingListTableWaiting.fnRestoreAllFilters();
        
        var waitingListTableInvited = defineTableNew(
                                    "#waitingListInvited", 
                                    [[2,"asc"]], 
                                    [], 
                                    [2], 
                                    "YCC_YCCWAITING_INVITED", 
                                    "No Entries Found", 
                                    tableLayout, 
                                    true,
                                    30,
                                    function () {
                                        
                                    }
                                );
        waitingListTableInvited.fnRestoreAllFilters();
        
    });

</script>

<?php 
    
}

?>