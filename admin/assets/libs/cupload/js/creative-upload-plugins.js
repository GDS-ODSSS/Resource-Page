jQuery(document).ready(function() {
    // accordion library
    jQuery('.creative-media-upload-accordion-title').on('click', function(){
        jQuery(this).parent().find('.creative-media-upload-accordion-body').toggle();
        return false;
    });
    // Gallery selectd
    jQuery(".creative-media-upload-gallery li").on("click", function()
	{
         sel = jQuery(this).attr('class');
         if( sel == 'selectd' )
         {
            jQuery(this).find('input:checkbox').removeAttr('checked');
            jQuery(this).removeClass('selectd');
         }
         else
         {
            jQuery(this).addClass('selectd');
            jQuery(this).find('input:checkbox').attr('checked','checked');
         }
		 return false;
	});
    // this old code
    jQuery("#browser-uploader").click(function(){
        jQuery("#multi-file").hide();
        jQuery("#browser-file").show();
        return false;
    });
    jQuery("#multi-file-uploader").click(function(){
        jQuery("#browser-file").hide();
        jQuery("#multi-file").show();
        return false;
    });
});


// open popup thickbox auto width and height
function creative_media_upload(field, type, tab ) {
	var button = ".cmp-"+field+"-button";
    if (type === undefined) {
          type = 'image';
    } 
    if (tab === undefined) {
          tab = 'type';
    } 
	$(button).click(function() {
		var tbwidth = $(window).width() - 80;
        var tbheight = $(window).height() - 60;
        if (type == 'gallery')
        {
            window.restore_send_to_gallery = window.send_to_gallery;
        }
        else
        {
            window.restore_send_to_editor  = window.send_to_editor;
        }
		tb_show('creative media upload', 'media-upload.php?type='+type+'&tab='+tab+'&TB_iframe=true&height='+tbheight+'&width='+tbwidth+'');
	    if (type == 'gallery')
        {
            creative_media_upload_send_gallery(field);
        }
        else
        {
            creative_media_upload_send_image(field);
        }
        return false;
	});
	$('.cmp-input-'+field).change(function(){
        if($(this).val() != '')
        {
            $('.cmp-'+field+'-preview').show();
            $('.cmp-'+field+'-button').hide();
    		$('.cmp-'+field+'-preview img').attr("src",$('.cmp-input-'+field).val());
        }
        else
        {
            $('.cmp-'+field+'-preview').hide();
            $('.cmp-'+field+'-button').show();
    		$('.cmp-'+field+'-preview img').attr("src",'');
        }
    		
	});
    
    // remove image 
    $(".cmp-remove-"+field+"-image").on('click', function(){
        $(this).parent().fadeOut(function() {
    		$(this).hide();
            $('.cmp-'+field+'-button').show();
            $('.cmp-input-'+field+'').val('');
    	});
	});
    // remove gallery
    $(".cmp-remove-"+field+"-gallery").on('click', function(){
        $(this).parent().fadeOut(function() {
    		$(this).hide();
            $('.cmp-'+field+'-button').show();
            $('.cmp-input-'+field+'').val('');
    	});
	});
    
    
}
// set selected image
function creative_media_upload_send_image(field) {
	window.send_to_editor = function(html) {
		imgurl = $('img',html).attr('src');
		tb_remove();
		if(typeof imgurl == 'undefined')
			imgurl = $(html).attr('src');
		$('.cmp-input-'+field).val(imgurl);
		$('.cmp-'+field+'-preview').show();
		$('.cmp-'+field+'-preview img').attr("src",imgurl);
        $('.cmp-'+field+'-button').hide();
		window.send_to_editor = window.restore_send_to_editor;
	}
};
// set selected gallery
function creative_media_upload_send_gallery(field) {
	window.send_to_gallery = function(html) {
		imgurl = $('input',html).attr('value');
		tb_remove();
		if(typeof imgurl == 'undefined')
			imgurl = $(html).attr('value');
		$('.cmp-input-'+field).val(imgurl);
		$('.cmp-'+field+'-gallery').show().attr("title","Gallery ids=\""+imgurl+"\"");
        $('.cmp-'+field+'-button').hide();
		window.send_to_gallery = window.restore_send_to_gallery;
	}
};
// old code used in page 
//creative_media_upload("image");

// get url image
var addExtImage = {
	width : '',
	height : '',
	align : 'alignnone',
	insert : function() {
		var t = this, html, f = document.forms[0], cls, title = '', alt = '', caption = '';
		if ( '' == f.src.value || '' == t.width )
			return false;
		if ( f.alt.value )
			alt = f.alt.value.replace(/'/g, '&#039;').replace(/"/g, '&quot;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
            
		if ( f.caption.value ) {
			caption = f.caption.value.replace(/\r\n|\r/g, '\n');
			caption = caption.replace(/<[a-zA-Z0-9]+( [^<>]+)?>/g, function(a){
				return a.replace(/[\r\n\t]+/, ' ');
			});
			caption = caption.replace(/\s*\n\s*/g, '<br />');
		}
		cls = caption ? '' : ' class="'+t.align+'"';
		html = '<img alt="'+alt+'" src="'+f.src.value+'"'+cls+' width="'+t.width+'" height="'+t.height+'" />';
		if ( f.url.value ) {
			url = f.url.value.replace(/'/g, '&#039;').replace(/"/g, '&quot;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
			html = '<a href="'+url+'">'+html+'</a>';
		}
		if ( caption )
			html = '[caption id="" align="'+t.align+'" width="'+t.width+'"]'+html+caption+'[/caption]';
		var win = window.dialogArguments || opener || parent || top;
		win.send_to_editor(html);
		return false;
	},
	resetImageData : function() {
		var t = addExtImage;
		t.width = t.height = '';
		if ( ! document.forms[0].src.value ){document.getElementById('status_img').innerHTML = '*';}
		else {document.getElementById('status_img').innerHTML = '<img src="assets/images/no.png" alt="" />';
        document.getElementById('preloadImg').src = "";
        }
	},
	updateImageData : function() {
		var t = addExtImage;
		t.width = t.preloadImg.width;
		t.height = t.preloadImg.height;
		document.getElementById('status_img').innerHTML = '<img src="assets/images/yes.png" alt="" />';
        document.getElementById('preloadImg').src = document.getElementById('src').value;
	},
	getImageData : function() {
		if ( jQuery('table.describe').hasClass('not-image') )
			return;
		var t = addExtImage, src = document.forms[0].src.value;
		if ( ! src ) {
			t.resetImageData();
			return false;
		}
		document.getElementById('status_img').innerHTML = '<img src="assets/images/loading.gif" alt="" width="16" />';
		t.preloadImg = new Image();
		t.preloadImg.onload = t.updateImageData;
		t.preloadImg.onerror = t.resetImageData;
		t.preloadImg.src = src;
	}
}