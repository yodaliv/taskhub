"use strict";
google.charts.load('current', {
    'packages': ['corechart']
});
google.charts.setOnLoadCallback(drawPieChart);

function drawPieChart() {

    var data1 = google.visualization.arrayToDataTable([
        ['Product', 'Count'],

        temp
    ]);
    var options1 = {
        title: '',
        is3D: true
    };

    var chart1 = new google.visualization.PieChart(document.getElementById('piechart'));

    chart1.draw(data1, options1);
}
google.charts.load('current', {
    'packages': ['bar']
});
google.charts.setOnLoadCallback(drawChart);

function drawChart() {
    var data = google.visualization.arrayToDataTable([
        ['Date', 'Total Sale In <?= get_currency_symbol() ?>'],
        barchartdata
    ]);
    var options = {
        chart: {
            title: 'Weekly Sale',
            subtitle: 'Total Sale In Last Week (Month: <?php echo date("M"); ?>)',
        }
    };
    var chart = new google.charts.Bar(document.getElementById('earning_chart'));
    chart.draw(data, google.charts.Bar.convertOptions(options));
}
