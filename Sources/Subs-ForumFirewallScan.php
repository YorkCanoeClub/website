<?php
/*********************************************************************************
* ref kb_scan.php                                                                    *
* ------------------------------------------------------------------------------ *
* This file can be used to check for file infections using the recent krisbarteo *
* exploit. If any infections are found, this should also be able to fix them.    *
**********************************************************************************
* Utility version:                1.0.5                                           *
* Utility by:                     Fustrate                                       *
* Testing:                        JBlaze                                         *
* Exploit Info:                   Sarge                                          *
* File Traversal:                 SlammedDime                                    *
* Detection Query:                SleePy                                         *
* ============================================================================== *
* Instructions: Upload this file to the root of your SMF installation, alongside *
* SSI.php and index.php, and navigate to it in your browser.                     *
*********************************************************************************/
if (!defined('SMF'))
	die('Hacking attempt...');

class ImageScanner {
	var $_dir = '';
	var $_include = array('jpg', 'png', 'gif');

	function ImageScanner($_dir) {
		$this->_dir = $_dir;
	}

	function run() {
		set_time_limit(300);
		$this->_run($this->_dir);
	}

	function _run($file) {
		if (substr($file,-1) == '.')
			$file = substr($file,0,-1);

		if (is_file($file) && is_readable($file))
			return $this->_checkFile($file);
		elseif (!is_dir($file) || !$dir_handle = opendir($file)) {
			loadLanguage('Errors');
			log_error($txt['cfscan'] . $file, 'user');
			return;
		}

		while(($filename = readdir($dir_handle)) !== false) {
			if (($filename == '.') || ($filename == '..'))
				continue;

			$extension = array_pop(explode('.', $filename));
			$real_path = $file . DIRECTORY_SEPARATOR . $filename;

			$skip_file = (($this->_include !== false) && !in_array($extension, $this->_include)) ? true : false;

			if(is_file($real_path) && is_readable($real_path) && !$skip_file) {
				$this->_checkFile($real_path);
			}
			elseif(is_dir($real_path))
				$this->_run($real_path);
		}
		closedir($dir_handle);
	}
	function _checkFile($filepath) {
	global $modSettings;

		$contents = file($filepath);

		if (!empty($modSettings['forumfirewall_xxs'])) {
			//  Look at settings - elements are separated by '|'
			$exploits_array = array();
			$exploits_array = explode('|', $modSettings['forumfirewall_xxs']);

			$forumfirewall_query = $exploit = '';

		$contents = str_ireplace(array('%3C','<','&lt','&#60;','&#060;','&#0060;','&#00060;','&#000060;','&#0000060;','&#60','&#060','&#0060','&#00060','&#000060','&#0000060','&#x3c;','&#x03c;','&#x003c;','&#x0003c;','&#x00003c;','&#x000003c;','&#x3c','&#x03c','&#x003c','&#x0003c','&#x00003c','&#x000003c','\x3c','\u003c','PA=='), '&lt;', $contents);
		$contents = str_ireplace(array('%3E','>','&gt','&#62;','&#062;','&#0062;','&#00062;','&#000062;','&#0000062;','&#62','&#062','&#0062','&#00062','&#000062','&#0000062','&#x3e;','&#x03e;','&#x003e;','&#x0003e;','&#x00003e;','&#x000003e;','&#x3e','&#x03e','&#x003e','&#x0003e','&#x00003e','&#x000003e','\x3e','\u003e','PG=='), '&gt;', $contents);
		$contents = str_ireplace(array('%3F','&#63;','&#063;','&#0063;','&#00063;','&#000063;','&#0000063;','&#63','&#063','&#0063','&#00063','&#000063','&#0000063','&#x3f;','&#x03f;','&#x003f;','&#x0003f;','&#x00003f;','&#x000003f;','&#x3f','&#x03f','&#x003f','&#x0003f','&#x00003f','&#x000003f','\x3f','\u003f','PW=='), '?', $contents);
		$contents = str_ireplace(array('%5C','&#92;','&#092;','&#0092;','&#00092;','&#000092;','&#0000092;','&#92','&#092','&#0092','&#00092','&#000092','&#0000092','&#x5c;','&#x05c;','&#x005c;','&#x0005c;','&#x00005c;','&#x000005c;','&#x5c','&#x05c','&#x005c','&#x0005c','&#x00005c','&#x000005c','\x5c','\u005c','XA=='), '\\', $contents);

		@$forumfirewall_query = strtoupper(htmlspecialchars($contents));

		foreach ($exploits_array as $exploit) {
			$pos = strpos($forumfirewall_query, strtoupper(htmlspecialchars($exploit)));
			if ($pos !== FALSE) {
				// Injection detected
				global $sourcedir;
				require_once($sourcedir . '/Subs-ForumFirewall.php');
				//  Make data array
				$forumfirewall_data = array(
					'visitor_ip' => '',
					'request_method' => $filepath.' contains the following exploit: ' .$exploit,
					'request_uri' => '',
					'user_agent' => '',
					'server_protocol' => '',
					'sql_reason' => 'FORUM INFECTED with XSS!',
				);
				forumfirewall_db_query(forumfirewall_insert($forumfirewall_data, $forumfirewall_data['sql_reason']));
				unset($forumfirewall_data);
			}	}	}

		$base = basename($filepath);
}	}
if (!function_exists('str_ireplace')) {
	function str_ireplace($search, $replace, $subject) {
		if (is_array($search)) {
			$words = array();
			foreach ($search as $word) {
				$words[] = '/' . $word . '/i';
		}	}
		else {
			$words = '';
			$words = '/' . $search . '/i'; }
		return preg_replace($words, $replace, $subject);
}	}

?>