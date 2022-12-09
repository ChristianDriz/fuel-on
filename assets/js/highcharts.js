$(function () {
    $.ajax({
        type: 'POST',
        url: 'assets/includes/getChartData-inc.php',
        data: {
            shopId: $('#input-shop-id').val(), status: $('#input-ord-status').val()
        },
        dataType: 'json',
        success: function (data) {
            console.log(data);
            initializeChart(data);
            // $.each(data, function (index, element) {
            //     $('body').append($('<div>', {
            //         text: element.name
            //     }));
            // });
        }
    });

    function initializeChart(chartData) {
        Highcharts.chart('chart', {
            chart: {
                type: 'column'
            },
            colors: ['#058DC7', '#50B432', '#ED561B', '#DDDF00', '#24CBE5', '#64E572', '#FF9655', '#FFF263', '#6AF9C4'],
            title: {
                text: 'Average Sales Monthly Report'
            },
            xAxis: {
                type: 'category',
                labels: {
                    formatter: function () {
                        return 'Month ' + this.axis.defaultLabelFormatter.call(this);
                    }            
                },
            },
            yAxis: {
                labels: {
                    formatter: function () {
                        return '₱' + this.axis.defaultLabelFormatter.call(this);
                    }            
                },
                min: 0,
                title: {
                    text: ''
                }
            },
            tooltip: {
                headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                    '<td style="padding:0"><b>{point.y:.1f}</b></td></tr>',
                footerFormat: '</table>',
                shared: true,
                useHTML: true
            },
            plotOptions: {
                column: {
                    pointPadding: 0.2,
                    borderWidth: 0,
                    colorByPoint: true
                }
            },
            series: chartData['series']
        });
    }
});