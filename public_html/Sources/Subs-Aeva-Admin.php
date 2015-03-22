<?php
/********************************************************************************
* Subs-Aeva-Admin.php v7
* By Rene-Gilles Deberdt (created by Karl Benson)
*********************************************************************************
* These are extra functions used by the Admin page of the Aeva mod.
*********************************************************************************
* This program is distributed in the hope that it is and will be useful, but
* WITHOUT ANY WARRANTIES; without even any implied warranty of MERCHANTABILITY
* or FITNESS FOR A PARTICULAR PURPOSE.
********************************************************************************/

// Prevent attempts to access this file directly
if (!defined('SMF'))
	die('Hacking attempt...');

///////////////////////////////////////
define('AEVA_VERSION', '7.1.708');
define('AEVA_SITELIST_VERSION', '708');
///////////////////////////////////////

function aeva_admin_settings($is_sites)
{
	global $txt, $context, $modSettings, $sourcedir, $smcFunc, $settings, $config_vars;

	// Increase timeout limit - Reading and writing files may take a little time
	@set_time_limit(600);

	// Should already have been loaded for the menu items/desc etc
	// Just in case, if not, load it. If not in your language, then please load english.
	if (loadlanguage('Aeva') == false)
		loadLanguage('Aeva', 'english');

	// Only show the MASTER setting, if its disabled
	if (empty($modSettings['aeva_enable']))
	{
		return array(
			'&nbsp;',
			array('check', 'aeva_enable'),
		);
	}

	// Test whether lookups work - don't let it run more than once every day, except if we add ;flt (force lookup test) in the URL
	if (!isset($modSettings['aeva_lookup_result']) || ((time() - 24*3600) >= @$modSettings['aeva_lookup_test']) || isset($_GET['flt']))
	{
		// We need to access Aeva's aeva_fetch function to grab url files
		require_once($sourcedir . '/Subs-Aeva.php');

		// Unlikely, but we might need more umph.
		@ini_set('memory_limit', '64M');

		// Domo arigato, misutaa Robotto
		$url = 'http://www.google.com/robots.txt';

		// Fetch the file... Now or never.
		$data = @aeva_fetch($url, true);

		// If we got nothin', try a last time on Noisen...
		if (empty($data) || strlen($data) < 50)
			$data = @aeva_fetch('http://noisen.com/external.gif');

		// Result? If it's empty or too short, then lookups won't work :(
		$modSettings['aeva_lookup_result'] = $test = empty($data) || strlen($data) < 50 ? 0 : 1;

		// Save the result so we don't need to run this again.
		$results = array('aeva_lookup_test' => time(), 'aeva_lookup_result' => $test);
		if (!isset($modSettings['aeva_lookups']))
			$results['aeva_lookups'] = (int) $modSettings['aeva_lookup_result'];
		updateSettings($results);

		$test = $txt['aeva_lookup_' . (empty($test) ? 'fail' : 'success')];
	}
	else
		$test = $txt['aeva_lookup_' . (empty($modSettings['aeva_lookup_result']) ? 'fail' : 'success')];

	if (empty($context['aeva_auto_updating']) && !empty($modSettings['aeva_list_updates']))
	{
		$warning_message = $modSettings['aeva_list_updates'];
		updateSettings(array('aeva_list_updates' => ''));
	}

	$is_curve = $settings['name'] == 'Curve' || $settings['name'] == 'Courbes'; // Replace Courbes with your theme name if you're using a Curve-based theme with a different name!
	$warning_message = (empty($warning_message) ? '' : $warning_message . '<br />') . (isset($modSettings['aeva_latest_version']) && $modSettings['aeva_latest_version'] != AEVA_VERSION ?
			'<span style="color: red">' . sprintf($txt['aeva_new_version'], 'http://custom.simplemachines.org/mods/index.php?mod=977', $modSettings['aeva_latest_version']) .
			'</span> - ' . sprintf($txt['aeva_current_version'], AEVA_VERSION) : sprintf($txt['aeva_current_version'], AEVA_VERSION));

	// If we're on the main settings page, fill the settings list, obviously... Duh!
	if (!$is_sites)
	{
		// Emphasis the result
		$test = $txt['aeva_lookups_desc'] . '<br /><span style="font-weight: bold; color: ' . (empty($modSettings['aeva_lookup_result']) ? 'red' : 'green') . '">' . $test . '</span>';

		// Settings - SMF does the hard work for these. Apart from the various site sections which we have to build ourselves
		$config_vars = array(
			$warning_message,
			'',
			array('check', 'aeva_enable', null, 'aeva_enable'),
			'',
			array('check', 'aeva_lookups', null, 'aeva_lookups', 'subtext' => $test, 'disabled' => $modSettings['aeva_lookup_result'] ? 0 : 1),
			array('select', 'aeva_yq', array(&$txt['aeva_yq_default'], &$txt['aeva_yq_hd'])),
			'',
			array('select', 'aeva_titles', array(&$txt['aeva_titles_yes'], &$txt['aeva_titles_yes2'], &$txt['aeva_titles_no'], &$txt['aeva_titles_no2']), 'subtext' => $txt['aeva_titles_desc']),
			array('check', 'aeva_lookup_titles', 'subtext' => $txt['aeva_lookup_titles_desc']),
			array('select', 'aeva_inlinetitles', array(&$txt['aeva_inlinetitles_yes'], &$txt['aeva_inlinetitles_maybe'], &$txt['aeva_inlinetitles_no']), 'subtext' => $txt['aeva_inlinetitles_desc']),
			'',
			array('check', 'aeva_center', 'subtext' => $txt['aeva_center_desc']),
			array('check', 'aeva_incontext'),
			array('check', 'aeva_quotes'),
			array('check', 'aeva_fix_html'),
			array('check', 'aeva_includeurl', 'subtext' => $txt['aeva_includeurl_desc']),
			'',
			array('check', 'aeva_noscript', 'subtext' => $txt['aeva_noscript_desc']),
			array('check', 'aeva_expins', 'subtext' => $txt['aeva_expins_desc']),
			array('check', 'aeva_debug', 'subtext' => $txt['aeva_debug_desc']),
			array('check', 'aeva_checkadmin', 'subtext' => $txt['aeva_checkadmin_desc']),
			'',
			array('int', 'aeva_max_width', 'subtext' => $txt['aeva_max_width_desc']),
			array('int', 'aeva_max_per_post'),
			array('int', 'aeva_max_per_page'),
			$is_curve ? array('title', 'aeva_local_title', '', $txt['aeva_local']) : $txt['aeva_local'],
			array('check', 'aeva_nonlocal', 'subtext' => $txt['aeva_nonlocal_desc']),
			array('check', 'aeva_mp3'),
			array('check', 'aeva_mp4'),
			array('check', 'aeva_flv'),
			array('check', 'aeva_avi'),
			array('check', 'aeva_divx'),
			array('check', 'aeva_mov'),
			array('check', 'aeva_wmp'),
			array('check', 'aeva_real'),
			array('check', 'aeva_swf'),
			$is_curve ? '' : ' ',
		);

		if (!isset($_GET['save']))
			return $config_vars;

		// These need to be within limits, and max per page >= max per post
		$_POST['aeva_max_per_page'] = min(1000, max(1, (int) @$_POST['aeva_max_per_page']));
		$_POST['aeva_max_per_post'] = min(min(1000, max(1, (int) @$_POST['aeva_max_per_post'])), $_POST['aeva_max_per_page']);
	}

	// Clear sites that may have already been loaded (possibly for news and such)
	$sites = array();

	// Avoid errors - we'll use full in an emergency
	$definitions = 'full';

	// If we're not saving, or if we're not in the site list, we will want to find out about enabled sites.
	if ((!$is_sites && isset($_GET['save'])) || ($is_sites && !isset($_GET['save'])))
	{
		// Attempt to Load - Enabled Sites
		@include($sourcedir . '/Subs-Aeva-Generated-Sites.php');

		// Site definitions
		if (empty($sites))
			$definitions = 'full';
		elseif ($sites[0] == 'none')
		{
			// No enabled sites
			$definitions = 'none';
			$enabled_sites = array();
		}
		else
		{
			// Generated set means that we have an optimized array with only the enabled sites in it
			$definitions = 'generated';

			// Only count as enabled, sites with an actual ID
			foreach (array_keys($sites) as $site)
				if (!empty($sites[$site]['id']))
					$enabled_sites[$sites[$site]['id']] = 1;
		}

		// Clear static
		$sites = array();
	}

	// Load the FULL definitions into the $sites static
	@include($sourcedir . '/Subs-Aeva-Sites.php');

	// Checkall helps us decide whether to make the checkboxes all checked="checked"
	$checkall = array('pop' => true, 'video' => true, 'audio' => true, 'adult' => true, 'other' => true);
	// Create arrays to store bits of information/organize them into various sections
	$stypes = array('local', 'pop', 'video', 'audio', 'adult', 'other');

	if (file_exists($sourcedir . '/Subs-Aeva-Custom.php'))
	{
		@include($sourcedir . '/Subs-Aeva-Custom.php');
		$checkall['custom'] = true;
		$stypes[] = 'custom';
	}

	$sitelist = array();
	foreach ($stypes as $stype)
		$sitelist[$stype] = array();

	// Prepare to organize the sites into specific sections
	foreach (array_keys($sites) as $site)
	{
		$s = &$sites[$site];

		// Make sure it has the enabled setting
		$s['disabled'] = !empty($s['disabled']);

		// Override the default setting, based on which sites are enabled
		if ($definitions == 'generated')
			$s['disabled'] = empty($enabled_sites[$s['id']]);
		elseif ($definitions == 'none')
			$s['disabled'] = true;

		// Checkall - whether the checkall setting for each section is checked. It won't be if just one is unchecked.
		if ($s['disabled'])
			$checkall[$s['type']] = false;

		// Store in arrays organized for different types of supported sites
		// We only need the local ones on saving
		if (isset($s['type'], $sitelist[$s['type']]) && ($s['type'] != 'local' || isset($_GET['save'])))
			$sitelist[$s['type']][] = $s;
	}

	// Clear static
	$sites = array();

	// Build the blocks for checkboxes for each clip section (Except if saving)
	if (!isset($_GET['save']))
	{
		$config_vars = array(
			$warning_message,
			'<span style="font-weight: normal; color: ' . (empty($modSettings['aeva_lookup_result']) ? 'red' : 'green') .
			'" class="smalltext">' . $txt['aeva_' . (empty($modSettings['aeva_lookup_result']) ? 'fish' : 'denotes')] . '</span>'
		);

		foreach ($stypes as $stype)
			if (!empty($sitelist[$stype]))
				$config_vars[] = aeva_settings($sitelist[$stype], $stype, $checkall);
	}
	else
	{
		// Saving? Check session...
		if (empty($context['aeva_auto_updating']))
			checkSession();

		// Prepare/optimize the arrays for the generated file by removing disabled sites and unneeded details
		foreach ($stypes as $stype)
			if (!empty($sitelist[$stype]))
				$sitelist[$stype] = aeva_prepare_sites($sitelist[$stype], $stype, $is_sites);

		// Writes/outputs a php file of all the ENABLED sites only
		aeva_write_file($sitelist);
	}

	// Tidy up
	unset($sitelist, $stypes, $site, $checkall, $a, $b, $enabled_sites);

	return isset($config_vars) ? $config_vars : array();
}

function aeva_update_sitelist()
{
	global $modSettings, $sourcedir, $context;

	$mod_url = 'http://custom.simplemachines.org/mods/index.php?mod=977';
	$download_url = 'http://custom.simplemachines.org/mods/index.php?action=download;mod=977;id=';
	$preg_mod_url = preg_quote('<a href="' . $download_url) . '([0-9]+)"\>';
	@set_time_limit(900);
	$data = @aeva_fetch($mod_url);

	if (strlen($data) > 1000)
	{
		$latest_version = preg_match('~<dt>Latest Version:</dt>\s*?<dd>([^<]+)</dd>~', $data, $latest_version) ? $latest_version[1] : AEVA_VERSION;
		$latest_sitelist = preg_match('~' . $preg_mod_url . 'Subs-Aeva-Sites\.php\</a\>~', $data, $latest_sitelist) ? $latest_sitelist[1] : AEVA_SITELIST_VERSION;
		$current_id = !empty($modSettings['aeva_latest_sitelist']) ? $modSettings['aeva_latest_sitelist'] :
						(preg_match('~' . $preg_mod_url . '[^<]+?' . preg_quote(AEVA_VERSION) . '[^<]+?\</a\>~', $data, $current_id) ? $current_id[1] : 0);

		if ($latest_sitelist > $current_id)
		{
			$new_sitelist = @aeva_fetch($download_url . $latest_sitelist[1]);
			if (substr($new_sitelist, -2) == '?>')
			{
				$aeva_min = preg_match('~\$aeva_min \= ([0-9]+)~', $new_sitelist, $aeva_min) ? $aeva_min[1] : 0;
				$len = strlen($new_sitelist);
				if ($len > 100000 && ($aeva_min <= AEVA_SITELIST_VERSION))
				{
					$sites = array();
					@include($sourcedir . '/Subs-Aeva-Sites.php');
					$my_file = fopen($sourcedir . '/Subs-Aeva-Sites.php', 'w');
					if (!$my_file)
						return;
					fwrite($my_file, $new_sitelist, $len);
					fclose($my_file);
					aeva_show_sitelist_updates();
					$sites = array();
					$update_me = true;
				}
			}
		}

		updateSettings(array('aeva_latest_version' => $latest_version, 'aeva_latest_sitelist' => $latest_sitelist, 'aeva_version_test' => time()));
	}

	if (empty($update_me))
		return;

	// Now it'll be easier to just simulate calling the Aeva admin area to save the updated sitelist, won't it?
	$gs = $_GET;
	$ps = $_POST;
	$_GET['save'] = true;
	$context['aeva_auto_updating'] = true;
	foreach ($modSettings as $ms => $cms)
		if (substr($ms, 0, 5) == 'aeva_' && !empty($cms))
			$_POST[$ms] = $cms;
	aeva_admin_settings(false);
	$_GET = $gs;
	$_POST = $ps;
	unset($gs, $ps);
}

function aeva_show_sitelist_updates()
{
	global $sites, $txt, $sourcedir, $modSettings, $smcFunc;

	if (!isset($txt['aeva']) && loadlanguage('Aeva') == false)
		loadLanguage('Aeva', 'english');

	$alu = empty($modSettings['aeva_list_updates']) ? '' : $modSettings['aeva_list_updates'];
	$old_sites = $new_sites = array();
	foreach ($sites as $s)
		$old_sites[$s['id']] = $s['title'] . '@' . serialize($s);

	$sites = array();
	@include($sourcedir . '/Subs-Aeva-Sites.php');
	foreach ($sites as $s)
		$new_sites[$s['id']] = $s['title'] . '@' . serialize($s);
	unset($sites);

	$sites_added = function_exists('array_diff_key') ? array_diff_key($new_sites, $old_sites) : array_flip(array_diff(array_flip($new_sites), array_flip($old_sites)));
	$sites_removed = function_exists('array_diff_key') ? array_diff_key($old_sites, $new_sites) : array_flip(array_diff(array_flip($old_sites), array_flip($new_sites)));
	$sites_modified = array();
	foreach (array_keys($new_sites) as $a)
		if (isset($old_sites[$a]) && $old_sites[$a] != $new_sites[$a])
			$sites_modified[] = $new_sites[$a];

	$msg = '';
	$sb = isset($smcFunc) ? array('</strong><br />', '<strong> ') : array('</b><br />', '<b> ');
	if (count($sites_added) > 0)
	{
		$msg .= $sb[0] . $txt['aeva_sitelist_added'] . $sb[1];
		foreach ($sites_added as $s)
			$msg .= substr($s, 0, strpos($s, '@')) . ', ';
		$msg = substr($msg, 0, -2) . '<br />';
	}
	if (count($sites_removed) > 0)
	{
		$msg .= $sb[0] . $txt['aeva_sitelist_removed'] . $sb[1];
		foreach ($sites_removed as $s)
			$msg .= substr($s, 0, strpos($s, '@')) . ', ';
		$msg = substr($msg, 0, -2) . '<br />';
	}
	if (count($sites_modified) > 0)
	{
		$msg .= $sb[0] . $txt['aeva_sitelist_modified'] . $sb[1];
		foreach ($sites_modified as $s)
			$msg .= substr($s, 0, strpos($s, '@')) . ', ';
		$msg = substr($msg, 0, -2) . '<br />';
	}
	unset($sites_added, $sites_removed, $sites_modified);

	// Nothing changed? Then it's a false alarm, keep it silent.
	if (empty($msg))
		return;

	updateSettings(array('aeva_list_updates' => empty($alu) ? '<span style="color: red">' . $txt['aeva_sitelist_updated'] . '</span><br />' . $msg : $alu . $msg));
}

// Removes disabled sites, and removes information we won't need.
function aeva_prepare_sites($array, $type, $is_sites)
{
	global $modSettings;

	if ($is_sites && $type != 'local' && (empty($_POST['aeva_'.$type]) || !is_array($_POST['aeva_'.$type])))
		return array();

	// Unset our KNOWN unnecessary information - this way it won't interfere with future variables, upgrading, or any custom variables you decide to use.

	// These are NEVER needed
	$fields = array('title', 'website', 'type', 'disabled', 'added');

	// Lookups are disabled, so get rid of all unnecessary information
	if ($type != 'local' && ($is_sites ? empty($modSettings['aeva_lookups']) : empty($_POST['aeva_lookups'])))
		$fields = array_merge($fields, array(
			'lookup-url', 'lookup-title', 'lookup-title-skip', 'lookup-pattern', 'lookup-actual-url',
			'lookup-final-url', 'lookup-unencode', 'lookup-urldecode', 'lookup-skip-empty')
		);

	// If fixing embed html is disabled, add that to the fields to drop (is likely to be bandwidth saving with this one)
	if ($type != 'local' && ($is_sites ? empty($modSettings['aeva_fix_html']) : empty($_POST['aeva_fix_html'])))
		$fields = array_merge($fields, array('fix-html-pattern', 'fix-html-url'));

	// Unset video sites from arrays which are disabled
	foreach ($array as $a => $b)
	{
		if ($type == 'local')
		{
			// No plugin, then it can't be a local one, so unset it
			if (empty($b['plugin']))
				unset($array[$a], $b);
			// Don't save data if box was unchecked or option was disabled
			elseif ($is_sites ? empty($modSettings['aeva_' . substr($b['id'], 6)]) : !isset($_POST['aeva_' . substr($b['id'], 6)]))
				unset($array[$a], $b);
		}
		// Site disabled? Skip it
		elseif ($is_sites ? !isset($_POST['aeva_' . $type][$b['id']]) : $b['disabled'])
			unset($array[$a], $b);
		elseif (isset($b['plugin']) && $b['plugin'] == 'flash')
			unset($array[$a]['plugin']);

		// Drop each one of those fields from our array if it exists
		if (!empty($b))
			foreach ($fields as $c)
				unset($array[$a][$c]);

		if (isset($array[$a]['lookup-title']) && ($is_sites ? !empty($modSettings['aeva_titles']) : !empty($_POST['aeva_titles']))
		&& (empty($array[$a]['lookup-title-skip']) || (!empty($modSettings['aeva_titles']) && ($modSettings['aeva_titles'] % 2 == 1))))
			unset($array[$a]['lookup-title']);
	}

	return $array;
}

// Generates the file containing optimized arrays (ONLY enabled sites with only necessary information
function aeva_write_file($arrays)
{
	global $sourcedir;

	// Filename 
	$filename = $sourcedir . '/Subs-Aeva-Generated-Sites.php';

	// Chmod - suppress errors, especially for Windows
	@chmod($filename, 0777);

	// Open file for writing (replacing what's there)
	$fp = fopen($filename, 'w');

	// Comment header - left-justified
	$page = '<?php
/********************************************************************************
* Subs-Aeva-Generated-Sites.php v6
* By Rene-Gilles Deberdt (created by Karl Benson)
*********************************************************************************
* The full/complete definitions are now stored in Subs-Aeva-Sites.php
* This is a GENERATED php file containing ONLY ENABLED sites for the Aeva Mod,
* and is created when enabling/disabling sites via the admin panel.
* It\'s more efficient this way.
*********************************************************************************
* This program is distributed in the hope that it is and will be useful, but
* WITHOUT ANY WARRANTIES; without even any implied warranty of MERCHANTABILITY
* or FITNESS FOR A PARTICULAR PURPOSE.
********************************************************************************/

';

	// If no sites are enabled, then exit early
	if (count($arrays) == count($arrays, COUNT_RECURSIVE))
	{
		// Last piece, and close the file early
		$page .= 'global $sites;
$sites = array(\'none\');
				/* No Sites Are Enabled */
?>';
		fwrite($fp, $page);
		fclose($fp);
		return;
	}

	// Ok we've got some enabled sites to output, start the array
	$page .= 'global $sites;
$sites = array(';
	fwrite($fp, $page);

	foreach ($arrays as $one_array)
		if (!empty($one_array))
			fwrite($fp, aeva_generate_sites($one_array));

	// Last piece, and close the file
	$page = '
);
?>';
	fwrite($fp, $page);
	fclose($fp);
}

// Returns a string with the sites in array - ONLY necessary pieces are included for optimized/effiency
function aeva_generate_sites(&$array)
{
	$page = '';
	foreach ($array as $a)
	{
		$page .= '
	array(';

		// Bools show as bools, the rest shows in single quotes. (Re-adding them because PHP stripped them.)
		foreach ($a as $b => $c)
			if (isset($c) && $c !== '')
				if (is_array($c) && $b == 'size')
				{
					$page .= "
		'$b' => array(";
					if (isset($c['normal']))
					{
						foreach ($c as $d => $e)
							$page .= "'$d' => array(" . $e[0] . ', ' . $e[1] . '), ';
						$page = substr($page, 0, -2) . '),';
					}
					else
						$page .= implode($c, ', ') . '),';
				}
				elseif (is_array($c))
				{
					$page .= "
		'$b' => array(";
					foreach ($c as $d => $e)
						$page .= "'$d' => " . (is_int($e) ? $e . ', ' : "'" . str_replace("'", "\'", $e) . "', ");
					$page = substr($page, 0, -2) . '),';
				}
				else
					$page .= "
		'$b' => " . ($b == 'ui-height' ? (int) $c : (is_bool($c) || is_int($c) ? ($c == true ? 'true' : 'false') : "'" . str_replace('\'', '\\\'', $c) . "'")) . ",";

		$page .= '
	),';
	}
	return $page;
}

// Helps to write out the admin settings for the Aeva mod for each types of sites
function aeva_settings($array, $type = 'video', $checkall = array())
{
	// Access globals
	global $txt, $modSettings, $context, $smcFunc, $settings, $config_vars;

	// Togglebar - If it doesn't exist (eg 1.1.x) or it does and is 1, then the admin area is in sidebar form (limit to 3 columns eg 33%). Else, its in dropdown (limit to 4 columns eg 25%)
	$cols = !isset($context['admin_preferences']['tb']) || $context['admin_preferences']['tb'] == 1 ? 3 : 4;

	// Weird html, but it hacks its way into the html created by SMF. That's why some tags start opened.
	if (isset($smcFunc))
	{
		$page = '</strong>
		<table border="0" class="smalltext" width="100%">';
		$config_vars[] = array('title', 'aeva_sites_' . $type, '', '<strong>' . $txt['aeva_' . $type] . ' (' . count($array) . ')' . '</strong> - <input type="checkbox" id="checkall_' . $type . '" onclick="invertAll(this, this.form, \'aeva_' . $type . '\');" ' . (!empty($checkall[$type]) ? 'checked="checked" ' : '') . '/><label class="smalltext" for="checkall_' . $type . '"> <em>' . $txt['aeva_select'] . '</em></label>');
	}
	else
		$page = (isset($smcFunc) ? '</strong>' : '</b>') . '</td>
						</tr>
						<tr class="titlebg">
							<td>&nbsp;</td>
							<td valign="middle" colspan="2">' . (isset($smcFunc) ? '<strong>' : '<b>') . $txt['aeva_' . $type] . ' (' . count($array) . ')' . (isset($smcFunc) ? '</strong>' : '</b>') . ' - <input type="checkbox" id="checkall_' . $type . '" onclick="invertAll(this, this.form, \'aeva_' . $type . '\');" ' . (!empty($checkall[$type]) ? 'checked="checked" ' : '') . '/><label class="smalltext" for="checkall_' . $type . '"> <em>' . $txt['aeva_select'] . '</em></label></td>
						</tr>
						<tr class="windowbg2">
							<td class="windowbg2">&nbsp;</td>
							<td class="windowbg2" colspan="2">
		<table border="0" class="smalltext" width="100%">';

	// We're going to have 3 columns to fill. First we need to know how many sites we have in this section.
	$j = count($array) - 1;

	// Start with column 1
	$k = 1;

	// Item 0
	$i = 0;
	$colwid = floor(100/$cols);
	$colwid5 = floor((100-($cols*5))/$cols);

	// Now for the magic block builder
	foreach ($array as $arr)
	{
		// Column 1 starts with opening a row.
		if ($k == 1)
			$page .= '
		<tr>';

		// Build this cell.
		$page .= '
			<td width="5%">
				<input type="checkbox" title="' . $arr['title'] . '" name="aeva_' . $type . '[' . $arr['id'] . ']" id="aeva_' . $type . '_' . $arr['id'] . '" value="1"' . ($arr['disabled'] ? '' : ' checked="checked"') .' class="check" />
			</td>
			<td width="' . $colwid5 . '%"><label for="aeva_' . $type . '_' . $arr['id'] . '" title="' . $arr['title'] . (!empty($arr['website']) ? ' - ' . $arr['website'] . '">
				<a href="' . $arr['website'] . '" target="_blank">' . $arr['title'] . '</a>' : '">' . $arr['title']) .
				(!empty($arr['lookup-url']) ? '<span style="color: ' . (empty($modSettings['aeva_lookup_result']) ? 'red' : 'green') . '">*</span>' : '') . '
			</label></td>';

		// Last one, but we have remaining column cells to fill
		if ($i == $j && $k == 1)
			$page .= '<td colspan="' . ($cols - $k)*2 . '" width="' . $colwid * ($cols-$k) . '%">&nbsp;</td></tr>';
		elseif ($i == $j && ($k == 2 || ($k == 3 && $cols == 4)))
			$page .= '<td colspan="' . ($cols - $k)*2 . '" width="' . $colwid * ($cols-$k) . '%">&nbsp;</td></tr>';
		elseif (($k == 3 && $cols == 3) || ($k == 4 && $cols == 4))
			$page .= '
		</tr>';

		// Increase the item
		$i++;
		// Increase the column
		$k++;
		// Once we reached the third column, reset us back to column 1
		$k = (($k > 3 && $cols == 3) || ($k > 4 && $cols == 4))? 1 : $k;
	}
	// Don't forget to close this table
	$page .= '</table>' . (isset($smcFunc) ? '<strong>' : '<b>');

	// Return this block, so it can be included with the settings to make it format correctly.
	return $page;
}

function aeva_admin_manager($return_config, $page = 'aeva')
{
	global $txt, $scripturl, $context, $smcFunc;

	$config_vars = aeva_admin_settings($page == 'aevasites');
	if (!empty($page))
		$context['settings_title'] = $txt['aeva_admin_' . $page];

	if (isset($smcFunc)) // SMF 2.x?
	{
		$context[$context['admin_menu_name']]['current_subsection'] = $page;
		$context[$context['admin_menu_name']]['tab_data']['title'] = $context['page_title'] = $txt['aeva_title'];

		if ($return_config)
			return $config_vars;

		$context['post_url'] = $scripturl . '?action=admin;area=modsettings;sa=' . $page . ';save';

		if (isset($_GET['save']))
		{
			checkSession();

			if (!empty($config_vars))
				saveDBSettings($config_vars);

			redirectexit('action=admin;area=modsettings;sa=' . $page);
		}
		prepareDBSettingContext($config_vars);
		return true;
	}

	// Change title and description of the headers/sections
	$context['admin_tabs']['description'] = $txt['aeva_desc'];
	$context['admin_tabs']['title'] = $context['page_title'] = $txt['aeva_title'];

	// Saving?
	if (isset($_GET['save']))
	{
		if (!empty($config_vars))
			saveDBSettings($config_vars);

		redirectexit('action=featuresettings;sa=' . $page);

		loadUserSettings();
		writeLog();
	}

	// Fix descriptions (due to a 1.1.x bug)
	foreach ($config_vars as $a)
		if (isset($a['subtext'], $a[1], $txt[$a[1]]))
			$txt[$a[1]] .= '<div class="smalltext">' . $a['subtext'] . '</div>';

	$context['post_url'] = $scripturl . '?action=featuresettings2;save;sa=' . $page;

	prepareDBSettingContext($config_vars);
}

?>