"use strict";
var ctx = document.getElementById("myChart").getContext('2d');
var myChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: labels,
        datasets: [{
            label: '',
            data: data,
            borderWidth: 2,
            backgroundColor: bg_colors,
            borderColor: bg_colors,
            borderWidth: 2.5,
            pointBackgroundColor: '#ffffff',
            pointRadius: 4
        }]
    },
    options: {
        legend: {
            display: false
        },
        scales: {
            yAxes: [{
                gridLines: {
                    drawBorder: false,
                    color: '#f2f2f2',
                },
                ticks: {
                    beginAtZero: true,
                    stepSize: 3000
                }
            }],
            xAxes: [{
                gridLines: {
                    display: false
                }
            }]
        },
    }
});
var ctx = document.getElementById("earning_chart").getContext('2d');
var myChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: dates,
        datasets: [{
            label: 'Total sale in ('+currency_symbol+')',
            data: amounts,
            borderWidth: 2,
            backgroundColor: '#6777ef',
            borderColor: '#6777ef',
            borderWidth: 2.5,
            pointBackgroundColor: '#ffffff',
            pointRadius: 4
        }]
    },
    options: {
        legend: {
            display: false
        },
        scales: {
            yAxes: [{
                gridLines: {
                    drawBorder: false,
                    color: '#f2f2f2',
                },
                ticks: {
                    beginAtZero: true,
                    stepSize: 1000
                }
            }],
            xAxes: [{
                ticks: {
                    display: false
                },
                gridLines: {
                    display: false
                }
            }]
        },
    }
});
var ctx = document.getElementById("piechart").getContext('2d');
var myChart = new Chart(ctx, {
    type: 'pie',
    data: {
        datasets: [{
            data:
                data
            ,
            backgroundColor:
                bg_colors
            ,
            label: 'Dataset 1'
        }],
        labels:
            labels
        ,
    },
    options: {
        responsive: true,
        legend: {
            position: 'bottom',
        },
    }
});

