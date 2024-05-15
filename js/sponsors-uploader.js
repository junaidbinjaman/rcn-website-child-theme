jQuery(document).ready( function($) {
    jQuery('body').on('click', '#sponsor_uploader .ik_sponsor_media_manager', function(e){
        e.preventDefault();
		var buttonmedia = jQuery(this);
        var image_frame;
        if(image_frame){
            image_frame.open();
        }
        // Define image_frame as wp.media object
        image_frame = wp.media({
            title: 'Media',
            multiple : false,
            library : {
                type : 'image',
            }
       });

       image_frame.on('close',function() {
            // On close, get selections and save to the hidden input
            // plus other AJAX stuff to refresh the image preview
            var selection =  image_frame.state().get('selection');
            var gallery_ids = new Array();
            var my_index = 0;
            selection.each(function(attachment) {
                gallery_ids[my_index] = attachment['id'];
                my_index++;
            });
            
            var ids = gallery_ids.join(",");
            buttonmedia.parent().find('.ik_sponsor_image_id').val(ids);
            Refresh_Sponsor_Image(ids,buttonmedia);
        });

        image_frame.on('open',function() {
            // On open, get the id from the hidden input
            // and select the appropiate images in the media manager
            var selection =  image_frame.state().get('selection');
            var ids = buttonmedia.parent().find('.ik_sponsor_image_id').val().split(',');
            ids.forEach(function(id) {
                var attachment = wp.media.attachment(id);
                attachment.fetch();
                selection.add( attachment ? [ attachment ] : [] );
            });
        });
        
        image_frame.open();
     });

});

//Function to delete image
jQuery( "body" ).on( "click", "#sponsor_uploader .ik_sponsor_media_remove", function() {
	if (jQuery("#sponsor_uploader label").length < 2){
		jQuery(this).parent().find('.ik_sponsor_preview_image').removeAttr('src');
		jQuery(this).parent().find('.ik_sponsor_preview_image').addClass('hidden');
		jQuery(this).parent().find('.ik_sponsor_image_id').val('');
		jQuery(this).addClass('hidden');
	} else {
	    jQuery(this).parent().remove();
	}	
});

//function to add more sponsor images
jQuery("body").on("click", "#ik_add_more_sponsors", function(){
	
	jQuery("#sponsor_uploader").append('<label for="sponsor_image"><img class="ik_sponsor_preview_image hidden" src="" /><input type="hidden" name="ik_sponsor_image_id[]" class="ik_sponsor_image_id" value="0" /><input type="button" class="button-primary ik_sponsor_media_manager" value="Upload Sponsor Image" /><input type="button" class="button-primary ik_sponsor_media_remove hidden" value="Delete Sponsor Image" /></label>');
	
	if (jQuery("#ik_remove_sponsors").prop("disabled") == true){
		jQuery("#ik_remove_sponsors").removeAttr("disabled");
	}
	
	return false;
});

//function to delete sponsor images
jQuery("body").on("click", "#ik_remove_sponsors", function(){
	jQuery("#sponsor_uploader label:last-child").remove();
	if (jQuery("#sponsor_uploader label").length < 2){
		jQuery("#ik_remove_sponsors").prop("disabled", true);
		jQuery("#sponsor_uploader label:last-child .ik_sponsor_media_manager").text('Upload Sponsor Image');

	}
	
	return false;
});

// Ajax request to refresh the image preview
function Refresh_Sponsor_Image(the_id, buttonmedia){
    var data = {
        action: 'ik_rcn_ajax_img_upload',
        id: the_id
    };
	
    jQuery.get(ajaxurl, data, function(response) {
        if(response.success === true) {
            buttonmedia.parent().find('.ik_sponsor_preview_image').attr('src', response.data );
            buttonmedia.parent().find('.ik_sponsor_preview_image').removeClass('hidden');
            buttonmedia.parent().find('.ik_sponsor_media_remove').removeClass('hidden');
            buttonmedia.parent().find('.ik_sponsor_media_manager').text('Change Sponsor Image');
        }
    });
}