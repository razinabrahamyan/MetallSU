$(document).ready(function () {
    console.log('custom dropdown loaded')
    $(document).on('click','.custom_dropdown_toggler', function () {
        let DROP_MENU = $(this).next();
        if (DROP_MENU.hasClass('active')) {
            $('.custom_dropdown_menu.active').removeClass('active')
        } else {
            $('.custom_dropdown_menu.active').removeClass('active')
            DROP_MENU.addClass('active animated').one("webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend", function () {
                $(this).removeClass("animated");
            });
            if (DROP_MENU.offset().left + DROP_MENU.outerWidth() > $(window).width()) {
                DROP_MENU.addClass('right_aligned')
            } else if (!DROP_MENU.hasClass('right_aligned')) {
                DROP_MENU.removeClass('right_aligned')
            }
        }
    })
    $(document).on('click', function (event) {
        if (!$(event.target).closest('.custom_dropdown_menu.active').length && !$(event.target).closest('.custom_dropdown_toggler').length && !$(event.target).closest('.select2-container').length && !$(event.target).closest('.select2-selection__choice').length) {
            let active = $('.custom_dropdown_menu.active');
            active.removeClass('active')
        }
    });
    $('.zero_date_field').click(function () {
        $(this).closest('.datetime_delete_handler').find('.datepicker').val('').trigger('change')
    })
});
