$('#payment_methods_form').on('submit', function (e) {
    e.preventDefault();
    var formData = new FormData(this);
    formData.append(csrfName, csrfHash);
    $.ajax({
        type: 'POST',
        url: $(this).attr('action'),
        data: formData,
        beforeSend: function () { $('#submit_button').html('Please Wait..'); $('#submit_button').attr('disabled', true); },
        cache: false,
        contentType: false,
        processData: false,
        dataType: "json",
        success: function (result) {
            $('#submit_button').html('Submit'); $('#submit_button').attr('disabled', false);
            csrfName = result['csrfName'];
            csrfHash = result['csrfHash'];
            if (result['error'] == false) {
                location.reload();
            } else {
                iziToast.error({
                    title: result['message'],
                    message: '',
                    position: 'topRight'
                });
            }

        }
    });
});