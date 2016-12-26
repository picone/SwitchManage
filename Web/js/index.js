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
                legend:{
                    enabled:false
                },
                tooltip: {
                    pointFormat:  '宕机: <b>{point.y} 台</b>'
                },
                title: {
                    text: "一周内交换机宕机一览"
                },
                plotOptions: {
                    series: {
                        cursor: 'pointer',
                        point: {
                            events: {
                                click: function () {
                                    location.href = "/index.php/Index/detail/day/"+this.category.replace("周","");
                                }
                            }
                        }
                    }
                },
                series: [{
                    name:"name",
                    data: yData,
                    dataLabels: {
                        enabled: true,
                        align: 'center',
                        y:5
                    }
                }]
            });

        }
    });
$("#search-btn").click(function () {
    var ip = $("#search-input").val();
    ip = ip2int(ip);
    window.location = "/index.php/Manage?ip=" + ip + "&cmd=1";

});
    $("#search-input").keydown(function(event) {
        if (event.keyCode == "13") {
            $("#search-btn").trigger('click');
        }});

});
function ip2int(ip) {
    var num = 0;
    ip = ip.split(".");
    num = Number(ip[0]) * 256 * 256 * 256 + Number(ip[1]) * 256 * 256 + Number(ip[2]) * 256 + Number(ip[3]);
    num = num >>> 0;
    return num;
}