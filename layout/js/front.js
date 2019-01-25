$(function () {
    'use strict';


    //Switch between login & signup
    $('.login-page h1 span').click(function () {

        $(this).addClass('selected').siblings().removeClass('selected');
        $('.login-page form').hide();
        $('.' + $(this).data('class')).fadeIn(100);

    });
    //Turn on the selectboxIt
    $("select").selectBoxIt({

        autoWidth:false
    });

    // Hide placeholder on Form focus

    $('[placeholder]').focus(function () {
        $(this).attr('data-text',$(this).attr('placeholder'));
        $(this).attr('placeholder','');
    }).blur(function () {
        $(this).attr('placeholder',$(this).attr('data-text'));
    });

    //Add Asterisk on required fields
    $('input').each(function () {

        if($(this).attr('required') === 'required') {
            $(this).after('<span class="asterisk">*</span>');
        }
    });


    // Confirm Delete operation
    $('.confirm').click( function () {
        return confirm('If you proceed this user will be deleted, Are You Sure?');
    });

    $('.live').keyup(function () {
        $($(this).data('class')).text($(this).val());
    });



});

