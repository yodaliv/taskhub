"use strict";
$('#plan_type').on('change', function (e) {
    var plan_type = $('#plan_type').val();
    if (plan_type == 'free') {
        $('#monthly_price').hide();
        $('#yealy_price').hide();
    } else {
        $('#monthly_price').show();
        $('#yealy_price').show();
    }
});

function queryParams(p) {
    return {
        "package_type": $('#plan_type').val(),
        "status": $('#status').val(),
        limit: p.limit,
        sort: p.sort,
        order: p.order,
        offset: p.offset,
        search: p.search
    };
}
function queryParams1(p) {
    return {
        "type": $('#type').val(),
        limit: p.limit,
        sort: p.sort,
        order: p.order,
        offset: p.offset,
        search: p.search
    };
}
$('#filter').on('click', function (e) {
    e.preventDefault();
    $('#subscription_list').bootstrapTable('refresh');
});
$('#filter_btn').on('click', function (e) {
    e.preventDefault();
    $('#package_list').bootstrapTable('refresh');
});
$('#create_package_form').validate({
    rules: {
        title: "required",
        sequence_no: "required",
        description: "required"
    }
});
$('#edit_package_form').validate({
    rules: {
        title: "required",
        sequence_no: "required",
        monthly_price: "required",
        yealy_price: "required",
        description: "required"
    }
});
$(document).on('click', '#ckbCheckAll', function (e) {
    $(".checkBoxClass").prop('checked', $(this).prop('checked'));
});
$(document).on('click', '#editckbCheckAll', function (e) {
    $(".checkBoxClass").prop('checked', $(this).prop('checked'));
});
$('#create_package_form').on('submit', function (e) {
    e.preventDefault();
    var formData = new FormData(this);
    formData.append(csrfName, csrfHash);
    if ($("#create_package_form").validate().form()) {
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
$(document).on('click', '#ckbCheckAll', function (e) {
    if ($(this).is(':checked')) {
        $('#is-projects-allowed').val(1);
        $('#is-tasks-allowed').val(1);
        $('#is-calendar-allowed').val(1);
        $('#is-chat-allowed').val(1);
        $('#is-finance-allowed').val(1);
        $('#is-users-allowed').val(1);
        $('#is-clients-allowed').val(1);
        $('#is-activity-logs-allowed').val(1);
        $('#is-leave-requests-allowed').val(1);
        $('#is-notes-allowed').val(1);
        $('#is-settings-allowed').val(1);
        $('#is-languages-allowed').val(1);
        $('#is-mail-allowed').val(1);
        $('#is-announcements-allowed').val(1);
        $('#is-notifications-allowed').val(1);
        $('#is-sms-notifications-allowed').val(1);
    } else {
        $('#is-projects-allowed').val(0);
        $('#is-tasks-allowed').val(0);
        $('#is-calendar-allowed').val(0);
        $('#is-chat-allowed').val(0);
        $('#is-finance-allowed').val(0);
        $('#is-users-allowed').val(0);
        $('#is-clients-allowed').val(0);
        $('#is-activity-logs-allowed').val(0);
        $('#is-leave-requests-allowed').val(0);
        $('#is-notes-allowed').val(0);
        $('#is-settings-allowed').val(0);
        $('#is-languages-allowed').val(0);
        $('#is-mail-allowed').val(1);
        $('#is-announcements-allowed').val(0);
        $('#is-notifications-allowed').val(0);
        $('#is-sms-notifications-allowed').val(1);
    }
});
$(document).on('click', '#projects-checkbox', function (e) {
    if ($(this).is(':checked')) {
        $('#is-projects-allowed').val(1);
    } else {
        $('#is-projects-allowed').val(0);
    }
});
$(document).on('click', '#tasks-checkbox', function (e) {
    if ($(this).is(':checked')) {
        $('#is-tasks-allowed').val(1);
    } else {
        $('#is-tasks-allowed').val(0);
    }
});
$(document).on('click', '#calendar-checkbox', function (e) {
    if ($(this).is(':checked')) {
        $('#is-calendar-allowed').val(1);
    } else {
        $('#is-calendar-allowed').val(0);
    }
});
$(document).on('click', '#chat-checkbox', function (e) {
    if ($(this).is(':checked')) {
        $('#is-chat-allowed').val(1);
    } else {
        $('#is-chat-allowed').val(0);
    }
});
$(document).on('click', '#finance-checkbox', function (e) {
    if ($(this).is(':checked')) {
        $('#is-finance-allowed').val(1);
    } else {
        $('#is-finance-allowed').val(0);
    }
});
$(document).on('click', '#users-checkbox', function (e) {
    if ($(this).is(':checked')) {
        $('#is-users-allowed').val(1);
    } else {
        $('#is-users-allowed').val(0);
    }
});
$(document).on('click', '#clients-checkbox', function (e) {
    if ($(this).is(':checked')) {
        $('#is-clients-allowed').val(1);
    } else {
        $('#is-clients-allowed').val(0);
    }
});
$(document).on('click', '#activity-logs-checkbox', function (e) {
    if ($(this).is(':checked')) {
        $('#is-activity-logs-allowed').val(1);
    } else {
        $('#is-activity-logs-allowed').val(0);
    }
});
$(document).on('click', '#leave-requests-checkbox', function (e) {
    if ($(this).is(':checked')) {
        $('#is-leave-requests-allowed').val(1);
    } else {
        $('#is-leave-requests-allowed').val(0);
    }
});
$(document).on('click', '#notes-checkbox', function (e) {
    if ($(this).is(':checked')) {
        $('#is-notes-allowed').val(1);
    } else {
        $('#is-notes-allowed').val(0);
    }
});
$(document).on('click', '#mail-checkbox', function (e) {
    if ($(this).is(':checked')) {
        $('#is-mail-allowed').val(1);
    } else {
        $('#is-mail-allowed').val(0);
    }
});
$(document).on('click', '#announcements-checkbox', function (e) {
    if ($(this).is(':checked')) {
        $('#is-announcements-allowed').val(1);
    } else {
        $('#is-announcements-allowed').val(0);
    }
});
$(document).on('click', '#languages-checkbox', function (e) {
    if ($(this).is(':checked')) {
        $('#is-languages-allowed').val(1);
    } else {
        $('#is-languages-allowed').val(0);
    }
});

$(document).on('click', '#notifications-checkbox', function (e) {
    if ($(this).is(':checked')) {
        $('#is-notifications-allowed').val(1);
    } else {
        $('#is-notifications-allowed').val(0);
    }
});
$(document).on('click', '#sms-notifications-checkbox', function (e) {
    if ($(this).is(':checked')) {
        $('#is-sms-notifications-allowed').val(1);
    } else {
        $('#is-sms-notifications-allowed').val(0);
    }
});

var i = 0;
$(document).on('click', '.add-tenure-item', function (e) {
    e.preventDefault();
    var tenure = $("#item_0_tenure").val();
    var months = $("#item_0_months").val();
    var rate = $("#item_0_rate").val();
    if (tenure != '' && rate != '') {
        i++;
        var html = '<div class="tenure-item py-1">' +
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
            '<a href="#" class="btn btn-icon btn-danger remove-tenure-item"><i class="fas fa-trash"></i></a>' +
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
    $(this).closest('.tenure-item').remove();
});
$(document).on('click', '.submit_btn', function (e) {
    e.preventDefault();
    var package_id = $(this).attr("data-id")
    $("#package_id").val();
});
function update_amount(count) {
    var id = $('#tenure_' + count + '_price').val();
    if (id != '') {
        $.ajax({
            type: 'POST',
            url: base_url + 'admin/billing/get_tenure_by_id/' + id,
            data: csrfName + '=' + csrfHash,
            dataType: "json",
            success: function (result) {
                csrfName = csrfName;
                csrfHash = result.csrfHash;
                $('.tenure_' + count + '_price').html(result.rate);
                $('.tenure_' + count + '_months').html(result.months);
            }
        });
    } else {
        $('.item_' + count + '_tax_title').html('');
    }
}

$(document).on('click', '.remove-tenure-item', function (e) {
    e.preventDefault();
    $(this).closest('.tenure-item').remove();
});

$(document).on('click', '.remove-tenure-item', function (e) {
    e.preventDefault();
    $(this).closest('.tenure-item').remove();
});
if(max_storage_size!=0){
    var ctx = document.getElementById("piechart").getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'pie',
        data: {
            datasets: [{
                data: [
                    storageFree,
                    storageUsed,                
                ],
    
                backgroundColor: [
                    '#63ed7a',                
                    '#fc544b'
                ],
                label: 'Dataset 1'
            }],
            labels: [
                'Free',
                'Used'
            ],
        },
        options: {
            responsive: true,
            legend: {
                position: 'bottom',
            },
        }
    });
}


