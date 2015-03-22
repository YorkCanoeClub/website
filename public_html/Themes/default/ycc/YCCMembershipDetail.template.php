<?php

function template_main() {
    
    global $context;
    
    ?>

<h1>YCC Memberships Detail</h1><br />

<?php

if ($context["member"]) {
    
    $memberArray = $context["memberArray"];
    $contactsArray = $context["memberContacts"];
    $linksArray = $context["memberLinks"];
    $manager = $context['memberManage'];
    $membersU = $context['membersU'];
    
    ?>
    
    <form id="memberRecord">

        <input type="hidden" name="membershipNumber" id="membershipNumber" value="<?php print($memberArray["membershipNumber"]); ?>" />
        <input type="hidden" name="membershipType" id="membershipType" value="<?php print($memberArray["membershipType"]); ?>" />

        <fieldset>
            <legend>Membership Details</legend>

            <label>Membership No.</label>
            <span class="inputContainer"><?php print($memberArray["membershipNumber"] . "/" . $memberArray["membershipType"]); ?></span>
            <label>Status</label>
            <?php if ($manager) { ?>
                <span class="inputContainer">
                    <input type="hidden" name="originalMembershipStatus" id="originalMembershipStatus" value="<?php print($memberArray["membershipStatus"]); ?>" />
                    <select name="membershipStatus" id="membershipStatus">
                        <option value="100" <?php if ($memberArray["membershipStatus"] == "100") { print("selected=\"selected\""); }?>>New</option>
                        <option value="200" <?php if ($memberArray["membershipStatus"] == "200") { print("selected=\"selected\""); }?>>Pending</option>
                        <option value="300" <?php if ($memberArray["membershipStatus"] == "300") { print("selected=\"selected\""); }?>>Renewal Due</option>
                        <option value="350" <?php if ($memberArray["membershipStatus"] == "350") { print("selected=\"selected\""); }?>>Renewal In Progress</option>
                        <option value="400" <?php if ($memberArray["membershipStatus"] == "400") { print("selected=\"selected\""); }?>>Lapsed</option>
                        <option value="500" <?php if ($memberArray["membershipStatus"] == "500") { print("selected=\"selected\""); }?>>Current</option>
                        <option value="550" <?php if ($memberArray["membershipStatus"] == "550") { print("selected=\"selected\""); }?>>Honorary</option>
                        <option value="900" <?php if ($memberArray["membershipStatus"] == "900") { print("selected=\"selected\""); }?>>Cancelled</option>
                        <option value="999" <?php if ($memberArray["membershipStatus"] == "999") { print("selected=\"selected\""); }?>>Deleted</option>
                    </select>
                </span><br />
            <?php } else { ?>
                <span class="inputContainer">
                    <?php print(getStatusDesc($memberArray["membershipStatus"])); ?>
                    <input type="hidden" name="membershipStatus" id="membershipStatus" value="<?php print($memberArray["membershipStatus"]); ?>" />
                </span><br />
            <?php } ?>
            <label>Started / Renewed</label>
            <?php if ($manager) { ?>
                <span class="inputContainer">
                    <span class="inputContainer"><input type="text" name="membershipStarted" id="membershipStarted" value="<?php print($memberArray["membershipStartedF"]); ?>" /></span><br />
                </span>
            <?php } else { ?>
                <span class="inputContainer">
                    <?php print($memberArray["membershipStartedF"]); ?>
                    <input type="hidden" name="membershipStarted" id="membershipStarted" value="<?php print($memberArray["membershipStartedF"]); ?>" />
                </span>
            <?php } ?><label>Expires</label>
            <?php if ($manager) { ?>
                <span class="inputContainer">
                    <span class="inputContainer"><input type="text" name="membershipExpires" id="membershipExpires" value="<?php print($memberArray["membershipExpiresF"]); ?>" /></span><br />
                </span><br />
            <?php } else { ?>
                <span class="inputContainer">
                    <?php print($memberArray["membershipExpiresF"]); ?>
                    <input type="hidden" name="membershipExpires" id="membershipExpires" value="<?php print($memberArray["membershipExpiresF"]); ?>" />
                </span><br />
            <?php } ?>

        </fieldset>

        <fieldset>
            <legend>About You</legend>

            <label>First Name(s)<span class="required">*</span></label>
            <span class="inputContainer"><input type="text" name="memberFirstName" id="memberFirstName" value="<?php print($memberArray["memberFirstName"]); ?>" /></span>
            <label>Last Name<span class="required">*</span></label>
            <span class="inputContainer"><input type="text" name="memberLastName" id="memberLastName" value="<?php print($memberArray["memberLastName"]); ?>" /></span><br />

            <label>Known As</label>
            <span class="inputContainer"><input type="text" name="memberKnownAs" id="memberKnownAs" value="<?php print($memberArray["memberKnownAs"]); ?>" /></span>
            <label>Gender<span class="required">*</span></label>
            <span class="inputContainer">
                <select name="memberGender" id="memberGender">
                    <option value="">Please Select</option>
                    <option value="M" <?php if ($memberArray["memberGender"] == "M") { print("selected=\"selected\""); }?>>Male</option>
                    <option value="F" <?php if ($memberArray["memberGender"] == "F") { print("selected=\"selected\""); }?>>Female</option>
                </select>
            </span><br />

            <label>Date of Birth<span class="required">*</span></label>
            <span class="inputContainer"><input type="text" name="memberDob" id="memberDob" value="<?php print($memberArray["memberDobF"]); ?>" /></span>
            <label>BCU No.<span class="required">&dagger;</span></label>
            <span class="inputContainer"><input type="text" name="memberBcuNumber" id="memberBcuNumber" value="<?php print($memberArray["memberBcuNumber"]); ?>" /></span><br />

            <label>Age</label>
            <span class="inputContainer"><?php print($memberArray["age"]); ?></span><br /><br />

            <label>Address<span class="required">*</span></label>
            <span class="inputContainer2"><input type="text" name="memberAddress" id="memberAddress" value="<?php print($memberArray["memberAddress"]); ?>" /></span><br />
            <label>Postcode<span class="required">*</span></label>
            <span class="inputContainer"><input type="text" name="memberPostcode" id="memberPostcode" value="<?php print($memberArray["memberPostcode"]); ?>" /></span><br /><br />

            <label>Primary Phone<span class="required">*</span></label>
            <span class="inputContainer"><input type="text" name="memberPhone1" id="memberPhone1" value="<?php print($memberArray["memberPhone1"]); ?>" /></span>
            <label>Secondary Phone</label>
            <span class="inputContainer"><input type="text" name="memberPhone2" id="memberPhone2" value="<?php print($memberArray["memberPhone2"]); ?>" /></span><br />

            <label>Email<span class="required">*</span></label>
            <span class="inputContainer2"><input type="text" name="memberEmail" id="memberEmail" value="<?php print($memberArray["memberEmail"]); ?>" /></span><br />

        </fieldset>

        <fieldset>
            <legend>Contact Preferences</legend>

            <label>Can We Contact You?</label>
            <span class="inputContainer">
                <select name="memberContact" id="memberContact">
                    <option value="Y" <?php if ($memberArray["memberContact"] == "Y") { print("selected=\"selected\""); }?>>Yes</option>
                    <option value="N" <?php if ($memberArray["memberContact"] == "N") { print("selected=\"selected\""); }?>>No</option>
                </select>
            </span><br />
            <p class="small">Your contact details will not be shared with anyone and will be used for club communication only.</p>

        </fieldset>

        <fieldset>
            <legend>Primary Emergency Contact</legend>

            <label>Name<span class="required">*</span></label>
            <span class="inputContainer"><input type="text" name="contactsName1" id="contactsName1" value="<?php print($contactsArray[0]["contactsName"]); ?>" /></span>
            <label>Relationship<span class="required">*</span></label>
            <span class="inputContainer"><input type="text" name="contactsRelationship1" id="contactsRelationship1" value="<?php print($contactsArray[0]["contactsRelationship"]); ?>" /></span><br />
            <label>Primary Phone<span class="required">*</span></label>
            <span class="inputContainer"><input type="text" name="contactsPhone11" id="contactsPhone11" value="<?php print($contactsArray[0]["contactsPhone1"]); ?>" /></span>
            <label>Secondary Phone</label>
            <span class="inputContainer"><input type="text" name="contactsPhone21" id="contactsPhone21" value="<?php print($contactsArray[0]["contactsPhone2"]); ?>" /></span><br /><br />

        </fieldset>

        <fieldset>
            <legend>Secondary Emergency Contact</legend>

            <label>Name</label>
            <span class="inputContainer"><input type="text" name="contactsName2" id="contactsName2" value="<?php print($contactsArray[1]["contactsName"]); ?>" /></span>
            <label>Relationship</label>
            <span class="inputContainer"><input type="text" name="contactsRelationship2" id="contactsRelationship2" value="<?php print($contactsArray[1]["contactsRelationship"]); ?>" /></span><br />
            <label>Primary Phone</label>
            <span class="inputContainer"><input type="text" name="contactsPhone12" id="contactsPhone12" value="<?php print($contactsArray[1]["contactsPhone1"]); ?>" /></span>
            <label>Secondary Phone</label>
            <span class="inputContainer"><input type="text" name="contactsPhone22" id="contactsPhone22" value="<?php print($contactsArray[1]["contactsPhone2"]); ?>" /></span><br /><br />

        </fieldset>

        <fieldset>
            <legend>Forum</legend>

            <label for="memberFirstName">Forum Profile</label>
            <?php if ($manager) { ?>
                <span class="inputContainer"><select name="forumId" id="forumId">
                    <option value="">None</option>
                    <?php 

                    foreach ($membersU as $member) {

                        print("<option value=\"" . $member["id_member"] . "\"");
                        if ($memberArray["id_member"] == $member["id_member"]) {
                            print(" selected");
                        }
                        print(">" . $member["real_name"] . "</option>");

                    }

                    ?> 

                </select></span>
            <?php } else { ?>

                    <span class="inputContainer"><?php print($memberArray["real_name"]); ?></span>
                    <input type="hidden" name="forumId" id="forumId" value="<?php print($memberArray["id_member"]); ?>" />

            <?php } ?>

        </fieldset>
        
        <fieldset>
            <legend>Linked Memberships</legend>

            <table style="width: 100%;" id="linksTable" class="membershipTable">
                <thead>
                    <tr>
                        <th class="ignoreSize">Membership No.</th>
                        <th>Member Name</th>
                        <th class="ignoreSize">Age</th>
                        <th class="ignoreSize">Status</th>
                        <th class="ignoreSize">Actions</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                </tfoot>
                <tbody>

                <?php 

                    foreach($linksArray as $link) {

                        print("<tr>");
                        print("<td>" . $link["membershipNumber"] . "/" . $link["membershipType"] . "</td>");
                        print("<td>" . $link["memberFirstName"] . " " . $link["memberLastName"] . "</td>");
                        print("<td>" . $link["age"] . "</td>");
                        print("<td>" . getStatusDesc($link["membershipStatus"]) . "</td>");
                        print("<td>&nbsp;</td>");
                        print("</tr>");

                    }

                ?>

                </tbody>
            </table>

        </fieldset>

        <fieldset>
            <legend>Membership Agreement</legend>

            <p>As a member of York Canoe Club you agree to the following:</p>
            <ul>
                <li>I understand that canoeing and kayaking are adventurous and potentially dangerous sports, and that I undertake this activity at my own risk.</li>
                <li>I understand that York Canoe Club will hold my personal information on a computer system for the duration of my membership</li>
                <li>I understand that my emergency contact information will be held by trip organisers and river leads whilst I attend organised club trips. This information will only be used in the case of an emergency</li>
                <li>I understand that it is my responsibility to notify a committee member and appropriate river leads of any medical condition which may affect my participation in YCC activities.</li>
            </ul>

        </fieldset>

        <button id="save">Save</button>
        <button id="cancel">Cancel</button>

    </form>

    <p class="footnote">* - Required<br />
    &dagger; - Required for BCU Member Discount</p>
    <?php 
    
} else {
    
    print("You aren't allowed to do that!");
    
}

?>

<script type="text/javascript">
    
    $(document).ready(function() {
    
        $("#cancel").click(function(event) {

           event.preventDefault();
           location.href = "index.php?action=<?php print($context["fromFunction"]); ?>";
           return false;

        });

        $("#save").click(function(event) {

          event.preventDefault();

          $.ajax({
             type:    "POST",
             url:     "index.php",
             data:    "action=ymembershipdetailsubmit&" + $("#memberRecord").serialize(),
             cache:   false,
             success: function(data) {
                 if (data == "*OK") {
                    location.href = "index.php?action=<?php print($context["fromFunction"]); ?>";
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
        
        var linksTable = defineTableNew("#linksTable", [], [0,1,2,3,4], [], "YCC_MEMBERLINKS", "No Links Found", tableLayoutTab, true);
        linksTable.fnRestoreAllFilters();
        
    });
    
</script>

<?php 
    
}

?>