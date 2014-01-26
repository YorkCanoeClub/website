<?php

function template_main() {
    
    global $context;
    $forumDetails = $context["forumDetails"];
    $waitingCount = $context["waitingCount"];
    if (isset($context["errors"])) {
        $errors = $context["errors"];
        $values = $context["values"];
    } else {
        $values["memberEmail"] = $forumDetails["email_address"];
        $values["memberGender"] = "";
        $values["memberContact"] = "Y";
        $values["additionalSwim"] = "Y";
        $values["additionalKit"] = "N";
    }
    ?>

<h1>Joining York Canoe Club</h1>
<p>Thank you for you interest in joining York Canoe Club. We are currently heavily oversubscribed and are running a waiting list for entry into the club. We hope that the wait will not be too long but please be aware that there is currently around a 9 month wait for the 'New Starters Program' and around a 3 month wait for experienced paddlers who don't have their own equipment. If you are an experienced paddler with your own equipment, that you can bring to every session, then the wait is normally very short.</p>
<h2>New Members</h2>
<p>If you are not currently a member of York Canoe Club regardless of the amount of experience you have you'll need to put your name on our waiting list. If you are filling this form in for someone else please ensure you fill the form in with the details of the person who wishes to join rather than your own (that may sound like a silly request but you'd be surprised!). There are 2 ways to join the club and using the information you provide us we'll decide which way you should enter:</p>
<ul>
    <li>New Starters Program - This is aimed at giving you a solid platform from which to develop your paddling skills within York Canoe Club. As the main focus of York Canoe Club is white water kayaking this is what the program is designed to prepare you for. Anyone with little or no experience will enter the club this way in addition to all under 18's (unless they hold BCU 3* white water).</li>
    <li>Direct Entry - If you have experience of flatwater or white water kayaking you'll be able to enter the club directly. Generally we are looking for BCU 3* white water but understand that bits of paper aren't everyones bag so please give us as much detail as possible about what you have done in a kayak. If you own all your own kit and are able to bring it to sessions then this will speed up your entry into the club.</li>
</ul>
<?php if ($context['user']['is_guest']) { ?>
<p>Please note that in order to join the waiting list you need to <a href="http://www.yorkcanoeclub.co.uk/index.php?action=register">register</a> and <a href="http://www.yorkcanoeclub.co.uk/index.php?action=login">login</a> to our club website. This is to ensure that we have a valid email address for you. All communication will be done using email and the club forum.</p>
<?php } ?>

<h2>Existing Members</h2>
<p>If you are an existing member of York Canoe Club you are in the wrong place! You should be able to find your membership and renew it by clicking <a href="https://www.yorkcanoeclub.co.uk/index.php?action=ymembership">here</a>. If that isn't working out for you then please get in touch with a member of the committee or <a href="https://www.yorkcanoeclub.co.uk/index.php?action=pm;sa=send;u=2">PM Jon</a></p>
<?php if ($context['user']['is_guest']) { ?>
<p>Please note that in order to manage your membership you need to <a href="http://www.yorkcanoeclub.co.uk/index.php?action=register">register</a> and <a href="http://www.yorkcanoeclub.co.uk/index.php?action=login">login</a> to our club website. This is to ensure that we have a valid email address for you. All communication will be done using email and the club forum.</p>
<?php } ?>

<h2>Membership Rates</h2>
<p>Membership rates for the season October 2012 - September 2013, as set at the AGM in November 2011 are:</p>
<ul>
    <li>Adult - &pound;40</li>
    <li>Adult BCU Member - &pound;39</li>
    <li>Junior - &pound;25</li>
    <li>Junior BCU Member - &pound;24</li>
    <li>Family - &pound;80</li>
</ul>
<p class="small">'Juniors' are under 18 at the time of joining, 'Family' consists of 2 adults and up to 3 juniors living at the same address full time.</p>

<?php if (!$context['user']['is_guest']) { ?>

<h2>Waiting List Application Form</h2><br />
    
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

    <form id="memberRecord" action="index.php" method="POST">

        <input type="hidden" name="action" id="action" value="ynewmembersubmit" />
        
        <fieldset>
            <legend>About You</legend>

            <label for="memberFirstName">First Name(s)<span class="required">*</span></label>
            <span class="inputContainer"><input type="text" name="memberFirstName" id="memberFirstName" value="<?php print($values["memberFirstName"]); ?>" /></span>
            <label for="memberLastName">Last Name<span class="required">*</span></label>
            <span class="inputContainer"><input type="text" name="memberLastName" id="memberLastName" value="<?php print($values["memberLastName"]); ?>" /></span><br />

            <label for="memberKnownAs">Known As</label>
            <span class="inputContainer"><input type="text" name="memberKnownAs" id="memberKnownAs" value="<?php print($values["memberKnownAs"]); ?>" /></span>
            <label for="memberGender">Gender<span class="required">*</span></label>
            <span class="inputContainer">
                <select name="memberGender" id="memberGender">
                    <option value="">Please Select</option>
                    <option value="M" <?php if ($values["memberGender"] == "M") { print("selected=\"selected\""); } ?>>Male</option>
                    <option value="F" <?php if ($values["memberGender"] == "F") { print("selected=\"selected\""); } ?>>Female</option>
                </select>
            </span><br />

            <label for="memberDob">Date of Birth<span class="required">*</span></label>
            <span class="inputContainer"><input type="text" name="memberDob" id="memberDob" value="<?php print($values["memberDob"]); ?>" /></span>
            <label for="memberBcuNumber">BCU No.<span class="required">&dagger;</span></label>
            <span class="inputContainer"><input type="text" name="memberBcuNumber" id="memberBcuNumber" value="<?php print($values["memberBcuNumber"]); ?>" /></span><br /><br />

            <label for="memberAddress">Address<span class="required">*</span></label>
            <span class="inputContainer2"><input type="text" name="memberAddress" id="memberAddress" value="<?php print($values["memberAddress"]); ?>" /></span><br />
            <label for="memberPostcode">Postcode<span class="required">*</span></label>
            <span class="inputContainer"><input type="text" name="memberPostcode" id="memberPostcode" value="<?php print($values["memberPostcode"]); ?>" /></span><br /><br />

            <label for="memberPhone1">Primary Phone<span class="required">*</span></label>
            <span class="inputContainer"><input type="text" name="memberPhone1" id="memberPhone1" value="<?php print($values["memberPhone1"]); ?>" /></span>
            <label for="memberPhone2">Secondary Phone</label>
            <span class="inputContainer"><input type="text" name="memberPhone2" id="memberPhone2" value="<?php print($values["memberPhone2"]); ?>" /></span><br />
            <label for="memberEmail">Email<span class="required">*</span></label>
            <span class="inputContainer2"><input type="text" name="memberEmail" id="memberEmail" value="<?php print($values["memberEmail"]); ?>" /></span><br />

        </fieldset>

        <fieldset>
            <legend>Contact Preferences</legend>

            <label for="memberContact">Can We Contact You?</label>
            <span class="inputContainer">
                <select name="memberContact" id="memberContact">
                    <option value="Y" <?php if ($values["memberContact"] == "Y") { print("selected=\"selected\""); } ?>>Yes</option>
                    <option value="N" <?php if ($values["memberContact"] == "N") { print("selected=\"selected\""); } ?>>No</option>
                </select>
            </span><br />
            <p class="small">Your contact details will not be shared with anyone and will be used for club communication only.</p>

        </fieldset>

        <fieldset>
            <legend>Primary Emergency Contact</legend>

            <label for="contactsName1">Name<span class="required">*</span></label>
            <span class="inputContainer"><input type="text" name="contactsName1" id="contactsName1" value="<?php print($values["contactsName1"]); ?>" /></span>
            <label for="contactsRelationship1">Relationship<span class="required">*</span></label>
            <span class="inputContainer"><input type="text" name="contactsRelationship1" id="contactsRelationship1" value="<?php print($values["contactsRelationship1"]); ?>" /></span><br />
            <label for="contactsPhone11">Primary Phone<span class="required">*</span></label>
            <span class="inputContainer"><input type="text" name="contactsPhone11" id="contactsPhone11" value="<?php print($values["contactsPhone11"]); ?>" /></span>
            <label for="contactsPhone21">Secondary Phone</label>
            <span class="inputContainer"><input type="text" name="contactsPhone21" id="contactsPhone21" value="<?php print($values["contactsPhone21"]); ?>" /></span><br /><br />

        </fieldset>


        <fieldset>
            <legend>Secondary Emergency Contact</legend>

            <label for="contactsName2">Name</label>
            <span class="inputContainer"><input type="text" name="contactsName2" id="contactsName2" value="<?php print($values["contactsName2"]); ?>" /></span>
            <label for="contactsRelationship2">Relationship</label>
            <span class="inputContainer"><input type="text" name="contactsRelationship2" id="contactsRelationship2" value="<?php print($values["contactsRelationship2"]); ?>" /></span><br />
            <label for="contactsPhone12">Primary Phone</label>
            <span class="inputContainer"><input type="text" name="contactsPhone12" id="contactsPhone12" value="<?php print($values["contactsPhone12"]); ?>" /></span>
            <label for="contactsPhone22">Secondary Phone</label>
            <span class="inputContainer"><input type="text" name="contactsPhone22" id="contactsPhone22" value="<?php print($values["contactsPhone22"]); ?>" /></span><br /><br />

        </fieldset>

        <fieldset>
            <legend>Additional Information</legend>

            <label for="swimQ" class="inputContainer2root">Can you swim 25 metres unaided?<span class="required">*</span></label><br />
            <span class="inputContainer">
                <select name="swimQ" id="swimQ">
                    <option value="">[ Please Select ]</option>
                    <option value="*NO" <?php if ($values["swimQ"] == "*NO") { print("selected=\"selected\""); } ?>>No</option>
                    <option value="*YES" <?php if ($values["swimQ"] == "*YES") { print("selected=\"selected\""); } ?>>Yes</option>
                </select>
            </span><br />
            
            <label for="kitQ" class="inputContainer2root">Do you own all your own kit?<span class="required">*</span></label><br />
            <span class="inputContainer">
                <select name="kitQ" id="kitQ">
                    <option value="">[ Please Select ]</option>
                    <option value="*NO" <?php if ($values["kitQ"] == "*NO") { print("selected=\"selected\""); } ?>>No</option>
                    <option value="*YES" <?php if ($values["kitQ"] == "*YES") { print("selected=\"selected\""); } ?>>Yes</option>
                </select>
            </span><br />
            
            <label for="experienceQ" class="inputContainer2root">Do you have any prior experience in a <em>kayak</em>?<span class="required">*</span></label><br />
            <span class="inputContainer">
                <select name="experienceQ" id="experienceQ">
                    <option value="">[ Please Select ]</option>
                    <option value="*NO" <?php if ($values["experienceQ"] == "*NO") { print("selected=\"selected\""); } ?>>No</option>
                    <option value="*YES" <?php if ($values["experienceQ"] == "*YES") { print("selected=\"selected\""); } ?>>Yes</option>
                </select>
            </span><br />
            <span id="experienceHide" class="jQueryHidden">
                <label for="experience" class="inputContainer2root">Please provide as much detail as possible about your prior experience in a <em>kayak</em>.</label><br />
                <span class="inputContainer2root"><textarea name="experience" id="experience" rows="6" cols="30" style="width: 100%;"><?php print($values["experience"]); ?></textarea></span><br />
            </span>
            
            <label for="qualificationsQ" class="inputContainer2root">Do you have any BCU qualifications in <em>kayak</em>?<span class="required">*</span></label><br />
            <span class="inputContainer">
                <select name="qualificationsQ" id="qualificationsQ">
                    <option value="">[ Please Select ]</option>
                    <option value="*NO" <?php if ($values["qualificationsQ"] == "*NO") { print("selected=\"selected\""); } ?>>No</option>
                    <option value="*YES" <?php if ($values["qualificationsQ"] == "*YES") { print("selected=\"selected\""); } ?>>Yes</option>
                </select>
            </span><br />
            <span id="qualificationsHide" class="jQueryHidden">
                <label for="qualifications" class="inputContainer2root">What BCU qualifications do you have in <em>kayak</em>?</label><br />
                <span class="inputContainer2root"><textarea name="qualifications" id="qualifications" rows="6" cols="30" style="width: 100%;"><?php print($values["qualifications"]); ?></textarea></span><br />    
            </span>
                
            <label for="medicalQ" class="inputContainer2root">Do you have any medical conditions that could affect your ability to kayak or swim?<span class="required">*</span></label><br />
            <span class="inputContainer">
                <select name="medicalQ" id="medicalQ">
                    <option value="">[ Please Select ]</option>
                    <option value="*NO" <?php if ($values["medicalQ"] == "*NO") { print("selected=\"selected\""); } ?>>No</option>
                    <option value="*YES" <?php if ($values["medicalQ"] == "*YES") { print("selected=\"selected\""); } ?>>Yes</option>
                </select>
            </span><br />
            <span id="medicalHide" class="jQueryHidden">
                <label for="medical" class="inputContainer2root">Please provide details of the medical conditions that could affect your ability to kayak or swim.<br />
                <span class="inputContainer2root"><textarea name="medical" id="medical" rows="6" cols="30" style="width: 100%;"><?php print($values["medical"]); ?></textarea></span><br />    
            </span>
            
        </fieldset>

        <fieldset>
            <legend>Membership Agreement</legend>

            <p>By submitting this form you agree to the following:</p>
            <ul>
                <li>I understand that canoeing and kayaking are adventurous and potentially dangerous sports, and that I undertake this activity at my own risk.</li>
                <li>I understand that York Canoe Club will hold my personal information on a computer system for the duration of my membership</li>
                <li>I understand that my emergency contact information will be held by trip organisers and river leads whilst I attend organised club trips. This information will only be used in the case of an emergency</li>
                <li>I understand that it is my responsibility to notify a committee member and appropriate river leads of any medical condition which may affect my participation in YCC activities.</li>
            </ul>

        </fieldset>

        <button id="save">Submit</button>
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
           location.href = "index.php?action=forum";
           return false;

        });

        $("#save").click(function(event) {

          event.preventDefault();

          $("#memberRecord").submit();
          
          return false;  

        });
        
    });
    
</script>

<?php 
    
}

?>