(function($) { 
	"use strict";
    jQuery("#button-signup-submit").on('click', function(){
        var thisButton = $(this),
            Buttontxt = thisButton.html();
        thisButton.html('<i class="fa fa-circle-notch fa-spin fa-fw"></i> '+Buttontxt);
        thisButton.prop('disabled',true);
        ajaxRequests.push = $.ajaxQueue({
			type	: 'post',
			url 	: ajax_url,
			data	: $('#form-signup').serialize(),
			success	: function(data) {
                var result = $.parseJSON(data);
				if (result['status'] === 'error'){
				    thisButton.html(Buttontxt);
					thisButton.prop('disabled',false);
					create_thebox_modal();
					$('.thebox-window').html(result['html']);
				} else if (result['status'] === 'success'){
					setTimeout(function(){window.location = result['links'];},200);
				} else {
				    thisButton.html(Buttontxt);
					thisButton.prop('disabled',false);
				}
			}
		});
    });
})(jQuery);