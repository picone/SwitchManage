/**
 * Created by King-z on 2016/2/13 0013.
 */
$(function () {
    /*备忘：记得做成动态！！！！！！！*/
    var randomScalingFactor = function () {
        return Math.round(Math.random() * 100)
    };
    var Linedata = [randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), 0, randomScalingFactor(), -1, randomScalingFactor()];
    //var $hid = $("#hidden");
    //console.log($hid);

    /* $("#List").click(function(){
     if(window.innerWidth<767){
     $("#List ").attr('href',"singlestate.html");
     }

     })
     */
    Highcharts.setOptions({
        global: {
            useUTC: false
        }
    });
    $('#pingChartModal').highcharts();
    $("#List a[name='ipList']").each(function () {
        var test;

        $(this).click(function () {
            if (window.innerWidth < 2) {
                //$(this).attr('href', "#myModal");
            } else {
                $("#myModal").modal('show');
                var ipId = $(this).attr("data-ip");
                var ip = this.firstChild.textContent;
                var url =  "List/getDetail/ip/" + ipId;
                $.ajax({
                    url: url,
                    beforeSend: function () {
                    },
                    success: function (History) {
                        var Hisdata = History.data;
                        console.log("获取到的数据");
                        console.log(Hisdata);
                        $('#pingChart').highcharts({
                            chart: {
                                type: 'spline',
                                animation: Highcharts.svg, // don't animate in old IE
                                marginRight: 10,
                                zoomType: 'x',
                                pinchType:'x',
                                panning: true,
                                panKey: 'shift',
                                events: {
                                    load: function () {
                                        var series = this.series[0];
                                        setInterval(function () {
                                            $.getJSON(url, function (data) {
                                                console.log("开始进行动态增加");
                                                var newPoint = data.data.pop();
                                                series.addPoint([newPoint.dateline*1000.0,newPoint.val*1.0],true,true);
                                                console.log("这轮增加结束");
                                            });
                                        },60000)
                                    }
                                }
                            },
                            title: {
                                text: ip +
                                '最近Ping值'
                            },
                            xAxis: {
                                type: 'datetime',
                                tickPixelInterval: 150,
                                maxZoom: 300000// fourteen days
                            },
                            yAxis: {
                                title: {
                                    text: 'Value'
                                },
                                plotLines: [{
                                    value: 0.2,
                                    width: 1,
                                    color: '#FF0000'
                                }]
                            },
                            subtitle: {
                                text: '在下方拖动放大区间,放大后拖动或按shift移动区间'
                            },
                            tooltip: {
                                formatter: function () {
                                    return '<b>' + this.series.name + '</b><br/>' +
                                        Highcharts.dateFormat('%Y-%m-%d %H:%M:%S', this.x) + '<br/>' +
                                        Highcharts.numberFormat(this.y, 2);
                                }
                            },
                            legend: {
                                enabled: false
                            },
                            exporting: {
                                enabled: false
                            },
                            series: [{
                                name: '数值',
                                pointInterval: 24 * 3600 * 1000,
                                pointStart: Date.UTC(Hisdata[0].dateline * 1000.0),
                                data: (function () {
                                    var data = [];
                                    for (i in Hisdata) {
                                        data.push({
                                            x: Hisdata[i].dateline * 1000.0,
                                            y: Hisdata[i].val * 1.0
                                        })
                                    }
                                    return data;
                                }())
                            }]
                        });
                        /*暂时测试*/
                        test = $('#pingChartModal').highcharts({
                            chart: {
                                type: 'spline',
                                animation: Highcharts.svg, // don't animate in old IE
                                marginRight: 10,
                                events: {
                                    load: function () {
                                        // set up the updating of the chart each second
                                        //var series = this.series[0];
                                        //setInterval(function () {
                                        //    var x = (new Date()).getTime(), // current time
                                        //        y = -1;
                                        //    console.log("time");
                                        //    series.addPoint([x, y], true, true);
                                        //}, 60000);
                                    }
                                }
                            },
                            title: {
                                text: ip +
                                '最近Ping值'
                            },
                            xAxis: {
                                type: 'datetime',
                                tickPixelInterval: 150
                            },
                            yAxis: {
                                title: {
                                    text: 'Value'
                                },
                                plotLines: [{
                                    value: 0.2,
                                    width: 1,
                                    color: '#FF0000'
                                }]
                            },
                            tooltip: {
                                formatter: function () {
                                    return '<b>' + this.series.name + '</b><br/>' +
                                        Highcharts.dateFormat('%Y-%m-%d %H:%M:%S', this.x) + '<br/>' +
                                        Highcharts.numberFormat(this.y, 2);
                                }
                            },
                            legend: {
                                enabled: false
                            },
                            exporting: {
                                enabled: false
                            },
                            series: [{
                                name: '数值',
                                data: (function () {
                                    var data = [];
                                    console.log(Hisdata);
                                    for (i in Hisdata) {
                                        data.push({
                                            x: Hisdata[i].dateline * 1000.0,
                                            y: Hisdata[i].val * 1.0
                                        })
                                    }
                                    //console.log(data);
                                    //console.log(time);
                                    return data;
                                }())
                            }]
                        });
                    }
                });
                $(this).attr('href', "#showChart");
                $("#myModal").on('shown.bs.modal', function () {
                    $('#pingChartModal').highcharts().reflow();
               })
            }
        });
    });
});