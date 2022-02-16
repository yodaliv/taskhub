"use strict";

$('#signup_form').validate({
    rules: {
        first_name: "required",
        last_name: "required",
        email: "required",
        password: {
            required: true,
            minlength: 8
        },
        password_confirm: {
            required: true,
            equalTo: "#password"
        }
    }
});
$(document).on('keypress', function (e) {
    var keycode = e.keyCode || e.which;
    if (keycode == 13) {
        $('#signup_form').submit();
    }
});
$(document).on('click', '.submit_btn', function (e) {
    e.preventDefault();
    $('#signup_form').submit();
});
$('#signup_form').on('submit', function (e) {
    e.preventDefault();
    var formData = new FormData(this);
    formData.append(csrfName, csrfHash);
    if ($("#signup_form").validate().form()) {
        $.ajax({
            type: 'POST',
            url: $(this).attr('action'),
            data: formData,
            beforeSend: function () { $('.submit_btn').html('Please Wait..'); $('.submit_btn').attr('disabled', true); },
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function (result) {
                $('.submit_btn').html('Submit'); $('.submit_btn').attr('disabled', false);
                csrfName = result['csrfName'];
                csrfHash = result['csrfHash'];
                if (result['error'] == false) {
                    window.location.replace(base_url + 'login');
                } else {
                    $('#result').html('<div class="alert alert-danger">' + result['message'] + '</div>')
                    $('#result').removeClass("d-none");
                    setTimeout(function () { $('#result').delay(6000).addClass("d-none") }, 6000);

                }

            }
        });
    }
});

