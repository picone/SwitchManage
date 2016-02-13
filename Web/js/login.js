/**
 * Created by King-z on 2016/1/20 0020.
 */
$(function () {
    $('#form').on('submit',function(e){
        e.preventDefault();
        $(this).ajaxSubmit(function(data){
            if(data.code==1){
                window.location=data.data.url;
            }else{
                alert(data.msg);
                return false;
            }
        });
    });
});