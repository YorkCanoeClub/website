<?php
/**********************************************************************************
* ForumFirewall.php - PHP template for ForumFirewall mod
* Version 1.1.2 by JMiller a/k/a butchs
* (http://www.eastcoastrollingthunder.com)
*********************************************************************************
* This program is distributed in the hope that it is and will be useful, but
* WITHOUT ANY WARRANTIES; without even any implied warranty of MERCHANTABILITY
* or FITNESS FOR A PARTICULAR PURPOSE.
**********************************************************************************/

###############################################################################
###############################################################################
if (!defined('SMF')) die('Hacking attempt...');

global $sourcedir, $settings, $modSettings, $user_info, $language, $txt;

// Get support routines
require_once($sourcedir . '/Subs-ForumFirewall.php');

//  Check if mod is on and visitor is not admin
if (!isset($modSettings['forumfirewall_enable']) || !$modSettings['forumfirewall_enable']) return;

$forumfirewall_ip = '';
$headers = $headers_mixed = $result = array();

//  Look up headers
if (!is_callable('getallheaders')) {
	foreach ($_SERVER as $h => $v)
		if (preg_match('/HTTP_(.+)/', $h, $hp))
			$headers[str_replace('_', '-', forumfirewall_uc($hp[1]))] = $v;
} else {
	$headers = getallheaders(); }
foreach ($headers as $h => $v) {
	$headers_mixed[forumfirewall_uc($h)] = $v; }
unset($headers, $v);

if ($user_info['is_admin']) {
	$forumfirewall_ip = $user_info['ip'];
 	//  Admin or IPv4 address test to protect against hacking cookies
	if (!$modSettings['forumfirewall_enable_admin']) {
			unset($result); //  Match we are not alowed to test
			require_once($sourcedir . '/ForumFirewall-Admin.php');
			return; }
	if (($forumfirewall_ip == '') || (empty($forumfirewall_ip)))
		$forumfirewall_ip = forumfirewall_get_real_ip_address($headers_mixed);
	if (($forumfirewall_ip == '') || (empty($forumfirewall_ip))) {
		//  Blocked admin ip is a hack attempt
	} else {
		$pos = forumfirewall_checkAdmin($forumfirewall_ip);
		if ($pos === false) {
			unset($result);
			require_once($sourcedir . '/ForumFirewall-Admin.php');
			return; }
		//  Check if proxy is on but not sending the correct ip
		if ((array_key_exists($modSettings['forumfirewall_header_id'], $headers_mixed)) && (isset($modSettings['forumfirewall_real_ip'])) && (!empty($modSettings['forumfirewall_real_ip']))) {
			$forumfirewall_ip = forumfirewall_get_real_ip_address($headers_mixed);
			$pos = forumfirewall_checkAdmin($forumfirewall_ip);
			if ($pos === false) {
				unset($result);
				require_once($sourcedir . '/ForumFirewall-Admin.php');
				return;
	}	}	}
	//  user is not admin or DNS server is corrupt - fall thru and get blocked
	$result[0] = '8';
} else {
	$forumfirewall_ip = forumfirewall_get_real_ip_address($headers_mixed);
	$result[0] = 'true'; }

//  Make data array
$forumfirewall_data = array();
$forumfirewall_data = forumfirewall_load_data($forumfirewall_ip);

//  check cache
$cache_flag = false;
$cache_test = false;
if ((isset($modSettings['forumfirewall_cache_duration'])) && (!empty($modSettings['forumfirewall_cache_duration'])) && (((int) $modSettings['forumfirewall_cache_duration']) !== 0)) $cache_test = true;

if ($cache_test !== false) {
	$cache_flag = forumfirewall_checkCache($forumfirewall_data['visitor_ip']);
	if ($cache_flag !== false) {
		$result = forumfirewall_get_cache_content($forumfirewall_data['visitor_ip']);

		//  Check for dos
		if ($modSettings['forumfirewall_enable_dos']) {
			//  First start with a workaround for sloppy Avea media action code
			if ((empty($modSettings['aeva_enable'])) || (!$modSettings['aeva_enable']) || (($modSettings['aeva_enable']) && (strpos($forumfirewall_data['request_uri'], 'index.php?action=media;sa=media;in=') === false))) {
				//  Look for dos pass
				$forumfirewall_ua = $useragents = '';
				$dos_cond = true;
					if ((isset($forumfirewall_data['user_agent'])) && (!empty($forumfirewall_data['user_agent'])) && (isset($modSettings['forumfirewall_good_ua'])) && (!empty($modSettings['forumfirewall_good_ua']))) {
						$good_ua = array();
						$good_ua = explode('|', $modSettings['forumfirewall_good_ua']);
//						@$forumfirewall_ua = $forumfirewall_data['user_agent'];
						foreach ($good_ua as $useragents) {
							$pos = strpos($forumfirewall_data['user_agent'], $useragents);
							if ($pos !== false) {
								//  Good UA detected do not test
								$dos_cond = false;
								break;
						}	}
				unset($good_ua, $useragents, $forumfirewall_ua); }
				$dos_cond = forumfirewall_checkGoodgroup($forumfirewall_data['visitor_ip']);
				//  check for dos
				if (($dos_cond !== false) && (isset($result[1])) && (isset($result[2])))  {
					$time_diff =  '';
					$time_diff = (time() - $result[2]);

					if ($time_diff >= 20) {  //  Min 20 seconds for test
						if  ((($result[1] + 1)/$time_diff) >= $modSettings['forumfirewall_trigger']) {
							//  Fail dos test
							$result[0] = '3';
							forumfirewall_block($forumfirewall_data, $result, $time_diff);
							return;
		}	}	}	}	}
		//  Check cache for previous violation
		if ($result[0] == '1') {
			unset($headers_mixed);
			forumfirewall_block($forumfirewall_data, $result);
			return; }
		if  ($result[0] == '2') {
			unset($headers_mixed);
			forumfirewall_block($forumfirewall_data, $result);
			return; }
		if  ($result[0] == '3') {
			unset($headers_mixed);
			forumfirewall_block($forumfirewall_data, $result);
			return; }
		if  ($result[0] == '4') {
			unset($headers_mixed);
			forumfirewall_block($forumfirewall_data, $result);
			return; }
		if  ($result[0] == '5') {
			unset($headers_mixed);
			forumfirewall_block($forumfirewall_data, $result);
			return; }
		if  ($result[0] == '6') {
			unset($headers_mixed);
			forumfirewall_block($forumfirewall_data, $result);
			return; }
		if  ($result[0] == '7') {
			unset($headers_mixed);
			forumfirewall_block($forumfirewall_data, $result);
			return; }
		if  ($result[0] == '8') {
			unset($headers_mixed);
			forumfirewall_block($forumfirewall_data, $result);
			return; }
		if  ($result[0] == '9') {
			unset($headers_mixed);
			forumfirewall_block($forumfirewall_data, $result);
			return; }
		if  ($result[0] == '10') {
			unset($headers_mixed);
			forumfirewall_block($forumfirewall_data, $result);
			return; }
		if  ($result[0] == '11') {
			unset($headers_mixed);
			forumfirewall_block($forumfirewall_data, $result);
			return; }
		if  ($result[0] == '12') {
			unset($headers_mixed);
			forumfirewall_block($forumfirewall_data, $result);
			return; }
		if  ($result[0] == '13') {
			unset($headers_mixed);
			forumfirewall_block($forumfirewall_data, $result);
			return; }
		if  ($result[0] == '14') {
			unset($headers_mixed);
			forumfirewall_block($forumfirewall_data, $result);
			return; }
}	}

//  Time for fake admin to go away
if (($modSettings['forumfirewall_enable_admin']) && ($result[0] == '8')) {
		unset($headers_mixed);
		$forumfirewall_data['sql_reason'] = $forumfirewall_ip;
		forumfirewall_block($forumfirewall_data, $result);
		return;
}

//  Check cache and see if visitor has passed earlier
if (($cache_test !== false) && ($result[0] == 'false') && ($cache_flag !== false)) {
		//  Try to update cache
		$result[1] = $result[1] + 1;
		forumfirewall_savecache($forumfirewall_data, $result);
}

//  Test for SQL injection attempt
if ($modSettings['forumfirewall_enable_inj']) {
	if ((isset($modSettings['forumfirewall_exploits'])) && (!empty($modSettings['forumfirewall_exploits']))) {
		$forumfirewall_query = $exploit = '';
		@$forumfirewall_query = queryspecialchars($forumfirewall_data['request_uri']);
		if (!preg_match('|^['.str_replace(array('\\-', '\-'), '-', preg_quote($modSettings['forumfirewall_uri_chars'], '-')).']+$|i', strtolower($forumfirewall_query))) {
			// Malicious characters found
			$forumfirewall_data['sql_reason'] = '100';
			$result[0] = '2';
			unset($headers_mixed);
			forumfirewall_block($forumfirewall_data, $result); 
			return; }
		//  Look at settings - elements are separated by '|'
		$exploits_array = array();
		$exploits_array = explode('|', $modSettings['forumfirewall_exploits']);
		foreach ($exploits_array as $exploit) {
			$pos = strpos($forumfirewall_query, strtoupper(htmlspecialchars($exploit)));
			if ($pos !== false) {
				// Injection detected
				$forumfirewall_data['sql_reason'] = $exploit;
				$result[0] = '2';
				unset($headers_mixed, $exploits_array, $exploit);
				forumfirewall_block($forumfirewall_data, $result);
				return;
		}	}
		unset($exploits_array, $exploit);
}		}

//  Inspect Ip address
if (($modSettings['forumfirewall_enable_check_ip']) && ($result[0] != 'false')) {
	$pos = (((strpos($forumfirewall_ip, ':')) ? forumfirewall_check_ip6($forumfirewall_ip) : forumfirewall_check_ip4($forumfirewall_ip)));
	if ($pos !== false) {
		//  Fail IP test
		$result[0] = '4';
		forumfirewall_block($forumfirewall_data, $result);
		return;
}	}

//	Check if proxy is being bypassed
if ($modSettings['forumfirewall_enable_bypass']) {
	if (!array_key_exists($modSettings['forumfirewall_header_id'], $headers_mixed)) {
		if((!forumfirewall_checkdns($forumfirewall_ip, $modSettings['forumfirewall_domain'])) || (ip2long($forumfirewall_ip) != ip2long($modSettings['forumfirewall_ip']))) {
			$result[0] = '1';
			forumfirewall_block($forumfirewall_data, $result);
			return;
}	}	}

// Robots inspection - Distributed Denial of Service Attacks DDoS
if (($modSettings['forumfirewall_enable_robots']) && (isset($modSettings['forumfirewall_test_robots'])) && (!empty($modSettings['forumfirewall_test_robots'])) && (isset($modSettings['forumfirewall_robotstxt_action'])) && (!empty($modSettings['forumfirewall_robotstxt_action']))) {
	if ((isset($forumfirewall_data['user_agent'])) && (!empty($forumfirewall_data['user_agent']))) {

		$forumfirewall_query = $test_robot =  '';
		$test_robot = array();
		$test_robot = explode('|', $modSettings['forumfirewall_test_robots']);

		@$forumfirewall_query =$forumfirewall_data['user_agent'];

		foreach ($test_robot as $test_robots) {
			$pos = strpos($forumfirewall_query, $test_robots);
			if ($pos !== false) {
			//  Yest UA detected test robots.txt Disallow's

			unset($forumfirewall_query);
			$forumfirewall_query = $robotstxt_actions = '';

			$robotstxt_action = array();
			$robotstxt_action = explode('|', $modSettings['forumfirewall_robotstxt_action']);
			@$forumfirewall_query = queryspecialchars($forumfirewall_data['request_uri']);

				foreach ($robotstxt_action as $robotstxt_actions) {
					$pos = strpos($forumfirewall_query, strtoupper(htmlspecialchars($robotstxt_actions)));
					if ($pos !== false) {
						$result[0] = '14';
						unset($test_robot, $robotstxt_action, $test_robots, $robotstxt_actions);
						forumfirewall_block($forumfirewall_data, $result);
						return;
					}
				unset($robotstxt_action);
				unset($robotstxt_actions);
		}	}	}
		unset($test_robot);
		unset($test_robots);
	}
	//  end ddos
}

// UA inspection
if ($modSettings['forumfirewall_enable_ua']) {
	if($forumfirewall_data['user_agent']) {
		if ($forumfirewall_data['user_agent'] == '-' ) {
			//  Bad user agent or possible DOS attack?
			$result[0] = '7';
			forumfirewall_block($forumfirewall_data, $result);
			return;
}	}	}

//  Cookie inspection - future look at header cookie?
if (($modSettings['forumfirewall_enable_xxs']) && (isset($modSettings['forumfirewall_xxs'])) && (!empty($modSettings['forumfirewall_xxs']))) {
	global $HTTP_COOKIE_VARS;

	$cookies = array();
	if (isset($_COOKIE)) {
		$cookies = $_COOKIE;
	} elseif (isset($HTTP_COOKIE_VARS)) {
		$cookies = $HTTP_COOKIE_VARS;}
	if ($cookies !== '') {
		if ((isset($modSettings['forumfirewall_xxs'])) && (!empty($modSettings['forumfirewall_xxs']))) {
			//  Look at settings - elements are separated by '|'
			$xxs_array = array();
			$xxs_array = explode('|', $modSettings['forumfirewall_xxs']);
			foreach ($cookies as $cookie_content) {
			@$cookie_content = queryspecialchars($cookie_content);
				foreach ($xxs_array as $exploit) {
					$pos = strpos($cookie_content, strtoupper(htmlspecialchars($exploit)));
					if ($pos !== false) {
						// XXS detected
						$forumfirewall_data['sql_reason'] = $cookie_content . ': ' . $exploit;
						$result[0] = '6';
						unset($cookies, $xxs_array, $cookie_content, $exploit);
						forumfirewall_block($forumfirewall_data, $result);
						return;
			}	}	}
			unset($xxs_array, $cookie_content, $exploit);
	}	}
unset($cookies); }

//  Referer check
if ($modSettings['forumfirewall_enable_header']) {
	if ((isset($forumfirewall_data['referer'])) && (!empty($forumfirewall_data['referer'])) && (isset($modSettings['forumfirewall_referer_attack'])) && (!empty($modSettings['forumfirewall_referer_attack']))) {
		// Check only if from different hosts
		$referer_parts = array();
		$referer_parts = parse_url($forumfirewall_data['referer']);
		if (array_key_exists('host', $referer_parts)) {
			if($referer_parts['host'] != forumfirewall_get_env('HTTP_HOST')) {
				$referer_attack = array();
				$referer_attack = explode('|', $modSettings['forumfirewall_referer_attack']);
				@$visitor_referer = queryspecialchars($forumfirewall_data['referer']);
				foreach ($referer_attack as $attacks) {
					$pos = strpos($visitor_referer, strtoupper(htmlspecialchars($attacks)));
					if ($pos !== false) {
						//  Referer attack detected
						$forumfirewall_data['sql_reason'] = $attacks;
						$result[0] = '9';
						unset($referer_attack, $attacks);
						forumfirewall_block($forumfirewall_data, $result);
						return;
			}	}
		unset($referer_attack, $attacks); }	}
	unset($referer_parts); }

	//  UA attack Inspection
	if ((isset($forumfirewall_data['user_agent'])) && (!empty($forumfirewall_data['user_agent'])) && (isset($modSettings['forumfirewall_ua_attack'])) && (!empty($modSettings['forumfirewall_ua_attack']))) {
		$ua_attack = array();
		$ua_attack = explode('|', $modSettings['forumfirewall_ua_attack']);
		@$visitor_ua = queryspecialchars($forumfirewall_data['user_agent']);
		foreach ($ua_attack as $attacks) {
			$pos = strpos($visitor_ua, strtoupper(htmlspecialchars($attacks)));
			if ($pos !== false) {
				//  UA attack detected
				$forumfirewall_data['sql_reason'] = $attacks;
				$result[0] = '10';
				unset($ua_attack, $attacks);
				forumfirewall_block($forumfirewall_data, $result);
				return;
		}	}
		unset($ua_attack, $attacks); }

	//  Request Entity Inspection
	if ((!empty($forumfirewall_data['request_entity'])) && (isset($modSettings['forumfirewall_entity_attack'])) && (!empty($modSettings['forumfirewall_entity_attack']))) {
			$enity_attack = array();
			$enity_content = '';
			$enity_attack = explode('|', $modSettings['forumfirewall_entity_attack']);
			foreach ($forumfirewall_data['request_entity'] as $h => $v) {
				$enity_content = $h.': '.$v;
				@$enity_content = queryspecialchars($enity_content);
				foreach ($enity_attack as $attacks) {
					$pos = strpos($enity_content, strtoupper(htmlspecialchars($attacks)));
					if ($pos !== false) {
						//  Enity attack detected
						$forumfirewall_data['sql_reason'] = $attacks;
						$result[0] = '13';
						unset($enity_attack, $attacks, $v);
						forumfirewall_block($forumfirewall_data, $result);
						return;
			}	}	}
			unset($enity_attack, $attacks, $v);
}	}

//  Time to check country
if ($modSettings['forumfirewall_enable_country']) {
	$forumfirewall_code = $countries = '';
	if ((isset($modSettings['forumfirewall_bad_countries'])) && (!empty($modSettings['forumfirewall_bad_countries']))) {
		//  Look at settings - elements are separated by '|'
		$bad_countries = array();
		$bad_countries = explode('|', strtoupper($modSettings['forumfirewall_bad_countries']));
		if ($modSettings['forumfirewall_in_geoip']) {
			if (function_exists('apache_note')) {
				@$forumfirewall_code = apache_note('GEOIP_COUNTRY_CODE');
			} else {
				@$forumfirewall_code = forumfirewall_get_env('GEOIP_COUNTRY_CODE'); }
		} else {
			@$forumfirewall_code = $headers_mixed[$modSettings['forumfirewall_country_id']]; }
		if ((isset($forumfirewall_code)) && (!empty($forumfirewall_code))) {
			foreach ($bad_countries as $countries) {
				$pos = strpos($forumfirewall_code, $countries);
				if ($pos !== false) {
					//  Bad country detected
					$forumfirewall_data['sql_reason'] = $countries;
					$result[0] = '5';
					unset($bad_countries, $countries);
					forumfirewall_block($forumfirewall_data, $result);
					return;
		}	}	}
		unset($bad_countries, $countries);
}	}
unset($headers_mixed);

//  Check server ports
if ($modSettings['forumfirewall_enable_svrport']) {
	if ((isset($modSettings['forumfirewall_good_ser_ports'])) && (!empty($modSettings['forumfirewall_good_ser_ports']))) {
		$forumfirewall_port = $good_ports = '';
		$good_port = array();
		$good_port = explode('|', $modSettings['forumfirewall_good_ser_ports']);
		@$forumfirewall_port = ((int) forumfirewall_get_env('SERVER_PORT'));
		foreach ($good_port as $good_ports) {
			$pos = strpos($forumfirewall_port, $good_ports);
			if ($pos === false) {
				//  Good port is not being used so block
				$forumfirewall_data['sql_reason'] = $forumfirewall_port;
				$result[0] = '11';
				unset($good_port, $good_ports);
				forumfirewall_block($forumfirewall_data, $result);
				return;
		}	}
	unset($good_port, $good_ports); }	}

//  Check remote ports
if ($modSettings['forumfirewall_enable_rmtport']) {
	$forumfirewall_port = '';
	$forumfirewall_port = ((int) forumfirewall_get_env('REMOTE_PORT'));
	if (($forumfirewall_port <= 1023) || ($forumfirewall_port > 65535)) {
		//  Found a bad port
		$forumfirewall_data['sql_reason'] = $forumfirewall_port;
		$result[0] = '12';
		forumfirewall_block($forumfirewall_data, $result);
		return;
}	}

//  Try to save to cache before leaving
if (($cache_test !== false) && ($cache_flag === false)) {
	$result[0] = 'false';
	$result[1] = '1';
	$result[2] = time();
	forumfirewall_savecache($forumfirewall_data, $result); }

//  We are done - do nothing else - let BB sort out the rest
unset($forumfirewall_data, $result);
unset($cache_flag, $cache_test, $dos_cond);

?>
