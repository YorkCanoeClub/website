<?php
/********************************************************************************
* Subs-Aeva-Generated-Sites.php v6
* By Rene-Gilles Deberdt (created by Karl Benson)
*********************************************************************************
* The full/complete definitions are now stored in Subs-Aeva-Sites.php
* This is a GENERATED php file containing ONLY ENABLED sites for the Aeva Mod,
* and is created when enabling/disabling sites via the admin panel.
* It's more efficient this way.
*********************************************************************************
* This program is distributed in the hope that it is and will be useful, but
* WITHOUT ANY WARRANTIES; without even any implied warranty of MERCHANTABILITY
* or FITNESS FOR A PARTICULAR PURPOSE.
********************************************************************************/

global $sites;
$sites = array(
	array(
		'id' => 'local_mp3',
		'plugin' => 'flash',
		'pattern' => '({local}[\w/ &;%\.-]+\.mp3)(?=")',
		'movie' => 'http://www.flash-mp3-player.net/medias/player_mp3_maxi.swf?mp3=$2&width=250&showstop=1&showinfo=1&showvolume=1&volumewidth=35&sliderovercolor=ff0000&buttonovercolor=ff0000',
		'size' => array(250, 20, 1),
		'show-link' => true,
	),
	array(
		'id' => 'local_mp4',
		'plugin' => 'flash',
		'pattern' => '({local}[\w/ &;%\.-]+\.mp4)(?=")',
		'movie' => 'http://www.archive.org/flow/FlowPlayerLight.swf?config=%7Bembedded%3Atrue%2CshowFullScreenButton%3Atrue%2CshowMuteVolumeButton%3Atrue%2CshowMenu%3Atrue%2CautoBuffering%3Afalse%2CautoPlay%3Afalse%2CinitialScale%3A%27fit%27%2CmenuItems%3A%5Bfalse%2Cfalse%2Cfalse%2Cfalse%2Ctrue%2Ctrue%2Cfalse%5D%2CusePlayOverlay%3Afalse%2CshowPlayListButtons%3Atrue%2CplayList%3A%5B%7Burl%3A%27$2%27%7D%5D%2CcontrolBarGloss%3A%27high%27%2CshowVolumeSlider%3Atrue%2Cloop%3Afalse%2CcontrolBarBackgroundColor%3A%270x808080%27%7D',
		'size' => array(480, 360),
		'show-link' => true,
	),
	array(
		'id' => 'local_flv',
		'plugin' => 'flash',
		'pattern' => '({local}[\w/ &;%\.-]+\.flv)(?=")',
		'movie' => 'http://www.archive.org/flow/FlowPlayerLight.swf?config=%7Bembedded%3Atrue%2CshowFullScreenButton%3Atrue%2CshowMuteVolumeButton%3Atrue%2CshowMenu%3Atrue%2CautoBuffering%3Afalse%2CautoPlay%3Afalse%2CinitialScale%3A%27fit%27%2CmenuItems%3A%5Bfalse%2Cfalse%2Cfalse%2Cfalse%2Ctrue%2Ctrue%2Cfalse%5D%2CusePlayOverlay%3Afalse%2CshowPlayListButtons%3Atrue%2CplayList%3A%5B%7Burl%3A%27$2%27%7D%5D%2CcontrolBarGloss%3A%27high%27%2CshowVolumeSlider%3Atrue%2Cloop%3Afalse%2CcontrolBarBackgroundColor%3A%270x808080%27%7D',
		'size' => array(480, 360),
		'show-link' => true,
	),
	array(
		'id' => 'local_swf',
		'plugin' => 'flash',
		'pattern' => '({local}[\w/ &;%\.-]+\.swf)(?=")',
		'movie' => '$2',
		'size' => array(425, 355),
		'show-link' => true,
	),
	array(
		'id' => 'local_divx',
		'plugin' => 'divx',
		'pattern' => '({local}[\w/ &;%\.-]+\.divx)(?=")',
		'movie' => '$2',
		'size' => array(500, 360),
		'show-link' => true,
	),
	array(
		'id' => 'local_mov',
		'plugin' => 'quicktime',
		'pattern' => '({local}[\w/ &;%\.-]+\.mov)(?=")',
		'movie' => '$2',
		'size' => array(500, 360),
		'show-link' => true,
	),
	array(
		'id' => 'local_real',
		'plugin' => 'realmedia',
		'pattern' => '({local}[\w/ &;%\.-]+\.ra?m)(?=")',
		'movie' => '$2',
		'size' => array(500, 360),
		'show-link' => true,
	),
	array(
		'id' => 'local_wmp',
		'plugin' => 'wmp',
		'pattern' => '({local}[\w/ &;%\.-]+\.wm[va])(?=")',
		'movie' => '$2',
		'size' => array(500, 360),
		'show-link' => true,
	),
	array(
		'id' => 'local_avi',
		'plugin' => 'divx',
		'pattern' => '({local}[\w/ &;%\.-]+\.avi)(?=")',
		'movie' => '$2',
		'size' => array(500, 360),
		'show-link' => true,
	),
	array(
		'id' => 'ytb',
		'pattern' => 'http://(?:video\.google\.(?:com|com?\.[a-z]{2}|[a-z]{2})/[^"]*?)?(?:(?:www|[a-z]{2})\.)?youtube\.com/[^"#[]*?(?:&|&amp;|/|\?|;|\%3F|\%2F)(?:video_id=|v(?:/|=|\%3D|\%2F))([\w-]{11})',
		'movie' => 'http://www.youtube.com/v/$2&rel=0&fs=1',
		'size' => array('normal' => array(480, 385), 'ws' => array(640, 385)),
		'ui-height' => 25,
		'fix-html-pattern' => '<object [^>]*><param name="movie" value="http://www\.youtube\.com/v/([\w-]{11})(?:&[^"]*)?">.*?</object>',
		'fix-html-url' => 'http://www.youtube.com/watch?v=$1',
		'lookup-url' => 'http://(?:video\.google\.(?:com|com?\.[a-z]{2}|[a-z]{2})/[^"]*?)?(?:(?:www|[a-z]{2})\.)?youtube\.com/[^"#[]*?(?:&|&amp;|/|\?|;|\%3F|\%2F)(?:video_id=|v(?:/|=|\%3D|\%2F))([\w-]{11})[^]#[]*',
		'lookup-actual-url' => 'http://gdata.youtube.com/feeds/api/videos/$1?v=2',
		'lookup-final-url' => 'http://www.youtube.com/watch?v=$1',
		'lookup-title' => true,
		'lookup-title-skip' => true,
		'lookup-pattern' => array('id' => '<id>.*?:([\w-]+)</id>', 'error' => '<internalReason>(.*?)</internalReason>', 'noexternalembed' => '<yt:accessControl\saction=\'embed\'\spermission=\'denied\'/>', 'ws' => '<yt:aspectRatio>widescreen</yt:aspectRatio>'),
	),
	array(
		'id' => 'ytp',
		'pattern' => 'http://(?:(?:www|[a-z]{2})\.)?youtube\.com/[^"]*?(?:&|&amp;|/|\?|;)(?:id=|p=|p/)([0-9a-f]{16})',
		'movie' => 'http://www.youtube.com/p/$2&rel=0&fs=1',
		'size' => array(480, 385),
		'ui-height' => 25,
		'fix-html-pattern' => '<object [^>]*><param name="movie" value="$1" />.*?</object>',
	),
	array(
		'id' => 'ggl',
		'pattern' => 'http://video\.google\.(com|com?\.[a-z]{2}|[a-z]{2})/(?:videoplay|url|googleplayer\.swf)\?[^"]*?docid=([\w-]{1,20})',
		'movie' => 'http://video.google.$2/googleplayer.swf?docId=$3',
		'size' => array(400, 326),
		'show-link' => true,
		'lookup-title' => true,
		'fix-html-pattern' => '<embed id="VideoPlayback" src="$1"[^>]*>\s</embed>',
	),
	array(
		'id' => 'gmap',
		'plugin' => 'html',
		'pattern' => '(http://maps\.google\.[^">]+/\w*?\?[^">]+)',
		'movie' => '<iframe class="aext" width="{int:width}" height="{int:height}" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="$1&amp;output=embed"></iframe>',
		'size' => array(425, 350),
		'ui-height' => 0,
		'fix-html-pattern' => '<iframe [^>]+src="$1"></iframe>(?:<br /><small>.*?</small>)?',
		'fix-html-url' => '$1',
	),
	array(
		'id' => 'vimeo',
		'pattern' => 'http://(?:www\.)?vimeo\.com/(\d{1,12})',
		'movie' => 'http://vimeo.com/moogaloop.swf?clip_id=$2&server=vimeo.com&fullscreen=1&show_title=1&show_byline=1&show_portrait=0&color=01AAEA',
		'size' => array(640, 360),
		'fix-html-pattern' => '<object [^>]*>\s{0,3}<param name="allowfullscreen" value="true" />\s{0,3}<param name="allowscriptaccess" value="always" />\s{0,3}<param name="movie" value="http://vimeo\.com/moogaloop\.swf\?clip_id=(\d{1,12})[^<>]*?>.*?</object>(?:<p><a href="http://vimeo\.com.*?</a>.*?</a>.*?</a>\.</p>)?',
		'fix-html-url' => 'http://www.vimeo.com/$1',
		'lookup-title' => true,
		'lookup-title-skip' => true,
	),
	array(
		'id' => 'face',
		'pattern' => 'http://(?:www.)?facebook\.com/video/video\.php\?v=(\w+)',
		'movie' => 'http://www.facebook.com/v/$2',
		'size' => array(480, 360),
		'lookup-url' => 'http://(?:www.)?facebook\.com/video/video\.php\?v=(\w+)(?:&oid=\d+)?',
		'lookup-pattern' => array('w' => '"video_width", "(\d+)"', 'h' => '"video_height", "(\d+)"'),
		'lookup-title' => false,
	),
	array(
		'id' => 'flk',
		'pattern' => 'http://www.flickr.com/photos/[^/]+/(\d+)/?#secret(\w+)',
		'movie' => 'http://www.flickr.com/apps/video/stewart.swf?v=66164&photo_secret=$3&photo_id=$2',
		'size' => array(425, 344),
		'lookup-url' => 'http://www.flickr.com/photos/[^/]+/(\d+)/?',
		'lookup-pattern' => array('secret' => 'photo_secret: \'(\w+)\'', 'w' => 'stewart_go_go_go\((\d+),', 'h' => 'stewart_go_go_go\(\d+, (\d+),'),
	),
);
?>