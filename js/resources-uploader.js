jQuery(document).ready( function($) {
   //Function to delete resource
	jQuery( "#resources_uploader" ).on( "click", ".delete_resource", function() {
		jQuery(this).parent().remove();
		return false;
	});
	//function to add more resources
	jQuery("#rosources_uploader_wrapper").on("click", "#add_resource", function(){
		
		jQuery("#resources_uploader").append('<div class="resource-row"><label for="resource_name"><input required type="text" name="resource_name[]" placeholder="Resource Name" value="" /></label><label for="resource_name"><input required type="text" name="resource_url[]" placeholder="Resource URL" value="" /></label><button class="button-primary delete_resource">Delete</button></div>');
		
		return false;
	});
});