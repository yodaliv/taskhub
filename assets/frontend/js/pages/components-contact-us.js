"use strict";

$('#contact_us_form').validate({
    rules: {
        full_name: "required",
        mobile_no: "required",
        email: "required",
        subject: "required",
        message: "required"
    }
});
$(document).on('keypress', function (e) {
    var keycode = e.keyCode || e.which;
    if (keycode == 13) {
        $('#contact_us_form').submit();
    }
});
$(document).on('click', '.contact_button', function (e) {
    e.preventDefault();
    $('#contact_us_form').submit();
});
$('#contact_us_form').on('submit', function (e) {
    e.preventDefault();
    var formData = new FormData(this);
    formData.append(csrfName, csrfHash);
    if ($("#contact_us_form").validate().form()) {
    $.ajax({
        type: 'POST',
        url: $(this).attr('action'),
        data: formData,
        beforeSend: function () { $('.contact_button').html('Please Wait..'); $('.contact_button').attr('disabled', true); },
        cache: false,
        contentType: false,
        processData: false,
        dataType: "json",
        success: function (result) {
            csrfName = result['csrfName'];
            csrfHash = result['csrfHash'];
            $('.contact_button').html('Submit'); $('.contact_button').attr('disabled', false);
            if (result['error'] == false) {
                location.reload();
            } else {
                $('#result_send_mail').html(result['message'])
                $('#result_send_mail').removeClass("d-none");
                setTimeout(function () { $('#result_send_mail').delay(6000).addClass("d-none") }, 6000);
            }

        }
    });
    }
});

