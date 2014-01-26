<?php

function template_main() {
    
    global $context;
    $contacts = $context["contacts"];
    
?>

<h1><?php print($context["pageTitle"]); ?></h1><br />

<table id="emergencyContactList" class="membershipTable">
    <thead>
        <tr>
            <th>Membership No.</th>
            <th>Member Name</th>
            <th>Primary Contact</th>
            <th>Primary Contact No's</th>
            <th>Secondary Contact</th>
            <th>Secondary Contact No's</th>
        </tr>
        <tr class="search">
            <th><input type="text" name="search0" id="search0" /></th>
            <th><input type="text" name="search1" id="search1" /></th>
            <th>&nbsp;</th>
            <th>&nbsp;</th>
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
        </tr>
    </tfoot>
    <tbody>
    
        <?php
        
        foreach ($contacts as $contact) {
    
            ?>
        
            <tr id="<?php print($contact["membershipNumber"]); ?>">
                <td><?php print($contact["membershipNumber"]); ?></td>
                <td><?php print($contact["memberName"]); ?></td>
                <td><?php if ($contact["primaryContactName"] != "") { print($contact["primaryContactName"] . " (" . $contact["primaryContactRelationship"] . ")"); } ?></td>
                <td><?php 
                    if ($contact["primaryContactName"] != "") { print($contact["primaryContactPhone1"]); } 
                    if ($contact["primaryContactPhone2"] != "") { print(" / " . $contact["primaryContactPhone2"]); } 
                ?></td>
                <td><?php if ($contact["secondaryContactName"] != "") { print($contact["secondaryContactName"] . " (" . $contact["secondaryContactRelationship"] . ")"); } ?></td>
                <td><?php 
                    if ($contact["secondaryContactName"] != "") { print($contact["secondaryContactPhone1"]); } 
                    if ($contact["secondaryContactPhone2"] != "") { print(" / " . $contact["secondaryContactPhone2"]); } 
                ?></td>
            </tr>

            <?php 
     
            
        }
     
        ?>
    
    </tbody>
</table>

<script type="text/javascript">
        
    $(document).ready(function() {
        
        var contactsListTable = defineTableNew("#emergencyContactList", [], [0], [], "YCC_COMMITTEEEMERGENCYCONTACTLIST", "No Contacts Found", tableLayout, true, 250);
        contactsListTable.fnRestoreAllFilters();
        
    });

</script>

<?php 
    
}

?>