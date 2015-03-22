<?php
/**********************************************************************************
* ForumFirewall_Admin.template.php - PHP template for ForumFirewall mod
* Version 1.0.10 by JMiller a/k/a butchs
* (http://www.eastcoastrollingthunder.com) 
*********************************************************************************
* This program is distributed in the hope that it is and will be useful, but
* WITHOUT ANY WARRANTIES; without even any implied warranty of MERCHANTABILITY
* or FITNESS FOR A PARTICULAR PURPOSE.
**********************************************************************************/

function template_forumfirewall_settings()
{
//  Not used
}

function template_forumfirewall_reports()
{
	global $smcFunc, $context, $txt, $settings, $scripturl;

	$context['start'] = (int) $_REQUEST['start'];

	// Distribute query search
	$type ='';
//	if ($context['sub_action'] == 'report_denied')
		$type = $txt['forumfirewall_type_den'];

	echo '
	<link rel="stylesheet" type="text/css" href="', $settings['theme_url'], '/css/forumfirewall.css?rc3" />
	<div id="admincenter"><div class="title_bar">
	<h4 class="titlebg"><span class="ie6_header floatleft">'.$txt['forumfirewall_log_title'].'</span></h4></div>';

	echo '
	<div class="pagesection">', $txt['pages'], $txt['forumfirewall_colin'], $context['page_index'], '</div>';

	//display the table title
	echo '
	<table width="100%" class="table_grid" style="table-layout:fixed;word-wrap:break-word;width:650px;">
	<thead>
	<tr class="catbg">
		<th class="first_th smalltext" style="width:40px">'.$txt['forumfirewall_log_id'].'</th>
		<th class="smalltext" style="width:120px">'.$txt['forumfirewall_log_ip'].'</th>
		<th class="smalltext" style="width:90px"><a href="', $scripturl, '?action=admin;area=forumfirewall;sa='.$context['sub_action'].';sort_by=date', $context['order_by'] == 'up' ? ';desc' : '', ';start=', $context['start'], '">' . $txt['forumfirewall_log_date'], $context['sort_by'] == 'date' ? '&nbsp;<img src="' . $settings['images_url'] . '/sort_' . $context['order_by'] . '.gif" alt="" />' : '', '</a></th>
		<th class="smalltext" style="width:360px">'.$txt['forumfirewall_log_headers'].'</th>
		<th class="last_th smalltext" style="width:90px">'.$txt['forumfirewall_log_result'].'</th>
	</tr></thead><tbody>';

	$alternate_rows = false;
	$row_color = 'windowbg2';

	if (empty($context['forumfirewall_log']))
		echo '
			<tr class="windowbg2"><td colspan="10">'.$txt['forumfirewall_empty'].'</td></tr>';
	else {
		foreach ($context['forumfirewall_log'] as $display) {

      if ($alternate_rows == true) { //Alternating colors for each row
        $alternate_rows = false;
        $row_color = 'windowbg2';
      } else {
        $alternate_rows = true;
        $row_color = 'windowbg'; }

	//display the table
	echo '
	<tr class="'.$row_color.' '.$row_color.'_hover smalltext">
		<td>'.$display['ffid'].'</td>
		<td><a href="' . $scripturl . '?action=trackip;searchip=' . $display['ip'] . '">'.$display['ip'].'</a></td>
		<td>'.$display['date'].'</td>
		<td>'.$display['http_headers'].'</td>
		<td>'.$display['result'].'</td>
	</tr>';
		}
	}

  echo '
	</tbody></table>'.$txt['forumfirewall_rec_disp'].'&nbsp;&nbsp;<b>'.$context['cf_where'].'</b>'.$txt['forumfirewall_to'].'<b>'.$context['cf_page_num'].'</b>&nbsp;&nbsp;'.$txt['forumfirewall_from'].'<b>'.$context['cf_per_page'].'</b>'.$txt['forumfirewall_rec_tot'].$type.'</div><br class="clear" />';
}

function template_forumfirewall_about()
{
	global $txt;
	
	echo '
	<div id="admincenter"><div class="cat_bar">
	<h3 class="catbg"><span class="ie6_header floatleft">'.$txt['forumfirewall_version_c'].'</span></h3></div>';

	echo'
      <div class="windowbg2"><span class="topslice"><span></span></span><div class="content">
		<strong>
		'.$txt['forumfirewall_cversion'].': ' . $txt['forumfirewall_cversion_mod'] . '<br />
		'.$txt['forumfirewall_mauthor'].'
		</strong></div><span class="botslice"><span></span></span></div>';

	echo'
      <div class="windowbg2"><span class="topslice"><span></span></span><div class="content">
		<strong><p>'.$txt['forumfirewall_msupport'].'</p></strong>
		</div><span class="botslice"><span></span></span></div>
		</div>';

	echo'
      <div class="windowbg2"><span class="topslice"><span></span></span><div class="content">
		<p>'.$txt['forumfirewall_oview'].'</p>
		</div><span class="botslice"><span></span></span></div><br class="clear" />';
}

?>