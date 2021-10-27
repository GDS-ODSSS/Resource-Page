function preview(img, selection) {
    if (!selection.width || !selection.height)
    return;
    var scaleX = 100 / selection.width;
    var scaleY = 100 / selection.height;
    var sizer = $('#imgedit-sizer').val();
    $('#imgedit-sel-width').val(Math.round(selection.width * sizer));
    $('#imgedit-sel-height').val(Math.round(selection.height * sizer)); 
    var x = Math.round(selection.x1);
    var y = Math.round(selection.y1);
    var w = Math.round(selection.x2-x);
    var h = Math.round(selection.y2-y);
    $('#imgedit-x').val(x);
    $('#imgedit-y').val(y);
    $('#imgedit-w').val(w);
    $('#imgedit-h').val(h);
    $("#imgedit-selection").val('{"x":'+x+',"y":'+y+',"w":'+w+',"h":'+h+'}');
    if( w >= '100' && h >= '100')
    {
        $("div.imgedit-crop").removeClass("disabled");
    }
    else
    {
        $("div.imgedit-crop").addClass("disabled");
    }
}
          
jQuery(document).ready(function(){
    $(function () {
        $('img#imgedit').imgAreaSelect({ handles: true,fadeSpeed: 200, onSelectChange: preview });
    });
    function mega_history(mode)
    {
        var history = $("#imgedit-history").val();
        var add_history = '';
        if( mode == 'rleft' )
        {
            if(history == '')
            {
                var add_history = '{"r":90}';
            }
            else
            {
                var add_history = ',{"r":90}';
            }
        }
        if( mode == 'rright' )
        {
           if(history == '')
            {
                var add_history = '{"r":-90}';
            }
            else
            {
                var add_history = ',{"r":-90}';
            }
        }
        if( mode == 'flipv' )
        {
            if(history == '')
            {
                var add_history = '{"f":1}';
            }
            else
            {
                var add_history = ',{"f":1}';
            }
        }
        if( mode == 'fliph' )
        {
            if(history == '')
            {
                var add_history = '{"f":2}';
            }
            else
            {
                var add_history = ',{"f":2}';
            }
        }
        if( mode == 'crop' )
        {
            var selection = $("#imgedit-selection").val();
            if(selection != '')
            {
                if( $('#imgedit-w').val() >= 100 && $('#imgedit-h').val() >= 100 )
                {
                    if(history == '')
                    {
                        var add_history = '{"c":'+selection+'}';
                    }
                    else
                    {
                       var add_history = ',{"c":'+selection+'}';
                    }
                }
                else
                {
                    if(history == '')
                    {
                        var add_history = '{"v":1}';
                    }
                    else
                    {
                       var add_history = ',{"v":1}';
                    }
                }
            }
            
        }
        if(history == '')
        {
            var set_history = '['+add_history+']';
            $("#imgedit-history").val(set_history);
            $("#image_undo").removeClass("disabled");
        }
        else
        {
            history = history.replace("[", "");
            history = history.replace("]", "");
            var set_history = '['+history+add_history+']';
            $("#imgedit-history").val(set_history);
        }
        return encodeURI(set_history);
    }
    //insertonlybutton
    $('#insertonlybutton').click(function(){
        var img = $("#imgedit").attr('src');
        $("#imgedit-save").val(img);
    });
    $('#crop').click(function(){
        var id = $("#imgedit-id").val();
        $("#imgedit").attr("src",'media-upload.php?action=imgedit&history='+mega_history('crop')+'&id='+id+'');
        $("#imgedit-selection").val('');
    });
    //rotate_rleft
    $('#rotate_rleft').click(function(){
        var id = $("#imgedit-id").val();
        $("#imgedit").attr("src",'media-upload.php?action=imgedit&history='+mega_history('rleft')+'&id='+id+'');
    });
    //rotate_rright
    $('#rotate_rright').click(function(){
        var id = $("#imgedit-id").val();
        $("#imgedit").attr("src",'media-upload.php?action=imgedit&history='+mega_history('rright')+'&id='+id+'');
        
    });
    //flip_vertically
    $('#flip_vertically').click(function(){
        var id = $("#imgedit-id").val();
        $("#imgedit").attr("src",'media-upload.php?action=imgedit&history='+mega_history('flipv')+'&id='+id+'');
    });
    //flip_vertically
    $('#flip_horizontally').click(function(){
        var id = $("#imgedit-id").val();
        $("#imgedit").attr("src",'media-upload.php?action=imgedit&history='+mega_history('fliph')+'&id='+id+'');
    });
});