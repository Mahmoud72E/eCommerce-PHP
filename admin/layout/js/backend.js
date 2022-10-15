$(function (){

    'use strict';
    // Dashboard 
    $('.toggle-info').click(function () {
        $(this).toggleClass('selected').parent().next('.card-body').fadeToggle(100);
        if ($(this).hasClass('selected')) {
            $(this).html('<i class="fa fa-minus-square fa-lg"></i>');
        }else {
            $(this).html('<i class="fa fa-plus-square fa-lg"></i>');
        }
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

    // eye Pass
    var passFailed = $('.password');

    $('.show-pass').hover(function () {
        passFailed.attr('type', 'text');

    }, function () {
        passFailed.attr('type', 'password');
    });

    // Confirm Massage Delete on Button

    $('.confirm').click(function () {
        
        return confirm('Are You Sure ?');

    });

    // Category View Option
    $('.cat h3').click(function () {
    
        $(this).next('.full-view').fadeToggle(230);

    });
    $('.option span').click(function () {
        
        $(this).addClass('active').siblings('span').removeClass('active');

        if ($(this).data('view') === 'full') {
            $('.cat .full-view').fadeIn(200);
        } else {
            $('.cat .full-view').fadeOut(200);
        }
    });

});