"use strict";
$('#button').on('click', function (e) {
    window.location.replace(base_url + 'auth');
});

$('#enquiry_form').on('submit', function (e) {
    e.preventDefault();
    var formData = new FormData(this);
    formData.append(csrfName, csrfHash);
    if ($("#enquiry_form").validate().form()) {
        $.ajax({
            type: 'POST',
            url: $(this).attr('action'),
            data: formData,
            beforeSend: function () { $('#btn').html('Please Wait..'); $('#btn').attr('disabled', true); },
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function (result) {
                csrfName = result['csrfName'];
                csrfHash = result['csrfHash'];
                $('#btn').html('Send'); $('#btn').attr('disabled', false);
                if (result['error'] == false) {
                    $('#enquiry_result').html('<div class="alert alert-success">' + result['message'] + '</div>')
                    $('#enquiry_result').removeClass("d-none");
                    setTimeout(function () { $('#enquiry_result').delay(6000).addClass("d-none") }, 6000);
                    $("#mail").val('');
                } else {
                    $('#enquiry_result').html('<div class="alert alert-danger">' + result['message'] + '</div>')
                    $('#enquiry_result').removeClass("d-none");
                    setTimeout(function () { $('#enquiry_result').delay(6000).addClass("d-none") }, 6000);
                }

            }
        });
    }
});