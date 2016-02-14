$(function () {
    var c=$('#switch-total');
    var totalData = [
        {
            value: c.data('down'),
            color: "red",
            highlight: "#FF5A5E",
            label: "宕机数"
        },
        {
            value: c.data('up'),
            color: "#46BFBD",
            highlight: "#5AD3D1",
            label: "正常交换机数"
        }
    ];
    var ctx = document.getElementById("switch-total").getContext("2d");
    var chart = new Chart(ctx).Pie(totalData, {responsive: false,animateScale:true});
    console.log(chart);
    $("#title").text(chart.total);

});