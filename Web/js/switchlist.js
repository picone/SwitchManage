/**
 * Created by King-z on 2016/2/13 0013.
 */
$(function () {
    var $hid = $("#hidden");
    console.log($hid);
    $("#List a").each(function () {
        $(this).click(function () {
            if (window.innerWidth < 767) {
                $(this).attr('href', "singlestate.html");
            } else {
                console.log($(this).text());
                var ip = this.firstChild.data;
                alert("click: " + ip);
            }
        })
    });
    /* $("#List").click(function(){
     if(window.innerWidth<767){
     $("#List ").attr('href',"singlestate.html");
     }

     })
     */
});