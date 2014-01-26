<?php

function template_main() {
    
    global $context, $settings, $options, $txt, $scripturl;
    
    $membersU = $context["membersU"];
    $membersL = $context["membersL"];
    $accountsU = $context["accountsU"];
    $accountsL = $context["accountsL"];

    ?>

    <label>YCC Member</label>
    <span class="inputContainer2"><select name="membershipNumber" id="membershipNumber">
        <option value="">Select Member</option>
        <?php 
        
        foreach ($membersU as $member) {
            
            print("<option value=\"" . $member["membershipNumber"] . "\">" . $member["memberName"] . "</option>");
            
        }
        
        ?> 
        <option value="">------------------------------------------------------------</option>
        <?php 
        
        foreach ($membersL as $member) {
            
            print("<option value=\"" . $member["membershipNumber"] . "\">" . $member["memberName"] . "</option>");
            
        }
        
        ?> 
        
    </select></span><br />

    <label>Forum ID</label>
    <span class="inputContainer2"><select name="forumId" id="forumId">
        <option value="">Select Account</option>
        <?php 
        
        foreach ($accountsU as $account) {
            
            print("<option value=\"" . $account["id_member"] . "\">" . $account["real_name"] . "</option>");
            
        }
        
        ?>
        <option value="">------------------------------------------------------------</option>
        <?php
        
        foreach ($accountsL as $account) {
            
            print("<option value=\"" . $account["id_member"] . "\">" . $account["real_name"] . "</option>");
            
        }
        
        ?>
        
    </select></span><span class="inputContainer" id="forumLink">&nbsp;</span><br />
    
    <label>Primary</label>
    <span class="inputContainer2"><select name="primary" id="primary">
        <option value="1">Yes</option>
        <option value="0">No</option>
    </select></span><br />
       
    <button id="link">Link</button>

<script type="text/javascript">
    
    $(document).ready(function() {
        
        $("#forumId").change(function(event) {
            
            $("#forumLink").html("<a href=\"index.php?action=profile;u=" + $(this).val() + "\">View Profile</a>");
            
        });
        
        $("#link").click(function(event) {
            
            event.preventDefault();
            
            $.ajax({
                type:    "POST",
                url:     "index.php",
                data:    "action=clinkersubmit&membershipNumber=" + $("#membershipNumber").val() + "&forumId=" + $("#forumId").val() + "&primary=" + $("#primary").val(),
                cache:   false,
                success: function(data) {
                    location.href = "index.php?action=clinker";
                }
            });
            
            return false;
            
        });
        
    });

</script>

<?php 
    
}

?>