<?php

function template_main() {
    
    global $context, $settings, $options, $txt, $scripturl;
    
    ?>

    <form id="takings">
        
        <fieldset>
            <legend>Session Takings</legend>
            
                <label>Session Type<span class="required">*</span></label>
                <span class="inputContainer">
                    <select name="sessionType" id="sessionType">
                        <option value="">Please Select</option>
                        <option value="*POOL">Pool</option>
                        <option value="*RIVER">River</option>
                    </select>
                </span><br />
                <label>Session Date<span class="required">*</span></label>
                <span class="inputContainer"><input type="text" name="sessionDate" id="sessionDate" value="" /></span><span class="inputContainer">dd/mm/yyyy please!</span><br /><br />

                <label>No. of New Starters<span class="required">*</span></label>
                <span class="inputContainer"><input type="text" name="sessionNewStarters" id="sessionNewStarters" value="" /></span>
                <label>Total Participants</label>
                <span class="inputContainer" id="totalParticipants">&nbsp;</span><br />
                <label>No. of Members<span class="required">*</span></label>
                <span class="inputContainer"><input type="text" name="sessionMembers" id="sessionMembers" value="" /></span>
                <label>Total Takings</label>
                <span class="inputContainer" id="totalTakings">&nbsp;</span><br />
                <label>No. of Non Members<span class="required">*</span></label>
                <span class="inputContainer"><input type="text" name="sessionNonMembers" id="sessionNonMembers" value="" /></span>
                <label>Profit</label>
                <span class="inputContainer" id="profit">&nbsp;</span><br />
                <label>No. of Kit Hires<span class="required">*</span></label>
                <span class="inputContainer"><input type="text" name="sessionKit" id="sessionKit" value="" /></span>
                <label>Kit Takings</label>
                <span class="inputContainer" id="kitTakings">&nbsp;</span><br /><br />
        
        </fieldset>

        <fieldset>
            <legend>Other Takings</legend>
            
            <label class="inputContainer2root">Other Takings - 1 Per line: Who, Why, How Much</label><br />
            <span class="inputContainer2root"><textarea name="sessionOther" id="sessionOther" rows="10" cols="30" style="width: 100%;"></textarea><br />    
            
        </fieldset>
        
        <fieldset class="jQueryHidden">
            <legend>Messages</legend>
            
            <span id="messages">&nbsp;</span>
            
        </fieldset>
        
        <button id="send">Send</button>
        
    </form>

<script type="text/javascript">
    
    $(document).ready(function() {
        
        $("#sessionNewStarters, #sessionMembers, #sessionNonMembers, #sessionKit").keyup(function(event) {
            
            var newStarters = parseInt($("#sessionNewStarters").val(), 10);
            if (isNaN(newStarters)) { newStarters = 0 };
            var members = parseInt($("#sessionMembers").val(), 10);
            if (isNaN(members)) { members = 0 };
            var nonMembers = parseInt($("#sessionNonMembers").val(), 10);
            if (isNaN(nonMembers)) { nonMembers = 0 };
            var kit = parseInt($("#sessionKit").val(), 10);
            if (isNaN(kit)) { kit = 0 };
            
            if ($("#sessionType").val() == "*POOL") {
                
                var participants = newStarters + members + nonMembers;
                var sessionTakings = (newStarters * 5) + (members * 4) + (nonMembers * 5);
                var kitTakings = (kit * 1.5);
                var profitLoss = sessionTakings - 87.50;
                
                $("#totalParticipants").html(participants);
                $("#totalTakings").html("&pound;" + sessionTakings);
                $("#profit").html("&pound;" + profitLoss);
                $("#kitTakings").html("&pound;" + kitTakings);
                
            } else if ($("#sessionType").val() == "*RIVER") {
                
                
                
            }
            
        });
        
        $("#send").click(function(event) {
            
            event.preventDefault();
            
            $.ajax({
                type:    "POST",
                url:     "index.php",
                data:    "action=ctakingssubmit&" + $("#takings").serialize(),
                cache:   false,
                success: function(data) {
                    if (data == "*OK") {
                        $("#messages").html("Takings sent for " + $("#sessionDate").val()).addClass("info");
                        $("input, select, textarea").val("");
                    } else if (data == "*VALIDATE") {
                        $("#messages").html("Please enter all fields marked with * and make sure all dates are in format dd/mm/yyyy").addClass("error");
                    } else if (data == "*DUPLICATE") {
                        $("#messages").html("Takings have already been sent for " + $("#sessionDate").val()).addClass("error");
                    } else {
                        $("#messages").html("Something went wrong - takings not sent").addClass("error");
                    }
                    $("#messages").parent().show();
                }
            });
            
            return false;
            
        });
        
    });

</script>

<?php 
    
}

?>