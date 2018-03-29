jQuery( document ).ready(function() {
 jQuery( document ).change(function() {
  jQuery("select[name='add_method_id'] option").each(function() { 
			if(jQuery(this).val().indexOf('allparcels') > -1){
		 jQuery(this).text(jQuery(this).text()+' (Allparcels.com)'); 
		} 
	}); 
});
jQuery('.subsubsub li a').each(function(){
    var $this = jQuery(this);
    if ($this.attr('href').indexOf('allparcels') > -1) {
        $this.parent().remove();
    }
});
});

