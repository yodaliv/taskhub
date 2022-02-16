
"use strict";
document.addEventListener('DOMContentLoaded', function () {
    var calendarEl = document.getElementById('calendar');

    var calendar = new FullCalendar.Calendar(calendarEl, {
        plugins: ['interaction', 'dayGrid', 'list'],

        header: {
            left: 'prev',
            center: 'title',
            right: 'next'
        },
        events: evnts,
        displayEventTime: false,
        editable: true,
        selectable: true,
        selectHelper: true,

        // don't show the time column in list view

        eventClick: function (info) {

            $.ajax({
                type: "POST",
                url: base_url + "admin/projects/get_project_by_id",
                data: "id=" + info.event.id + "&" + csrfName + "=" + csrfHash,
                dataType: "json",
                success: function (result) {
                    csrfName = result.csrfName;
                    csrfHash = result.csrfHash;
                    if (result.user_id != '' && result.user_id != null) {
                        var user_ids = result.user_id.split(',');
                        $('#update_users').val(user_ids);
                        $('#update_users').trigger('change');
                    }
                    if (result.client_id != '' && result.client_id != null) {
                        var client_ids = result.client_id.split(',');
                        $('#update_clients').val(client_ids);
                        $('#update_clients').trigger('change');
                    }
                    $('#update_status').val(result.status);
                    $('#update_status').trigger('change');
                    $('#update_title').val(result.title);
                    $('#update_budget').val(result.budget);
                    $('#update_description').val(result.description);
                    $('#update_id').val(result.id);
                    $('#update_end_date').val(result.end_date);
                    $('#update_start_date').val(result.start_date);

                    $(".modal-edit-project").trigger("click");

                }
            });

        },
        dateClick: function (info) {

            var start = moment(info.dateStr).format('YYYY-MM-DD');
            $('#start_date').val(start);
            $('#end_date').val(start);

            $("#modal-add-project").trigger("click");
        },
        select: function (info) {

            var startDate = moment(info.startStr).format('YYYY-MM-DD');
            var endDate = moment(info.endStr).format('YYYY-MM-DD');
            $('#start_date').val(startDate);
            $('#end_date').val(endDate);

            $("#modal-add-project").trigger("click");


        },
        eventDrop: function (info) {

            swal({
                title: 'Are you sure?',
                text: 'You want to update event',
                icon: 'warning',
                buttons: true,
                dangerMode: true,
            })
                .then((willDelete) => {
                    if (willDelete) {
                        var start = moment(info.event.start).format('DD-MMM-YYYY HH:mm:ss');
                        var end = moment(info.event.end).format('DD-MMM-YYYY HH:mm:ss');
                        if (end == 'Invalid date') {
                            end = start;
                        }
                        var id = info.event.id;
                        $.ajax({
                            type: "POST",
                            url: base_url + 'calendar/drag',
                            data: "id=" + id + "&start_date=" + start + "&end_date=" + end + "&" + csrfName + "=" + csrfHash,
                            dataType: "json",
                            success: function (result) {
                                location.reload();
                                csrfName = result.csrfName;
                                csrfHash = result.csrfHash;

                            }
                        });
                    } else {
                        info.revert();
                    }
                });
        },
    });
    calendar.render();
});
$('#fire-modal-31').on('hidden.bs.modal', function (e) {
    $(this).find('form').trigger('reset');
});