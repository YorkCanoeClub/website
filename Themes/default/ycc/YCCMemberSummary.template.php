<?php

function template_main() {
    
    global $context, $settings, $options, $txt, $scripturl;
    
    $seasons = $context["seasons"];
    
    ?>

<h2>Membership Summary</h2>
<p>A summary of the current breakdown of the membership. This information can be used to fill out the membership section of the 'BCU Affiliation' form using the last full seasons data.</p>

<form>
    
    <label for="currentSeason">Season</label>
    <select name="currentSeason" id="currentSeason">
        
    <?php 
    
        foreach($seasons as $season) {
            if ($season == $context["currentSeason"]) {
                print("<option value=\"$season\" selected=\"selected\">$season</option>");
            } else {
                print("<option value=\"$season\">$season</option>");
            }
        }
    
    ?>
        
    </select><br /><br />
    
</form>

<table class="summaryTable">
    <thead>
        <tr>
            <th>Club Members</th>
            <th colspan="2">Canoe England Members</th>
            <th colspan="2">Non - Canoe England members</th>
            <th colspan="2">Age Group Totals</th>
        </tr>
        <tr>
        <tr>
            <th>&nbsp;</th>
            <th>Males</th>
            <th>Females</th>
            <th>Males</th>
            <th>Females</th>
            <th>Totals</th>
            <th>Totals %</th>
        </tr>    
    </thead>
    <tbody>
        <tr>
            <td><b>Under 16</b></td>
            <td class="numbers"><?php print($context["M1B"]); ?></td>
            <td class="numbers"><?php print($context["F1B"]); ?></td>
            <td class="numbers"><?php print($context["M1N"]); ?></td>
            <td class="numbers"><?php print($context["F1N"]); ?></td>
            <td class="numbers"><b><?php print($context["A1"]); ?></b></td>
            <td class="numbers"><b><?php print($context["A1P"]); ?></b>%</td>
        </tr>
        <tr>
            <td><b>16 - 18</b></td>
            <td class="numbers"><?php print($context["M2B"]); ?></td>
            <td class="numbers"><?php print($context["F2B"]); ?></td>
            <td class="numbers"><?php print($context["M2N"]); ?></td>
            <td class="numbers"><?php print($context["F2N"]); ?></td>
            <td class="numbers"><b><?php print($context["A2"]); ?></b></td>
            <td class="numbers"><b><?php print($context["A2P"]); ?></b>%</td>
        </tr>
        <tr>
            <td><b>19 - 45</b></td>
            <td class="numbers"><?php print($context["M3B"]); ?></td>
            <td class="numbers"><?php print($context["F3B"]); ?></td>
            <td class="numbers"><?php print($context["M3N"]); ?></td>
            <td class="numbers"><?php print($context["F3N"]); ?></td>
            <td class="numbers"><b><?php print($context["A3"]); ?></b></td>
            <td class="numbers"><b><?php print($context["A3P"]); ?></b>%</td>
        </tr>
        <tr>
            <td><b>45+</b></td>
            <td class="numbers"><?php print($context["M4B"]); ?></td>
            <td class="numbers"><?php print($context["F4B"]); ?></td>
            <td class="numbers"><?php print($context["M4N"]); ?></td>
            <td class="numbers"><?php print($context["F4N"]); ?></td>
            <td class="numbers"><b><?php print($context["A4"]); ?></b></td>
            <td class="numbers"><b><?php print($context["A4P"]); ?></b>%</td>
        </tr>
        <tr>
            <td><b>Totals</b></td>
            <td class="numbers"><b><?php print($context["TMB"]); ?></b></td>
            <td class="numbers"><b><?php print($context["TFB"]); ?></b></td>
            <td class="numbers"><b><?php print($context["TMN"]); ?></b></td>
            <td class="numbers"><b><?php print($context["TFN"]); ?></b></td>
            <td class="numbers"><b><?php print($context["GT"]); ?></b></td>
            <td class="noBorder">&nbsp;</td>
        </tr>
        <tr>
            <td><b>Totals %</b></td>
            <td class="numbers"><b><?php print($context["TMBP"]); ?></b>%</td>
            <td class="numbers"><b><?php print($context["TFBP"]); ?></b>%</td>
            <td class="numbers"><b><?php print($context["TMNP"]); ?></b>%</td>
            <td class="numbers"><b><?php print($context["TFNP"]); ?></b>%</td>
            <td class="noBorder">&nbsp;</td>
            <td class="noBorder">&nbsp;</td>
        </tr>
        <tr>
            <td><b>Totals</b></td>
            <td class="numbers" colspan="2"><b><?php print($context["TB"]); ?></b></td>
            <td class="numbers" colspan="2"><b><?php print($context["TN"]); ?></b></td>
            <td class="noBorder">&nbsp;</td>
            <td class="noBorder">&nbsp;</td>
        </tr>
        <tr>
            <td><b>Totals %</b></td>
            <td class="numbers" colspan="2"><b><?php print($context["TBP"]); ?></b>%</td>
            <td class="numbers" colspan="2"><b><?php print($context["TNP"]); ?></b>%</td>
            <td class="noBorder">&nbsp;</td>
            <td class="noBorder">&nbsp;</td>
        </tr>
    </tbody>
</table>

<br />

<table class="summaryTable">
    <thead>
        <tr>
            <th>Club Members</th>
            <th colspan="4">Age Group Totals</th>
        </tr>
        <tr>
        <tr>
            <th>&nbsp;</th>
            <th>Males</th>
            <th>Females</th>
            <th>Totals</th>
            <th>Totals %</th>
        </tr>    
    </thead>
    <tbody>
        <tr>
            <td><b>Under 16</b></td>
            <td class="numbers"><?php print($context["AM1"]); ?></td>
            <td class="numbers"><?php print($context["AF1"]); ?></td>
            <td class="numbers"><b><?php print($context["A1"]); ?></b></td>
            <td class="numbers"><b><?php print($context["A1P"]); ?></b>%</td>
        </tr>
        <tr>
            <td><b>16 - 18</b></td>
            <td class="numbers"><?php print($context["AM2"]); ?></td>
            <td class="numbers"><?php print($context["AF2"]); ?></td>
            <td class="numbers"><b><?php print($context["A2"]); ?></b></td>
            <td class="numbers"><b><?php print($context["A2P"]); ?></b>%</td>
        </tr>
        <tr>
            <td><b>19 - 45</b></td>
            <td class="numbers"><?php print($context["AM3"]); ?></td>
            <td class="numbers"><?php print($context["AF3"]); ?></td>
            <td class="numbers"><b><?php print($context["A3"]); ?></b></td>
            <td class="numbers"><b><?php print($context["A3P"]); ?></b>%</td>
        </tr>
        <tr>
            <td><b>45+</b></td>
            <td class="numbers"><?php print($context["AM4"]); ?></td>
            <td class="numbers"><?php print($context["AF4"]); ?></td>
            <td class="numbers"><b><?php print($context["A4"]); ?></b></td>
            <td class="numbers"><b><?php print($context["A4P"]); ?></b>%</td>
        </tr>
        <tr>
            <td><b>Totals</b></td>
            <td class="numbers"><b><?php print($context["TM"]); ?></b></td>
            <td class="numbers"><b><?php print($context["TF"]); ?></b></td>
            <td class="numbers"><b><?php print($context["GT"]); ?></b></td>
            <td class="noBorder">&nbsp;</td>
        </tr>
        <tr>
            <td><b>Totals %</b></td>
            <td class="numbers"><b><?php print($context["TMP"]); ?></b>%</td>
            <td class="numbers"><b><?php print($context["TFP"]); ?></b>%</td>
            <td class="noBorder">&nbsp;</td>
            <td class="noBorder">&nbsp;</td>
        </tr>
    </tbody>
</table>

<script type="text/javascript">
    
    var pagesize = 15;
    
    $(document).ready(function() {
    
        $("#currentSeason").change(function(event) {
        
            location.href = "index.php?action=ymembersummary&currentSeason=" + $(this).val();
            
        });
        
    });

</script>

<?php 
    
}

?>