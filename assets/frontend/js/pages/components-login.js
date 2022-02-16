"use strict";

$('#login_form').validate({
    rules: {
        identity: "required",
        password: "required"
    }
});
$(document).on('keypress', function (e) {
    var keycode = e.keyCode || e.which;
    if (keycode == 13) {
        $('#login_form').submit();
    }
});
$(document).on('click', '.submit_btn', function (e) {
    e.preventDefault();
    $('#login_form').submit();
});
$('#login_form').on('submit', function (e) {
    e.preventDefault();
    var formData = new FormData(this);
    formData.append(csrfName, csrfHash);
    if ($("#login_form").validate().form()) {
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
                $('.submit_btn').html('Login'); $('.submit_btn').attr('disabled', false);
                csrfName = result['csrfName'];
                csrfHash = result['csrfHash'];
                if (result['error'] == false) {
                    window.location.replace(base_url+result['role']+'/home')
                } else {
                    $('#login_result').html('<div class="alert alert-danger">' + result['message'] + '</div>')
                    $('#login_result').removeClass("d-none");
                    setTimeout(function () { $('#login_result').delay(6000).addClass("d-none") }, 6000);
                }

            }
        });
    }
});
$('.login-as').on('click', function (e) {
    e.preventDefault();
    var login_as = $(this).data('login_as');
    if (login_as == 'super-admin') {
        var identity = 'super@gmail.com';
        var password = 'super@0124';
    }
    else if (login_as == 'admin') {
        var identity = 'admin@gmail.com';
        var password = 'admin@0124';
    }
    else if (login_as == 'team') {
        var identity = 'member@gmail.com';
        var password = 'member@0124';
    }
    else if (login_as == 'client') {
        var identity = 'client@gmail.com';
        var password = 'client@0124';
    } else {
        var identity = '';
        var password = '';
    }
    $('#identity').val(identity);
    $('#password').val(password);
});
