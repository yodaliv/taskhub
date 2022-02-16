"use strict";

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

if (max_storage_size != 0) {
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