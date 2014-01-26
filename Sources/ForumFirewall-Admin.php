<?php
/**********************************************************************************
* ForumFirewall-Admin.php - PHP template for ForumFirewall mod
* Version 1.1.2 by JMiller a/k/a butchs
* (http://www.eastcoastrollingthunder.com) 
*********************************************************************************
* This program is distributed in the hope that it is and will be useful, but
* WITHOUT ANY WARRANTIES; without even any implied warranty of MERCHANTABILITY
* or FITNESS FOR A PARTICULAR PURPOSE.
**********************************************************************************/

if (!defined('SMF')) die('Hacking attempt...');

// Admin initializer
function forumfirewall_admin_init($return_config = false) {
	global $txt, $scripturl, $context, $settings, $sc, $modSettings;
	global $smcFunc, $sourcedir, $user_info, $ffcachedir, $boarddir;

	require_once($sourcedir . '/ManageMembers.php');
	require_once($sourcedir . '/Security.php');

	$ftest = '';
	$ftest = file_exists($boarddir . '/ffcache');
	if (empty($ftest)) $ftest = @is_dir($ffcachedir);
	if (empty($ftest)) {
		 if (((strtolower(@ini_get('safe_mode')) != 'on') || (strtolower(@ini_get('safe_mode')) != 'yes') || (strtolower(@ini_get('safe_mode')) != 'true') || (@ini_get("safe_mode") != 1 )))
			$ftest = exec(escapeshellcmd("ls ".$ffcachedir));	}
	if (empty($ftest)) {
			if (function_exists('loadlanguage')) {
				if(loadlanguage('Errors') === false)
   		  		 	loadLanguage('Errors');
			} else {
				require_once($sourcedir . '/Load.php');
				if(loadlanguage('Errors') === false)
   		   			loadLanguage('Errors'); }

		log_error($txt['cfcachef'], 'user');
	}

	if (empty($_REQUEST['sa']))
		$_REQUEST['sa'] = '';

	$config_vars = array(
	);

	if ($return_config)
		return $config_vars;

	isAllowedTo('admin_forum');

	 loadTemplate('ForumFirewall_Admin');
	 loadLanguage('ForumFirewall');

	$context['html_headers'] .= '
		<link rel="stylesheet" type="text/css" href="' . $settings['theme_url'] . '/css/forumfirewall.css?rc3" />';
	$context['sub_template'] = 'show_settings';
	$context['page_title'] = $txt['forumfirewall_config'];
	$context['sub_action'] = $_REQUEST['sa'];
	$context['settings_message'] = '<br /><div class="forumfirewall_fail">' . forumfirewall_inspect_system() . '</div><br />';


	$context[$context['admin_menu_name']]['tab_data'] = array(
		'title' => &$txt['forumfirewall_admin'],
		'description' => $txt['forumfirewall_admin_desc'],
		'tabs' => array(
			'forumfirewall_settings' => array(
				'description' => $txt['forumfirewall_settings_desc'],
				'href' => $scripturl . '?action=admin;area=forumfirewall;sa=settings',
				'is_selected' => $_REQUEST['sa'] == 'forumfirewall_settings',
			),
			'forumfirewall_report_denied' => array(
				'description' => $txt['forumfirewall_reports_desc'],
				'href' => $scripturl . '?action=admin;area=forumfirewall;sa=forumfirewall_report_denied',
				'is_selected' => $_REQUEST['sa'] == 'forumfirewall_report_denied',
			),
			'forumfirewall_about' => array(
				'description' => $txt['forumfirewall_about_desc'],
				'href' => $scripturl . '?action=admin;area=forumfirewall;sa=about',
				'is_selected' => $_REQUEST['sa'] == 'forumfirewall_about',
			),
		),
	);

	$subActions = array(
		'forumfirewall_settings' => 'forumfirewall_admin_settings',
		'forumfirewall_report_denied' => 'forumfirewall_admin_reports',
		'forumfirewall_about' => 'forumfirewall_admin_about',
	);

	// By default go to the settings.
	$_REQUEST['sa'] = isset($_REQUEST['sa']) && isset($subActions[$_REQUEST['sa']]) ? $_REQUEST['sa'] : 'forumfirewall_settings';

	// Call the function for the sub-acton.
	$subActions[$_REQUEST['sa']]();
}

function forumfirewall_admin_settings($return_config = false)
{
	global $smcFunc, $txt, $scripturl, $context, $sourcedir, $modSettings, $db_prefix;

	require_once($sourcedir.'/ManageServer.php');

	loadLanguage('ForumFirewall');

	if ($modSettings['forumfirewall_domain'] == 'www.yourdomain.com')
		$modSettings['forumfirewall_enable_bypass'] = '0';

	if ($modSettings['forumfirewall_ip'] == '00.00.00.00')
		$modSettings['forumfirewall_enable_bypass'] = '0';

	$config_vars = array(
	    '',
		$txt['forumfirewall_general'],
	    '',
		array('check', 'forumfirewall_enable'),
		array('check', 'forumfirewall_enable_block'),
		array('check', 'forumfirewall_logging'),
		array('int', 'forumfirewall_cache_duration', '2', 'forumfirewall_cache_duration'),
		array('text', 'forumfirewall_salt'),
		array('select', 'forumfirewall_enable_email', array(&$txt['forumfirewall_email_diabled_0'], &$txt['forumfirewall_email_ddos_1'], &$txt['forumfirewall_email_all_2'])),
	    '',
		$txt['forumfirewall_dos_attack'],
	    '',
		array('check', 'forumfirewall_enable_ua'),
		array('check', 'forumfirewall_enable_dos'),
		array('large_text', 'forumfirewall_good_ua'),
		array('text', 'forumfirewall_trigger'),
		array('select', 'forumfirewall_longterm_ban', array(&$txt['forumfirewall_never_0'], &$txt['forumfirewall_1hr_1'], &$txt['forumfirewall_24hr_2'], &$txt['forumfirewall_1wk_3'], &$txt['forumfirewall_permanent_4'])),
	    '',
		$txt['forumfirewall_ip_title'],
	    '',
		array('check', 'forumfirewall_enable_check_ip'),
		array('check', 'forumfirewall_enable_proxy'),
		array('check', 'forumfirewall_enable_admin'),
		array('text', 'forumfirewall_admin_ip_lo'),
		array('text', 'forumfirewall_admin_ip_hi'),
		array('text', 'forumfirewall_admin_domain'),
	    '',
		$txt['forumfirewall_robots_title'],
	    '',
		array('check', 'forumfirewall_enable_robots'),
		array('large_text', 'forumfirewall_test_robots'),
		array('large_text', 'forumfirewall_robotstxt_action'),
	    '',
		$txt['forumfirewall_port_title'],
	    '',
		array('check', 'forumfirewall_enable_rmtport'),
		array('check', 'forumfirewall_enable_svrport'),
		array('text', 'forumfirewall_good_ser_ports'),
	    '',
		$txt['forumfirewall_injection'],
	    '',
		array('check', 'forumfirewall_enable_inj'),
		array('large_text', 'forumfirewall_uri_chars'),
		array('large_text', 'forumfirewall_exploits'),
	    '',
		$txt['forumfirewall_cookie'],
	    '',
		array('check', 'forumfirewall_enable_xxs'),
		array('large_text', 'forumfirewall_xxs'),
	    '',
		$txt['forumfirewall_header'],
	    '',
		array('check', 'forumfirewall_enable_header'),
		array('large_text', 'forumfirewall_referer_attack'),
		array('large_text', 'forumfirewall_ua_attack'),
		array('large_text', 'forumfirewall_entity_attack'),
	    '',
		$txt['forumfirewall_country'],
	    '',
		array('check', 'forumfirewall_enable_country'),
		array('check', 'forumfirewall_in_geoip'),
		array('text', 'forumfirewall_country_id'),
		array('large_text', 'forumfirewall_bad_countries'),
	    '',
		$txt['forumfirewall_bypass'],
	    '',
		array('text', 'forumfirewall_real_ip', '25'),
		array('text', 'forumfirewall_header_id'),
		array('check', 'forumfirewall_enable_bypass'),
		array('text', 'forumfirewall_domain', '32'),
		array('text', 'forumfirewall_ip'),
		);

	if ($return_config)
		return $config_vars;

	$context['post_url'] = $scripturl .'?action=admin;area=forumfirewall;save;sa=settings';
	$context['page_title'] = $txt['forumfirewall_settings_sub'];
	loadTemplate('ForumFirewall_Admin');
	$context['sub_template'] = 'show_settings';

	if (isset($_GET['save']))
	{
		checkSession();
		saveDBSettings($config_vars);
		redirectexit('action=admin;area=forumfirewall');
	}

	prepareDBSettingContext($config_vars);
}


function forumfirewall_admin_about()
{
	global $txt, $scripturl, $context;

	isAllowedTo('admin_forum');

	$context['sub_template'] = 'forumfirewall_about';
	$context['page_title'] = $txt['forumfirewall_about_title'];

}

function forumfirewall_admin_reports()
{
	global $smcFunc, $context, $txt, $page_num, $scripturl;

	isAllowedTo('admin_forum');

	if (empty($_REQUEST['ffid']))
		$_REQUEST['ffid'] = '';

	$context['ffid'] = $_REQUEST['ffid'];

	if ($context['ffid'] != '') {
		forumfirewall_event();

	} else {
	$sort_columns = array(
		'date' => 'date',
	);

	$page_num = 0;
	$per_page = 30;

	if ($context['sub_action'] == 'forumfirewall_report_denied') {
		$where = "cf.id >= 0";
	} else {
		$where = "cf.id >= 0";
	}

	$_REQUEST['start'] = empty($_REQUEST['start']) || $_REQUEST['start'] < 0 ? 0 : (int) $_REQUEST['start'];

	if (empty($_REQUEST['sort_by']) || !isset($sort_columns[$_REQUEST['sort_by']])) {
		$_REQUEST['sort_by'] = 'date';
		$_REQUEST['desc'] = true; }

	$context['order_by'] = isset($_REQUEST['desc']) ? 'down' : 'up';
	$context['sort_by'] = $_REQUEST['sort_by'];

	$context['start'] = $_REQUEST['start'];

	$sort_by = $sort_columns[$context['sort_by']];
	$order_by = (isset($_REQUEST['desc']) ? ' desc' : 'asc');

	$result = forumfirewall_db_manage($where, $page_num, $per_page, $sort_by, $order_by);

	$context['page_index'] = constructPageIndex($scripturl . '?action=admin;area=forumfirewall;sa='.$context['sub_action'].';sort_by=' . $context['sort_by']. ($context['order_by'] == 'down' ? ';desc' : ''), $_REQUEST['start'], $context['cf_per_page'], $per_page);

	$page_num = $result[3];

	$context['sub_template'] = 'forumfirewall_reports';
	}

	$context['description'] = $txt['forumfirewall_reports_desc'];

	if ($context['sub_action'] == 'forumfirewall_report_denied')
		$context['settings_title'] = $txt['forumfirewall_report_denied_title'];
}

function forumfirewall_db_manage($where = "cf.id >= 0", $page_num=1, $per_page=5, $sort_by='date', $order_by='desc') {

	global $smcFunc, $context;

	$limit_start = '';
	$context['forumfirewall_log'] = array();
	
	if ($page_num < 1) {
		$page_num = 1; }
	if ($per_page < 1) {
		$per_page = 1; }
  if ($sort_by !== 'date') {
		$sort_by='date'; }
	if ($order_by !== 'asc' && $order_by !== 'desc') {
		$order_by='desc'; }

	$total_result = $smcFunc['db_query']('', '
		SELECT COUNT(*)
		FROM {db_prefix}log_forumfirewall AS cf
		WHERE ' . $where,
		array(
		)
	);

	list ($total_count) = $smcFunc['db_fetch_row']($total_result);
	$smcFunc['db_free_result']($total_result);

	$limit_start = (isset($context['start'])? $context['start'] : ($limit_start = ($page_num * $per_page) - $per_page));
	if ($limit_start > $total_count) {
		$limit_start = $total_count-1; }

	if ($per_page > $total_count) {
		$per_page = $total_count; }
	
	$limit_end = $limit_start + $per_page;
	
	if ($limit_end > $total_count) {
		$limit_end = $total_count; }

	//Make sure the page number requested actually exists
	for ($i=$page_num; $i>1; $i--) {
		if ( ( ($page_num * $per_page) - $per_page) > $total_count) {
			$page_num--; } }

	$qresult = $smcFunc['db_query']('', '
		SELECT *
		FROM {db_prefix}log_forumfirewall AS cf
		WHERE ' . $where . '
		ORDER BY {identifier:sort} {raw:order}
		LIMIT {int:offset}, {int:items_per_page}',
		array(
			'sort' => $sort_by,
			'order' => $order_by,
			'offset' => $limit_start,
			'items_per_page' => $per_page,
		)
	);

	while($row = $smcFunc['db_fetch_assoc']($qresult)) {

		//This field can be blank alot, so put a space in it.
		if (empty($row['request_entity']))
			$row['request_entity'] = '&nbsp;';

		//  Load DB into array
		$context['forumfirewall_log'][] = array(
			'ffid'  => $row['id'],
			'ip'  => $row['ip'],
			'date'  => $row['date'],
			'http_headers'  => $row['http_headers'],
			'result'  => $row['result'],
		);
	}

	$smcFunc['db_free_result']($qresult);

    $limit_start++;	

	$context['cf_where'] = $limit_start;
	$context['cf_page_num'] = $limit_end;
	$context['cf_per_page'] = $total_count;

	$return_array = array($limit_start, $limit_end, $total_count, $page_num);

return $return_array;
}

function forumfirewall_event()
{
	global $context, $smcFunc;

	$type = $where = '';

	isAllowedTo('admin_forum');

	$context['ffid'] = $_REQUEST['ffid'];

	$where = "cf.id = {int:selected_id}";

	$qresult = $smcFunc['db_query']('', '
		SELECT *
		FROM {db_prefix}log_forumfirewall AS cf
		WHERE ' . $where,
		array(
			'selected_id' => $context['ffid'],
		)
	);

	$row = $smcFunc['db_fetch_assoc']($qresult);

		$context['forumfirewall_log'][] = array(
			'ffid'  => $row['id'],
			'ip'  => $row['ip'],
			'date'  => $row['date'],
			'http_headers'  => $row['http_headers'],
			'result'  => $row['result'],
		);

	$smcFunc['db_free_result']($qresult);

	$context['sub_template'] = 'forumfirewall_event';
}

?>
