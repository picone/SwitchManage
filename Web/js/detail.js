$(function () {
    var ip,cmd;
    var $toaInfo;

    $("#selCmd_btn").click(function () {
        cmd = $("#selCmd").val();
        ip = $("#selCmd")[0].name;
        var getInt = $("#selCmd :selected").data("int");
        if(getInt==1){
            loadInt();
        }else{
            toGlobal();
        }
    });
    $("#intList_btn").click(function () {
        if($("#intList").val()=="全局"){
            toGlobal();
        }else {
           toInterface();
        }
        $("#selIntModal").modal('hide');
    });
    function toGlobal(){
        var url = "/index.php/Manage/command/ip/" + ip +
            "/cmd/" + cmd;
        $.ajax({
            url: url,
            beforeSend: function () {
                toastr.clear();
                $toaInfo = toastr.info("正在尝试连接", "", {timeOut: 60000});
            },
            success: function (data) {
                $('#result').html(data);
                toastr.clear();
                $toaInfo= toastr.success("加载成功");
            },
            error: function () {
                $toaInfo = toastr.error("连接超时");
                return false;
            }
        });
    }
    function loadInt(){
        $("#selIntModal").modal();
        var IntUrl = "/index.php/Manage/getInterface/ip/"+ip+"/cmd/"+cmd;
        $.ajax({
            url:IntUrl,
            success: function (data) {
                console.log(data);
                document.getElementById('intList').options.length = 0;
                for(i in data.data){
                    var Interface = new Option(data.data[i],data.data[i]);
                    document.getElementById('intList').options.add(Interface);
                }
            }
        });
    }
    function toInterface(){
        var List;
        List = $("#intList").val();
        console.log(List);
        $.ajax({
            url:"/index.php/Manage/command/ip/" + ip +
            "/cmd/" + cmd+"?int="+List,
            success: function (data) {
                $('#result').html(data);
                toastr.clear();
                $toaInfo= toastr.success("加载成功");
            }
        })
    }
});
