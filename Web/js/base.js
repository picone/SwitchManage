/**
 * Created by King-z on 2016/3/9 0009.
 */
$(function () {
    $('#changePwd').click(function () {

    });
$('#pwdBtn').click(function () {
    var oldPwd = $('#oldPwd').val();
    var newPwd1 = $("#newPwd").val(),
        newPwd2 = $('#confirmNewPwd').val();
   if(newPwd1==newPwd2){
       $.ajax({
           type:'post',
           url:'/index.php/Login/changePassword?',
           data:{
               password:newPwd1
           },
           dataType : "JSON",
           success:function(data){
               console.log(data);
               toastr.info(data.msg);
               $("#formPwd input").val("");
               $("#cpwdmodal").modal('hide');
           }
       })
   }else {
       toastr.error("两次密码不一样");
   }

});


});
