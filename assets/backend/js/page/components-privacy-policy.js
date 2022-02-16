tinymce.init({
    selector: '#privacy_policy',
    height: 150,
    menubar: false,
    plugins: [
        'autolink lists link charmap print preview anchor textcolor',
        'searchreplace visualblocks code fullscreen',
        'insertdatetime table contextmenu paste code help wordcount'
    ],
    toolbar: 'insert | undo redo |  formatselect | bold italic backcolor  | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | help ',
    setup: function (editor) {
        editor.on("change keyup", function (e) {
            editor.save(); // updates this instance's textarea
            $(editor.getElement()).trigger('change'); // for garlic to detect change
        });
    }
});

$('#privacy_policy_form').on('submit', function (e) {
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