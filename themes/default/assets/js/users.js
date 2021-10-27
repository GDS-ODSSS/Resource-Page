(function($) { 
	"use strict";
    jQuery("#button-update-profile-submit").on('click', function(){
        var thisButton = $(this);
        thisButton.html('<i class="fa fa-circle-notch fa-spin fa-fw"></i> Update profile');
        thisButton.prop('disabled',true);
        ajaxRequests.push = $.ajaxQueue({
			type	: 'post',
			url 	: ajax_url,
			data	: $('#form-update-profile').serialize(),
			success	: function(data) {
                var result = $.parseJSON(data);
				if (result['status'] === 'error'){
				    thisButton.html('Update profile');
					thisButton.prop('disabled',false);
					create_thebox_modal();
					$('.thebox-window').html(result['html']);
				} else if (result['status'] === 'success'){
					setTimeout(function(){window.location = result['links'];},200);
				} else {
				    thisButton.html('Update profile');
					thisButton.prop('disabled',false);
				}
			}
		});
    });
    jQuery("#button-update-password-submit").on('click', function(){
        var thisButton = $(this);
        thisButton.html('<i class="fa fa-circle-notch fa-spin fa-fw"></i> Update password');
        thisButton.prop('disabled',true);
        ajaxRequests.push = $.ajaxQueue({
			type	: 'post',
			url 	: ajax_url,
			data	: $('#form-update-password').serialize(),
			success	: function(data) {
                var result = $.parseJSON(data);
				if (result['status'] === 'error'){
				    thisButton.html('Update password');
					thisButton.prop('disabled',false);
					create_thebox_modal();
					$('.thebox-window').html(result['html']);
				} else if (result['status'] === 'success'){
					setTimeout(function(){window.location = result['links'];},200);
				} else {
				    thisButton.html('Update password');
					thisButton.prop('disabled',false);
				}
			}
		});
    });
})(jQuery);