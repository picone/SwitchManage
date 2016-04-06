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
                $('#login-result').attr('class', "alert alert-danger");

                $('#login-result').text(data.msg);
                //alert(data.msg);
                $('#verify_img').click();
                switch (data.code) {
                    case 3:
                        $('#verify_code').val('');
                        $('#password').val("").focus();
                        break;
                    case 5:
                        $('#verify_code').val('').focus();
                        break;
                    default :
                        break;
                }
                return false;
            }
        });
    });
});