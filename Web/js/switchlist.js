/**
 * Created by King-z on 2016/2/13 0013.
 */
$(function () {
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

    $("#List a[name='ipList']").each(function () {
        $(this).click(function () {
            if (window.innerWidth < 767) {
                //$(this).attr('href', "singlestate.html");
            } else {

                var ipId = this.dataset.ip;
                alert("click: " + ipId);

                $.ajax({
                    url: "List/getDetail/ip/" + ipId,
                    success: function (History) {
                        console.log(History);
                        History = {
                            "code": 0,
                            "data": [{"dateline": "1456753501", "val": "4.72"}, {
                                "dateline": "1456753557",
                                "val": "2.60"
                            }, {"dateline": "1456753617", "val": "3.62"}, {
                                "dateline": "1456753677",
                                "val": "2.29"
                            }, {"dateline": "1456753737", "val": "10.60"}, {
                                "dateline": "1456753797",
                                "val": "3.01"
                            }, {"dateline": "1456753857", "val": "4.84"}, {
                                "dateline": "1456753918",
                                "val": "7.38"
                            }, {"dateline": "1456753978", "val": "6.33"}, {
                                "dateline": "1456754038",
                                "val": "3.00"
                            }, {"dateline": "1456754098", "val": "10.30"}, {
                                "dateline": "1456754158",
                                "val": "10.50"
                            }, {"dateline": "1456754218", "val": "10.80"}, {
                                "dateline": "1456754278",
                                "val": "8.74"
                            }, {"dateline": "1456754338", "val": "3.84"}, {
                                "dateline": "1456754398",
                                "val": "2.76"
                            }, {"dateline": "1456754458", "val": "3.59"}, {
                                "dateline": "1456754518",
                                "val": "9.14"
                            }, {"dateline": "1456754578", "val": "5.22"}, {
                                "dateline": "1456754638",
                                "val": "2.13"
                            }, {"dateline": "1456754698", "val": "10.50"}, {
                                "dateline": "1456754758",
                                "val": "2.99"
                            }, {"dateline": "1456754818", "val": "11.50"}, {
                                "dateline": "1456754878",
                                "val": "9.62"
                            }, {"dateline": "1456754938", "val": "8.12"}, {
                                "dateline": "1456754998",
                                "val": "2.60"
                            }, {"dateline": "1456755058", "val": "5.66"}, {
                                "dateline": "1456755118",
                                "val": "3.19"
                            }, {"dateline": "1456755178", "val": "11.50"}, {"dateline": "1456755238", "val": "3.09"}]
                        };
                        var Hisdata = History.data;
                        console.log(History);
                        Highcharts.setOptions({
                            global: {
                                useUTC: false
                            }
                        });

                        $('#pingChart').highcharts({
                            chart: {
                                type: 'spline',
                                animation: Highcharts.svg, // don't animate in old IE
                                marginRight: 10,
                                events: {
                                    load: function () {
                                        // set up the updating of the chart each second
                                        var series = this.series[0];
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
                                text: 'Live Ping Data'
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
                                name: 'Random data',
                                data: (function () {
                                    // generate an array of random data
                                    var data = [],
                                        time = (new Date()).getTime(),
                                        i = -19;
                                    console.log(Hisdata);
                                    for (i in Hisdata) {
                                        data.push({
                                            x: Hisdata[i].dateline * 1000.0,
                                            y: Hisdata[i].val * 1.0
                                        })
                                    }
                                    console.log(data);
                                    console.log(time);
                                    return data;
                                }())
                            }]
                        });
                        //
                    }
                })




            }
        })
    });
    function formatdate(time) {

    }
});