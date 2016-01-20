$(function () {
    var totalData = [
        {
            value: 5,
            color: "red",
            highlight: "#FF5A5E",
            label: "宕机数"
        },
        {
            value: 250,
            color: "#46BFBD",
            highlight: "#5AD3D1",
            label: "正常交换机数"
        }
    ];
    var ctx = document.getElementById("switch-total").getContext("2d");
    var chart = new Chart(ctx).Pie(totalData, {responsive: false});
    console.log(chart);
    $("#title").text(chart.total);

});