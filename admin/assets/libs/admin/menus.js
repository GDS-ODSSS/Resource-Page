(function($) {
    "use strict";
	$('ul#menu-to-edit').nestedSortable({
		forcePlaceholderSize: true,
		maxLevels: 2,
		tabSize: 42,
		opacity: .8,
		listType: 'ul',
		handle: 'div.menu-item-handle',
		items: 'li',
		placeholder: {
			element: function (currentItem) {
				return $("<li style='border: 1px dashed #ccc;height: 42px;width:320px;'>&nbsp;</li>")[0];
			},
			update: function (container, p) {
				return;
			},
		},
		change: function (event, ui) {

		},
		sort: function (event, ui) {

		},
		stop: function (event, ui) {

		},
		update: function (event, ui) {

		}
	}).disableSelection();
	$(document).on('keyup', '.menu-title', function () {
		var menuid = $(this).attr('data-id');
		var title = $(this).val();
		$('.menu-title-' + menuid).html(title);
	});
	$(document).on('keyup', '.menu-icons', function () {
		var icon = $(this).val();
		$(this).parent().find('.preview-icon').html('<i class="' + icon + '"></i>');
	});
	$(document).on('click', '.item-menu-edit', function () {
		var menuid = $(this).attr('data-id');
		if ($('#menu-item-' + menuid).hasClass("menu-open")) {
			$('#menu-item-' + menuid).removeClass('menu-open');
			$('.menu-open-' + menuid).val('0');
		} else {
			$('#menu-item-' + menuid).addClass('menu-open');
			$('.menu-open-' + menuid).val('1');
		}
	});
	$(document).on('click', '.menu-submitdelete', function () {
		var menuid = $(this).attr('data-id');
		$('#menu-item-' + menuid).remove();
	});
	$('body').on('click', "#save_menu_header", function () {
		var serialized  = $('#menu-to-edit').nestedSortable('serialize');
		serialized += '&action=savemenuitem';
		ajaxRequests.push = $.ajaxQueue({
			url: admin_ajax_url,
			type: 'post',
			data: serialized,
			success: function (data) {
				$('input#menu_item_map').val(data);
				$('form#menu-form-edit').submit();
			}
		});
	});
	$('.submit-add-custom-link-to-menu').click(function () {
		var Button = $(this),
			Buttonhtml = Button.html(),
			menuadd = 0,
			link_url = $('#custom_links_url'),
			link_title = $('#custom_links_title'),
			menu_item_count = $('#menu_item_count').val();
		if (link_url.val().length >= 1 && link_title.val().length >= 1) {
			Button.prop('disabled', true);
			Button.html('<i class="fas fa-circle-notch fa-spin fa-fw"></i> ' + Buttonhtml);
			menu_item_count++;
			ajaxRequests.push = $.ajaxQueue({
				url: admin_ajax_url,
				type: 'post',
				data: 'action=addmenuitem&type=Custom_Link&itemid=' + menu_item_count + '&title=' + link_title.val() + '&url=' + link_url.val(),
				success: function (data) {
					console.log(data);
					var result = $.parseJSON(data);
					if (result['status'] === 'success') {
						$('#menu-to-edit').append(result['html']);
						Button.html(Buttonhtml);
						Button.prop('disabled', false);
					}
				}
			});
			$('#menu_item_count').val(menu_item_count);
		}
	});
	$('.submit-add-to-menu').on('click', function () {
		var Button = $(this),
			type = Button.attr('data-type'),
			prifx = Button.attr('data-prifx'),
			Buttonhtml = Button.html(),
			menuadd = 0,
			menu_item_count = $('#menu_item_count').val(),
			menulength = $('#accordion-section-add-' + prifx + ' .menu-item-checkbox:checked').length;
		if (menulength) {
			$('#accordion-section-add-' + prifx + ' .menu-item-checkbox:checked').each(function () {
				Button.prop('disabled', true);
				Button.html('<i class="fas fa-circle-notch fa-spin fa-fw"></i> ' + Buttonhtml);
				menu_item_count++;
				var check = $(this),
					title = check.attr('data-title'),
					icon = check.attr('data-icon'),
					url = check.attr('data-url');
				check.parent().parent().append('<i class="fas fa-circle-notch fa-spin fa-fw"></i>');
				ajaxRequests.push = $.ajaxQueue({
					url: admin_ajax_url,
					type: 'post',
					data: 'action=addmenuitem&itemid=' + menu_item_count + '&title=' + title + '&icon=' + icon + '&url=' + url + '&type=' + type,
					success: function (data) {
						console.log(data);
						var result = $.parseJSON(data);
						if (result['status'] === 'success') {
							$('#menu-to-edit').append(result['html']);
							check.prop('checked', false);
							check.parent().parent().find('.fa-circle-notch').remove();
							menuadd++;
						}
						if (menulength == menuadd) {
							Button.html(Buttonhtml);
							Button.prop('disabled', false);
						}
					}
				});
				$('#menu_item_count').val(menu_item_count);
			});
		}
	});
})(jQuery);