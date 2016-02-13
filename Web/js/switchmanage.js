/**
 * Created by King-z on 2016/1/26 0026.
 */
$(function () {
    var chosenData;
    $("#next").click(function () {
        var list = [];
        $("input[name='switch']:checkbox").each(function () {
            if (this.checked) {
                list.push(this.value);
            }
            chosenData = list.join(",");
        });
        alert("选择了" + list.length + "台交换机:" + chosenData);

    });
    /*
     jQuery.each(jQuery("input[name='switch']:checkbox"),function (i,item){
     jQuery(item).change(function(){

     })
     });*/


});
