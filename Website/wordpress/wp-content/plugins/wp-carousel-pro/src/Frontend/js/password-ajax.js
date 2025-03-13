(function ($) {
    'use strict';
    jQuery('body').find('.wpcp-password-area').each(function () {
        var password_area_id = $(this).attr('id');
        var _this = $(this);
        jQuery('#' + password_area_id + ' .submit_wpcp_password').on('click', function () {
            var password = $('#' + password_area_id + ' .wpcp_password').val();
            var nonce = $('#' + password_area_id + ' .wpcp_password').data('nonce');
            var sid = $('#' + password_area_id + ' .wpcp_password').data('id');
            var ajax_url = $('#' + password_area_id + ' .wpcp_password').data('url');
            // location.reload();
            $.ajax({
                type: 'POST',
                url: ajax_url,
                data: {
                    pword: password,
                    id: sid,
                    action: 'wpcp_password_cookie',
                    nonce: nonce,
                },
                success: function (response) {
                   window.location.reload();
                }
            });
        });

    });
})(jQuery);