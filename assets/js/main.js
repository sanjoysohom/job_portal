// Place any jQuery/helper plugins in here.
jQuery(function($) {
    "use strict";

    /* ----------------------------------------------------------- */
    /*   sub-navbar JS
    /* ----------------------------------------------------------- */

    $(".has-sub-dropdown a.sub-dropdown-link").on("click", function(e) {
        $(this).next("ul").toggle();
        e.stopPropagation();
        e.preventDefault();
    });


});
