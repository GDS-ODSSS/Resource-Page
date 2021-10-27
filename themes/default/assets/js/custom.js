var ajaxRequests = [];
function create_thebox_modal() {
    "use strict";
    if (!jQuery('.thebox-window').length) {
        jQuery('body *').blur();
        jQuery('<div class="thebox-modal"><div class="thebox-overlay"></div><div class="thebox-window"><div style="height:100px"></div></div></div>').appendTo('body');
    }
}
function close_thebox_modal() {
    "use strict";
    if ($('.thebox-window').length) {
        jQuery('.thebox-modal').fadeOut(200);
        jQuery('.thebox-modal').addClass('tm-closing');
        setTimeout(function() {
            jQuery('.thebox-modal').remove();
        }, 300);
    }
}
(function($) {
    "use strict";
    jQuery(window).load(function() {
        jQuery('body').addClass('loaded');
    });
    $('.highlight pre').each(function(i, block) {
        hljs.highlightBlock(block);
    });
    if (is_jpinning) {
        jQuery('.jpinning #header').jPinning();
    }
    jQuery('.owl-clients').owlCarousel({
        navigation: false,
        pagination: false,
        autoPlay: true,
        items: 2,
        loop: !1,
        dots: true,
        margin: 25,
        navText: ['<i class="fas fa-chevron-left"></i>', '<i class="fas fa-chevron-right"></i>'],
        responsive: {
            0: {
                items: 1
            },
            360: {
                items: 2
            },
            500: {
                items: 3
            },
            750: {
                items: 4
            },
            1000: {
                items: 5
            },
            1200: {
                items: 6
            }
        }
    });
    jQuery('.owl-testimonial').owlCarousel({
        navigation: false,
        pagination: false,
        autoPlay: true,
        items: 2,
        loop: !1,
        dots: true,
        margin: 25,
        responsive: {
            0: {
                items: 1
            },
            480: {
                items: 1
            },
            768: {
                items: 1
            },
            1200: {
                items: 2
            }
        }
    });
    jQuery(window).on('scroll', function() {
        if (jQuery(this).scrollTop() > 60) {
            jQuery('.sticky #header').addClass('sticky')
        } else {
            jQuery('.sticky #header').removeClass('sticky')
        }
        return false;
    });
    jQuery('a.open_close').on('click', function() {
        jQuery('#main-menu').toggleClass('show');
    });
    jQuery('a.show-submenu').on('click', function() {
        jQuery(this).next().toggleClass('show_normal');
    });
    jQuery(window).on('scroll', function() {
        if (jQuery(this).scrollTop() > 100) {
            jQuery(".go-up").css("right", "10px");
        } else {
            jQuery(".go-up").css("right", "-50px");
        }
    });
    jQuery(".go-up").on('click', function() {
        jQuery("html,body").animate({ scrollTop: 0 }, 500);
        return false;
    });
    jQuery('.sticky-sidebar').theiaStickySidebar({
        additionalMarginTop: 100,
        additionalMarginBottom: 25,
    });
    $('body').on('click', '.thebox-overlay, .thebox-window .close, .thebox-window .cancel', function(e) {
        e.preventDefault();
        close_thebox_modal();
        return false;
    });
    jQuery(".accordion .accordion-title").each(function() {
        jQuery(this).on('click', function() {
            if (jQuery(this).parent().parent().hasClass("toggle-accordion")) {
                jQuery(this).parent().find("li:first .accordion-title").addClass("active");
                jQuery(this).parent().find("li:first .accordion-title").next(".accordion-inner").addClass("active");
                jQuery(this).toggleClass("active");
                jQuery(this).next(".accordion-inner").slideToggle().toggleClass("active");
                jQuery(this).find("i").toggleClass("pe-7s-angle-down").toggleClass("pe-7s-angle-up");
            } else {
                if (jQuery(this).next().is(":hidden")) {
                    jQuery(this).parent().parent().find(".accordion-title").removeClass("active").next().slideUp(200);
                    jQuery(this).parent().parent().find(".accordion-title").next().removeClass("active").slideUp(200);
                    jQuery(this).toggleClass("active").next().slideDown(200);
                    jQuery(this).next(".accordion-inner").toggleClass("active");
                    jQuery(this).parent().parent().find("i").removeClass("pe-7s-angle-up").addClass("pe-7s-angle-down");
                    jQuery(this).find("i").removeClass("pe-7s-angle-down").addClass("pe-7s-angle-up");
                }
            }
            return false;
        });
    });
    $("#button-submit-content").on('click', function() {
        var thisButton = $(this),
            Buttontxt = thisButton.html();
        thisButton.html('<i class="fa fa-circle-notch fa-spin fa-fw"></i> ' + Buttontxt);
        thisButton.prop('disabled', true);
        ajaxRequests.push = $.ajaxQueue({
            type: 'post',
            url: ajax_url,
            data: $('#form-submit-content').serialize(),
            success: function(data) {
                //console.log(data);
                var result = $.parseJSON(data);
                if (result['status'] === 'error') {
                    thisButton.html(Buttontxt);
                    thisButton.prop('disabled', false);
                    create_thebox_modal();
                    $('.thebox-window').html(result['html']);
                } else if (result['status'] === 'success') {
                    $('#form-submit-content').find("input[type=text], input[type=email], textarea").val("");
                    thisButton.html(Buttontxt).fadeOut();
                    $('#message-content').fadeIn().html(result['html']);
                } else {
                    thisButton.html(Buttontxt);
                    thisButton.prop('disabled', false);
                }
            }
        });
    });
    $("#button-submit-testimonial").on('click', function() {
        var thisButton = $(this),
            Buttontxt = thisButton.html();
        thisButton.html('<i class="fa fa-circle-notch fa-spin fa-fw"></i> ' + Buttontxt);
        thisButton.prop('disabled', true);
        ajaxRequests.push = $.ajaxQueue({
            type: 'post',
            url: ajax_url,
            data: $('#form-submit-testimonial').serialize(),
            success: function(data) {
                //console.log(data);
                var result = $.parseJSON(data);
                if (result['status'] === 'error') {
                    thisButton.html(Buttontxt);
                    thisButton.prop('disabled', false);
                    create_thebox_modal();
                    $('.thebox-window').html(result['html']);
                } else if (result['status'] === 'success') {
                    $('#form-submit-testimonial').fadeOut();
                    thisButton.html(Buttontxt).fadeOut();
                    $('#message-testimonial').fadeIn().html(result['html']);
                } else {
                    thisButton.html(Buttontxt);
                    thisButton.prop('disabled', false);
                }
            }
        });
    });
})(jQuery);