jQuery(function ($) {
    'use strict';
    
    $('body').on('submit', 'form', function(){
        let form = $(this);
        setTimeout(function() {
            form.find('button').prop('disabled', true).removeAttr('disabled');
        }, 1);
    });

    $('.acb-export-all').on('change', function () {
        let checked = $(this).prop('checked');
        $(this).closest('.acb-export-entities').find('.acb-export-entity input[type="checkbox"]').each(function () {
            $(this).prop('checked', checked).iCheck('update');
        });
    });
});
