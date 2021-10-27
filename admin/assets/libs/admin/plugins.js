
(function($) {
    "use strict";
    $(".checkbox_form input:checkbox:not([safari]), .checkbox_form input:radio:not([safari])").checkbox(),
    $(".checkbox_form input[safari]:checkbox, .checkbox_form input[safari]:radio").checkbox({cls:"$-safari-checkbox"}),
    $(".checkbox_form :checkbox, .checkbox_form :radio").checkbox();
})(jQuery);