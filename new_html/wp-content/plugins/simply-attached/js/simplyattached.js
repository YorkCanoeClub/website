function str_replace (search, replace, subject, count) {
    // http://kevin.vanzonneveld.net
    // +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +   improved by: Gabriel Paderni
    // +   improved by: Philip Peterson
    // +   improved by: Simon Willison (http://simonwillison.net)
    // +    revised by: Jonas Raoni Soares Silva (http://www.jsfromhell.com)
    // +   bugfixed by: Anton Ongson
    // +      input by: Onno Marsman
    // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +    tweaked by: Onno Marsman
    // +      input by: Brett Zamir (http://brett-zamir.me)
    // +   bugfixed by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +   input by: Oleg Eremeev
    // +   improved by: Brett Zamir (http://brett-zamir.me)
    // +   bugfixed by: Oleg Eremeev
    // %          note 1: The count parameter must be passed as a string in order
    // %          note 1:  to find a global variable in which the result will be given
    // *     example 1: str_replace(' ', '.', 'Kevin van Zonneveld');
    // *     returns 1: 'Kevin.van.Zonneveld'
    // *     example 2: str_replace(['{name}', 'l'], ['hello', 'm'], '{name}, lars');
    // *     returns 2: 'hemmo, mars'

    var i = 0, j = 0, temp = '', repl = '', sl = 0, fl = 0,
            f = [].concat(search),
            r = [].concat(replace),
            s = subject,
            ra = r instanceof Array, sa = s instanceof Array;
    s = [].concat(s);
    if (count) {
        this.window[count] = 0;
    }

    for (i=0, sl=s.length; i < sl; i++) {
        if (s[i] === '') {
            continue;
        }
        for (j=0, fl=f.length; j < fl; j++) {
            temp = s[i]+'';
            repl = ra ? (r[j] !== undefined ? r[j] : '') : r[0];
            s[i] = (temp).split(f[j]).join(repl);
            if (count && s[i] !== temp) {
                this.window[count] += (temp.length-s[i].length)/f[j].length;}
        }
    }
    return sa ? s : s[0];
}

function attachments_handle_attach(title,caption,id,thumb){

	attachment_index = jQuery('li.simplyattached-file', top.document).length;
	new_attachments = '';

	attachment_name 		= title;
	attachment_caption 		= caption;
	attachment_id			= id;
	attachment_thumb 		= thumb;

	attachment_index++;
	new_attachments += '<li class="simplyattached-file">';
	new_attachments += '<h2><span class="simplyattached-handle-icon"><img src="';
	new_attachments += simplyattached_custom.simplyattached_base + '/images/handle.gif" alt="Drag" /></span></a>';
	new_attachments += '<span class="attachment-name">' + attachment_name + '</span>';
	new_attachments += '<span class="attachment-delete"><a href="#">Delete</a></span></h2>';
	new_attachments += '<div class="simplyattached-fields">';
	new_attachments += '<div class="textfield" id="field_attachment_title_' + attachment_index + '"><label for="simplyattached_title_' + attachment_index + '">Title</label><input type="text" id="simplyattached_title_' + attachment_index + '" name="simplyattached_title_' + attachment_index + '" value="' + attachment_name + '" size="20" /></div>';
	new_attachments += '<div class="textfield" id="field_attachment_caption_' + attachment_index + '"><label for="simplyattached_caption_' + attachment_index + '">Caption</label><input type="text" id="simplyattached_caption_' + attachment_index + '" name="simplyattached_caption_' + attachment_index + '" value="' + attachment_caption + '" size="20" /></div>';
	new_attachments += '</div>';
	new_attachments += '<div class="simplyattached-data">';
	new_attachments += '<input type="hidden" name="simplyattached_id_' + attachment_index + '" id="simplyattached_id_' + attachment_index + '" value="' + attachment_id + '" />';
	new_attachments += '<input type="hidden" name="simplyattached_src_' + attachment_index + '" id="simplyattached_src_' + attachment_index + '" value="Media" />';
	new_attachments += '<input type="hidden" class="simplyattached_order" name="simplyattached_order_' + attachment_index + '" id="simplyattached_order_' + attachment_index + '" value="' + attachment_index + '" />';
	new_attachments += '</div>';
	new_attachments += '<div class="attachment-thumbnail"><span class="simplyattached-thumbnail">';

	
	new_attachments += '<img src="' + attachment_thumb + '" alt="Thumbnail" />';
	new_attachments += '</span></div>';
	new_attachments += '</li>';

	jQuery('div#simplyattached-list ul', top.document).append(new_attachments);

	if(jQuery('#simplyattached-list li', top.document).length > 0) {

		// We've got some attachments
		jQuery('#simplyattached-list', top.document).show();

	}
}

function attachments_dropbox_attach(files){

	attachment_index = jQuery('li.simplyattached-file', top.document).length;
	new_attachments = '';

	for (index = 0; index < files.length; ++index) {
		attachment_name 		= files[index].name;
		attachment_caption 		= "Dropbox file";
		attachment_thumb 		= (typeof files[index].thumbnailLink != 'undefined') ? files[index].thumbnailLink : files[index].icon;
		attachment_size			= files[index].bytes;
		attachment_link			= files[index].link;

		attachment_index++;
		new_attachments += '<li class="simplyattached-file">';
		new_attachments += '<h2><span class="simplyattached-handle-icon">';
		new_attachments += '<img src="' + simplyattached_custom.simplyattached_base + '/images/handle.gif" alt="Drag" /></span>';
		new_attachments += '<span class="attachment-name">' + attachment_name;
		new_attachments += '<img class="attachment-name-logo" src="' + simplyattached_custom.simplyattached_base + '/images/dropbox-logos_dropbox-glyph-blue.png" /></span>';
		new_attachments += '<span class="attachment-delete"><a href="#">Delete</a></span></h2>';
		new_attachments += '<div class="simplyattached-fields">';
		new_attachments += '<div class="textfield" id="field_attachment_title_' + attachment_index + '"><label for="simplyattached_title_' + attachment_index + '">Title</label><input type="text" id="simplyattached_title_' + attachment_index + '" name="simplyattached_title_' + attachment_index + '" value="' + attachment_name + '" size="20" /></div>';
		new_attachments += '<div class="textfield" id="field_attachment_caption_' + attachment_index + '"><label for="simplyattached_caption_' + attachment_index + '">Caption</label><input type="text" id="simplyattached_caption_' + attachment_index + '" name="simplyattached_caption_' + attachment_index + '" value="' + attachment_caption + '" size="20" /></div>';
		new_attachments += '</div>';
		new_attachments += '<div class="simplyattached-data">';
		new_attachments += '<input type="hidden" name="simplyattached_id_' + attachment_index + '" id="simplyattached_id_' + attachment_index + '" value="" />';
		new_attachments += '<input type="hidden" class="simplyattached_order" name="simplyattached_order_' + attachment_index + '" id="simplyattached_order_' + attachment_index + '" value="' + attachment_index + '" />';
		new_attachments += '<input type="hidden" name="simplyattached_name_' + attachment_index + '" id="simplyattached_name_' + attachment_index + '" value="' + attachment_name + '" />';
		new_attachments += '<input type="hidden" name="simplyattached_link_' + attachment_index + '" id="simplyattached_link_' + attachment_index + '" value="' + attachment_link + '" />';
		new_attachments += '<input type="hidden" name="simplyattached_thumb_' + attachment_index + '" id="simplyattached_thumb_' + attachment_index + '" value="' + attachment_thumb + '" />';
		new_attachments += '<input type="hidden" name="simplyattached_size_' + attachment_index + '" id="simplyattached_size_' + attachment_index + '" value="' + attachment_size + '" />';
		new_attachments += '<input type="hidden" name="simplyattached_src_' + attachment_index + '" id="simplyattached_src_' + attachment_index + '" value="db" />';
		new_attachments += '</div>';
		new_attachments += '<div class="attachment-thumbnail"><span class="simplyattached-thumbnail">';

		
		new_attachments += '<img src="' + attachment_thumb + '" alt="Thumbnail" />';
		new_attachments += '</span></div>';
		new_attachments += '</li>';
	}

	jQuery('div#simplyattached-list ul', top.document).append(new_attachments);

	if(jQuery('#simplyattached-list li', top.document).length > 0) {

		// We've got some attachments
		jQuery('#simplyattached-list', top.document).show();

	}
}


  function farbcallback1(color)
  {
   jQuery('.simplyattachedcolor1').css('background-color', color);
   jQuery('#simplyattached_rowcolor1').css('background-color', color);
   jQuery('#simplyattached_rowcolor1').val(color);
  }
 
  function farbcallback2(color)
  {
   jQuery('.simplyattachedcolor2').css('background-color', color);
   jQuery('#simplyattached_rowcolor2').css('background-color', color);
   jQuery('#simplyattached_rowcolor2').val(color);
  // f.setColor(jQuery(this).val());
  }
 
	function SimplyattachedSaveSort() {
				jQuery('#simplyattached-list ul li').each(function(i, id) {
					jQuery(this).find('input.simplyattached_order').val(i+1);
				});
			};

jQuery(document).ready(function() {

				var dropbox_options = {
                    success: function (files){attachments_dropbox_attach(files);},
                    linkType: "preview",
                    multiselect: true,
                };

	if(simplyattached_custom.simplyattached_is_attachments_context)
	{
	    jQuery('body').addClass('simplyattached-media-upload');

		// we need to hijack the Attach button
		jQuery('td.savesend input').live('click',function(e){
			theparent = jQuery(this).parent().parent().parent();
			thetitle = theparent.find('tr.post_title td.field input').val();
			thecaption = theparent.find('tr.post_excerpt td.field textarea').val();
			theid = str_replace( 'imgedit-response-', '', theparent.find('td.imgedit-response').attr('id') );
			thethumb = theparent.parent().parent().find('img.pinkynail').attr('src');
			attachments_handle_attach(thetitle,thecaption,theid,thethumb);
			jQuery(this).after('<span class="simplyattached-attached-note"> Attached</span>').parent().find('span.attachments-attached-note').delay(1000).fadeOut();
			return false;
		});
		
	}

	if(simplyattached_custom.simplyattached_db_key != '') {
		// Attach the dropbox button to the SPAN
		Dropbox.appKey=simplyattached_custom.simplyattached_db_key;
		var button = Dropbox.createChooseButton(dropbox_options);
		jQuery("#SA-dropbox-button").append(button);
	}
	
	// we're going to handle the Thickbox
	jQuery("body").click(function(event) {
		if (jQuery(event.target).is('a#simplyattached-thickbox')) {
			tb_show("Attach a file", event.target.href, false);
			return false;
		}
	});
	
	// Modify thickbox link to fit window. Adapted from wp-admin\js\media-upload.dev.js.
	jQuery('a#simplyattached-thickbox').each( function() {
		var href = jQuery(this).attr('href'), width = jQuery(window).width(), H = jQuery(window).height(), W = ( 720 < width ) ? 720 : width;
		if ( ! href )
		{
		    return;
		}
		href = href.replace(/&width=[0-9]+/g, '');
		href = href.replace(/&height=[0-9]+/g, '');
		jQuery(this).attr( 'href', href + '&width=' + ( W - 80 ) + '&height=' + ( H - 85 ) );
	});
	
	// If there are no attachments, let's hide this thing...
	if(jQuery('div#simplyattached-list li').length == 0) {
		jQuery('#simplyattached-list').hide();
	}

	// Hook our delete links
	jQuery('span.attachment-delete a').live('click', function() {
		attachment_parent = jQuery(this).parent().parent().parent();
		attachment_parent.slideUp(function() {
			attachment_parent.remove();
			jQuery('#simplyattached-list ul li').each(function(i, id) {
				jQuery(this).find('input.attachment_order').val(i+1);
			});
			if(jQuery('div#simplyattached-list li').length == 0) {
				jQuery('#simplyattached-list').slideUp(function() {
					jQuery('#simplyattached-list').hide();
				});
			}
		});		
		return false;
	});

	// Set up the drag to sort call
	jQuery("#simplyattached-attachment-list").dragsort({ placeHolderTemplate: "<li class='placeHolder'><div><h2><br></h2><br><br></div></li>" ,dragEnd:SimplyattachedSaveSort});
	
	//admin screen stuff.
	//Only declare these if needed
    if(jQuery('#farbtasticpicker').length > 0)
    {
    var f = jQuery.farbtastic('#farbtasticpicker');
     var p = jQuery('#farbtasticpicker').css('opacity', 0.25);
     var selected;
     var nameselected;
    }

    jQuery('.colorwell')
      .each(function () { f.linkTo(this); jQuery(this).css('opacity', 0.75); })
      .focus(function() {
        if (selected) {
          jQuery(selected).css('opacity', 0.75).removeClass('colorwell-selected');
        }
        
        nameselected = jQuery(this).attr('name');
        if(nameselected == 'simplyattached_rowcolor1')
        {
         f.linkTo(farbcallback1);
        } else {
         f.linkTo(farbcallback2);
        }
        p.css('opacity', 1);
        jQuery(selected = this).css('opacity', 1).addClass('colorwell-selected');
	jQuery.farbtastic('#farbtasticpicker').setColor(jQuery(this).val());
      });
      
	jQuery("#usedropbox").click(function(event) {
	    if(jQuery(this).attr('checked'))
	    {
			jQuery("#dropboxkey").show(); //('display', 'inherit');
	    } else {
			jQuery("#dropboxkey").hide(); //('display', 'none');
	    }
	});
      
	jQuery("#usecolor").click(function(event) {
	    if(jQuery(this).attr('checked'))
	    {
		jQuery(".colorwell").slideDown(); //('display', 'inherit');
	    } else {
		jQuery(".colorwell").slideUp(); //('display', 'none');
	    }
	});

	jQuery("#showfilesize").click(function(event) {
	    if(jQuery(this).attr('checked'))
	    {
		jQuery(".attachmentsize").slideDown(); //('display', 'inherit');
	    } else {
		jQuery(".attachmentsize").slideUp(); //('display', 'none');
	    }
	});

	jQuery("#boldfilesize").click(function(event) {
	    if(jQuery(this).attr('checked'))
	    {
		jQuery(".attachmentsize").css('font-weight', 'bold');
	    } else {
		jQuery(".attachmentsize").css('font-weight', 'inherit');
	    }
	});

	jQuery("#showfilecaption").click(function(event) {
	    if(jQuery(this).attr('checked'))
	    {
		jQuery(".attachmentcaption").slideDown(); //('display', 'inherit');
	    } else {
		jQuery(".attachmentcaption").slideUp(); //('display', 'none');
	    }
	});
	
	//load admin settings
	if(jQuery("#onattachmentsexamplepage").val() == "true")
	{
	    farbcallback1(jQuery('#simplyattached_rowcolor1').val());
	    farbcallback2(jQuery('#simplyattached_rowcolor2').val());
	
	   jQuery('#simplyattached_rowcolor1').change(function() {
	     farbcallback1(jQuery(this).val());
	     jQuery.farbtastic('#farbtasticpicker').setColor(jQuery(this).val());
	     
	   });
	 
	   jQuery('#simplyattached_rowcolor2').change(function() {
	     farbcallback2(jQuery(this).val());
	     jQuery.farbtastic('#farbtasticpicker').setColor(jQuery(this).val());
	     
	   });
	   
	   if(jQuery("#usedropbox").attr('checked'))
	   {
		jQuery("#dropboxkey").show();
	   } else {
		jQuery("#dropboxkey").hide();
	   }
	   
	    if(jQuery("#usecolor").attr('checked'))
	    {
		jQuery(".colorwell").show(); //('display', 'inherit');
	    } else {
		jQuery(".colorwell").hide(); //('display', 'none');
	    }
	    
	    if(jQuery("#showfilecaption").attr('checked'))
	    {
			jQuery(".attachmentcaption").show(); //('display', 'inherit');
	    } else {
			jQuery(".attachmentcaption").hide(); //('display', 'none');
	    }
	    
	    if(jQuery("#boldfilesize").attr('checked'))
	    {
			jQuery(".attachmentsize").css('font-weight', 'bold');
	    } else {
			jQuery(".attachmentsize").css('font-weight', 'inherit');
	    }

	    if(jQuery("#showfilesize").attr('checked'))
	    {
			jQuery(".attachmentsize").show();//('display', 'inherit');
	    } else {
			jQuery(".attachmentsize").hide(); //('display', 'none');
	    }

	}


	
	// we also need to get a bit hacky with sortable...
	//setInterval('init_simplyattached_sortable()',500);

});