$(function (){

    'use strict';
    // Switch Bettwen Login And Signup
    $('.login-page h1 span').click(function () {
        $(this).addClass('selected').siblings().removeClass('selected');
        $('login-page form').hide();
        $('.' + $(this).data('class')).fadeIn(100);
    });
    // Trigger The Select Box It

    $("select").selectBoxIt({
        autoWidth: false
    });
    
    //
    $('[placeholder]').focus(function () {
        $(this).attr('data-text', $(this).attr('placeholder'));

        $(this).attr('palceholder', '');
    
    }).blur(function (){
        $(this).attr('placeholder', $(this).attr('data-text'));
    });


    // Confirm Massage Delete on Button

    $('.confirm').click(function () {
        
        return confirm('Are You Sure ?');

    });

    $('.live-name').keyup(function () {
        $('.live-preview .caption h3').text($(this).val());
    });
    $('.live-desc').keyup(function () {
        $('.live-preview .caption p').text($(this).val());
    });
    $('.live-price').keyup(function () {
        $('.live-preview .price-tag').text('$' + $(this).val());
    });

});