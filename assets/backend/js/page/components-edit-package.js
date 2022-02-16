
"use strict";
$('#edit_package_form').validate({
    rules: {
        title: "required",
        sequence_no: "required",
        description: "required"
    }
});
$(document).on('click', '#editckbCheckAll', function (e) {
    $(".checkBoxClass").prop('checked', $(this).prop('checked'));
});
$('#edit_package_form').on('submit', function (e) {
    e.preventDefault();
    var formData = new FormData(this);
    formData.append(csrfName, csrfHash);
    if ($("#edit_package_form").validate().form()) {
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
    }
});
$(document).on('click', '#editckbCheckAll', function (e) {
    if ($(this).is(':checked')) {
        $('#edit-is-projects-allowed').val(1);
        $('#edit-is-tasks-allowed').val(1);
        $('#edit-is-calendar-allowed').val(1);
        $('#edit-is-chat-allowed').val(1);
        $('#edit-is-finance-allowed').val(1);
        $('#edit-is-users-allowed').val(1);
        $('#edit-is-clients-allowed').val(1);
        $('#edit-is-activity-logs-allowed').val(1);
        $('#edit-is-leave-requests-allowed').val(1);
        $('#edit-is-notes-allowed').val(1);
        $('#edit-is-settings-allowed').val(1);
        $('#edit-is-languages-allowed').val(1);
        $('#edit-is-mail-allowed').val(1);
        $('#edit-is-announcements-allowed').val(1);
        $('#edit-is-notifications-allowed').val(1);
        $('#edit-is-sms-notifications-allowed').val(1);
    } else {
        $('#edit-is-projects-allowed').val(0);
        $('#edit-is-tasks-allowed').val(0);
        $('#edit-is-calendar-allowed').val(0);
        $('#edit-is-chat-allowed').val(0);
        $('#edit-is-finance-allowed').val(0);
        $('#edit-is-users-allowed').val(0);
        $('#edit-is-clients-allowed').val(0);
        $('#edit-is-activity-logs-allowed').val(0);
        $('#edit-is-leave-requests-allowed').val(0);
        $('#edit-is-notes-allowed').val(0);
        $('#edit-is-settings-allowed').val(0);
        $('#edit-is-languages-allowed').val(0);
        $('#edit-is-mail-allowed').val(0);
        $('#edit-is-announcements-allowed').val(0);
        $('#edit-is-notifications-allowed').val(0);
        $('#edit-is-sms-notifications-allowed').val(0);
    }
});
$(document).on('click', '#edit-projects-checkbox', function (e) {
    if ($(this).is(':checked')) {
        $('#edit-is-projects-allowed').val(1);
    } else {
        $('#edit-is-projects-allowed').val(0);
    }
});
$(document).on('click', '#edit-tasks-checkbox', function (e) {
    if ($(this).is(':checked')) {
        $('#edit-is-tasks-allowed').val(1);
    } else {
        $('#edit-is-tasks-allowed').val(0);
    }
});
$(document).on('click', '#edit-calendar-checkbox', function (e) {
    if ($(this).is(':checked')) {
        $('#edit-is-calendar-allowed').val(1);
    } else {
        $('#edit-is-calendar-allowed').val(0);
    }
});
$(document).on('click', '#edit-chat-checkbox', function (e) {
    if ($(this).is(':checked')) {
        $('#edit-is-chat-allowed').val(1);
    } else {
        $('#edit-is-chat-allowed').val(0);
    }
});
$(document).on('click', '#edit-finance-checkbox', function (e) {
    if ($(this).is(':checked')) {
        $('#edit-is-finance-allowed').val(1);
    } else {
        $('#edit-is-finance-allowed').val(0);
    }
});
$(document).on('click', '#edit-users-checkbox', function (e) {
    if ($(this).is(':checked')) {
        $('#edit-is-users-allowed').val(1);
    } else {
        $('#edit-is-users-allowed').val(0);
    }
});
$(document).on('click', '#edit-clients-checkbox', function (e) {
    if ($(this).is(':checked')) {
        $('#edit-is-clients-allowed').val(1);
    } else {
        $('#edit-is-clients-allowed').val(0);
    }
});
$(document).on('click', '#edit-activity-logs-checkbox', function (e) {
    if ($(this).is(':checked')) {
        $('#edit-is-activity-logs-allowed').val(1);
    } else {
        $('#edit-is-activity-logs-allowed').val(0);
    }
});
$(document).on('click', '#edit-leave-requests-checkbox', function (e) {
    if ($(this).is(':checked')) {
        $('#edit-is-leave-requests-allowed').val(1);
    } else {
        $('#edit-is-leave-requests-allowed').val(0);
    }
});
$(document).on('click', '#edit-notes-checkbox', function (e) {
    if ($(this).is(':checked')) {
        $('#edit-is-notes-allowed').val(1);
    } else {
        $('#edit-is-notes-allowed').val(0);
    }
});
$(document).on('click', '#edit-mail-checkbox', function (e) {
    if ($(this).is(':checked')) {
        $('#edit-is-mail-allowed').val(1);
    } else {
        $('#edit-is-mail-allowed').val(0);
    }
});
$(document).on('click', '#edit-notification-checkbox', function (e) {
    if ($(this).is(':checked')) {
        $('#edit-is-notifications-allowed').val(1);
    } else {
        $('#edit-is-notifications-allowed').val(0);
    }
});
$(document).on('click', '#edit-language-checkbox', function (e) {
    if ($(this).is(':checked')) {
        $('#edit-is-languages-allowed').val(1);
    } else {
        $('#edit-is-languages-allowed').val(0);
    }
});

$(document).on('click', '#edit-announcements-checkbox', function (e) {
    if ($(this).is(':checked')) {
        $('#edit-is-announcements-allowed').val(1);
    } else {
        $('#edit-is-announcements-allowed').val(0);
    }
});
$(document).on('click', '#edit-sms-notifications-checkbox', function (e) {
    if ($(this).is(':checked')) {
        $('#edit-is-sms-notifications-allowed').val(1);
    } else {
        $('#edit-is-sms-notifications-allowed').val(0);
    }
});
var i = j;
$(document).on('click', '.add-tenure-item', function (e) {
    e.preventDefault();
    var tenure = $("#item_0_tenure").val();
    var months = $("#item_0_months").val();
    var rate = $("#item_0_rate").val();
    if (tenure != '' && rate != '') {
        i++;
        var html = '<div class="tenure-item py-1">' +
            '<input type="hidden" id=tenure_' + i + '_id name="tenure_id[]">' +
            '<div class="row">' +
            '<div class="col-md-4 custom-col">' +
            '<input type="text" class="form-control" name="tenure[]" id="item_' + i + '_tenure" placeholder="Ex. Weekly, Quarterly, Monthly, Yearly">' +
            '</div>' +
            '<div class="col-md-4 custom-col">' +
            '<select class="form-control" name="months[]" id="item_' + i + '_months">' +

            '<option value="1">1</option>' +
            '<option value="2">2</option>' +
            '<option value="3">3</option>' +
            '<option value="4">4</option>' +
            '<option value="5">5</option>' +
            '<option value="6">6</option>' +
            '<option value="7">7</option>' +
            '<option value="8">8</option>' +
            '<option value="9">9</option>' +
            '<option value="10">10</option>' +
            '<option value="11">11</option>' +
            '<option value="12">12</option>' +
            '<option value="13">13</option>' +
            '<option value="14">14</option>' +
            '<option value="15">15</option>' +
            '<option value="16">16</option>' +
            '<option value="17">17</option>' +
            '<option value="18">18</option>' +
            '<option value="19">19</option>' +
            '<option value="20">20</option>' +
            '<option value="21">21</option>' +
            '<option value="22">22</option>' +
            '<option value="23">23</option>' +
            '<option value="24">24</option>' +
            '<option value="25">25</option>' +
            '<option value="26">26</option>' +
            '<option value="27">27</option>' +
            '<option value="28">28</option>' +
            '<option value="29">29</option>' +
            '<option value="30">30</option>' +
            '<option value="31">31</option>' +
            '<option value="32">32</option>' +
            '<option value="33">33</option>' +
            '<option value="34">34</option>' +
            '<option value="35">35</option>' +
            '<option value="36">36</option>' +

            '</select>' +
            '</div>' +
            '<div class="col-md-3 custom-col">' +
            '<input type="number" class="form-control" name="rate[]" id="item_' + i + '_rate" min="0" placeholder="Rate">' +
            '</div>' +
            '<div class="col-md-1 custom-col">' +
            '<a href="#" class="btn btn-icon btn-danger remove-tenure-item" data-count=' + i + '><i class="fas fa-trash"></i></a>' +
            '</div>' +

            '</div>' +

            '</div>'
            ;
        $('#tenure_items').append(html);
        $('#item_' + i + '_tenure').val(tenure);
        $('#item_' + i + '_months').val(months);
        $('#item_' + i + '_rate').val(rate);


        $("#item_0_tenure").val('');
        $("#item_0_rate").val('');
    }
    else {
        iziToast.error({
            title: "Please fill required fields.",
            message: '',
            position: 'topRight'
        });
    }



});
$(document).on('click', '.remove-tenure-item', function (e) {
    e.preventDefault();
    var id = $(this).data('id');
    var deleted_tenure_ids = $("#deleted_tenure_ids").val();
    if (deleted_tenure_ids == '') {
        deleted_tenure_ids = id;
    } else {
        deleted_tenure_ids = deleted_tenure_ids + ',' + id;
    }
    $("#deleted_tenure_ids").val(deleted_tenure_ids);
    $(this).closest('.tenure-item').remove();
});