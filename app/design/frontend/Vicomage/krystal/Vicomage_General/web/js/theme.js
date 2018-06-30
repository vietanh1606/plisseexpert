/******************** Js Custom ***********************/
define([
    'jquery',
    'parallax'
], function ($) {

    //ScrollTop
    $('#backtotop').hide();
    $(window).scroll(function () {
        if ($(this).scrollTop() > 350) {
            $('#backtotop').show();
        }
        else{
            $('#backtotop').hide();
        }
        return false;
    });

    $(document).ready(function($) {
        $('#backtotop').click(function(e) {
            $('html,body').animate({scrollTop:0}, 500);
            return false;
            e.preventDefault();
        });
    });

    //parallax
    $(document).ready(function($) {
        if($('.bg-parallax').length >0){
            $('.bg-parallax').each(function(){
                $(this).parallax("50%",0.1);
            })
        }
    });
});

