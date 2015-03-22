=== Simply Attached ===
Contributors: michaelkay
Tags: simple, page, post, shortcode, document management, attachment, attachments, dropbox
Requires at least: 3.6
Tested up to: 3.9
Stable tag: 1.6
License: GPLv2

Simply Attached gives the ability to append any number of files to your pages and posts. It creates a nice looking table of download links. 

== Description ==
Simply Attached gives you the ability to append any number of downloadable files to your pages and posts. It creates a nice looking table of links and descriptions. You have the ability to select what information is displayed (such as file description and size) and there are five different list styles. 
 
Normally the file list is appended to the bottom of the post / page but there is an option to use a shortcode that allows you to insert the list anywhere in your text. 
 
Any file stored in the WordPress Media Library can be attached to a page or post. Also, You are able to share files stored in Dropbox. 

= Other features =
* Add a list of download files to any page or post
* List can appear at the bottom of the post or in the text with a shortcode
* Select files from the local Media Libary or Dropbox
* Pick from five different list styles
* The long file description can be disabled
* Select the background color for the list items or disable color
* Select other style options including file size format

== Installation ==
1. Download the plugin and extract the files
2. Upload "simplyattached" to your wp-content/plugins/ directory
3. Activate the plugin through the 'Plugins' menu in WordPress
4. Customize in the settings to match your theme.
5. Add some attachments to a page or post
6. (optional) Dropbox integration is disabled by default. Go to the Simply Attached configuration screen to enable and add the required API key. 

NOTE: To use the Dropbox File Chooser you need an application key (it's free) from Dropbox. Go to https://www.dropbox.com/developers/apps/create and create a free Drop-ins app to get the app code needed by Simply Attached.

== Frequently Asked Questions ==

= Will the list styles match my current theme? =

There are five attachment list styles that should work in most cases. Additionally, you have the ability to change the colors and some font styling. Also, more styles are coming.

= What features are coming? =

In the future I will be adding more list styles and more theme support, the ability for custom CSS in lists, MORE cloud file storage (Amazon S3 for example), PayPal support for paid digital downloads.

= Why do I need an App Key from Dropbox? =

The Dropbox Chooser app needs to be registered to a web domain and it cannot be generic. Sadly, that means I could not create a generic integration for everybody and you will have to register to get an app key. But, the app key is free and you can get it instantly.

= The Dropbox Chooser window is a pop-up and it doesn't always appear or it is under another window. = 

I really tried to fix this but it seems that there is a problem on the Dropbox side. I did create a bug ticket and I hope it gets fixed because it annoys me too.

= Can I disable all of the Dropbox attachments at once? = 

Yes. On the configuration screen uncheck the 'Use Dropbox' option. The normal attachments will continue to work and the Dropbox attachments will not appear in posts. Dropbox attachments WILL ALWAYS appear on the post edit screen until deleted.

= Is Simply Attached a new plugin? =

Simply Attached is based on the Attachments plugin by Jonathan Christopher and the Easy Attachments plugin by Dan Nagle. It includes bug fixes and improvements. 

== Screenshots ==

1. This shows the configuration screen located under the Settings section of the WordPress admin screens
2. This shows example of the three different list styles. The color of the blocks can be configured or disabled
3. Here are the two 'grid' list styles. The colors used can be changed or disabled
4. Here is the Post edit screen showing the Dropbox button

== Changelog ==

= 1.6 =
* Fixed a naming conflict that prevented new installs from working
* Fixed a bug with the Add Media Button

= 1.5 =
* Minor bug fix

= 1.4 =
* Added Dropbox integration
* Added two grid themes

= 1.3 =
* Javascript cleanup 

= 1.2 =
* Minor bug fixes 

= 1.1 =
* Added the ability to suppress color bars
* Minor bug fixes

= 1.0 =
* First version. Includes three basic list styles
