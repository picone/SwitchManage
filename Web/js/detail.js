$(function () {
    var ip;
    $("#selCmd").change(function (e) {
        var cmd = $(this).val();
        ip = this.name;
        switch (cmd) {
            case "1":
                //alert("1");
                toPage(1);
                break;
            case "2" :
                //alert("2");
                toPage(2);
                break;
            case "3" :
                //alert("3");
                toPage(3);
                break;
            case "4" :
                //alert("4");
                toPage(4);
                break;
        }

    })
    function toPage(num) {
        var url = "/index.php/Manage/command/ip/" + ip +
            "/cmd/" + num;
        var $toaInfo;

        $.ajax({
            url: url,
            beforeSend: function () {
                $toaInfo = toastr.info("正在尝试连接", "", {timeOut: 60000});
            },
            success: function (data) {
                $('#result').html(data);
                toastr.clear($toaInfo);
                toastr.success("加载成功");
            },
            error: function () {
                toastr.error("连接超时");
                return false;
            }
        });
    };
});
