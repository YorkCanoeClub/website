<?php
/*
Plugin Name: Simply Attached
Plugin URI: http://wordpress.org/plugins/simply-attached/
Description: Simply Attached is the quick way to add downloadable files to posts and pages. Includes several different formatting and style options.
Version: 1.6
Author: Michael Kercsmar 
Author URI: http://profiles.wordpress.org/michaelkay/
*/

/*  Copyright 2014 Green Island Companies

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/*
Simply attached is based on code by Dan Nagle / Jonathan Christopher / JR Tashjian
*/

// ===========
// = GLOBALS =
// ===========

global $wpdb;

// =========
// = HOOKS =
// =========
//if( WP_ADMIN )
if( is_admin() )
{
	add_action( 'admin_menu', 'simplyattached_init' );
	//add_action( 'admin_head', 'simplyattached_init_js' );
	add_action( 'save_post',  'simplyattached_save' );
	add_action( 'admin_menu', 'simplyattached_menu' );
	//add_action( 'admin_init', 'fix_async_upload_image' );
}

// Add the filter to add the HTML code after the post
add_filter( 'the_content', 'simplyattached_contentadder');
// Add the CSS file and js
add_action( 'wp_enqueue_scripts', 'safely_add_scripts_stylesheets' );
add_action( 'admin_enqueue_scripts', 'safely_add_admin_scripts_stylesheets' );


// [simplyattached show="val" hide="val]
function simplyattached_shortcode_handler( $atts, $content = "Attachments" ) {
	
	$simplyattached_useshortcode_check = get_option('simplyattached_useshortcode_check', false);
	if($simplyattached_useshortcode_check == "on" || $simplyattached_useshortcode_check == "true" || $simplyattached_useshortcode_check == "checked")
	{
		extract( shortcode_atts( array(
			'show' => 'true',
			'hide' => 'false',
		), $atts ) );

		return simplyattached_contentadder("", $content);
	} else {

		return "";		
	}
	
}
add_shortcode( 'simplyattached', 'simplyattached_shortcode_handler' );

// ========================
// = STADARD DISPLAY
// ========================

/**
 * Inserts the HYML for the attachments list
 *
 * @param string $content The original content
 * @param string $title The header for the section
 * @return string The modifidaed HTML of the post
 * @author Michael Kercsmar / Jonathan Christopher
 */
function simplyattached_contentadder ($content, $title = "Attachments") {

global $post;

	// If the shortcode is being used, do not automatically append the attachments list
	$simplyattached_useshortcode_check = get_option('simplyattached_useshortcode_check', false);
	if($simplyattached_useshortcode_check == "on" || $simplyattached_useshortcode_check == "true" || $simplyattached_useshortcode_check == "checked")
	{
		// Short codes are on - check if this is the 'end of post' filter call
		if($content != "")
		{
			// Just return the content unchanged if using short codes - do not add the attachments at the end too
			return $content;
		}
	
	}

    $attachments = simplyattached_get_attachments($post->ID, true);
    $total_attachments = count($attachments);
    if(($total_attachments) > 0)
    {

	// Get the parameters set on the admin page
	$simplyattached_showfilesize_check = get_option('simplyattached_showfilesize_check', true);
	$simplyattached_boldfilesize_check = get_option('simplyattached_boldfilesize_check', true);
	$simplyattached_showfilecaption_check = get_option('simplyattached_showfilecaption_check', true);
	$simplyattached_showlistingcount_check = get_option('simplyattached_showlistingcount_check', true);
	$simplyattached_usecolor_check = get_option('simplyattached_usecolor_check', true);
	$simplyattached_rowcolor1 = get_option('simplyattached_rowcolor1', '#fdfcc9');
	$simplyattached_rowcolor2 = get_option('simplyattached_rowcolor2', '#faf99e');
	$simplyattached_useshortcode_check = get_option('simplyattached_useshortcode_check', false);
	$simplyattached_style = get_option('simplyattached_style', 'default');
	
	// Is this a post/page or a summary list
	if(!is_singular())
	{
		if($simplyattached_showlistingcount_check == "on" || $simplyattached_showlistingcount_check == "true" || $simplyattached_showlistingcount_check == "checked")
		{
			return $content.'<br/>(Contains <a href="'.get_permalink($post->ID).'#attachments">'.$total_attachments.' attachments</a>.)'; 
		} else {
			return $content;
		}
	} else	{

		// Normal content or shortcode - Add the HTML for the attachments at the end of the current content string
		$content .= '<div id="simplyattached';
		if ($simplyattached_style == 'style2') $content .= '-s2';
		else if ($simplyattached_style == 'style3') $content .= '-s3';
		else if ($simplyattached_style == 'style4') $content .= '-s4';
		else if ($simplyattached_style == 'style5') $content .= '-s5';
		$content .= '">
		<h4>'.$title.'</h4><a name="attachments"></a>';
		
		// Start of the attachments ordered list
		$i = 0;
		$content .= '<ol>';
		foreach($attachments as $attachment)
		{

			// Alternate the formatting
			if(($i % 2) == 0)
			{
				$content .= '<li';
				if($simplyattached_usecolor_check == "on" || $simplyattached_usecolor_check == "true" || $simplyattached_usecolor_check == "checked")
				{
					$content .=' style="background-color:'.$simplyattached_rowcolor1.';"';
				}
				$content .= '><p>';

			} else {
				$content .='<li';
				if($simplyattached_usecolor_check == "on" || $simplyattached_usecolor_check == "true" || $simplyattached_usecolor_check == "checked")
				{
					$content .= ' style="background-color:'.$simplyattached_rowcolor2.';"';
				}
				$content .= '><p>';
			}
			$i++;
			
				  $content .='<a href="'.$attachment['location'].'">'.$attachment['title'].'</a>';
			  
			if($simplyattached_showfilesize_check == "on" || $simplyattached_showfilesize_check == "true" || $simplyattached_showfilesize_check == "checked")
			{
				if($simplyattached_boldfilesize_check == "on" || $simplyattached_boldfilesize_check == "true" || $simplyattached_boldfilesize_check == "checked")
				{
					$content .= ' <b>('.$attachment['filesizeformatted'].')</b>';
				} else {
					$content .= ' ('.$attachment['filesizeformatted'].')';
				}
			}

		  if($simplyattached_showfilecaption_check == "on" || $simplyattached_showfilecaption_check == "true" || $simplyattached_showfilecaption_check == "checked")
		  {
			if(trim($attachment['caption']) != '')
			{
				  $content .='<br><span class="attachmentcaption">'.$attachment['caption'].'</span>';
			}
		  }
			$content .='</p></li>';
		  }
		// End of the attachment list
		$content .='</ol>';
	 
		$content .='</div>';
		}

    }

	return $content;
}


// ========================
// = ADMIN PAGES DISPLAY
// ========================

/**
 * Creates the markup for the WordPress admin options page
 *
 * @return void
 * @author Jonathan Christopher
 */
function simplyattached_options()
{
	include 'simplyattached.options.php';
}

/**
 * Creates the entry for Attachments Options under Settings in the WordPress Admin
 *
 * @return void
 * @author Michael Kercsmar / Jonathan Christopher
 */
function simplyattached_menu()
{
	// Only show the menu item if the user can manage Wordpress Options
	add_options_page('Settings', 'Simply Attached', 'manage_options', __FILE__, 'simplyattached_options');
}

/**
 * Inserts HTML for meta box, including all existing attachments
 *
 * @return void
 * @author Michael Kercsmar
 */
function simplyattached_add()
{?>
	
	<div id="simplyattached-inner">
		
		<?php
			$media_upload_iframe_src = "media-upload.php?type=image&TB_iframe=1";
			$image_upload_iframe_src = apply_filters( 'image_upload_iframe_src', "$media_upload_iframe_src" );
		?>
		
		<div id="simplyattached-actions">
			<span class="simplyattached-actions-button">
				<a id="simplyattached-thickbox" href="<?php echo $image_upload_iframe_src; ?>&attachments_thickbox=1" title="Attachments" class="button button-highlighted">
					Attach from Media
				</a>
			</span>
			<span class="simplyattached-actions-button">
				<div id="SA-dropbox-button"></div>
			</span>
			<br>
			<span class="simplyattached-actions-label">
				Add attachments with the buttons above. To reorder, click on the grey square and drag and drop the items in the list below.
			</span>
		</div>
		
		<div id="simplyattached-list">
			<input type="hidden" name="simplyattached_nonce" id="simplyattached_nonce" value="<?php echo wp_create_nonce( plugin_basename(__FILE__) ); ?>" />
			<ul id="simplyattached-attachment-list">
				<?php
					if( !empty($_GET['post']) )
					{
						// get all attachments
						$existing_attachments = simplyattached_get_attachments( intval( $_GET['post'] ) );
						
						if( is_array($existing_attachments) && !empty($existing_attachments) )
						{
							$attachment_index = 0;
							foreach ($existing_attachments as $attachment) : $attachment_index++; ?>
								<li class="simplyattached-file">
									<h2>
										<span class="attachment-handle-icon"><img src="<?php echo WP_PLUGIN_URL; ?>/simply-attached/images/handle.gif" alt="Drag" /></span>
										<span class="attachment-name"><?php echo $attachment['name']; ?>
											<?php if (isset($attachment['src']) && $attachment['src'] == 'db') { ?>
												<img class="attachment-name-logo" src="<?php echo WP_PLUGIN_URL; ?>/simply-attached/images/dropbox-logos_dropbox-glyph-blue.png" />
											<?php } ?>
										</span>
										<span class="attachment-delete"><a href="#"><?php _e("Delete", "simplyattached")?></a></span>
									</h2>
									<div class="simplyattached-fields">
										<div class="textfield" id="field_attachment_title_<?php echo $attachment_index ; ?>">
											<label for="simplyattached_title_<?php echo $attachment_index; ?>"><?php _e("Title", "simplyattached")?></label>
											<input type="text" id="simplyattached_title_<?php echo $attachment_index; ?>" name="simplyattached_title_<?php echo $attachment_index; ?>" value="<?php echo $attachment['title']; ?>" size="20" />
										</div>
										<div class="textfield" id="field_attachment_caption_<?php echo $attachment_index; ?>">
											<label for="simplyattached_caption_<?php echo $attachment_index; ?>"><?php _e("Caption", "simplyattached")?></label>
											<input type="text" id="simplyattached_caption_<?php echo $attachment_index; ?>" name="simplyattached_caption_<?php echo $attachment_index; ?>" value="<?php echo $attachment['caption']; ?>" size="20" />
										</div>
									</div>
									<div class="simplyattached-data">
										<input type="hidden" name="simplyattached_id_<?php echo $attachment_index; ?>" id="simplyattached_id_<?php echo $attachment_index; ?>" value="<?php echo $attachment['id']; ?>" />
										<input type="hidden" class="simplyattached_order" name="simplyattached_order_<?php echo $attachment_index; ?>" id="simplyattached_order_<?php echo $attachment_index; ?>" value="<?php echo $attachment['order']; ?>" />
										<?php if (isset($attachment['src']) && $attachment['src'] == 'db') { ?>
											<input type="hidden" name="simplyattached_name_<?php echo $attachment_index; ?>" id="simplyattached_name_<?php echo $attachment_index; ?>" value="<?php echo $attachment['name']; ?>" />
											<input type="hidden" name="simplyattached_link_<?php echo $attachment_index; ?>" id="simplyattached_link_<?php echo $attachment_index; ?>" value="<?php echo $attachment['location']; ?>" />
											<input type="hidden" name="simplyattached_thumb_<?php echo $attachment_index; ?>" id="simplyattached_thumb_<?php echo $attachment_index; ?>" value="<?php echo $attachment['thumb']; ?>" />
											<input type="hidden" name="simplyattached_size_<?php echo $attachment_index; ?>" id="simplyattached_size_<?php echo $attachment_index; ?>" value="<?php echo $attachment['filesize']; ?>" />
											<input type="hidden" name="simplyattached_src_<?php echo $attachment_index; ?>" id="simplyattached_src_<?php echo $attachment_index; ?>" value="<?php echo $attachment['src']; ?>" />
										<?php } ?>
									</div>
									<div class="attachment-thumbnail">
										<span class="attachment-thumbnail">
											<?php if (isset($attachment['src']) && $attachment['src'] == 'db') {
												echo '<img src="'. $attachment['thumb'] . '"></img>';
											} else  {
												echo wp_get_attachment_image( $attachment['id'], array(80, 60), 1 );
											} ?>
										</span>
									</div>
								</li>
							<?php endforeach;
						}
					}
				?>
			</ul>
		</div>
		
	</div>
	
<?php }



/**
 * In the admin screens - Creates meta box on all Posts and Pages
 *
 * @return void
 * @author Jonathan Christopher
 */

function simplyattached_meta_box()
{
	// for posts
	add_meta_box( 'simplyattached_list', __( 'Simply Attached', 'simplyattached_textdomain' ), 'simplyattached_add', 'post', 'normal' );
	
	// for pages
	add_meta_box( 'simplyattached_list', __( 'Simply Attached', 'simplyattached_textdomain' ), 'simplyattached_add', 'page', 'normal' );
	
	// for custom post types
	if( function_exists( 'get_post_types' ) )
	{
		$args = array(
			'public'   => true,
			'_builtin' => false
			); 
		$output = 'objects';
		$operator = 'and';
		$post_types = get_post_types( $args, $output, $operator );
		foreach($post_types as $post_type)
		{
			if (get_option('simplyattached_cpt_' . $post_type->name)=='true')
			{
				add_meta_box( 'simplyattached_list', __( 'Simply Attched', 'simplyattached_textdomain' ), 'simplyattached_add', $post_type->name, 'normal' );
			}
		}
	}
}

/**
 * Fired when Post or Page is saved. Serializes all attachment data and saves to post_meta
 *
 * @param int $post_id The ID of the current post
 * @return void
 * @author Jonathan Christopher
 * @author JR Tashjian
 */
function simplyattached_save($post_id)
{
	// verify this came from the our screen and with proper authorization,
	// because save_post can be triggered at other times
	if ( !wp_verify_nonce( $_POST['simplyattached_nonce'], plugin_basename(__FILE__) )) {
		return $post_id;
	}

	// verify if this is an auto save routine. If it is our form has not been submitted, so we dont want
	// to do anything
	if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) 
		return $post_id;

	// Check permissions
	if ( 'page' == $_POST['post_type'] ) {
		if ( !current_user_can( 'edit_page', $post_id ) )
			return $post_id;
	} else {
		if ( !current_user_can( 'edit_post', $post_id ) )
			return $post_id;
	}

	// OK, we're authenticated: we need to find and save the data
	
	// delete all current attachments meta
	// moved outside conditional, else we can never delete all attachments
	delete_post_meta($post_id, '_simplyattached');
	
	// Since we're allowing Attachments to be sortable, we can't simply increment a counter
	// we need to keep track of the IDs we're given
	$attachment_ids = array();
	
	// We'll build our array of attachments
	foreach($_POST as $key => $data) {
		
		// Arbitrarily using the id number. This is the ID# in the span. NOT the ID number of the attachment
		// It is used as a simple counter
		if( substr($key, 0, 18) == 'simplyattached_id_' )
		{
			array_push( $attachment_ids, substr( $key, 18, strlen( $key ) ) );
		}
		
	}
		
	// If we have attachments, there's work to do
	if( !empty( $attachment_ids ) )
	{
		
		foreach ( $attachment_ids as $i )
		{
			if( !empty( $_POST['simplyattached_order_' . $i] ) )
			{
				$attachment_details = array(
						'id' 				=> $_POST['simplyattached_id_' . $i],
						'title' 			=> str_replace( '"', '&quot;', $_POST['simplyattached_title_' . $i] ),
						'caption' 			=> str_replace( '"', '&quot;', $_POST['simplyattached_caption_' . $i] ),
						'order' 			=> $_POST['simplyattached_order_' . $i],
						'src'				=> isset($_POST['simplyattached_src_' . $i]) ? $_POST['simplyattached_src_' . $i] : 'Media'
					);
					
				if (isset($_POST['simplyattached_src_' . $i]) && $_POST['simplyattached_src_' . $i] == 'db') {
					$attachment_details += array(
						'name'				=> str_replace( '"', '&quot;', $_POST['simplyattached_name_' . $i] ),
						'size'				=> str_replace( '"', '&quot;', $_POST['simplyattached_size_' . $i] ),
						'thumb'				=> $_POST['simplyattached_thumb_' . $i],
						'location'			=> $_POST['simplyattached_link_' . $i]
					);
				}
				
				// serialize data and encode
				$attachment_serialized = base64_encode( serialize( $attachment_details ) );
				
				// add individual attachment
				add_post_meta( $post_id, '_simplyattached', $attachment_serialized );
			}
		}
		
	}
	
}

/**
 * Retrieves all Attachments for provided Post or Page
 *
 * @param int $post_id (optional) ID of target Post or Page, otherwise pulls from global $post
 * @param boolean $filter_results (optional) Indicates if the attachment list should include or exclude integrations. On the post view page they should be filtered. On the post edit page we want to show everything always
 * @return array $post_attachments
 * @author Jonathan Christopher
 * @author JR Tashjian
 */
function simplyattached_get_attachments( $post_id=null, $filter_results = false )
{
	global $post;
	
	if( $post_id==null )
	{
		$post_id = $post->ID;
	}
	
	// Initialize the variable
	$post_attachments = array();
		
	// get all attachments
	$existing_attachments = get_post_meta( $post_id, '_simplyattached', false );

	if( is_array( $existing_attachments ) && count( $existing_attachments ) > 0 )
	{
		// Get the Dropbox setting and adjust for the different way a checkbox is reported
		if ($filter_results) {
			$simplyattached_use_db = get_option('simplyattached_use_db', false);
			$simplyattached_use_db = ($simplyattached_use_db == "on" || $simplyattached_use_db == "true" || $simplyattached_use_db == "checked");
		} else {
			// if $filter_results is false we need to include the Dropbox results
			$simplyattached_use_db = true;
		}
		
		foreach ($existing_attachments as $attachment)
		{
			// decode and unserialize the data
			$data = unserialize( base64_decode( $attachment ) );
			
			// The data depends on the source
			if (!isset($data['src']) || $data['src'] == 'Media') {
				array_push( $post_attachments, array(
					'id' 			=> stripslashes( $data['id'] ),
					'name' 			=> stripslashes( get_the_title( $data['id'] ) ),
					//'mime' 			=> stripslashes( get_post_mime_type( $data['id'] ) ),
					'title' 		=> stripslashes( $data['title'] ),
					'caption' 		=> stripslashes( $data['caption'] ),
					'location' 		=> stripslashes( wp_get_attachment_url( $data['id'] ) ),
					'order' 		=> stripslashes( $data['order'] ),
					//'link'			=> stripslashes( wp_get_attachment_link( $data['id'] ) ),
					//'path'			=> get_attached_file($data['id']),
					'filesize'		=> filesize(get_attached_file($data['id'])),
					'filesizeformatted'	=> simplyattached_format_bytes(filesize(get_attached_file($data['id']))) ));
			} else if ($simplyattached_use_db && isset($data['src']) && $data['src'] == 'db') {
				array_push( $post_attachments, array(
					'id' 			=> stripslashes( $data['id'] ),
					'src'			=> 'db',
					'name' 			=> stripslashes( $data['name'] ),
					'title' 		=> stripslashes( $data['title'] ),
					'caption' 		=> stripslashes( $data['caption'] ),
					'location' 		=> stripslashes( $data['location'] ),
					'thumb'			=> stripslashes( $data['thumb'] ),
					'order' 		=> stripslashes( $data['order'] ),
					'filesize'		=> stripslashes( $data['size'] ),
					'filesizeformatted'	=> simplyattached_format_bytes( $data['size'] ) ));
			}
		}
		
		// sort attachments
		if( count( $post_attachments ) > 1 )
		{
			usort( $post_attachments, "simplyattached_cmp" );
		}
	}
	
	return $post_attachments;
}



// ============================
// = INITIALIZATION FUNCTIONS =
// ============================

/**
 * The correct way to load scripts and stylesheets.
 *
 * @return void
 * @author Michael Kercsmar
 * @link http://www.paulund.co.uk/add-stylesheets-to-wordpress-correctly
 */
function safely_add_scripts_stylesheets()
{
	wp_enqueue_style( 'simplyattached', WP_PLUGIN_URL . '/simply-attached/css/simplyattached.css' );
	// Only need this script if the active style is #4 or #5. These scripts use a very little jQuery
	if (get_option('simplyattached_style', 'default') == 'style4' OR get_option('simplyattached_style', 'default') == 'style5') {
		wp_enqueue_script( 'simplyattacheduser', WP_PLUGIN_URL . '/simply-attached/js/simplyattacheduser.js', array( 'jquery' ), false, false );
	}
}

/**
 * The correct way to load scripts and stylesheets. Admin pages need everything.
 *
 * @return void
 * @author Michael Kercsmar
 * @link http://www.paulund.co.uk/add-stylesheets-to-wordpress-correctly
 */
function safely_add_admin_scripts_stylesheets()
{
	global $pagenow;

	wp_enqueue_style( 'simplyattached', WP_PLUGIN_URL . '/simply-attached/css/simplyattached.css' );
	wp_enqueue_style( 'simplyattachedadmin', WP_PLUGIN_URL . '/simply-attached/css/simplyattached-admin.css' );
	wp_enqueue_style( 'farbtastic', WP_PLUGIN_URL . '/simply-attached/css/farbtastic.css' );
	wp_enqueue_script( 'simplyattached', WP_PLUGIN_URL . '/simply-attached/js/simplyattached.js', array( 'jquery','thickbox' ), false, false );
	wp_enqueue_script( 'farbtastic', WP_PLUGIN_URL . '/simply-attached/js/farbtastic.js',array( 'jquery', 'jquery-ui-core' ));
	wp_enqueue_script( 'dragsort', WP_PLUGIN_URL . '/simply-attached/js/jquery.dragsort-0.5.1.min.js',array( 'jquery', 'jquery-ui-core' ));
	wp_enqueue_script( 'dropbox.js','https://www.dropbox.com/static/api/2/dropins.js',array( 'jquery'));

	// This will pass the needed localized javascript variables to the jQuery routines
	$simplyattached_custom = array( 'simplyattached_base' => WP_PLUGIN_URL . '/simply-attached',
									'simplyattached_db_key' => (get_option('simplyattached_use_db', false) ? get_option('simplyattached_db_key', '') : '' ),
									'simplyattached_upload' => ('media-upload.php' == $pagenow || 'async-upload.php' == $pagenow),
									'simplyattached_is_attachments_context' => (( 'media-upload.php' == $pagenow || 'async-upload.php' == $pagenow ) && is_attachments_context()) );
	wp_localize_script( 'simplyattached', 'simplyattached_custom', $simplyattached_custom );
}

/**
 * This is the main admin screen initialization function, it will invoke the necessary meta_box
 *
 * @return void
 * @author Jonathan Christopher
 */

function simplyattached_init()
{
	global $pagenow;
	
	if ( 'media-upload.php' == $pagenow || 'async-upload.php' == $pagenow )
	{
		add_filter( 'gettext', 'hijack_thickbox_text', 1, 3 );
	}

	if( function_exists( 'load_plugin_textdomain' ) )
	{
		if( !defined('WP_PLUGIN_DIR') )
		{
			load_plugin_textdomain( 'simplyattached', str_replace( ABSPATH, '', dirname( __FILE__ ) ) );
		}
		else
		{
			load_plugin_textdomain( 'simplyattached', false, dirname( plugin_basename( __FILE__ ) ) );
		}
	}

	simplyattached_meta_box();
}

// ====================
// = COMMON FUNCTIONS =
// ====================


/**
 * Used to fix a WordPress bug
 *
 */
if( !function_exists( 'fix_async_upload_image' ) )
{
	function fix_async_upload_image() {
		if( isset( $_REQUEST['simplyattached_id'] ) )
		{
			$GLOBALS['post'] = get_post( $_REQUEST['simplyattached_id'] );
		}
	}
}

/**
 * Figure out the context of the caller
 *
 */
function is_attachments_context()
{
	global $pagenow;
	
	// if post_id is set, it's the editor upload...
	if ( ( 'media-upload.php' == $pagenow || 'async-upload.php' == $pagenow ) && empty( $_REQUEST['post_id'] ) )
	{
		return true;
	}
	return false;
}

/**
 * Correct the text on the upload media box when uploading attachments
 *
 */
function hijack_thickbox_text($translated_text, $source_text, $domain)
{
	if ( is_attachments_context() )
	{
		if ('Insert into Post' == $source_text) {
			return __('Attach', 'simplyattached' );
		}
	}
	return $translated_text;
}

/**
 * Compares two array values with the same key "order"
 *
 * @param string $a First value
 * @param string $b Second value
 * @return int
 * @author Jonathan Christopher
 */
function simplyattached_cmp($a, $b)
{
	$a = intval( $a['order'] );
	$b = intval( $b['order'] );
	
	if( $a < $b )
	{
		return -1;
	}
	else if( $a > $b )
	{
		return 1;
	}
	else
	{
		return 0;
	}
}

/**
 * Make the file size easy to read
 *
 * @param int $size The file size
 * @return string The rounded size with easy to read units
 * @author Michael Kercsmar / Jonathan Christopher
 */
function simplyattached_format_bytes($size) {
    $units = array(' B', ' KB', ' MB', ' GB', ' TB');
    for ($i = 0; $size >= 1024 && $i < 4; $i++) $size /= 1024;
    return round($size, 1).$units[$i];
}
