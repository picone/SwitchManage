/**
 * Created by King-z on 2016/2/13 0013.
 */
$(function () {
    /*备忘：记得做成动态！！！！！！！*/
    var randomScalingFactor = function () {
        return Math.round(Math.random() * 100)
    };
    var Linedata = [randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), 0, randomScalingFactor(), -1, randomScalingFactor()];
    Highcharts.setOptions({
        global: {
            useUTC: false
        },
        lang: {
            resetZoom: "重置"
        }
    });
    var SelectedList;
    var trl, currtrl;
    $("#List a[name='ipList']").each(function () {

        $(this).click(function () {
            if (window.innerWidth < 2) {
                //$(this).attr('href', "#myModal");
            } else {
                //$("#myModal").modal('show');
                if (SelectedList) SelectedList.attr('style', 'background-color:#fff');
                $(this).attr('style', 'background-color:#E6E6E6');
                SelectedList = $(this);
                var ipId = $(this).attr("data-ip");
                var ip = this.firstChild.textContent;
                var newUrl = "List/getDetail/ip/" + ipId + "/time/50";
                var $pingChart;
                $.ajax({
                    url: "List/getDetail/ip/" + ipId,
                    beforeSend: function () {
                        $('#pingChart').highcharts({
                            title: {
                                text: ip +
                                '最近Ping值'
                            }
                        });
                        $pingChart = $('#pingChart').highcharts();
                        $pingChart.showLoading("正在从服务器获取数据......");
                    },
                    success: function (History) {
                        var Hisdata = History.data;
                        console.log("获取到的数据");
                        console.log(Hisdata);
                        console.log(trl);
                        clearInterval(trl);
                        clearInterval(currtrl);
                        console.log(trl);

                        $('#pingChart').highcharts({
                            chart: {
                                type: 'line',
                                animation: Highcharts.svg, // don't animate in old IE
                                marginRight: 10,
                                zoomType: 'x',
                                pinchType: 'x',
                                panning: true,
                                panKey: 'shift',
                                events: {
                                    load: function () {
                                        var series = this.series[0];
                                        trl = setInterval(function () {
                                            $.getJSON(newUrl, function (data) {
                                                console.log("开始进行动态增加");
                                                console.log(data);
                                                var newPoint = data.data[0];
                                                series.addPoint([newPoint.dateline * 1000.0, newPoint.val * 1.0], true, true);
                                                console.log("这轮增加结束");
                                            });
                                        }, 60000);
                                    }
                                }
                            },
                            title: {
                                text: ip +
                                '最近Ping值'
                            },
                            xAxis: {
                                type: 'datetime'
                            },
                            yAxis: {
                                title: {
                                    text: '时延'
                                },
                                labels: {
                                    formatter: function () {
                                        if (this.value >= 0) return this.value + 'ms';

                                    }
                                },
                                plotBands: [{
                                    color: '#F5DDDD',
                                    from: 0,
                                    to: -500
                                }]
                            },
                            subtitle: {
                                text: '在下方拖动放大区间,放大后拖动或按shift移动区间'
                            },
                            tooltip: {
                                crosshairs: true,
                                formatter: function () {
                                    return '<b>' + Highcharts.dateFormat('%m-%d %H:%M', this.x) + '</b><br/>' +
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
                                name: '延时',
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
                        $("#current-btn").click(function () {
                            currentPing(ipId, ip);
                        });
                        /*暂时测试*/
                        /*         test = $('#pingChartModal').highcharts({
                         chart: {
                         type: 'spline',
                         animation: Highcharts.svg, // don't animate in old IE
                         marginRight: 10,
                         renderTo: 'pingChartModal',
                         reflow:true,
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
                         tickPixelInterval:300
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
                         return data;
                         }())
                         }]
                         });*/
                    }
                });
                $(this).attr('href', "#showChart");
                /*  $("#myModal").on('shown.bs.modal', function () {
                 $('#pingChartModal').highcharts().reflow();
                 })*/
            }
        });
    });
    function currentPing(ipId, ip) {
        console.log("commint to clear" + currtrl);
        console.log("has benn cleared" + currtrl);
        var url = "Manage/ping?ip=" + ipId;
        $.ajax({
            url: url,
            success: function (currdata) {
                $('#currChart').highcharts({
                    chart: {
                        type: 'spline',
                        animation: Highcharts.svg, // don't animate in old IE
                        marginRight: 10,
                        panning: true,
                        events: {
                            load: function () {
                                var series = this.series[0];
                                clearInterval(currtrl);
                                currtrl = setInterval(function () {
                                    $.getJSON(url, function (data) {
                                        console.log("开始进行动态增加Ping");
                                        console.log(data);
                                        var x = (new Date().getTime());
                                        var y = data.data * 1.0;
                                        series.addPoint([x, y], true, true);
                                    });
                                }, 5000);
                                console.log(ipId + "的计时器是" + currtrl);
                            }
                        }
                    },
                    title: {
                        text: ip +
                        '最近Ping值'
                    },
                    xAxis: {
                        type: 'datetime',
                        startOnTick: true
                    },
                    yAxis: {
                        title: {
                            text: '时延'
                        },
                        labels: {
                            formatter: function () {
                                if (this.value >= 0) return this.value + 'ms';

                            }
                        },
                        plotBands: [{
                            color: '#F5DDDD',
                            from: 0,
                            to: -500
                        }]
                    },
                    tooltip: {
                        crosshairs: true,
                        formatter: function () {
                            return '<b>' + Highcharts.dateFormat('%m-%d %H:%M', this.x) + '</b><br/>' +
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
                        name: '延时',
                        data: (function () {
                            var data = [];
                            var i;


                            for (i = -10; i<=0; i++) {
                                data.push({
                                    x: (new Date()).getTime() + i * 1000,
                                    y: null
                                })
                            }
                            data.push({
                                x: (new Date()).getTime(),
                                y: currdata.data * 1.0
                            });
                            return data;
                        }())
                    }]
                });


            }
        })
    }
});

