$(function () {
    var ip;
    $("#selCmd").change(function (e) {
        var cmd = $(this).val();
        ip = this.name;
        toPage(cmd);
    });
    function toPage(num) {
        var url = "/index.php/Manage/command/ip/" + ip +
            "/cmd/" + num;
        var IntUrl = "/index.php/Manage/getInterface/ip/"+ip+"/cmd/"+num;
        var $toaInfo;
        var getInt = $("#selCmd :selected").data("int");
        if (getInt==1){
            $.ajax({
                url:IntUrl,
                success: function (data) {
                    $("#selIntModal").modal('show');
                    console.log(data);
                    document.getElementById('intList').options.length = 0;
                    for(i in data.data){
                        var Interface = new Option(data.data[i],i);
                        document.getElementById('intList').options.add(Interface);
                    }
                }
            });
            $("#intList").click(function () {
               if($(this+" :checked").text()=="全局"){

               }
            })
        }
        else {
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
        }
    };
});
