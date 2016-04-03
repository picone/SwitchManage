$(function () {
    $("select[name='cmd']").change(function (e) {
        var cmd = $(this).val();

        switch (cmd) {
            case "1":
                alert("1");
                break;
            case "2" :
                alert("2");
                break;
            case "3" :
                alert("3");
                break;
            case "4" :
                alert("4");
                break;
        }

    })
});