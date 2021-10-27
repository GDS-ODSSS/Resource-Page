jQuery(document).ready(function() {
    //feature-details
    jQuery('.feature-details').tipsy({
        fade: true, 
        gravity: 's'
    });
    
    var cookiename = jQuery('.nav-tabs-cookie').attr('data-cookie');
    if(!cookiename)
    {
        cookiename = 'cookieptions';
    }
    /*-------------------------------------------------------------------------------------------------*/
    jQuery(".megapanel-tabs a").click(function() {
        var tabs = jQuery(this).attr("data-tab");
        jQuery(".megapanel-tabs a").removeClass("active");
        jQuery(".megapanel-tab-content").removeClass("active");
        jQuery(this).addClass("active");
        jQuery(tabs).addClass("active");
        return false;
    });
    /*-------------------------------------------------------------------------------------------------*/
    jQuery(".nav-tabs-cookie a").each(function() {
        var id = jQuery(this).attr('data-tab');
        if (jQuery.cookie(cookiename) == id) {
            jQuery(this).addClass('active');
            jQuery(id).addClass('active');
        } else {
            jQuery(this).removeClass('active');
            jQuery(id).removeClass('active');
        }
    });
    /*-------------------------------------------------------------------------------------------------*/
    jQuery(".nav-tabs-cookie a").on('click', function() {
        var id = jQuery(this).attr('data-tab');
        jQuery.cookie(cookiename, id);
    });
    /*-------------------------------------------------------------------------------------------------*/
    jQuery(document).on('click', '.megapanel-buttons-options button' , function () {
        var options = jQuery(this).parent(),
            input = options.find('input');
        options.find('button').removeClass('active');
        jQuery(this).addClass('active');
        input.val(jQuery(this).attr("data-value"));
    });
    /*-------------------------------------------------------------------------------------------------*/
    jQuery(document).on('click', '.megapanel-buttons-checkbox button' , function () {
        jQuery(this).toggleClass('active');
        var cid = jQuery(this).attr("data-id");
        if(jQuery(cid).is(':checked'))
        {
            jQuery(cid).removeAttr('checked');
        }
        else
        {
             jQuery(cid).attr('checked', 'checked');
        }
    });
    /*-------------------------------------------------------------------------------------------------*/
    jQuery('.megapanel-list-sortable').sortable({
        opacity: 0.8,
        revert: true,
        cursor: 'move',
        handle: '.hndle',
        placeholder: {
            element: function(currentItem) {
                return jQuery("<li style='border: 1px dashed #ccc;height: 36px;background: #fffdea;'>&nbsp;</li>")[0];
            },
            update: function(container, p) {
                return;
            }
        }
    });
    /*-------------------------------------------------------------------------------------------------*/
    jQuery(document).on('click', '.megapanel-section-help-title' , function () {
        var gettoggle   = jQuery(this);
        var getinput    = jQuery(this).find('.megapanel_toggle');
        jQuery(this).parent().find('.megapanel-controls-help-container').toggle();
    });
    /*-------------------------------------------------------------------------------------------------*/
    jQuery(document).on('click', '.megapanel-toggle' , function () {
        var gettoggle   = jQuery(this);
        var getinput    = jQuery(this).parent().find('.megapanel_toggle');
        jQuery(this).parent().parent().parent().find('.megapanel_inner_box').toggle('fast',function(){
            var getvisible = jQuery(this).is(':visible');
            if (getvisible)
            {
                 gettoggle.addClass('dashicons-arrow-up'); 
                 gettoggle.removeClass('dashicons-arrow-down');
                 getinput.val('1');
            }
            else
            {
                 gettoggle.addClass('dashicons-arrow-down'); 
                 gettoggle.removeClass('dashicons-arrow-up');
                 getinput.val('0');
            }
        });
    });
    /*-------------------------------------------------------------------------------------------------*/
    jQuery(document).on('click', '.megapanel-options-head-items h3 .collapse-button' , function () {
        var gettoggle   = jQuery(this);
        var getinput    = jQuery(this).parent().find('.megapanel_toggle');
        jQuery(this).parent().parent().find('.megapanel-toggle-content').toggle('fast',function(){
            var getvisible = jQuery(this).is(':visible');
            if (getvisible)
            {
                 gettoggle.find('i').addClass('fa-minus'); 
                 gettoggle.find('i').removeClass('fa-plus');
                 getinput.val('1');
            }
            else
            {
                 gettoggle.find('i').addClass('fa-plus'); 
                 gettoggle.find('i').removeClass('fa-minus');
                 getinput.val('0');
            }
        });
    });
    /*-------------------------------------------------------------------------------------------------*/
    jQuery(document).on('keyup', '.megapanel_version' , function () {
        jQuery(this).parents('li').find('.megapanel-title-item').html(jQuery(this).val());
    });
    /*-------------------------------------------------------------------------------------------------*/
    jQuery(document).on('click', '.remove-megapanel-button' , function () {
        jQuery(this).parents('li').addClass('megapanel-removered').fadeOut(function() {
			jQuery(this).remove();
		});
    });
    /*-------------------------------------------------------------------------------------------------*/
    jQuery(document).on('click', '#megapanel_add_criteria_item' , function (event) {
		event.preventDefault(event);
        megapanel_itemscount++;
        var template = jQuery('#megapanel_tmpl_criteria_item'),data = {data: megapanel_itemscount, title: 'type version'};
		var compile = template.tmpl(data).html();
		jQuery('#megapanel-reviews-list').append(compile);
	});
    /*-------------------------------------------------------------------------------------------------*/
    jQuery(document).on('click', '.megapanel-icon-default' , function () {
        var $this   = jQuery(this),
            $parent = $this.closest('.megapanel-icon-select');
        var icon = jQuery(this).data('geticon');
        $parent.find('i').removeAttr('class').addClass(icon);
        $parent.find('input').val(icon).trigger('change');
        return false;
    });
    /*-------------------------------------------------------------------------------------------------*/
    jQuery(document).on('click', '.megapanel-icon-remove' , function () {
        var $this   = jQuery(this),
            $parent = $this.closest('.megapanel-icon-select');
        $parent.find('i').removeAttr('class');
        $parent.find('input').val('').trigger('change');
        return false;
    });
    /*-------------------------------------------------------------------------------------------------*/
    jQuery(document).on('click', '.megapanel-icon-add' , function () {
        var $this = jQuery(this),
            onload = true,
            $dialog = jQuery('#megapanel-icon-dialog'),
            $load = $dialog.find('.megapanel-dialog-load'),
            $select = $dialog.find('.megapanel-dialog-select'),
            $insert = $dialog.find('.megapanel-dialog-insert'),
            $search = $dialog.find('.megapanel-icon-search');
        // set parent
        $parent = $this.closest('.megapanel-icon-select');
        // open dialog
        $dialog.dialog({
            width: 850,
            height: 700,
            modal: true,
            resizable: false,
            closeOnEscape: true,
            position: {my: 'center', at: 'center', of: window},
            open: function () {
                // fix scrolling
                jQuery('body').addClass('megapanel-icon-scrolling');
                // fix button for VC
                jQuery('.ui-dialog-titlebar-close').addClass('ui-button').html('<i class="si-cross2"></i>');
                // set viewpoint
                jQuery(window).on('resize', function () {
                    var height = jQuery(window).height(), load_height = Math.floor(height - 237), set_height = Math.floor(height - 125);
                    $dialog.dialog('option', 'height', set_height).parent().css('max-height', set_height);
                    $dialog.css('overflow', 'auto');
                    $load.css('height', load_height);
                }).resize();
            },
            close: function () {
                jQuery('body').removeClass('megapanel-icon-scrolling');
            }
        });
        // load icons
        if (onload) {            
            jQuery.ajax({
                type: 'GET',
                url: admin_ajax_url,
                data: {action: 'megapanel_geticons'},
                success: function (content) {
                    $load.html(content);
                    onload = false;
                    $load.on('click', 'a', function () {
                        var icon = jQuery(this).data('geticon');
                        $parent.find('i').removeAttr('class').addClass(icon);
                        $parent.find('input').val(icon).trigger('change');
                        $parent.find('.megapanel-icon-preview').removeClass('hidden');
                        $parent.find('.megapanel-icon-remove').removeClass('hidden');
                        $dialog.dialog('close');
                    });
                    $search.keyup(function () {
                        var value = jQuery(this).val(),
                            $icons = $load.find('a');
                        $icons.each(function () {
                            var $ico = jQuery(this);
                            if ($ico.data('geticon').search(new RegExp(value, 'i')) < 0) {
                                $ico.hide();
                            } else {
                                $ico.show();
                            }
                        });
                    });
                    //$load.find('.megapanel-icon-tooltip').tooltip({html:true, placement:'top', container:'body'});
                }
            });
        }
        //return false;
    });
    
});