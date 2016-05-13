$(function () {
    $('#container').highcharts({
        chart: {
            type: 'column'
        },
        xAxis: {
            categories: []/*x轴的数据*/
        },
        title: {
            text: "一周内交换机宕机一览"

        },
        plotOptions: {
            series: {
                allowPointSelect: true
            }
        },
        series: [{
            data: []/*y的数据*/
        }]
    });
});