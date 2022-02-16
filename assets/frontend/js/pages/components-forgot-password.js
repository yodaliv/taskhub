"use strict";

$('#forgot_password_form').validate({
    rules: {
        identity: "required"
    }
});

$(document).on('keypress', function (e) {
    var keycode = e.keyCode || e.which;
    if (keycode == 13) {
        $('#forgot_password_form').submit();
    }
});

$(document).on('click', '.submit_btn', function (e) {
    e.preventDefault();
    $('#forgot_password_form').submit();
});
$('#forgot_password_form').on('submit', function (e) {
    e.preventDefault();
    var formData = new FormData(this);
    formData.append(csrfName, csrfHash);
    if ($("#forgot_password_form").validate().form()) {
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
                    $('#result').html('<div class="alert alert-success">' + result['message'] + '</div>')
                    $('#result').removeClass("d-none");
                    setTimeout(function () { location.reload() }, 4000);
                } else {
                    $('#result').html('<div class="alert alert-danger">' + result['message'] + '</div>')
                    $('#result').removeClass("d-none");
                    setTimeout(function () { $('#result').delay(6000).addClass("d-none") }, 6000);
                }

            }
        });
    }
});
