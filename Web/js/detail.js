$(function () {
    $("select[name='cmd']").change(function (e) {
        var cmd = $(this).val();

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
});
function toPage(num) {
    $.ajax({
        url: num,
        beforeSend: function () {
            toastr.info("正在连接");
        },
        success: function (data) {
            if (data.hasOwnProperty("code")) {
                toastr.error(data.code.info);
                return false;
            }
            toastr.success("成功 正在跳转");
            window.location = num;
        },
        error: function (data) {
            console.log(data);
            toastr.error("无法连接");
            return false;
        }
    });
}