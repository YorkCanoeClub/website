<?php if ((!defined('FFW')) && (!defined('SMF'))) die('Access denied!');

function forumfirewall_display_block($honeyLink, $request_uri, $result_temp, $webmaster_nospam, $webmaster_at) {
	global $txt, $language, $sourcedir;

	if (function_exists('loadlanguage')) {
		if (loadlanguage('ff_firewall', $language) === false)
			loadLanguage('ff_firewall', $language);
	} else {
		require_once($sourcedir . '/Load.php');
		loadLanguage('ff_firewall', $language); }

	$protocol = '';
	$protocol = ((isset($_SERVER['SERVER_PROTOCOL']))  && (!empty($_SERVER['SERVER_PROTOCOL']))) ? $_SERVER['SERVER_PROTOCOL'] : @getenv('SERVER_PROTOCOL');
	if ($protocol != 'HTTP/1.0' && $protocol != 'HTTP/1.1') $protocol = 'HTTP/1.0';

	header($protocol . ' ' . $txt['forumfirewall_notice'] . ' ' . $txt['forumfirewall_ban']);
	header('Status: ' . $txt['forumfirewall_notice'] . ' ' . $txt['forumfirewall_ban']);

echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
   "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="' , $txt['forumfirewall_langcode'] , '" xml:lang="' , $txt['forumfirewall_langcode'] , '">
<head>';
echo '<meta http-equiv="content-type" content="text/html; charset=' , $txt['forumfirewall_charset'] , '" /><title>', $txt['forumfirewall_notice'], '</title>';
echo '</head><body>';
echo '<h1>' . $txt['forumfirewall_notice'] . '</h1>
<p>' . $txt['forumfirewall_fufill']. '</p><p>' . htmlspecialchars($request_uri) . '' . $txt['forumfirewall_server'] . '</p><p>' . $txt['forumfirewall_determined'] . '' . htmlspecialchars($result_temp) . '' . $honeyLink . '</p><p>' . $txt['forumfirewall_please'] . '<a href="' . $webmaster_nospam . '">' . $webmaster_at . '</a>' . $txt['forumfirewall_provide'] . '</p>
</body></html>';

die();
}
?>