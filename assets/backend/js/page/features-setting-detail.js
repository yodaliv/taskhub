"use strict";
$(document).on('submit', '#languages-setting-form', function (e) {
    e.preventDefault();

    let save_button = $(this).find('#languages-save-btn'),
        output_status = $("#output-status"),
        card = $('#languages-settings-card');

    let card_progress = $.cardProgress(card, {
        spinner: false
    });
    save_button.addClass('btn-progress');
    output_status.html('');


    var formData = new FormData(this);

    $.ajax({
        type: 'POST',
        url: $(this).attr('action'),
        data: formData,
        dataType: 'json',
        beforeSend: function() {
            // setting a timeout
            $('#languages-save-btn').html('Please Wait..'); $('#languages-save-btn').attr('disabled', true);
        },
        cache: false,
        contentType: false,
        processData: false,
        success: function (result) {

            csrfName = result['csrfName'];
            csrfHash = result['csrfHash'];
            if (result['error'] == false) {
                location.reload();
            } else {
                $('#languages-save-btn').html('Save Changes'); $('#languages-save-btn').attr('disabled', false);
                $('#result').prepend('<div class="alert alert-danger">' + result['message'] + '</div>')
                $('#result').delay(4000).fadeOut();
            }
        }
    });

    card_progress.dismiss(function () {
        save_button.removeClass('btn-progress');
    });


});
$(document).on('submit', '#general-setting-form', function (e) {

    e.preventDefault();

    let save_button = $(this).find('#general-save-btn'),
        output_status = $("#output-status"),
        card = $('#general-settings-card');

    let card_progress = $.cardProgress(card, {
        spinner: false
    });
    save_button.addClass('btn-progress');
    output_status.html('');


    var formData = new FormData(this);

    $.ajax({
        type: 'POST',
        url: $(this).attr('action'),
        data: formData,
        beforeSend: function() {
            // setting a timeout
            $('#general-save-btn').html('Please Wait..'); $('#general-save-btn').attr('disabled', true);
        },
        dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        success: function (result) {

            csrfName = result['csrfName'];
            csrfHash = result['csrfHash'];
            if (result['error'] == false) {
                location.reload();
            } else if (result['error'] == true && result['is_reload'] == 1) {
                location.reload();
            }
            else {
                $('#general-save-btn').html('Save Changes'); $('#general-save-btn').attr('disabled', false);
                $('#result').prepend('<div class="alert alert-danger">' + result['message'] + '</div>')
                $('#result').delay(4000).fadeOut();
            }
        }
    });

    card_progress.dismiss(function () {
        save_button.removeClass('btn-progress');
    });


});

$(document).on('submit', '#email-setting-form', function (e) {

    e.preventDefault();

    let save_button = $(this).find('#email-save-btn'),
        output_status = $("#output-status"),
        card = $('#email-settings-card');

    let card_progress = $.cardProgress(card, {
        spinner: false
    });
    save_button.addClass('btn-progress');
    output_status.html('');


    var formData = new FormData(this);

    $.ajax({
        type: 'POST',
        url: $(this).attr('action'),
        data: formData,
        beforeSend: function() {
            // setting a timeout
            $('#eamil-save-btn').html('Please Wait..'); $('#eamil-save-btn').attr('disabled', true);
        },
        dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        success: function (result) {

            csrfName = result['csrfName'];
            csrfHash = result['csrfHash'];
            if (result['error'] == false) {
                location.reload();
            }
            else if (result['error'] == true && result['is_reload'] == 1) {
                location.reload();
            } else {
                $('#eamil-save-btn').html('Save Changes'); $('#eamil-save-btn').attr('disabled', false);
                $('#result').prepend('<div class="alert alert-danger">' + result['message'] + '</div>')
                $('#result').delay(4000).fadeOut();
            }
        }
    });

    card_progress.dismiss(function () {
        save_button.removeClass('btn-progress');
    });


});
$(document).on('submit', '#system-setting-form', function (e) {

    e.preventDefault();

    let save_button = $(this).find('#system-save-btn'),
        output_status = $("#output-status"),
        card = $('#system-settings-card');

    let card_progress = $.cardProgress(card, {
        spinner: false
    });
    save_button.addClass('btn-progress');
    output_status.html('');


    var formData = new FormData(this);

    $.ajax({
        type: 'POST',
        url: $(this).attr('action'),
        data: formData,
        beforeSend: function() {
            // setting a timeout
            $('#system-save-btn').html('Please Wait..'); $('#system-save-btn').attr('disabled', true);
        },
        dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        success: function (result) {

            csrfName = result['csrfName'];
            csrfHash = result['csrfHash'];
            if (result['error'] == false) {
                location.reload();
            } else if (result['error'] == true && result['is_reload'] == 1) {
                location.reload();
            }
            else {
                $('#system-save-btn').html('Save Changes'); $('#system-save-btn').attr('disabled', false);
                $('#result').prepend('<div class="alert alert-danger">' + result['message'] + '</div>')
                $('#result').delay(4000).fadeOut();
            }

        }
    });

    card_progress.dismiss(function () {
        save_button.removeClass('btn-progress');
    });


});
$(document).on('submit', '#twilio-setting-form', function (e) {

    e.preventDefault();

    let save_button = $(this).find('#twilio-save-btn'),
        output_status = $("#output-status"),
        card = $('#twilio-settings-card');

    let card_progress = $.cardProgress(card, {
        spinner: false
    });
    save_button.addClass('btn-progress');
    output_status.html('');


    var formData = new FormData(this);

    $.ajax({
        type: 'POST',
        url: $(this).attr('action'),
        data: formData,
        beforeSend: function() {
            // setting a timeout
            $('#twilio-save-btn').html('Please Wait..'); $('#twilio-save-btn').attr('disabled', true);
        },
        dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        success: function (result) {

            csrfName = result['csrfName'];
            csrfHash = result['csrfHash'];
            if (result['error'] == false) {
                location.reload();
            } else if (result['error'] == true && result['is_reload'] == 1) {
                location.reload();
            }
            else {
                $('#twilio-save-btn').html('Save Changes'); $('#system-save-btn').attr('disabled', false);
                $('#result').prepend('<div class="alert alert-danger">' + result['message'] + '</div>')
                $('#result').delay(4000).fadeOut();
            }

        }
    });

    card_progress.dismiss(function () {
        save_button.removeClass('btn-progress');
    });


});
$('#default_package').on('change', function (e) {
    var id = $('#default_package').val();
    if (id != '') {
        $.ajax({
            type: 'POST',
            url: base_url + 'super-admin/packages/get_tenures_by_package_id/' + id,
            data: csrfName + '=' + csrfHash,
            dataType: "json",
            success: function (result) {
                csrfName = result.csrfName;
                csrfHash = result.csrfHash;
                $('#default_tenure').html(result.tenures);
            }
        });
    } else {
        $('#default_tenure').html('<option value="">Select</option>');
    }
});

