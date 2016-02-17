/**
 * Created by King-z on 2016/2/13 0013.
 */
$(function () {
    var randomScalingFactor = function () {
        return Math.round(Math.random() * 100)
    };
    var Linedata = [randomScalingFactor(), randomScalingFactor(), randomScalingFactor(), 0, randomScalingFactor(), -1, randomScalingFactor()];
    var $hid = $("#hidden");
    console.log($hid);

    /* $("#List").click(function(){
     if(window.innerWidth<767){
     $("#List ").attr('href',"singlestate.html");
     }

     })
     */
    var lineChartData = {
        labels: ["24", "23", "22", "21", "20", "19", "18", "17", "16", "15", "14", "13", "12", "11", "10", "9", "8", "7", "6", "5", "4", "3", "2", "1", "now"],
        datasets: [
            {
                label: "My First dataset",
                fillColor: "rgba(151,187,205,0.2)",
                strokeColor: "#31b0d5",
                pointColor: "rgba(151,187,205,1)",
                pointStrokeColor: "#fff",
                pointHighlightFill: "#fff",
                pointHighlightStroke: "rgba(220,220,220,1)",
                data: Linedata

            }
        ]

    };
    var ctx = document.getElementById("pingChart").getContext("2d");
    var myLine = new Chart(ctx).Line(lineChartData, {
        responsive: true
    });
    $("#List a[name='ipList']").each(function () {
        $(this).click(function () {
            if (window.innerWidth < 767) {
                $(this).attr('href', "singlestate.html");
            } else {
                console.log($(this).text());
                var ip = this.firstChild.data;
                alert("click: " + ip);
                myLine.update();


            }
        })
    });

});