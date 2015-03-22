<?php

function template_main() {
    
    global $context, $settings, $options, $txt, $scripturl;
    
    ?>

<form id="memberRecord">

    <fieldset>
        <legend>Addressing Details</legend>

        <label>Send To</label>
        <span class="inputContainer2"><?php print($context["toAddresses"]); ?></span><br /><br />

        <label>From Address<span class="required">*</span></label>
        <span class="inputContainer"><input type="text" name="memberLastName" id="memberLastName" value="<?php print($memberArray["memberLastName"]); ?>" /></span>
        <label>Reply To Address<span class="required">*</span></label>
        <span class="inputContainer"><input type="text" name="memberLastName" id="memberLastName" value="<?php print($memberArray["memberLastName"]); ?>" /></span><br />
            
    </fieldset>
    
    <fieldset>
        <legend>Course Details</legend>

        <label>Course<span class="required">*</span></label>
        <span class="inputContainer"><input type="text" name="memberLastName" id="memberLastName" value="<?php print($memberArray["memberLastName"]); ?>" /></span>
        <label>Cut Off<span class="required">*</span></label>
        <span class="inputContainer"><input type="text" name="memberLastName" id="memberLastName" value="<?php print($memberArray["memberLastName"]); ?>" /></span><br />

        <label>Adult Cost (£)<span class="required">*</span></label>
        <span class="inputContainer"><input type="text" name="memberLastName" id="memberLastName" value="<?php print($memberArray["memberLastName"]); ?>" /></span>
        <label>Junior Cost (£)<span class="required">*</span></label>
        <span class="inputContainer"><input type="text" name="memberLastName" id="memberLastName" value="<?php print($memberArray["memberLastName"]); ?>" /></span><br />

    </fieldset>
    
    
    <fieldset>
        <legend>Message Details</legend>

        <label class="inputContainer2root">Subject<span class="required">*</span></label><br />
        <span class="inputContainer2root"><input type="text" name="memberLastName" id="memberLastName" value="<?php print($memberArray["memberLastName"]); ?>" /></span><br />
    
        <label class="inputContainer2root">Body<span class="required">*</span></label><br />
        <span class="inputContainer2root"><textarea name="experience" id="experience" rows="6" cols="30" style="width: 100%;"><?php print($values["experience"]); ?></textarea><br />
            
    </fieldset>
    
<button id="inviteWaiting">Send Invites</button>

<script type="text/javascript">
    
    $(document).ready(function() {
        
        
    });

</script>

<?php 
    
}

?>