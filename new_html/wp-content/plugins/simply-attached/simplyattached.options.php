<?php
    //must check that the user has the required capability 
    if (!current_user_can('manage_options'))
    {
      wp_die( __('You do not have sufficient permissions to access this page.') );
    }

if(isset($_POST['saveattachmentsettings'])) {
    //wp_die( __('<pre>'.print_r($_POST, true).'</pre>' ));
	
	// Don't assume anything. If the POST value is not set, put in a default value.
	if (isset($_POST['simplyattached_usecolor_check']))
		update_option('simplyattached_usecolor_check', $_POST['simplyattached_usecolor_check']);
	else
		update_option('simplyattached_usecolor_check', false);
		
	if (isset($_POST['simplyattached_rowcolor1']))
		update_option('simplyattached_rowcolor1', $_POST['simplyattached_rowcolor1']);
	else
		update_option('simplyattached_rowcolor1', '#fdfcc9');
		
	if (isset($_POST['simplyattached_rowcolor2']))
		update_option('simplyattached_rowcolor2', $_POST['simplyattached_rowcolor2']);
	else
		update_option('simplyattached_rowcolor2', '#faf99e');
	
	if (isset($_POST['simplyattached_showfilesize_check']))
		update_option('simplyattached_showfilesize_check', $_POST['simplyattached_showfilesize_check']);
	else
		update_option('simplyattached_showfilesize_check', false);
	
	if (isset($_POST['simplyattached_showfilecaption_check']))
		update_option('simplyattached_showfilecaption_check', $_POST['simplyattached_showfilecaption_check']);
	else
		update_option('simplyattached_showfilecaption_check', false);
		
    if (isset($_POST['simplyattached_showlistingcount_check']))
		update_option('simplyattached_showlistingcount_check', $_POST['simplyattached_showlistingcount_check']);
	else
		update_option('simplyattached_showlistingcount_check', false);
		
    if(isset($_POST['simplyattached_boldfilesize_check']))
		update_option('simplyattached_boldfilesize_check', $_POST['simplyattached_boldfilesize_check']);
	else
		update_option('simplyattached_boldfilesize_check', false);
		
	if (isset($_POST['simplyattached_useshortcode_check']))
		update_option('simplyattached_useshortcode_check', $_POST['simplyattached_useshortcode_check']);
	else
		update_option('simplyattached_useshortcode_check', false);
		
	if (isset($_POST['simplyattached_use_db']))
		update_option('simplyattached_use_db', $_POST['simplyattached_use_db']);
	else
		update_option('simplyattached_use_db', false);

	if (isset($_POST['simplyattached_db_key']))
		update_option('simplyattached_db_key', $_POST['simplyattached_db_key']);
	else
		update_option('simplyattached_db_key', '');

		if (isset($_POST['simplyattached_style']))
		update_option('simplyattached_style', $_POST['simplyattached_style']);
	else
		update_option('simplyattached_style', 'style1');
}


$simplyattached_showfilesize_check = get_option('simplyattached_showfilesize_check', true);
$simplyattached_boldfilesize_check = get_option('simplyattached_boldfilesize_check', true);
$simplyattached_showfilecaption_check = get_option('simplyattached_showfilecaption_check', true);
$simplyattached_showlistingcount_check = get_option('simplyattached_showlistingcount_check', true);
$simplyattached_usecolor_check = get_option('simplyattached_usecolor_check', true);
$simplyattached_rowcolor1 = get_option('simplyattached_rowcolor1', '#fdfcc9');
$simplyattached_rowcolor2 = get_option('simplyattached_rowcolor2', '#faf99e');
$simplyattached_useshortcode_check = get_option('simplyattached_useshortcode_check', false);
$simplyattached_use_db = get_option('simplyattached_use_db', false);
$simplyattached_db_key = get_option('simplyattached_db_key', '');
$simplyattached_style = get_option('simplyattached_style', 'style1');


?>
 <style type="text/css" media="screen">
.colorwell {
    border: 2px solid #fff;
    width: 6em;
    text-align: center;
    cursor: pointer;
}
body .colorwell-selected {
    border: 2px solid #000;
    font-weight: bold;
}
   

   
   
 </style>
<div class="wrap">
<div class="simplyattached-inner">
    <div id="icon-options-general" class="icon32"><br /></div>
     <h2>Simply Attached Options</h2>
    <div id="simplyattached-info"><div class="simplyattached-info-box">
	Do you like Simply Attached?<br><a target="blank" href="http://wordpress.org/support/view/plugin-reviews/simply-attached">Please give a review.</a>
	<br><br>Have ideas for for list style?<br>Need support?<br>
	<a target="blank" href="http://wordpress.org/support/plugin/simply-attached">Leave a comment in the support area.</a>
	</div>
	<div class="simplyattached-info-space"><br></div>
	<div class="simplyattached-info-box">
	<a target="blank" href="http://profiles.wordpress.org/michaelkay/">Contact the author</a>
	</div></div>
    <form action="" method="post">
    <table border=0>
    <tr><td>
            <br><input id="usecolor" name="simplyattached_usecolor_check" type="checkbox"
                       <?php
                        if($simplyattached_usecolor_check == "on" || $simplyattached_usecolor_check == "true" || $simplyattached_usecolor_check == "checked")
                        {
                            echo 'checked="checked"';
                        }?>> Use background colors.

            <br><input id="showfilesize" name="simplyattached_showfilesize_check" type="checkbox"
                       <?php
                        if($simplyattached_showfilesize_check == "on" || $simplyattached_showfilesize_check == "true" || $simplyattached_showfilesize_check == "checked")
                        {
                            echo 'checked="checked"';
                        }?>> Show file size.

            <br><input id="boldfilesize" name="simplyattached_boldfilesize_check"  type="checkbox"
                       <?php
                        if($simplyattached_boldfilesize_check == "on" || $simplyattached_boldfilesize_check == "true" || $simplyattached_boldfilesize_check == "checked")
                        {
                            echo 'checked="checked"';
                        }?>> Bold file size.

            <br><input  id="showfilecaption" name="simplyattached_showfilecaption_check"  type="checkbox" 
                       <?php
                        if($simplyattached_showfilecaption_check == "on" || $simplyattached_showfilecaption_check == "true" || $simplyattached_showfilecaption_check == "checked")
                        {
                            echo 'checked="checked"';
                        }?>> Show caption.

            <br><input  id="showlistingcount" name="simplyattached_showlistingcount_check"  type="checkbox" 
                       <?php
                        if($simplyattached_showlistingcount_check == "on" || $simplyattached_showlistingcount_check == "true" || $simplyattached_showlistingcount_check == "checked")
                        {
                            echo 'checked="checked"';
                        }?>> Show attachment count in summary listings

            <br><br><input  id="useshortcode" name="simplyattached_useshortcode_check"  type="checkbox" 
                       <?php
                        if($simplyattached_useshortcode_check == "on" || $simplyattached_useshortcode_check == "true" || $simplyattached_useshortcode_check == "checked")
                        {
                            echo 'checked="checked"';
                        }?>> Use shortcode <a class="sa-tooltip" href="#">to display attachments:<span class="help"><em>When unchecked, the attachments will only display at the bottom of the post. When checked, the attachments will ONLY display where the shortcode appears in the post (and not at the bottom).</em></span></a>
							<br> <b>[simplyattached]</b>Title for Attachments<b>[/simplyattached]</b>
			<br><br><input id="usedropbox" name="simplyattached_use_db" type="checkbox"
                       <?php
                        if($simplyattached_use_db == "on" || $simplyattached_use_db == "true" || $simplyattached_use_db == "checked")
                        {
                            echo 'checked="checked"';
                        }?>> Use Dropbox
						
			<br><span id="dropboxkey">Your Dropbox <a class="sa-tooltip" href="#">Api key:<span class="help"><em>You will need an API Key from Dropbox to enable the Chooser button. See the Simply Attached page or the Dropbox site (https://www.dropbox.com/developers/apps/create) for more information.<em></span></a>
					<input name="simplyattached_db_key" type="text"
                    <?php echo 'value="' . $simplyattached_db_key . '">';?>   
				</span>		
			<br><br>Attachment list Style:<br><select id="displaystyle" name="simplyattached_style">
						<option value="style1">Style One</option>
						<option value="style2"<?php if($simplyattached_style == "style2") echo ' selected'; ?>>Style Two</option>
						<option value="style3"<?php if($simplyattached_style == "style3") echo ' selected'; ?>>Style Three</option>
						<option value="style4"<?php if($simplyattached_style == "style4") echo ' selected'; ?>>Style Four</option>
						<option value="style5"<?php if($simplyattached_style == "style5") echo ' selected'; ?>>Style Five</option>
						</select>
            <br>
            <br><input type="submit" name="saveattachmentsettings" value="Save Settings">
        </td>
        <td>
			<div id="farbtasticpicker" ></div>
			<div style="width:220px;">
			<div class="form-item"><label for="simplyattached_rowcolor1">Row Highlight 1:</label><input type="text" id="simplyattached_rowcolor1" name="simplyattached_rowcolor1" class="colorwell" value="<?php echo $simplyattached_rowcolor1;?>" /></div>
			<div class="form-item"><label for="simplyattached_rowcolor2">Row Highlight 2:</label><input type="text" id="simplyattached_rowcolor2" name="simplyattached_rowcolor2" class="colorwell" value="<?php echo $simplyattached_rowcolor2;?>" /></div>
			</div>
        </td>
    </tr>
    </table>
    </form>
 
<hr>
<h2>Example (Style One)</h2> <h3>(See a live page for view with your theme.)</h3>

<input type="hidden" id="onattachmentsexamplepage" value="true">

<div id="simplyattached" style="width:600px;">
<h4>Attachments</h4><a name="simplyattached"></a>
<?php
simplyattached_print_sample_list($simplyattached_usecolor_check);
?>
</div>
 
<h2>Example (Style Two)</h2>

<input type="hidden" id="onattachmentsexamplepage" value="true">

<div id="simplyattached-s2" style="width:600px;">
<p></p><h4>Attachments</h4><a name="simplyattached"></a>
<?php
simplyattached_print_sample_list($simplyattached_usecolor_check);
?>
</div>

<h2>Example (Style Three)</h2>

<input type="hidden" id="onattachmentsexamplepage" value="true">

<div id="simplyattached-s3" style="width:600px;">
<p></p><h4>Attachments</h4><a name="simplyattached"></a>
<?php
simplyattached_print_sample_list($simplyattached_usecolor_check);
?>
</div>

<h2>Example (Style Four)</h2>

<input type="hidden" id="onattachmentsexamplepage" value="true">

<div id="simplyattached-s4" style="width:600px;">
<p></p><h4>Attachments</h4><a name="simplyattached"></a>
<?php
simplyattached_print_another_sample_list($simplyattached_usecolor_check);
?>
</div>
<h2>Example (Style Five)</h2>

<input type="hidden" id="onattachmentsexamplepage" value="true">

<div id="simplyattached-s5" style="width:600px;">
<p></p><h4>Attachments</h4><a name="simplyattached"></a>
<?php
simplyattached_print_another_sample_list($simplyattached_usecolor_check);
?>
</div>

</div>
</div>
<?php
function simplyattached_print_sample_list($usecolor)
{
?>
<ol>
 <li
<?php
	if($usecolor) {
		echo ' class="simplyattachedcolor1"';
	} ?>
	><p><a href="#">Title for Attachment 1</a> <span class="attachmentsize">(2.19 MiB)</span>
    <br><span class="attachmentcaption">This is a caption for attachment 1.</span>
    </p>
 </li>
 <li 
 <?php
	if($usecolor) {
		echo ' class="simplyattachedcolor2"';
	} ?>
	><p><a href="#">Another Attachment title</a> <span class="attachmentsize">(417 KiB)</span>
    <br><span class="attachmentcaption">This is a caption for attachment 2.</span>
    </p>
 </li>
 <li 
 <?php
 	if($usecolor) {
		echo ' class="simplyattachedcolor1"';
	} ?>
	><p><a href="#">Some Other Attachment</a><span class="attachmentsize"> (4.5 MiB)</span>
    <br><span class="attachmentcaption">This is a caption for attachment 3.</span>
    </p>
 </li>
</ol>
<?php
}
?>
<?php
function simplyattached_print_another_sample_list($usecolor)
{
?>
<ol>
  <li
<?php
	if($usecolor) {
		echo ' class="simplyattachedcolor1"';
	} ?>
	><p><a href="#">Title for Attachment 1</a> <span class="attachmentsize">(2.19 MiB)</span>
    <br><span class="attachmentcaption">This is a caption for attachment 1.</span>
    </p>
 </li>
 <li 
 <?php
	if($usecolor) {
		echo ' class="simplyattachedcolor2"';
	} ?>
	><p><a href="#">Another Attachment title</a> <span class="attachmentsize">(417 KiB)</span>
    <br><span class="attachmentcaption">This is a caption for attachment 2.</span>
    </p>
 </li>
 <li 
 <?php
 	if($usecolor) {
		echo ' class="simplyattachedcolor1"';
	} ?>
	><p><a href="#">Some Other Attachment</a><span class="attachmentsize"> (4.5 MiB)</span>
    <br><span class="attachmentcaption">This is a caption for attachment 3.</span>
    </p>
 </li>
<li
<?php
	if($usecolor) {
		echo ' class="simplyattachedcolor2"';
	} ?>
	><p><a href="#">Title for Attachment 4</a> <span class="attachmentsize">(6.19 MiB)</span>
    <br><span class="attachmentcaption">This is a caption for attachment 4.</span>
    </p>
 </li>
 <li 
 <?php
	if($usecolor) {
		echo ' class="simplyattachedcolor1"';
	} ?>
	><p><a href="#">Another Attachment title</a> <span class="attachmentsize">(447 KiB)</span>
    <br><span class="attachmentcaption">This is a caption for attachment 5.</span>
    </p>
 </li>
 <li 
 <?php
 	if($usecolor) {
		echo ' class="simplyattachedcolor2"';
	} ?>
	><p><a href="#">Some Other Attachment</a><span class="attachmentsize"> (3.14 MiB)</span>
    <br><span class="attachmentcaption">This is a caption for attachment 6.</span>
    </p>
 </li>
</ol>
<?php
}
?>
