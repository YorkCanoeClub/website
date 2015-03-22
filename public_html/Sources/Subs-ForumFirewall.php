<?php
/**********************************************************************************
* Subs_ForumFirewall.php - PHP template for ForumFirewall mod
* Version 1.1.4 by JMiller a/k/a butchs
* (http://www.eastcoastrollingthunder.com) 
*********************************************************************************
* This program is distributed in the hope that it is and will be useful, but
* WITHOUT ANY WARRANTIES; without even any implied warranty of MERCHANTABILITY
* or FITNESS FOR A PARTICULAR PURPOSE.
**********************************************************************************/

if (!defined('SMF')) die('Hacking attempt...');

function forumfirewall_uc($string) {
	$temp = preg_split('/(\W)/', str_replace('_', '-', $string), -1, PREG_SPLIT_DELIM_CAPTURE);
	foreach ($temp as $key=>$word) {
		$temp[$key] = ucfirst(strtolower($word));
	}
	unset($word);
	return join ('', $temp);
}
function forumfirewall_get_real_ip_address($headers_mixed) {
	global $modSettings;
	if ((array_key_exists($modSettings['forumfirewall_header_id'], $headers_mixed)) && (!empty($modSettings['forumfirewall_real_ip']))) {
		if (forumfirewall_get_env($modSettings['forumfirewall_real_ip'])) {
			forumfirewall_check_hidden();
			return forumfirewall_get_env($modSettings['forumfirewall_real_ip']); }
		elseif ($headers_mixed[$modSettings['forumfirewall_real_ip']]) {
			forumfirewall_check_hidden();
			return ($headers_mixed[$modSettings['forumfirewall_real_ip']]); }
		}
		elseif (forumfirewall_get_env('HTTP_FORWARDED')) {
			forumfirewall_check_hidden();
			return forumfirewall_check_ip_array('HTTP_FORWARDED'); }
		elseif (forumfirewall_get_env('X_HTTP_FORWARDED_FOR')) {
			forumfirewall_check_hidden();
			return forumfirewall_check_ip_array('X_HTTP_FORWARDED_FOR'); }
		elseif (forumfirewall_get_env('X_FORWARDED_FOR')) {
			forumfirewall_check_hidden();
			return forumfirewall_check_ip_array('X_FORWARDED_FOR'); }
		elseif (forumfirewall_get_env('HTTP_X_FORWARDED_FOR')) {
			forumfirewall_check_hidden();
			return forumfirewall_check_ip_array('HTTP_X_FORWARDED_FOR'); }
		elseif (forumfirewall_get_env('FORWARDED_FOR')) {
			forumfirewall_check_hidden();
			return forumfirewall_check_ip_array('FORWARDED_FOR'); }
		elseif (forumfirewall_get_env('HTTP_PROXY_CONNECTION')) {
			forumfirewall_check_hidden();
			return forumfirewall_check_ip_array('HTTP_PROXY_CONNECTION'); }
		elseif (forumfirewall_get_env('HTTP_CLIENT_IP')) {
			forumfirewall_check_hidden();
			return forumfirewall_check_ip_array('HTTP_CLIENT_IP'); }
		elseif (forumfirewall_get_env('HTTP_VIA')) {
			forumfirewall_check_hidden();
			return forumfirewall_check_ip_array('HTTP_VIA'); }
		elseif (forumfirewall_get_env('HTTP_X_FORWARDED')) {
			forumfirewall_check_hidden();
			return forumfirewall_check_ip_array('HTTP_X_FORWARDED'); }
		elseif (forumfirewall_get_env('HTTP_COMING_FROM')) {
			forumfirewall_check_hidden();
			return forumfirewall_check_ip_array('HTTP_COMING_FROM'); }
		elseif (forumfirewall_get_env('HTTP_X_COMING_FROM')) {
			forumfirewall_check_hidden();
			return forumfirewall_check_ip_array('HTTP_X_COMING_FROM'); }
/*		elseif (forumfirewall_get_env('REMOTE_HOST')) {
			forumfirewall_check_hidden();
			return forumfirewall_check_ip_array('REMOTE_HOST'); } */
		elseif (forumfirewall_get_env('REMOTE_ADDR')) {
			return forumfirewall_get_env('REMOTE_ADDR'); }
	return $_SERVER['REMOTE_ADDR'];
}
function forumfirewall_check_hidden() {
	global $modSettings;
	if (forumfirewall_get_env('REMOTE_ADDR')) {
		if ($modSettings['forumfirewall_enable_check_ip']  && $modSettings['forumfirewall_enable_proxy']) {
			$forumfirewall_ip = '';
			$forumfirewall_ip = forumfirewall_get_env('REMOTE_ADDR');
			$pos = (((strpos($forumfirewall_ip, ':')) ? forumfirewall_check_ip6($forumfirewall_ip) : forumfirewall_check_ip4($forumfirewall_ip)));
			if ($pos !== false) {
				if (!isset($result)) $result = array();
				if (!isset($forumfirewall_data)) {
					$forumfirewall_data = array();
					$forumfirewall_data = forumfirewall_load_data($forumfirewall_ip);
				} else {
					$forumfirewall_data['visitor_ip'] = preg_replace('/^::ffff:/', '', $forumfirewall_ip); }
				$forumfirewall_data['sql_reason'] = '210';
				$result[0] = '4';
				forumfirewall_block($forumfirewall_data, $result);
				break;
	}	}	}
	return;
}
function forumfirewall_check_ip_array($htvars) {
	global $modSettings;
	$forumfirewall_ip = '';
	$forumfirewall_ip = forumfirewall_get_env($htvars);
	if ((strpos($forumfirewall_ip, ',') !== false) || (strpos($forumfirewall_ip, ' ') !== false)) {
		$forumfirewall_ip_array = array();
		$forumfirewall_ip = preg_replace('# {2,}#', ' ', str_replace(',', ' ', $forumfirewall_ip));
		$forumfirewall_ip_array = explode(' ', trim($forumfirewall_ip));
		$forumfirewall_ip_array = array_values($forumfirewall_ip_array);
		foreach ($forumfirewall_ip_array as $forumfirewall_ips) {
			$forumfirewall_ip = $forumfirewall_ips;
			if ($modSettings['forumfirewall_enable_check_ip']  && $modSettings['forumfirewall_enable_proxy']) {
				$pos = (((strpos($forumfirewall_ip, ':')) ? forumfirewall_check_ip6($forumfirewall_ip) : forumfirewall_check_ip4($forumfirewall_ip)));
				if ($pos !== false) {
					if (!isset($result)) $result = array();
					if (!isset($forumfirewall_data)) {
						$forumfirewall_data = array();
						$forumfirewall_data = forumfirewall_load_data($forumfirewall_ip);
					} else {
						$forumfirewall_data['visitor_ip'] = preg_replace('/^::ffff:/', '', $forumfirewall_ip); }
					$forumfirewall_data['sql_reason'] = '200';
					$result[0] = '4';
					unset($forumfirewall_ip_array, $forumfirewall_ips);
					forumfirewall_block($forumfirewall_data, $result);
					break;
		}	}	}
	unset($forumfirewall_ip_array, $forumfirewall_ips); }
	return $forumfirewall_ip;
}
function forumfirewall_get_env($htvars) {
	if ((isset($_SERVER[$htvars])) && (!empty($_SERVER[$htvars]))) {
		return strip_tags($_SERVER[$htvars]);
	} elseif ((isset($_ENV[$htvars])) && (!empty($_ENV[$htvars]))) {
		return strip_tags($_ENV[$htvars]);
	} elseif ((isset($HTTP_SERVER_VARS[$htvars])) && (!empty($HTTP_SERVER_VARS[$htvars]))) {
		return strip_tags($HTTP_SERVER_VARS[$htvars]);
	} elseif ((getenv($htvars)) && (getenv($htvars) != '')) {
		return strip_tags(getenv($htvars));
	} elseif (function_exists('apache_getenv') && apache_getenv($htvars, true)) {
		if (strip_tags(apache_getenv($htvars, true)) != '') {
			return strip_tags(apache_getenv($htvars, true)); } }
	return false;
}
function forumfirewall_get_real_uri() {

	$request_uri = '';
	if (forumfirewall_get_env('HTTP_X_REWRITE_URL')) {
		$request_uri = forumfirewall_get_env('HTTP_X_REWRITE_URL');
	} elseif ((forumfirewall_get_env('IIS_WasUrlRewritten')) && (forumfirewall_get_env('IIS_WasUrlRewritten') == '1')
				&& (forumfirewall_get_env('UNENCODED_URL')) && (forumfirewall_get_env('UNENCODED_URL') != '')) {
		$request_uri = forumfirewall_get_env('UNENCODED_URL');
	} elseif (forumfirewall_get_env('REQUEST_URI')) {
		$request_uri = forumfirewall_get_env('REQUEST_URI');
		if ((forumfirewall_get_env('HTTP_HOST')) && (strstr($request_uri , forumfirewall_get_env('HTTP_HOST')))) {
			$parts = @parse_url($request_uri);
			if ($parts !== false) {
				$requestUri  = (empty($parts['path']) ? '' : $parts['path'])
								. ((empty($parts['query'])) ? '' : '?' . $parts['query']);
		}	}
	} elseif ((forumfirewall_get_env('ORIG_PATH_INFO')) || (forumfirewall_get_env('PATH_INFO'))) {
		$request_uri = forumfirewall_get_env('ORIG_PATH_INFO');
		if (forumfirewall_get_env('QUERY_STRING')) {
			if (strpos(forumfirewall_get_env('REQUEST_URI'), forumfirewall_get_env('QUERY_STRING')) === false) {
				$request_uri .= '?' . forumfirewall_get_env('QUERY_STRING');
		}	}
	} elseif (forumfirewall_get_env('QUERY_STRING')) {
		if (forumfirewall_get_env('SCRIPT_NAME')) {
			$request_uri = forumfirewall_get_env('SCRIPT_NAME') . '?' . forumfirewall_get_env('QUERY_STRING'); 
		} else { 
			$request_uri = forumfirewall_get_env('PHP_SELF') .'?'. forumfirewall_get_env('QUERY_STRING');
			$request_uri = '/' . ltrim($request_uri, '/');
		}
	} else {
		$request_uri = forumfirewall_get_env('SCRIPT_NAME');
	}
	return $request_uri;
}
function forumfirewall_load_data($forumfirewall_ip) {

	$request_entity = array();
	if (!strcasecmp(forumfirewall_get_env('REQUEST_METHOD'), 'POST') || !strcasecmp(forumfirewall_get_env('REQUEST_METHOD'), 'PUT')) {
		foreach ($_POST as $h => $v) {
			$request_entity[$h] = $v;
	}	}
	$forumfirewall_data = array(
		'visitor_ip' => preg_replace('/^::ffff:/', '', $forumfirewall_ip),
		'request_method' => forumfirewall_get_env('REQUEST_METHOD'),
		'request_uri' => forumfirewall_get_real_uri(),
		'referer' => forumfirewall_get_env('HTTP_REFERER'),
		'user_agent' => forumfirewall_get_env('HTTP_USER_AGENT'),
		'server_protocol' => forumfirewall_get_env('SERVER_PROTOCOL'),
		'request_entity' => $request_entity,
		'sql_reason' => '999',
	);
	unset($request_entity, $v);
	return $forumfirewall_data;
}
function forumfirewall_checkAdmin($forumfirewall_ip) {
	global $modSettings;
	$pos = true;

	if ((ip2long($forumfirewall_ip) >= ip2long($modSettings['forumfirewall_admin_ip_lo'])) && (ip2long($forumfirewall_ip) <= ip2long($modSettings['forumfirewall_admin_ip_hi']))) {
		if (forumfirewall_checkdns($forumfirewall_ip, $modSettings['forumfirewall_admin_domain'])) {
			$pos = (((strpos($forumfirewall_ip, ':')) ? forumfirewall_check_ip6($forumfirewall_ip) : forumfirewall_check_ip4($forumfirewall_ip)));
	}	}

return $pos;
}
function forumfirewall_checkGoodgroup($forumfirewall_ip) {
	global $sourcedir, $context, $user_info, $smcFunc;

	if ($forumfirewall_ip == '') return true;

	//  Look up ip address and check
	$forumfirewall_goodgroup = array();
	$dos_cond = true;

	$qresult = $smcFunc['db_query']('', '
		SELECT id_group, permission, add_deny
		FROM {db_prefix}permissions
		WHERE permission = {string:search_permission}
			AND add_deny = {int:permission_state}',
		array(
			'search_permission' => 'forumfirewall_goodgroup',
			'permission_state' => 1,
		)
	);

	if ($smcFunc['db_num_rows']($qresult) > 0) {
		while ($row = $smcFunc['db_fetch_assoc']($qresult)) {
			$forumfirewall_goodgroup[$row['permission']] = $row['id_group'];
	}	}
	$smcFunc['db_free_result']($qresult);

	if (!empty($forumfirewall_goodgroup)) {
		if (function_exists('loadIllegalPermissions')) {
			loadIllegalPermissions();
		} else {
			require_once($sourcedir . '/ManagePermissions.php');
			loadIllegalPermissions(); }
		foreach ($forumfirewall_goodgroup as $perm => $group_id) {
			if ($user_info['groups']['0'] == $group_id) {
				$dos_cond = false;
				break; }
			if (!empty($context['illegal_permissions']) && in_array($perm, $context['illegal_permissions'])) {
				$dos_cond = true;
				break; }
			$qresult = $smcFunc['db_query']('', '
				SELECT
					id_group, member_ip, member_ip2, is_activated
				FROM {db_prefix}members
				WHERE member_ip = {string:ipofuser}
					OR member_ip2 = {string:ipofuser}',
				array(
					'ipofuser' => $forumfirewall_ip,
				)
			);
			if ($smcFunc['db_num_rows']($qresult) > 0) {
				while ($row = $smcFunc['db_fetch_assoc']($qresult)) {
					if ($row['id_group'] == $group_id) {
						$dos_cond = false;
						if ($row['is_activated'] >= 10) $dos_cond = true;  //  is_banned
						break;
			}	}	}
	$smcFunc['db_free_result']($qresult); }
	unset($perm, $group_id); }

	unset($forumfirewall_goodgroup);

	return $dos_cond;
}
function forumfirewall_checkCache($forumfirewall_ip) {
	global $modSettings;
	$result = array();
	if ($forumfirewall_ip == '') return false;
	$cache_content = $stamp = '';
	$stamp = date('Ymd');
	if (function_exists('hash')) {
		$forumfirewall_algo = forumfirewall_get_hash($stamp);
		$cache_content = forumfirewall_cache_get_data('ffirewall-' . substr(hash($forumfirewall_algo,serialize($forumfirewall_ip.$modSettings['forumfirewall_salt'].$stamp)), -8), 0);
	} else {
		$cache_content = forumfirewall_cache_get_data('ffirewall-' . substr(md5(serialize($forumfirewall_ip.$modSettings['forumfirewall_salt'].$stamp)), -8), 0); }
	$result = explode('|', $cache_content);
	if (empty($cache_content) || (!array_key_exists(0, $result)) || (!array_key_exists(1, $result)) || (!array_key_exists(2, $result))) {
		unset($cache_content,$stamp);
		unset($result);
       	return false;
	}
	unset($cache_content, $stamp);
	unset($result);
	return true;
}
function forumfirewall_get_cache_content($forumfirewall_ip) {
	global $modSettings;
	$result = array();
	$cache_content = $result = $stamp = '';
	$stamp = date('Ymd');

	if (function_exists('hash')) {
		$forumfirewall_algo = forumfirewall_get_hash($stamp);
		$cache_content = forumfirewall_cache_get_data('ffirewall-' . substr(hash($forumfirewall_algo,serialize($forumfirewall_ip.$modSettings['forumfirewall_salt'].$stamp)), -8), 0);
	} else { 
		$cache_content = forumfirewall_cache_get_data('ffirewall-' . substr(md5(serialize($forumfirewall_ip.$modSettings['forumfirewall_salt'].$stamp)), -8), 0); }

	$result = explode('|', $cache_content);

	unset($cache_content);

	return $result;
}
function forumfirewall_savecache($forumfirewall_data, $result) {

	global $modSettings;
	if (empty($forumfirewall_data)) return;
	if (!is_array($forumfirewall_data)) return;

	if (empty($result)) return;
	if (!is_array($result)) return;

	$forumfirewall_ip = $forumfirewall_data['visitor_ip'];
	$forumfirewall_cach_array = array();
	$forumfirewall_cach_array[0] = $result[0];
	$forumfirewall_cach_array[1] = $result[1];  //  Count
	$forumfirewall_cach_array[2] = $result[2];  //  First Timestamp

	forumfirewall_loadCache($forumfirewall_ip, $forumfirewall_cach_array);
	unset($forumfirewall_cach_array);
}
function forumfirewall_loadCache($forumfirewall_ip, $forumfirewall_cach_array) {

	global $modSettings;
	if ($forumfirewall_ip == '') return;
	if (empty($forumfirewall_cach_array)) return;
	if (!is_array($forumfirewall_cach_array)) return;
	$cache_content = $forumfirewall_expire = $stamp = '';
	$forumfirewall_expire = 0;
	$stamp = date('Ymd');
	foreach($forumfirewall_cach_array as $key => $value) {
		$cache_content .= $value . '|'; }
	unset($value);
	$cache_content = substr($cache_content, 0, -1);
	if (is_int((int) $modSettings['forumfirewall_cache_duration']))
		$forumfirewall_expire = ((int) $modSettings['forumfirewall_cache_duration'] - (time() - $forumfirewall_cach_array[2]));
		if (function_exists('hash')) {
			$forumfirewall_algo = forumfirewall_get_hash($stamp);
			forumfirewall_cache_put_data('ffirewall-' . substr(hash($forumfirewall_algo,serialize($forumfirewall_ip.$modSettings['forumfirewall_salt'].$stamp)), -8), $cache_content, $forumfirewall_expire);
		} else {
			forumfirewall_cache_put_data('ffirewall-' . substr(md5(serialize($forumfirewall_ip.$modSettings['forumfirewall_salt'].$stamp)), -8), $cache_content, $forumfirewall_expire); } 
		unset($forumfirewall_expire, $cache_content, $stamp);
	return;
}
function queryspecialchars($forumfirewall_conv_dta) {

	if ($forumfirewall_conv_dta == '') return;
	if (empty($forumfirewall_conv_dta)) return;

	if (forumfirewall_is_utf8($forumfirewall_conv_dta))
		$forumfirewall_conv_dta = forumfirewall_my_utf8_decode($forumfirewall_conv_dta);

	$forumfirewall_conv_dta = str_ireplace(array('%3C','<','&lt','&#60;','&#060;','&#0060;','&#00060;','&#000060;','&#0000060;','&#60','&#060','&#0060','&#00060','&#000060','&#0000060','&#x3c;','&#x03c;','&#x003c;','&#x0003c;','&#x00003c;','&#x000003c;','&#x3c','&#x03c','&#x003c','&#x0003c','&#x00003c','&#x000003c','\x3c','\u003c','PA=='), '&lt;', $forumfirewall_conv_dta);
	$forumfirewall_conv_dta = str_ireplace(array('%3E','>','&gt','&#62;','&#062;','&#0062;','&#00062;','&#000062;','&#0000062;','&#62','&#062','&#0062','&#00062','&#000062','&#0000062','&#x3e;','&#x03e;','&#x003e;','&#x0003e;','&#x00003e;','&#x000003e;','&#x3e','&#x03e','&#x003e','&#x0003e','&#x00003e','&#x000003e','\x3e','\u003e','PG=='), '&gt;', $forumfirewall_conv_dta);
	$forumfirewall_conv_dta = str_ireplace(array('%3F','&#63;','&#063;','&#0063;','&#00063;','&#000063;','&#0000063;','&#63','&#063','&#0063','&#00063','&#000063','&#0000063','&#x3f;','&#x03f;','&#x003f;','&#x0003f;','&#x00003f;','&#x000003f;','&#x3f','&#x03f','&#x003f','&#x0003f','&#x00003f','&#x000003f','\x3f','\u003f','PW=='), '?', $forumfirewall_conv_dta);
	$forumfirewall_conv_dta = str_ireplace(array('%5C','&#92;','&#092;','&#0092;','&#00092;','&#000092;','&#0000092;','&#92','&#092','&#0092','&#00092','&#000092','&#0000092','&#x5c;','&#x05c;','&#x005c;','&#x0005c;','&#x00005c;','&#x000005c;','&#x5c','&#x05c','&#x005c','&#x0005c','&#x00005c','&#x000005c','\x5c','\u005c','XA=='), '\\', $forumfirewall_conv_dta);
	$forumfirewall_conv_dta = strtoupper(htmlspecialchars($forumfirewall_conv_dta));
	return $forumfirewall_conv_dta;
}
if (!function_exists('str_ireplace')) {
	function str_ireplace($search, $replace, $subject) {
		if (is_array($search)) {
			$words = array();
			foreach ($search as $word) {
				$words[] = '/' . $word . '/i'; }
			unset($word);
		} else {
			$words = '';
			$words = '/' . $search . '/i'; }
		return preg_replace($words, $replace, $subject);
}	}
function forumfirewall_check_ip4($forumfirewall_ip) {

	global $modSettings, $user_info;

	if ($forumfirewall_ip == '') return true;
  	if (empty($forumfirewall_ip)) return true;
  	if (ip2long($forumfirewall_ip) == -1) return true;

	$octet = '';
	$octet = explode('.', $forumfirewall_ip);
	if (count($octet) !== 4) {
		return true; }
	if ($octet[0][0] == '0') {
			return true; }
	for ($i = 0; $i < 4; $i++) {
		if (!is_numeric($octet[$i])) {
			return true; }
		if(strlen($octet[$i]) > 3) {
			return true; }
		if ($octet[$i] < 0 || $octet[$i] > 255) {
			return true;
	}	}

	if ((!$modSettings['forumfirewall_enable_admin']) || (($modSettings['forumfirewall_enable_admin']) && ($user_info['is_admin']) && ((ip2long('127.0.0.1') != ip2long($modSettings['forumfirewall_admin_ip_lo'])) && (ip2long('127.0.0.1') != ip2long($modSettings['forumfirewall_admin_ip_hi']))))) {

		$ip_min = $ip_max = '';
		$reserved_ip = array(
			array('0.0.0.0','0.255.255.255'),
			array('10.0.0.0','10.255.255.255'),
			array('127.0.0.0','127.255.255.255'),
			array('169.254.0.0','169.254.255.255'),
			array('172.16.0.0','172.31.255.255'),
			array('192.0.2.0','192.0.2.255'),
			array('192.88.99.0','192.88.99.255'),
			array('192.168.0.0','192.168.255.255'),
			array('198.18.0.0','198.19.255.255'),
			array('198.51.100.0','198.51.100.255'),
			array('203.0.113.0','203.0.113.255'),
			array('224.0.0.0','239.255.255.255'),
			array('240.0.0.0','255.255.255.255'),
		);

		foreach ($reserved_ip as $limit) {
			$ip_min = ip2long($limit[0]);
			$ip_max = ip2long($limit[1]);

			if ((ip2long($forumfirewall_ip) >= $ip_min) && (ip2long($forumfirewall_ip) <= $ip_max)) {
				unset($reserved_ip, $limit);
				return true;
		}	}
		unset($reserved_ip, $limit); }

	if (strnatcmp(phpversion(),'5.2.0') >= 0) {
		if(!filter_var($forumfirewall_ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4))
  			return true; }

	return false;
}
function forumfirewall_check_ip6($forumfirewall_ip) {
	global $modSettings, $user_info;

	if ($forumfirewall_ip == '') return true;
  	if (empty($forumfirewall_ip)) return true;

	$octet = $prefix = '';
	if(preg_match('!^[A-F0-9:]{1,39}$!i', $forumfirewall_ip) == true) {
		$octet = explode(':::', $forumfirewall_ip);
		if(count($octet) > 1) {
			return true; }
		$octet = explode('::', $forumfirewall_ip);
		if(count($octet) > 2) {
			return true; }
		$octet = explode(':', $forumfirewall_ip);
		if(count($octet) > 8) {
			return true;
			}
		foreach($octet as $checkPart) {
			if(strlen($checkPart) > 4) {
				unset($checkPart);
				return true;
		}	}
		unset($checkPart);
		if ((!$modSettings['forumfirewall_enable_admin']) || (($modSettings['forumfirewall_enable_admin']) && ($user_info['is_admin']) && ((ip2long('::1') != ip2long($modSettings['forumfirewall_admin_ip_lo'])) && (ip2long('::1') != ip2long($modSettings['forumfirewall_admin_ip_hi']))))) {
			if (($forumfirewall_ip == '::1') || ($forumfirewall_ip == '::1/128')  || ($forumfirewall_ip == '0:0:0:0:0:0:0:1') || ($forumfirewall_ip == '0000:0000:0000:0000:0000:0000:0000:0001')) return true; }
		if (($forumfirewall_ip == '::') || ($forumfirewall_ip == '::/128') || ($forumfirewall_ip == '0:0:0:0:0:0:0:0') || ($forumfirewall_ip == '0000:0000:0000:0000:0000:0000:0000:0000')) return true;
		if (($forumfirewall_ip == '::/0') || ($forumfirewall_ip == '0:0:0:0:0:0:0:0/0') || ($forumfirewall_ip == '0000:0000:0000:0000:0000:0000:0000:0000/0')) return true;

		if (strlen($forumfirewall_ip) >= 2) {
			$prefix = strtolower(substr($forumfirewall_ip, 0, 2));
			if ($prefix == 'ff') return true;
			if (($prefix == 'fc') || ($prefix == 'fd')) return true;
			if ($prefix == '5f') return true;
		}
		if (strlen($forumfirewall_ip) >= 3) {
			$prefix = strtolower(substr($forumfirewall_ip, 0, 3));
			if (($prefix == 'fe8') || ($prefix == 'fe9') || ($prefix == 'fea') || ($prefix == 'feb')) return true;
			if (($prefix == 'fec') || ($prefix == 'fed') || ($prefix == 'fee') || ($prefix == 'fef')) return true;
		}

		if (strlen($forumfirewall_ip) >= 4) {
			$prefix = strtolower(substr($forumfirewall_ip, 0, 4));
			if ($prefix == '3ff3') return true;
		}

		if (strlen($forumfirewall_ip) >= 7) {
			$prefix = strtolower(substr($forumfirewall_ip, 0, 7));
			if ($prefix == '2001:1:') return true;
		}

		if (strlen($forumfirewall_ip) >= 8) {
			$prefix = strtolower(substr($forumfirewall_ip, 0, 8));
			if ($prefix == '2001:001') return true;
		}

		if (strlen($forumfirewall_ip) >= 9) {
			$prefix = strtolower(substr($forumfirewall_ip, 0, 9));
			if (($prefix == '2001:0db8') || ($prefix == '2001:db8:')) return true;
		}
		unset($prefix);

		if (strnatcmp(phpversion(),'5.2.0') >= 0) {
			if(!filter_var($forumfirewall_ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6))
  				return true;
			if(!filter_var($forumfirewall_ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE))
  				return true;
			if(!filter_var($forumfirewall_ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_RES_RANGE))
  				return true; }
		return false; }
	return true;
}
function forumfirewall_checkdns($forumfirewall_ip, $forumfirewall_domain) {
	if (strpos($forumfirewall_ip, ':')) return true;
	if ($forumfirewall_ip == '') return true;
  	if (empty($forumfirewall_ip)) return true;
	if (empty($forumfirewall_domain)) return true;
	if (!function_exists('is_callable')) return true;
	if ((!is_callable('gethostbyaddr')) || (!is_callable('gethostbynamel'))) return true;

	$name = gethostbyaddr($forumfirewall_ip);
	if ($name === $forumfirewall_ip) return false;  //  failure

	$pos = strpos(strrev($name), strrev($forumfirewall_domain));
	if ($pos !== false) {
		$host = @gethostbynamel($name);

		if (($host !== false) && (!empty($host)) && is_array($host)) {
			if (sizeof($host) !== 0) {
				$flipped_host = array();
				$flipped_host = array_flip($host);
				if ($flipped_host !== null) {
					if (isset($flipped_host[$forumfirewall_ip])) {
						unset($host, $flipped_host);
						return true;
				}	}
				unset($flipped_host);
			}
			unset($host);
		} else {
		 	if ($host === $forumfirewall_ip) return true;
	}	}

	return false;
}
function forumfirewall_block($forumfirewall_data, $result) {
//  If log is enabled I will load the package and log in the database then send to error screen
	global $modSettings, $sourcedir, $boarddir, $webmaster_email, $txt;
	if (function_exists('loadlanguage')) {
		if(loadlanguage('ForumFirewall') === false)
     		 loadLanguage('ForumFirewall');
	} else {
		require_once($sourcedir . '/Load.php');
		if(loadlanguage('ForumFirewall') === false)
      		loadLanguage('ForumFirewall'); }

	$result_temp = $request_uri = '';

	if ($forumfirewall_data['sql_reason'] == '999')
		$forumfirewall_data['sql_reason'] = $txt['forumfirewall_sql_reason'];

	if ($result[0] == '1')
		$result_temp = $txt['result1'];
	if ($result[0] == '2') {
		if ($forumfirewall_data['sql_reason'] == '100') {
			$result_temp = $txt['result2'] . $txt['forumfirewall_reason0'] . $txt['result0'];
		} else {
			$result_temp = $txt['result2'] . htmlspecialchars($forumfirewall_data['sql_reason']) . $txt['result0']; } }
	if ($result[0] == '3')
		$result_temp = $txt['result3'];
	if ($result[0] == '4')
		if ($forumfirewall_data['sql_reason'] == '200') {
			$result_temp = $txt['result4'] . $txt['forumfirewall_reason1'] . $txt['result0'];
		} elseif ($forumfirewall_data['sql_reason'] == '210') {
			$result_temp = $txt['result4'] . $txt['forumfirewall_reason2'] . $txt['result0'];
		} else {
			$result_temp = $txt['result4']. $txt['result0']; }
	if ($result[0] == '5')
		$result_temp = $txt['result5'] . htmlspecialchars($forumfirewall_data['sql_reason']) . $txt['result0'];
	if ($result[0] == '6')
		$result_temp = $txt['result6'] . htmlspecialchars($forumfirewall_data['sql_reason']) . $txt['result0'];
	if ($result[0] == '7')
		$result_temp = $txt['result7'];
	if ($result[0] == '8')
		$result_temp = $txt['result8'] . htmlspecialchars($forumfirewall_data['sql_reason']) . $txt['result0'];
	if ($result[0] == '9')
		$result_temp = $txt['result9'] . htmlspecialchars($forumfirewall_data['sql_reason']) . $txt['result0'];
	if ($result[0] == '10')
		$result_temp = $txt['result10'] . htmlspecialchars($forumfirewall_data['sql_reason']) . $txt['result0'];
	if (($result[0] == '11') || ($result[0] == '12'))
		$result_temp = $txt['result11'] . htmlspecialchars($forumfirewall_data['sql_reason']) . $txt['result0'];
	if ($result[0] == '13')
		$result_temp = $txt['result13'] . htmlspecialchars($forumfirewall_data['sql_reason']) . $txt['result0'];
	if ($result[0] == '14')
		$result_temp = $txt['result14'];

	// Slow down the bot.
	sleep(2);

	// Log the violation
	if (!empty($modSettings['forumfirewall_logging']) && ($modSettings['forumfirewall_logging']))
		forumfirewall_db_query(forumfirewall_insert($forumfirewall_data, $result_temp));

	//  Send a message
	$request_uri = $forumfirewall_data['request_uri'];
	if (!empty($modSettings['forumfirewall_enable_email'])) {
		if ((($modSettings['forumfirewall_enable_email'] == 1) && ($result[0] == '3')) || ($modSettings['forumfirewall_enable_email'] == 2))
			forumfirewall_email($request_uri, $result_temp, $forumfirewall_data['visitor_ip']); }

	//  Try to save to cache
	if ((isset($modSettings['forumfirewall_cache_duration'])) && (!empty($modSettings['forumfirewall_cache_duration'])) && (((int) $modSettings['forumfirewall_cache_duration']) !== 0)) {
		if (empty($result[1]))
			$result[1] = '1';
		else
			$result[1] = $result[1] + 1;
		if (empty($result[2]))
			$result[2] = time();

		forumfirewall_savecache($forumfirewall_data, $result);
	} else {
		if (((int) $modSettings['forumfirewall_cache_duration']) !== 0) {
			if (function_exists('loadlanguage')) {
				if(loadlanguage('Errors') === false)
   		  		 	loadLanguage('Errors');
			} else {
				require_once($sourcedir . '/Load.php');
				if(loadlanguage('Errors') === false)
   		   			loadLanguage('Errors'); }

			log_error($txt['cfcache'], 'user'); } }

	//  BAN DOS thru SMF
	if ($result[0] == '3') {
		if ($modSettings['forumfirewall_enable_block'] && !empty($modSettings['forumfirewall_longterm_ban']) && ((int) $modSettings['forumfirewall_longterm_ban'] !== 0)) {
			forumfirewall_ip_ban($forumfirewall_data, $result_temp);
  			//  Try to eliminate duplicates 
			sleep(5); }	}

	//  Do not tell them why
	if (($result[0] == '2') || ($result[0] == '9'))
		$result_temp = $txt['result2a'];
	if ($result[0] == '6')
		$result_temp = $txt['result6a'];
	if (($result[0] == '7') || ($result[0] == '10'))
		$result_temp = $txt['result7'];
	if ($result[0] == '8')
		$result_temp = $txt['result4'] . $txt['result0'];

	//  Good bye
	if ($modSettings['forumfirewall_enable_block']) {
		$webmaster_nospam = $webmaster_at = $honeyLink = $maxnum = '';
		$webmaster_nospam = forumfirewall_obfuscate(htmlspecialchars($webmaster_email));
		$webmaster_at = $txt['forumfirewall_theadmin'];
		if(stristr($forumfirewall_data['user_agent'], 'Mediapartners-Google') || stristr($forumfirewall_data['user_agent'], 'Googlebot') || stristr($forumfirewall_data['user_agent'], 'Google Web Preview') || stristr($forumfirewall_data['user_agent'], 'AdsBot-Google')) $maxnum=10;
		$honeyLink = forumfirewall_honeyLink($maxnum);
		unset($result);
		unset($forumfirewall_data);
		define('FFW', dirname(__FILE__));
		require_once($boarddir . '/ff_firewall.php');
		forumfirewall_display_block($honeyLink, $request_uri, $result_temp, $webmaster_nospam, $webmaster_at);
		die(); }
	return;
}
function forumfirewall_honeyLink($maxnum = 18)
{
	global $modSettings;
	$forumfirewall_httpbl_link = $forumfirewall_httpbl_word = '';
	$forumfirewall_httpbl_word = 'login';
	if (empty($maxnum)) $maxnum = 18;
	if (empty($modSettings['badbehavior_httpbl_link']))	{
		if (empty($modSettings['httpBL_honeyPot_link'])) {
			return '';
		} else {
			$forumfirewall_httpbl_link = $modSettings['httpBL_honeyPot_link'];
			if (!empty($modSettings['httpBL_honeyPot_word']))
				$forumfirewall_httpbl_word = $modSettings['httpBL_honeyPot_word'];
		}
	} else {
		$forumfirewall_httpbl_link = $modSettings['badbehavior_httpbl_link'];
		if (!empty($modSettings['badbehavior_httpbl_word']))
			$forumfirewall_httpbl_word = $modSettings['badbehavior_httpbl_word'];
	}

	mt_srand(forumfirewall_make_seed());

	switch (mt_rand(0, $maxnum)) {
		case 0:
			return '<a href="'. $forumfirewall_httpbl_link .'"><!-- '. $forumfirewall_httpbl_word .' --></a>';
		case 1:
			return '<a href="'. $forumfirewall_httpbl_link .'"></a>';
		case 2:
			global $boardurl, $settings;
			return '<img src="'. $boardurl. '/Themes/default/images/'. 'blank.gif" alt="image" usemap="#Map" border="0" width="1" height="1" /><map name="Map" /><area shape="rect" coords="5,-49,35,-4" href="'. $forumfirewall_httpbl_link .'" alt="'. $forumfirewall_httpbl_word .'" /></map>';
		case 3:
			return '<!-- <a href="'. $forumfirewall_httpbl_link .'">'. $forumfirewall_httpbl_word .'</a> -->';
		case 4:
			global $boardurl, $settings;
			return '<a href="'. $forumfirewall_httpbl_link .'"><img src="'. $boardurl. '/Themes/default/images/'. 'blank.gif" alt="'. $forumfirewall_httpbl_word .'" border="0" height="1" width="1" /></a>';
		case 5:
			return '<div style="position: absolute; top: -370px; left: -370px;"><a href="'. $forumfirewall_httpbl_link .'">'. $forumfirewall_httpbl_word .'</a></div>';
		case 6:
			return '<a href="'. $forumfirewall_httpbl_link .'"><font style="font-family: sans-serif;"></font></a>';
		case 7:
			return '<a href="'. $forumfirewall_httpbl_link .'">&#20</a>';
		case 8:
			return '<a href="'. $forumfirewall_httpbl_link .'">&#x020</a>';
		case 9:
			return '<a href="'. $forumfirewall_httpbl_link .'"><img src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAAAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==" alt="'. $forumfirewall_httpbl_word .'" border="0" height="1" width="1" /></a>';
		case 10:
			return '<span style="position:absolute;top:-450px;left:-450px;"><a href="'. $forumfirewall_httpbl_link .'">'. $forumfirewall_httpbl_word .'</a></span>';
		case 11:
			return '<a style="cursor: text; text-decoration: none;" href="'. $forumfirewall_httpbl_link .'">&nbsp;</a>';
		case 12:
			return '<a href="'. $forumfirewall_httpbl_link .'"><div style="height: 0px; width: 0px;"></div></a>';
		case 13:
			return '<a href="'. $forumfirewall_httpbl_link .'" style ="color: #fff;">'. $forumfirewall_httpbl_word .'</a>';
		case 14:
			return '<a href="'. $forumfirewall_httpbl_link .'" style ="visibility: hidden; height: 0px; width: 0px;">'. $forumfirewall_httpbl_word .'</a>';
		case 15:
			return '<a href="'. $forumfirewall_httpbl_link .'"><span style="display: none;">'. $forumfirewall_httpbl_word .'</span></a>';
		case 16:
			return '<img src="'. $forumfirewall_httpbl_link .'" alt="'. $forumfirewall_httpbl_word .'" width="0" height="0" />';
		case 17:
			return '<a href="'. $forumfirewall_httpbl_link .'" style="display: none;">'. $forumfirewall_httpbl_word .'</a>';
		case 16:
			return '<div style="display: none;"><a href="'. $forumfirewall_httpbl_link .'">'. $forumfirewall_httpbl_word .'</a></div>';
		default:
			return '<!-- <a href="'. $forumfirewall_httpbl_link .'">'. $forumfirewall_httpbl_word .'</a> -->';
	}
}
function forumfirewall_make_seed() {
  list($usec, $sec) = explode(' ', microtime());
  return (float) $sec + ((float) $usec * 100000);
}
function forumfirewall_obfuscate($webmaster_email) {
	global $txt, $sourcedir ;

	if (function_exists('loadlanguage')) {
		if(loadlanguage('ForumFirewall') === false)
     		 loadLanguage('ForumFirewall');
	} else {
		require_once($sourcedir . '/Load.php');
		if(loadlanguage('ForumFirewall') === false)
      		loadLanguage('ForumFirewall'); }

	$stamp = date('Ymd');
	$webmaster_nospam  = $email = $encoding = $x = '';
	$search = array('@','.', '-');
	$replace = array($txt['forumfirewall_nospam'], $txt['forumfirewall_dot'], $txt['forumfirewall_dash']);
	$characters = array('0','0000','00','00000','000','00','0000','0', '00000', '000');

	$webmaster_nospam = $txt['forumfirewall_mailto'].str_replace($search, $replace, htmlspecialchars($webmaster_email));

	mt_srand(forumfirewall_make_seed());
	$x = mt_rand(0, (count($characters)-1));
	$encoding = $characters[$x];

	if ($stamp  % 2) {
		for ($i = 0, $email_lng = strlen($webmaster_nospam); $i < $email_lng; $i++) {
			$email .= '&#' . $encoding . ord($webmaster_nospam[$i]). ';'; }

 	} else {
		for ($i = 0, $email_lng = strlen($webmaster_nospam); $i < $email_lng; $i++) {
			$email .= '&#x' .$encoding . dechex(ord($webmaster_nospam[$i])). ';'; }
	}
	$webmaster_nospam = $email;

	unset($email_lng, $i, $x, $email);
	unset($search, $replace, $characters);

	return $webmaster_nospam ;
}
function forumfirewall_email($request_uri, $result_temp, $ip_temp) {
	global $modSettings, $sourcedir, $webmaster_email, $txt;
	if (function_exists('loadlanguage')) {
		if(loadlanguage('ForumFirewall') === false)
     		 loadLanguage('ForumFirewall');
	} else {
		require_once($sourcedir . '/Load.php');
		if(loadlanguage('ForumFirewall') === false)
      		loadLanguage('ForumFirewall'); }

	if (empty($request_uri)) return;
	if (empty($result_temp)) return;

	$emailsubject = $emailbody = '';
	require_once($sourcedir . '/Subs-Post.php');

	$emailsubject = $txt['forumfirewall'] . $txt['forumfirewall_block'];
$emailbody = " " . $txt['forumfirewall_ip'] . $ip_temp . ", " . htmlspecialchars($result_temp) . "\n\r";
	$emailbody .= $txt['forumfirewall_for'] . htmlspecialchars($request_uri) . "\n\r";
	sendmail($webmaster_email, $emailsubject, $emailbody, null, null, false, 0);
}
function forumfirewall_db_query($query) {
	global $smcFunc;

	if ((!isset($query)) || (empty($query))) return false;
	$link = $smcFunc['db_query']($query);
	if (!isset($link) || empty($link)) return false;
	if ($link === true) {
		$affected_rows = $smcFunc['db_affected_rows']();
		if ($affected_rows >= 1) {
			return true;
		} else { return false; }
	} else {
		$number_of_rows = $smcFunc['db_num_rows']($link);
		if ($number_of_rows == '0') {
			return false;
	}	}
	$qresult = forumfirewall_db_rows($link);
	return $qresult;
}
function forumfirewall_db_rows($linkid) {
	global $smcFunc;

	if (empty($linkid)) return false;
	if (!is_array($linkid)) return false;
	$qresult = array();
	$i = 0;
	while($row = $smcFunc['db_fetch_assoc']($linkid)) {
		$qresult[$i] = $row;
    	$i++; }
	if (empty($qresult))
		$qresult = $linkid;
	$smcFunc['db_free_result']($linkid);
	return $qresult;
}
function forumfirewall_ip_ban($forumfirewall_data, $result_temp) {
	global $modSettings, $smcFunc;

	$forumfirewall_ip = $request = $bantime = $banexpire = '';
	$remoteinfo = $row = $octet = array();
	$bannedBefore = false;
	$bantime = time(); //  Ban to cool off

	switch ((int) $modSettings['forumfirewall_longterm_ban']) {
	case 0:
		return; //  Should not be here
	break;
	case 1:
		$banexpire = $bantime + 3600;
	break;
	case 2:
		$banexpire = $bantime + 86400;
	break;
	case 3:
		$banexpire = $bantime + 604800;
	break;
	case 4:
		$banexpire = null;
	break;
	default:
		$banexpire = $bantime + 3600;
	break; }

	$forumfirewall_ip = $forumfirewall_data['visitor_ip'];
	$qresult = $smcFunc['db_query']('', '
		SELECT id_ban_group, expire_time, notes
		FROM {db_prefix}ban_groups
		WHERE name = {string:ipofuser}
		LIMIT 1',
		array(
		'ipofuser' => $forumfirewall_ip,
		)
	);
	if ($smcFunc['db_num_rows']($qresult) > 0) {
		while ($row = $smcFunc['db_fetch_assoc']($qresult)) {
			$remoteinfo['id_ban_group'] = $row['id_ban_group'];
			$remoteinfo['expire_time'] = $row['expire_time'];
			$remoteinfo['notes'] = $row['notes'];
			$bannedBefore = true;
	}	}
	$smcFunc['db_free_result']($qresult);

	if ($bannedBefore) {
		//  First see if the old ban has expired let ban trigger count it
		if (($remoteinfo['expire_time'] == null) || ($remoteinfo['expire_time'] >= time()))
			return;
		$numberOfViolations = intval(substr($remoteinfo['notes'], 20, 4)) + 1;
		$numberOfViolations = strlen($numberOfViolations) == 1 ? '000'. $numberOfViolations : (strlen($numberOfViolations) == 2 ? '00' . $numberOfViolations : (strlen($numberOfViolations) == 3 ? '0' . $numberOfViolations : (strlen($numberOfViolations) == 4 ? $numberOfViolations :  '0001'))) ;

		$smcFunc['db_query']('', '
				UPDATE {db_prefix}ban_groups
				SET
					name = {string:ban_name},
					reason = {string:reason},
					notes = {string:notes},
					expire_time = {raw:expiration},
					cannot_access = {int:cannot_access},
					cannot_post = {int:cannot_post},
					cannot_register = {int:cannot_register},
					cannot_login = {int:cannot_login}
				WHERE id_ban_group = {int:id_ban_group}',
				array(
					'expiration' => $banexpire,
					'cannot_access' => 1,
					'cannot_post' => 0,
					'cannot_register' => 0,
					'cannot_login' => 0,
					'id_ban_group' => $remoteinfo['id_ban_group'],
					'ban_name' => $forumfirewall_ip,
					'reason' => $result_temp,
					'notes' => 'Multiple Violation: ' . $numberOfViolations . ' updated ' . date('Y-m-d H:i:s'),
				)
			);
	} else {
		$smcFunc['db_insert']('',
			'{db_prefix}ban_groups',
			array(
				'name' => 'string-20',
				'ban_time' => 'int',
				'expire_time' => 'raw',
				'cannot_access' => 'int',
				'cannot_register' => 'int',
				'cannot_post' => 'int',
				'cannot_login' => 'int',
				'reason' => 'string-255',
				'notes' => 'string-65534',
			),
			array(
				$forumfirewall_ip,
				$bantime,
				$banexpire,
				1,
				0,
				0,
				0,
				$result_temp,
				$result_temp .' on: '. date('Y-m-d H:i:s'),
			),
			array('id_ban_group')
		);
		$request = $smcFunc['db_query']('', '
			SELECT id_ban_group, reason
			FROM {db_prefix}ban_groups
			WHERE name = {string:ipofuser} AND reason = {string:reasonText}
			LIMIT 1',
			array(
				'ipofuser' => $forumfirewall_ip,
				'reasonText' => $result_temp,
			)
		);
		if ($smcFunc['db_num_rows']($request) > 0)
			while ($row = $smcFunc['db_fetch_assoc']($request)) {
				$remoteinfo['id_ban_group'] = $row['id_ban_group'];
				break; }

		$octet = explode('.', $forumfirewall_ip);
		if (isset($remoteinfo['id_ban_group'])) {
			$smcFunc['db_insert']('',
			'{db_prefix}ban_items',
    	       	array(
					'id_ban_group' => 'int',
					'ip_low1' => 'int',
					'ip_high1' => 'int',
					'ip_low2' => 'int',
					'ip_high2' => 'int',
					'ip_low3' => 'int',
					'ip_high3' => 'int',
					'ip_low4' => 'int',
					'ip_high4' => 'int',
				),
        	   	array(
					$remoteinfo['id_ban_group'],
					$octet[0],
					$octet[0],
					$octet[1],
					$octet[1],
					$octet[2],
					$octet[2],
					$octet[3],
					$octet[3],
        	   	),
     	      	array()
    	    );
	}	}
return;
}
function forumfirewall_inspect_system() {
	global $modSettings, $txt, $sourcedir;

	if (function_exists('loadlanguage')) {
		if(loadlanguage('ForumFirewall') === false)
     		 loadLanguage('ForumFirewall');
	} else {
		require_once($sourcedir . '/Load.php');
		if(loadlanguage('ForumFirewall') === false)
      		loadLanguage('ForumFirewall'); }

	$string = '';
	if (!$modSettings['forumfirewall_enable']) return $string;

	if ((bool) ini_get('register_gobals')) {
		$string = $txt['forumfirewall_register_globals'];
		if (get_magic_quotes_gpc() || get_magic_quotes_runtime() || ((bool) ini_get('magic-quotes-sybase')))
			$string .= '<br />' . $txt['forumfirewall_magic_quotes'];
	} else {
		if (get_magic_quotes_gpc() || get_magic_quotes_runtime() || ((bool) ini_get('magic-quotes-sybase')))
			$string = $txt['forumfirewall_magic_quotes']; }
	return strtoupper($string);
}
// ref (OWASP A1)
function forumfirewall_my_utf8_decode($forumfirewall_conv_dta) {
return strtr($forumfirewall_conv_dta,
  "???????¥µÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝßàáâãäåæçèéêëìíîïðñòóôõöøùúûüýÿ",
  "SOZsozYYuAAAAAAACEEEEIIIIDNOOOOOOUUUUYsaaaaaaaceeeeiiiionoooooouuuuyy");
}

function forumfirewall_is_utf8($forumfirewall_conv_dta) {

	for ($i = 0, $len = strlen($forumfirewall_conv_dta); $i < $len; $i++) {
        $c = ord($forumfirewall_conv_dta[$i]);
        if ($c < 0x80) {
            if (($c > 0x1F && $c < 0x7F) || $c == 0x09 || $c == 0x0A || $c == 0x0D) continue; }
		if (($c & 0xE0) == 0xC0) $n = 1;
		elseif (($c & 0xF0) == 0xE0) $n = 2;
		elseif (($c & 0xF8) == 0xF0) $n = 3;
		elseif (($c & 0xFC) == 0xF8) $n = 4;
		elseif (($c & 0xFE) == 0xFC) $n = 5;
		else return false;
		for ($j = 0; $j < $n; $j++) {
			$i++;
			if ($i == $len || ((ord($forumfirewall_conv_dta[$i]) & 0xC0) != 0x80)) return false;
	}	}
    return true;
}
function forumfirewall_get_hash($stamp) {
	if ($stamp  % 2) {
		$forumfirewall_algo = 'haval160,4';
	} else {
	$forumfirewall_algo = 'tiger192,4'; }
	return $forumfirewall_algo;
}
function forumfirewall_cache_put_data($key, $value, $ttl = 20) {
	global $boardurl, $sourcedir, $modSettings;
	global $cache_hits, $cache_count, $db_show_debug, $ffcachedir;

	if (((int) $modSettings['forumfirewall_cache_duration']) < 20) return;
	
	$cache_count = isset($cache_count) ? $cache_count + 1 : 1;
	if (isset($db_show_debug) && $db_show_debug === true)
	{
		$cache_hits[$cache_count] = array('k' => $key, 'd' => 'put', 's' => $value === null ? 0 : strlen(serialize($value)));
		$st = microtime();
	}

	$value = empty($value) ? null : serialize($value);

	// Custom cache?
	if (function_exists('fwrite')) {
		if ($value === null)
			@unlink($ffcachedir . '/data_' . $key . '.php');
		else {
			$cache_data = $cache_bytes = $fh = '';
			$cache_data = '<' . '?' . 'php if (!defined(\'SMF\')) die; if (' . (time() + $ttl) . ' < time()) $expired = true; else{$expired = false; $value = \'' . addcslashes($value, '\\\'') . '\';}' . '?' . '>';
			$fh = @fopen($ffcachedir . '/data_' . $key . '.php', 'w');
			if ($fh) {
				// Write the file.
				set_file_buffer($fh, 0);
				flock($fh, LOCK_EX);
				$cache_bytes = fwrite($fh, $cache_data);
				flock($fh, LOCK_UN);
				fclose($fh);

				// Check that the cache write was successful; all the data should be written
				// If it fails due to low diskspace, remove the cache file
				if ($cache_bytes != strlen($cache_data))
					@unlink($ffcachedir . '/data_' . $key . '.php');
	}	}	}

	if (isset($db_show_debug) && $db_show_debug === true)
		$cache_hits[$cache_count]['t'] = array_sum(explode(' ', microtime())) - array_sum(explode(' ', $st));

	return;
}

function forumfirewall_cache_get_data($key, $ttl = 20) {
	global $boardurl, $sourcedir, $modSettings;
	global $cache_hits, $cache_count, $db_show_debug, $ffcachedir;

	if (((int) $modSettings['forumfirewall_cache_duration']) < 20) return '';

	$cache_count = isset($cache_count) ? $cache_count + 1 : 1;
	if (isset($db_show_debug) && $db_show_debug === true) {
		$cache_hits[$cache_count] = array('k' => $key, 'd' => 'get');
		$st = microtime();
	}

	// Use SMF data cache!
	if (file_exists($ffcachedir . '/data_' . $key . '.php') && filesize($ffcachedir . '/data_' . $key . '.php') > 10) {
		require($ffcachedir . '/data_' . $key . '.php');
		if (!empty($expired) && isset($value)) {
			@unlink($ffcachedir . '/data_' . $key . '.php');
			unset($value);
	}	}

	if (isset($db_show_debug) && $db_show_debug === true) {
		$cache_hits[$cache_count]['t'] = array_sum(explode(' ', microtime())) - array_sum(explode(' ', $st));
		$cache_hits[$cache_count]['s'] = isset($value) ? strlen($value) : 0;
	}

	if (empty($value))
		return null;
	// If it's broke, it's broke... so give up on it.
	else
		return @unserialize($value);
}
function FFCopyright() {
	// The Copyright is required to remain.  It can only be removed if you provide a donation
	echo '<br /><div align="center"><span class="smalltext">Protected by: <a href="http://www.eastcoastrollingthunder.com" target="_blank" class="new_win">Forum Firewall &copy; 2010-2011</a></span></div>';
}
// Our log table structure - 
function forumfirewall_table_structure($name) {
	global $txt, $modSettings, $db_prefix, $smcFunc;

	db_extend('packages');

	return $smcFunc['db_table_structure']($db_prefix.'log_forumfirewall');
}

// Insert a new record modified for SMF 2.0 RC2
function forumfirewall_insert($forumfirewall_data, $result) {
	global $txt, $modSettings, $db_prefix, $smcFunc;

	if (empty($forumfirewall_data)) return;
	if (!is_array($forumfirewall_data)) return;
	if (empty($result)) return;

	$request = $headers = $forumfirewall_ip = $request_method = '';
	$request_uri = $server_protocol = $user_agent = $referer = '';

	$forumfirewall_ip = $forumfirewall_data['visitor_ip'];
	if (empty($forumfirewall_data['request_entity']))
		$request_method = $forumfirewall_data['request_method'];
	else {
		$request_method = $forumfirewall_data['request_method'];
		foreach ($forumfirewall_data['request_entity'] as $h => $v) {
			$request_method .= $h . ": " .$v . "\n\r"; }
		unset($v);
	}
	$request_uri = $forumfirewall_data['request_uri'];
	$server_protocol = $forumfirewall_data['server_protocol'];
	$user_agent = $forumfirewall_data['user_agent'];
	$referer = (isset($forumfirewall_data['referer']) ? $forumfirewall_data['referer']: '');

	$headers = "$request_method $request_uri $server_protocol\n";
	$headers .= "$user_agent\n";
	$headers .= "$referer\n";

	$date = date('Y-m-d H:i:s');

$request = $smcFunc['db_insert']('insert',
	'{db_prefix}log_forumfirewall',
	array(
		'ip' => 'string-16', 
		'date' => 'string-19',
		'http_headers' => 'string-65534',
		'result' => 'string-255',
         ),
	array(
		$forumfirewall_ip,
		$date,
		$headers,
		$result,
	),
	array()
);

	return $request;
}
// Create a new table for SMF 2.0 RC2
function forumfirewall_insert_table() {

	global $smcFunc, $txt, $user_info, $db_prefix, $ssi_theme;

	if (function_exists('db_extend')) {
		db_extend('packages');
		db_extend();
	} else {
		require_once($sourcedir . '/Subs-Db-mysql.php');
		db_extend('packages');
		db_extend(); }

	//  check for old database
	$old_table_exists = $smcFunc['db_list_tables'](false, $db_prefix . 'log_forumfirewall');

	//  add new 2.0 database
	if (empty($old_table_exists)) {
		$smcFunc['db_create_table']($db_prefix.'log_forumfirewall',
			array(
				array(
					'name' => 'id',
					'type' => 'int',
					'size' => 11,
					'null' => false,
					'default' => '',
					'auto' => true,
					'unsigned' => true,
				),
				array(
					'name' => 'ip',
					'type' => 'varchar',
					'size' => 16,
					'null' => false,
					'default' => '',
					'auto' => false,
					'unsigned' => false,
				),
				array(
					'name' => 'date',
					'type' => 'varchar',
					'size' => 19,
					'null' => false,
					'default' => '0000-00-00 00:00:00',
					'auto' => false,
					'unsigned' => false,
				),
				array(
					'name' => 'http_headers',
					'type' => 'text',
					'size' => '',
					'null' => false,
					'default' => '',
					'auto' => false,
					'unsigned' => false,
				),
				array(
					'name' => 'result',
					'type' => 'varchar',
					'size' => 255,
					'null' => false,
					'default' => '',
					'auto' => false,
					'unsigned' => false,
				),
			),
			array( 
				array(
					'type' => 'primary',
					'columns' => array('id')
				),
				array(
					'type' => 'index',
					'columns' => array('ip'),
					'size' => 15,
				),
			),
			'ignore'
		);
	}
}

?>