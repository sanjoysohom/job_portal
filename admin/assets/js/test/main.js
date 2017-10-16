(function($){
    "use strict";  

/*=========Top Dropdown menu start=========*/
	$('.dropdown').on('show.bs.dropdown', function(e){
    $(this).find('.dropdown-menu').first().stop(true, true).slideDown();
  });

    $('.dropdown').on('hide.bs.dropdown', function(e){
    $(this).find('.dropdown-menu').first().stop(true, true).slideUp();
  });
/*=========Top Dropdown menu end=========*/
  $(document).ready(function() {
       /* $('.checkme').iCheck({
            checkboxClass: 'icheckbox_polaris',
            radioClass: 'iradio_polaris',
            increaseArea: '-10%' 
        });*/

        $(".historyTab a").click(function(){
            $(this).tab('show');
        });
        var $newDiv = "<div class='newImgSec'><div class='browseImg'><input type='file' class='imgFile'></div><div class='imgForm'><select><option>Select image view</option><option>Front-view</option><option>Back-view</option><option>Left-view</option><option>Right-view</option><option>Inside-view</option></select></div><div class='imgBtn'><a href='#' class='no'><i class='fa fa-times'></i></a><a class='yes'><i href='#' class='fa fa-check'></i></a></div></div>";
        $('.addImg').click(function(){
            $('.imageUploadSec').prepend($newDiv);
        });

        $('.addImgSpace').on('click','.no', function(e){
            e.preventDefault();
            $(this).parent().parent().remove();
        });
        $( ".left_panel_bar" ).click(function() {
            if (!$( "#accordion" ).hasClass('main_left_panel_add')) {
                $(this).before('<div class="overlay-dark"></div>');
            }else {
                $('.overlay-dark').remove();
            }
            
            $(this).find('i').toggleClass('fa-times');
            $( "#accordion" ).toggleClass( "main_left_panel_add animated rollIn" );
        });
});
/*=================Responsive menu end===============*/

})(jQuery);