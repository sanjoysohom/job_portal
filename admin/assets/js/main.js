// Place any jQuery/helper plugins in here.


jQuery(function($) {
    "use strict";

    $(document).ready(function() {
        /* ----------------------------------------------------------- */
        /*   owl-carousel  JS
        /* ----------------------------------------------------------- */

        $(document).ready(function() {

            var owl = $("#agent-carosuel-owl");

            owl.owlCarousel({
                items: 6, //10 items above 1000px browser width
                itemsDesktop: [1199, 4], //5 items between 1000px and 901px
                itemsDesktopSmall: [900, 4], // betweem 900px and 601px
                itemsTablet: [600, 2], //2 items between 600 and 0
                itemsMobile: false, // itemsMobile disabled - inherit from itemsTablet option
                autoPlay: false,
                pagination: false
            });

            // Custom Navigation Events
            $(".next").click(function() {
                owl.trigger('owl.next');
            })
            $(".prev").click(function() {
                owl.trigger('owl.prev');
            })


        });

        /* ----------------------------------------------------------- */
        /*   sub menue  JS
        /* ----------------------------------------------------------- */
        /*$(window).on('resize', function() {
            var query = Modernizr.mq('(max-width: 767px)');
            var mainMenue = $('#main-menue');
            var submenueHead = $('#sub-menue-head');
            var navbarTarget = $('#navbar-target');
            if (query) {
                mainMenue.addClass('collapse');
                submenueHead.attr("data-toggle", "collapse");
                navbarTarget.attr("data-toggle", "collapse");

            } else {
                mainMenue.removeClass('collapse');
                submenueHead.removeAttr("data-toggle");
                navbarTarget.removeAttr("data-toggle");
            }

        }).resize();*/



        /* ----------------------------------------------------------- */
        /*   jquery ui select  JS
        /* ----------------------------------------------------------- */

        /*$("#agent-ropdown").selectmenu();
        $(".agent-dropdown").selectmenu();*/
    });

    /*$(function() {
        $(".main-menue").accordion({
            collapsible: true,
            active: false,
            heightStyle: "content",
            create: function(event, ui) {
                $(".list-group-item.main-item").click(function() {
                    var dataHref = $(this).attr("href");
                    if (dataHref.length > 0) {
                        window.location.href = dataHref;
                        event.preventDefault();
                        return;
                    }
                })

            }



        });
    });*/

    /*$(function() {
        $('#browse').change(function() {
            $('#filename').val($(this).val().split(/\\|\//).pop());
        });
    });*/



});
