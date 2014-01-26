<?php

function template_main() {
    
    global $context;
    $waitingDetails = $context["waitingDetails"];
    $contacts = $context['memberContacts'];
    if (isset($context["errors"])) {
        $errors = $context["errors"];
        $values = $context["values"];
    }
    $membersU = $context['membersU'];
    
    ?>

<?php if (!$context['user']['is_guest']) { ?>
    
    <?php

        if ($waitingCount > 0) {
    
            print("<span class=\"warnings\">");
            print("$waitingCount waiting list entries are already attached to this forum account. If you are adding someone else please continue otherwise please relax and wait to be invited!<br />");
            print("</span>");
        
        }
        
        print("<span class=\"errors\">");
        
        foreach ($errors as $error) {
        
            if ($error[0] != "") {
                
                ?>

                    <script>
                        $(document).ready(function() {
                            $("label[for='<?php print($error[0]); ?>']").addClass("error");
                        });
                    </script>        
                <?php 
                
            }
            print($error[1] . "<br />");
            
        }
        
        print("</span><br />");
    
    ?>

    <form id="memberRecord">

        <input type="hidden" name="membershipNumber" id="membershipNumber" value="<?php print($waitingDetails["membershipNumber"]); ?>" />
        <input type="hidden" name="membershipType" id="membershipType" value="<?php print($waitingDetails["membershipType"]); ?>" />

        <fieldset>
            <legend>Forum</legend>

            <label for="memberFirstName">Forum Profile</label>
            <span class="inputContainer"><select name="forumId" id="forumId">
                <option value="">None</option>
                <?php 

                foreach ($membersU as $member) {

                    print("<option value=\"" . $member["id_member"] . "\"");
                    if ($waitingDetails["forumId"] == $member["id_member"]) {
                        print(" selected");
                    }
                    print(">" . $member["real_name"] . "</option>");

                }

                ?> 

            </select></span>
            
        </fieldset>
        
        <fieldset>
            <legend>About You</legend>

            <label for="memberFirstName">First Name(s)<span class="required">*</span></label>
            <span class="inputContainer"><input type="text" name="memberFirstName" id="memberFirstName" value="<?php print($waitingDetails["memberFirstName"]); ?>" /></span>
            <label for="memberLastName">Last Name<span class="required">*</span></label>
            <span class="inputContainer"><input type="text" name="memberLastName" id="memberLastName" value="<?php print($waitingDetails["memberLastName"]); ?>" /></span><br />

            <label for="memberKnownAs">Known As</label>
            <span class="inputContainer"><input type="text" name="memberKnownAs" id="memberKnownAs" value="<?php print($waitingDetails["memberKnownAs"]); ?>" /></span>
            <label for="memberGender">Gender<span class="required">*</span></label>
            <span class="inputContainer">
                <select name="memberGender" id="memberGender">
                    <option value="">Please Select</option>
                    <option value="M" <?php if ($waitingDetails["memberGender"] == "M") { print("selected=\"selected\""); } ?>>Male</option>
                    <option value="F" <?php if ($waitingDetails["memberGender"] == "F") { print("selected=\"selected\""); } ?>>Female</option>
                </select>
            </span><br />

            <label for="memberDob">Date of Birth<span class="required">*</span></label>
            <span class="inputContainer"><input type="text" name="memberDob" id="memberDob" value="<?php print($waitingDetails["memberDobF"]); ?>" /></span>
            <label for="memberBcuNumber">BCU No.<span class="required">&dagger;</span></label>
            <span class="inputContainer"><input type="text" name="memberBcuNumber" id="memberBcuNumber" value="<?php print($waitingDetails["memberBcuNumber"]); ?>" /></span><br /><br />

            <label for="memberAddress">Address<span class="required">*</span></label>
            <span class="inputContainer2"><input type="text" name="memberAddress" id="memberAddress" value="<?php print($waitingDetails["memberAddress"]); ?>" /></span><br />
            <label for="memberPostcode">Postcode<span class="required">*</span></label>
            <span class="inputContainer"><input type="text" name="memberPostcode" id="memberPostcode" value="<?php print($waitingDetails["memberPostcode"]); ?>" /></span><br /><br />

            <label for="memberPhone1">Primary Phone<span class="required">*</span></label>
            <span class="inputContainer"><input type="text" name="memberPhone1" id="memberPhone1" value="<?php print($waitingDetails["memberPhone1"]); ?>" /></span>
            <label for="memberPhone2">Secondary Phone</label>
            <span class="inputContainer"><input type="text" name="memberPhone2" id="memberPhone2" value="<?php print($waitingDetails["memberPhone2"]); ?>" /></span><br />
            <label for="memberEmail">Email<span class="required">*</span></label>
            <span class="inputContainer2"><input type="text" name="memberEmail" id="memberEmail" value="<?php print($waitingDetails["memberEmail"]); ?>" /></span><br />

        </fieldset>

        <fieldset>
            <legend>Contact Preferences</legend>

            <label for="memberContact">Can We Contact You?</label>
            <span class="inputContainer">
                <select name="memberContact" id="memberContact">
                    <option value="Y" <?php if ($waitingDetails["memberContact"] == "Y") { print("selected=\"selected\""); } ?>>Yes</option>
                    <option value="N" <?php if ($waitingDetails["memberContact"] == "N") { print("selected=\"selected\""); } ?>>No</option>
                </select>
            </span><br />
            <p class="small">Your contact details will not be shared with anyone and will be used for club communication only.</p>

        </fieldset>

        <fieldset>
            <legend>Primary Emergency Contact</legend>

            <label for="contactsName1">Name<span class="required">*</span></label>
            <span class="inputContainer"><input type="text" name="contactsName1" id="contactsName1" value="<?php print($contacts[0]["contactsName"]); ?>" /></span>
            <label for="contactsRelationship1">Relationship<span class="required">*</span></label>
            <span class="inputContainer"><input type="text" name="contactsRelationship1" id="contactsRelationship1" value="<?php print($contacts[0]["contactsRelationship"]); ?>" /></span><br />
            <label for="contactsPhone11">Primary Phone<span class="required">*</span></label>
            <span class="inputContainer"><input type="text" name="contactsPhone11" id="contactsPhone11" value="<?php print($contacts[0]["contactsPhone1"]); ?>" /></span>
            <label for="contactsPhone21">Secondary Phone</label>
            <span class="inputContainer"><input type="text" name="contactsPhone21" id="contactsPhone21" value="<?php print($contacts[0]["contactsPhone2"]); ?>" /></span><br /><br />

        </fieldset>


        <fieldset>
            <legend>Secondary Emergency Contact</legend>

            <label for="contactsName2">Name</label>
            <span class="inputContainer"><input type="text" name="contactsName2" id="contactsName2" value="<?php print($contacts[1]["contactsName"]); ?>" /></span>
            <label for="contactsRelationship2">Relationship</label>
            <span class="inputContainer"><input type="text" name="contactsRelationship2" id="contactsRelationship2" value="<?php print($contacts[1]["contactsRelationship"]); ?>" /></span><br />
            <label for="contactsPhone12">Primary Phone</label>
            <span class="inputContainer"><input type="text" name="contactsPhone12" id="contactsPhone12" value="<?php print($contacts[1]["contactsPhone1"]); ?>" /></span>
            <label for="contactsPhone22">Secondary Phone</label>
            <span class="inputContainer"><input type="text" name="contactsPhone22" id="contactsPhone22" value="<?php print($contacts[1]["contactsPhone2"]); ?>" /></span><br /><br />

        </fieldset>

        <fieldset>
            <legend>Additional Information</legend>

            <label for="swimQ" class="inputContainer2root">Can you swim 25 metres unaided?<span class="required">*</span></label><br />
            <span class="inputContainer">
                <select name="swimQ" id="swimQ">
                    <option value="">[ Please Select ]</option>
                    <option value="*NO" <?php if ($waitingDetails["additionalSwimQ"] == "*NO") { print("selected=\"selected\""); } ?>>No</option>
                    <option value="*YES" <?php if ($waitingDetails["additionalSwimQ"] == "*YES") { print("selected=\"selected\""); } ?>>Yes</option>
                </select>
            </span><br />
            
            <label for="kitQ" class="inputContainer2root">Do you own all your own kit?<span class="required">*</span></label><br />
            <span class="inputContainer">
                <select name="kitQ" id="kitQ">
                    <option value="">[ Please Select ]</option>
                    <option value="*NO" <?php if ($waitingDetails["additionalKitQ"] == "*NO") { print("selected=\"selected\""); } ?>>No</option>
                    <option value="*YES" <?php if ($waitingDetails["additionalKitQ"] == "*YES") { print("selected=\"selected\""); } ?>>Yes</option>
                </select>
            </span><br />
            
            <label for="experienceQ" class="inputContainer2root">Do you have any prior experience in a <em>kayak</em>?<span class="required">*</span></label><br />
            <span class="inputContainer">
                <select name="experienceQ" id="experienceQ">
                    <option value="">[ Please Select ]</option>
                    <option value="*NO" <?php if ($waitingDetails["additionalExperienceQ"] == "*NO") { print("selected=\"selected\""); } ?>>No</option>
                    <option value="*YES" <?php if ($waitingDetails["additionalExperienceQ"] == "*YES") { print("selected=\"selected\""); } ?>>Yes</option>
                </select>
            </span><br />
            <span id="experienceHide" class="jQueryHidden">
                <label for="experience" class="inputContainer2root">Please provide as much detail as possible about your prior experience in a <em>kayak</em>.</label><br />
                <span class="inputContainer2root"><textarea name="experience" id="experience" rows="6" cols="30" style="width: 100%;"><?php print($waitingDetails["additionalExperience"]); ?></textarea></span><br />
            </span>
            
            <label for="qualificationsQ" class="inputContainer2root">Do you have any BCU qualifications in <em>kayak</em>?<span class="required">*</span></label><br />
            <span class="inputContainer">
                <select name="qualificationsQ" id="qualificationsQ">
                    <option value="">[ Please Select ]</option>
                    <option value="*NO" <?php if ($waitingDetails["additionalQualificationsQ"] == "*NO") { print("selected=\"selected\""); } ?>>No</option>
                    <option value="*YES" <?php if ($waitingDetails["additionalQualificationsQ"] == "*YES") { print("selected=\"selected\""); } ?>>Yes</option>
                </select>
            </span><br />
            <span id="qualificationsHide" class="jQueryHidden">
                <label for="qualifications" class="inputContainer2root">What BCU qualifications do you have in <em>kayak</em>?</label><br />
                <span class="inputContainer2root"><textarea name="qualifications" id="qualifications" rows="6" cols="30" style="width: 100%;"><?php print($waitingDetails["additionalQualifications"]); ?></textarea></span><br />    
            </span>
                
            <label for="medicalQ" class="inputContainer2root">Do you have any medical conditions that could affect your ability to kayak or swim?<span class="required">*</span></label><br />
            <span class="inputContainer">
                <select name="medicalQ" id="medicalQ">
                    <option value="">[ Please Select ]</option>
                    <option value="*NO" <?php if ($waitingDetails["additionalMedicalQ"] == "*NO") { print("selected=\"selected\""); } ?>>No</option>
                    <option value="*YES" <?php if ($waitingDetails["additionalMedicalQ"] == "*YES") { print("selected=\"selected\""); } ?>>Yes</option>
                </select>
            </span><br />
            <span id="medicalHide" class="jQueryHidden">
                <label for="medical" class="inputContainer2root">Please provide details of the medical conditions that could affect your ability to kayak or swim.<br />
                <span class="inputContainer2root"><textarea name="medical" id="medical" rows="6" cols="30" style="width: 100%;"><?php print($waitingDetails["additionalMedical"]); ?></textarea></span><br />    
            </span>
            
        </fieldset>

        <button id="save">Save</button>
        <button id="cancel">Cancel</button>
        
    </form>

    <p class="small">* - Required<br />
    &dagger; - Required for BCU Member Discount</p>

<?php } ?>

<script type="text/javascript">
    
    $(document).ready(function() {
        
        $("#experienceQ").change(function() {
        
            if ($(this).val() == "*YES") {
            
                $("#experienceHide").show();
                
            } else {
                
                $("#experienceHide").hide(); 
                
            }
            
        });
        
        $("#qualificationsQ").change(function() {
         
            if ($(this).val() == "*YES") {
            
                $("#qualificationsHide").show();
                
            } else {
                
                $("#qualificationsHide").hide(); 
                
            }
            
        });
        
        $("#medicalQ").change(function() {
            
            if ($(this).val() == "*YES") {
            
                $("#medicalHide").show();
                
            } else {
                
                $("#medicalHide").hide(); 
                
            }
            
        });
        
        $("#cancel").click(function(event) {

           event.preventDefault();
           location.href = "index.php?action=cwaiting";
           return false;

        });

        $("#save").click(function(event) {

          event.preventDefault();

          $.ajax({
             type:    "POST",
             url:     "index.php",
             data:    "action=cwaitingdetailsubmit&" + $("#memberRecord").serialize(),
             cache:   false,
             success: function(data) {
                 if (data == "*OK") {
                    location.href = "index.php?action=cwaiting";
                 } else if (data == "*VALIDATEDATE") {
                    alert("Please ensure all dates are in the format dd/mm/yyyy for example 31/12/2013.");
                 } else if (data == "*VALIDATE") {
                    alert("Please ensure all fields marked with a * are filled in!");
                 } else {
                    alert("Update Failed ... Please try again later");
                 }
             }
          });

          return false;  

        });
        
        $("#experienceQ").trigger("change");
        $("#qualificationsQ").trigger("change");
        $("#medicalQ").trigger("change");
        
    });
    
</script>

<?php 
    
}

?>