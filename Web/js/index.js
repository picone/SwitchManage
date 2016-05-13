$(function () {
    $.ajax({
        url:"/index.php/index/getAvailability",
        success: function (data) {
            console.log(data);
            var xData=[];
            var yData=[];
            for(i in data.data){
                xData.push(data.data[i].dateline);
                yData.push(parseInt(data.data[i].num));
            }

            $('#container').highcharts({
                chart: {
                    type: 'column'
                },
                xAxis: {
                    categories: xData
                },
                yAxis: {
                   min:0,
                   title:{
                       text:"YDATA"
                   }
                },
                tooltip: {
                    pointFormat:  '宕机: <b>{point.y} 台</b>'

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
                    name:"name",
                    data: yData,
                    dataLabels: {
                        enabled: false,
                        rotation: -90,
                        color: '#FFFFFF',
                        align: 'right',
                        x: 4,
                        y: 10,
                        style: {
                            fontSize: '13px',
                            fontFamily: 'Verdana, sans-serif',
                            textShadow: '0 0 3px black'
                        }
                    }
                }]
            });

        }
    });
$("#search-btn").click(function () {
    var ip = $("#search-input").val();
    ip = ip2int(ip);
    window.location = "/index.php/Manage?ip=" + ip + "&cmd=1";

})
});
function ip2int(ip) {
    var num = 0;
    ip = ip.split(".");
    num = Number(ip[0]) * 256 * 256 * 256 + Number(ip[1]) * 256 * 256 + Number(ip[2]) * 256 + Number(ip[3]);
    num = num >>> 0;
    return num;
}