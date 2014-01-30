<?php

function template_main() {

    global $context, $settings, $options, $txt, $scripturl;

    $members = $context["memberArray"];
    $manager = $context["memberManage"];
    $seasons = $context["seasons"];

    ?>

<table id="memberList" class="membershipTable">
    <thead>
        <tr>
            <th>No.</th>
            <th>Name</th>
            <th>Forum Name</th>
            <th>Expires</th>
            <th>BCU No.</th>
            <th>Status</th>
            <th>Problems</th>
            <th>Actions</th>
        </tr>
        <tr class="search">
            <th><input type="text" name="search0" id="search0" /></th>
            <th><input type="text" name="search1" id="search1" /></th>
            <th><input type="text" name="search2" id="search2" /></th>
            <th>&nbsp;</th>
            <th>&nbsp;</th>
            <th><span id="search5"></span></th>
            <th><span id="search6"></span></th>
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
            <td>&nbsp;</td>
        </tr>
    </tfoot>
    <tbody>

        <?php

        foreach ($members as $member) {

            ?>

            <tr id="<?php print($member["membershipNumber"]); ?>" class="clickableRow">
                <td><?php print($member["membershipNumber"] . "/" . $member["membershipType"]); ?></td>
                <td><?php print($member["memberFirstName"] . " " . $member["memberLastName"]); ?></td>
                <td><?php print($member["member_name"]); if ($member["member_name"] != $member["real_name"]) { print(" (" . $member["real_name"] . ")"); } ?></td>
                <td><?php print($member["membershipExpiresF"]); ?></td>
                <td><?php print($member["memberBcuNumber"]); ?></td>
                <td><?php print(getStatusDesc($member["membershipStatus"])); ?></td>
                <td><?php print($member["complete"]); ?></td>
                <td><?php if ($manager) { ?><a href="index.php?action=ymembershipdetail&yno=<?php print($member["membershipNumber"]); ?>&from=ylist">Edit</a><?php } ?></td>
            </tr>

            <?php


        }

        ?>

    </tbody>
</table>

<script type="text/javascript">

    var pagesize = 15;

    $(document).ready(function() {

        $("#memberList tbody").click(function(event) {

            var target = event.target;
            var jTarget = $(target);
            var className = target.className;
            var row = jTarget.closest("tr");
            var cell = jTarget.closest("td");
            var key = row.attr("id");

            bConsole(target);
            bConsole(jTarget);
            bConsole(className);
            bConsole(row);
            bConsole(cell);
            bConsole(key);

            if (jTarget.is("input")) {

                return true;

            } else {

                if (!row.find("td:first").hasClass("dataTables_empty")) {
                    location.href = "index.php?action=ymembershipdetail&yno=" + key + "&from=ylist";
                }

            }

            return false;

        });

        var memberListTable = defineTableNew("#memberList", [], [7], [], "YCC_YCCMEMBERLIST", "No Members Found", tableLayout, true);
        memberListTable.fnRestoreAllFilters();

        $("#search5").html(fnCreateSelect( memberListTable.fnGetColumnData(5, true, false, false)));

	$("#search5 select").change(function() {
            memberListTable.fnFilter($(this).val(), 5);
        });


        $("#search6").html(fnCreateSelect( memberListTable.fnGetColumnData(6, true, false, false)));

	$("#search6 select").change(function() {
            memberListTable.fnFilter($(this).val(), 6);
        });

        memberListTable.fnRestoreAllFilters();

    });

</script>

<?php

}

?>