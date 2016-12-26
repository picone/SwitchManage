/**
 * Created by King-z on 2016/1/29 0029.
 */
$(function () {
    var $tv;
    var revealSearch;

    //toastr.warning("暂时使用预设交换机列表");
    var listData = [
        {
            text: "西区",
            nodes: [
                {
                    text: "西一",
                    tags: ['东边'],
                    nodes: [
                        {
                            text: "172.16.121.1",
                            tags: ['一楼']
                        },
                        {
                            text: "172.16.121.2"
                        },
                        {
                            text: "172.16.121.3"
                        },
                        {
                            text: "172.16.121.4"
                        },
                        {
                            text: "172.16.121.5"
                        }
                    ]
                },
                {
                    text: "西二",
                    nodes: [
                        {
                            text: "172.16.122.1"
                        },
                        {
                            text: "172.16.122.2"
                        },
                        {
                            text: "172.16.122.3"
                        },
                        {
                            text: "172.16.122.4"
                        },
                        {
                            text: "172.16.122.5"
                        },
                        {
                            text: "172.16.122.6"
                        },
                        {
                            text: "172.16.122.7"
                        },
                        {
                            text: "172.16.122.8"
                        },
                        {
                            text: "172.16.122.9"
                        },
                        {
                            text: "172.16.122.10"
                        },
                        {
                            text: "172.16.122.11"
                        },
                        {
                            text: "172.16.122.12"
                        },
                        {
                            text: "172.16.122.13"
                        },
                        {
                            text: "172.16.122.14"
                        },
                        {
                            text: "172.16.122.15"
                        },
                        {
                            text: "172.16.122.16"
                        },
                        {
                            text: "172.16.122.17"
                        },
                        {
                            text: "172.16.122.18"
                        },
                        {
                            text: "172.16.122.19"
                        },
                        {
                            text: "172.16.122.20"
                        },
                        {
                            text: "172.16.122.21"
                        }

                    ]
                },
                {
                    text: "西三",
                    nodes: [
                        {
                            text: "172.16.123.1"
                        },
                        {
                            text: "172.16.123.2"
                        }
                    ]
                }
            ]
        },
        {
            text: "东区",
            nodes: [
                {
                    text: "东一",
                    nodes: [
                        {
                            text: "172.16.101.1"
                        },
                        {
                            text: "172.16.101.2"
                        },
                        {
                            text: "172.16.101.3"
                        },
                        {
                            text: "172.16.101.4"
                        },
                        {
                            text: "172.16.101.5"
                        }
                    ]
                },
                {
                    text: "东二",
                    nodes: [
                        {
                            text: "172.16.102.1"
                        },
                        {
                            text: "172.16.102.2"
                        },
                        {
                            text: "172.16.102.3"
                        },
                        {
                            text: "172.16.102.4"
                        },
                        {
                            text: "172.16.102.5"
                        }
                    ]
                },
                {
                    text: "东十四",
                    nodes: [
                        {
                            text: "172.16.115.1"
                        },
                        {
                            text: "172.16.115.2"
                        },
                        {
                            text: "172.16.115.3"
                        },
                        {
                            text: "172.16.115.4"
                        },
                        {
                            text: "172.16.115.5"
                        }
                    ]
                }
            ]
        }
    ];
    $.getJSON('/index.php/Manage/getTree',function(data){
    //    console.log(data);
    $tv = $('#listTree').treeview({
        data: data.data,
        //data: listData,
        showIcon: false,
        selectable: true,
        tags: true,
        showTags: true,
        levels: 1,
        //color: '#9D9D9D',
        //onhoverColor: 'gray',
        //backColor: '#222222',
        onNodeSelected: function (even, node) {
            var isChild = ifchild(node);
            //$(".nodeToggle").attr('disabled', isChild);
            //$("#btn-wait-select").attr('disabled', !isChild);
            //if (isChild) $('#checkable-output').append('<p name=' + node.text + '>' + node.text + '</p>');
            if (isChild) {
                connectCmd(node.text);
                $("#left_nav_btn").trigger('click');
            }else{
                $('#listTree').treeview('expandNode',node.nodeId);
            }
        },
        onNodeUnselected: function (even, node) {
            if(!ifchild(node))
                $(this).treeview('collapseNode',node.nodeId);

            //$('#checkable-output p[name="' + node.text + '"]').remove();
        }

        });
    });
//初始化并失活按钮
    $(".btn-wait-search").attr('disabled', true);
    $('.nodeToggle').attr('disabled', true);
    $("#btn-wait-select").attr('disabled', true);
    /*左边导航栏激活事件绑定*/
    $("#left_nav_btn").click(function () {
        /*       console.log($("#left_nav").hasClass("show-left_nav"));
         console.log($("#left_nav").prop('class'));*/
        $("#left_nav").toggleClass("show-left_nav");
        $("#left_nav_btn span").toggleClass("glyphicon-chevron-left");
        $("#left_nav_btn span").toggleClass("glyphicon-chevron-right");

    });

    var search = function (e) {
        var content = $('#search').val();
        var options = {
            ignoreCase: true,
            exactMatch: false,
            revealResults: true
        };
        var results = $tv.treeview('search', content);
        console.log(content, results);
        return results;
    };

    $('#search').on('keyup', function (e) {
        revealSearch = search();
        $(".btn-wait-search").prop('disabled', revealSearch.length <= 0);
    });
    //获得子代id [array]
    function getid(child) {
        var arr = [];
        for (var i = 0; i < child.length; i++) {
            arr.push(child[i].nodeId);
        }
        return arr;
    }

    function ifchild(node) {
        if (node.nodes) return false;
        else return true;
    }
    /*如果是ip地址 不是楼栋 return true*/
    //search btn
    $("#btn-cResult").click(function () {
        revealSearch = search();
        console.log(revealSearch);
        $("#listTree").treeview('checkNode', [revealSearch]);
    });
    $("#btn-uncResult").click(function () {
        revealSearch = search();
        $("#listTree").treeview('uncheckNode', [revealSearch]);
    });
    var checkonselect = function (state) {
        var selNode = $("#listTree").treeview('getSelected');
        $("#listTree").treeview(state, selNode);
        console.dir("mode:" + state);
        console.dir(selNode);
    };
    /*展开收缩按钮绑定*/
    $('#btn-collapse').click(function () {
        var selected = $tv.treeview('getSelected');
        $tv.treeview('collapseNode', selected);
    });
    $('#btn-expand').click(function () {
        var selected = $tv.treeview('getSelected');
        $tv.treeview('expandNode', selected);
    });
    /*下一步按钮绑定*/
    /*请求尝试*/
    $("#btn-wait-select").click(function () {
        console.log($(this).attr('disabled'));
        if (!$(this).attr('disabled')) {
            var selected = $tv.treeview('getSelected');
            console.log(selected[0].text);
            if (ifchild(selected)) {
                var ipNum = selected[0].text;
                ipNum = ip2int(ipNum);
                $.ajax({
                    url: "Manage/connect/ip/" + ipNum,
                    timeout: 5000,
                    beforeSend: function () {
                        toastr.info("正在连接");
                    },
                    success: function (data) {
                        if (data.code != 1) {
                            toastr.error(data.msg);
                            return false;
                        } else {
                            window.location = "Manage/detail/ip/" + ipNum + "/cmd/1";
                        }
                    },
                    error: function (data) {
                        console.log(data);
                        toastr.error("请求超时");
                        return false;
                    }
                });
            }
        }
        else {
            document.getElementById('btn-wait-select').href = "#";
        }
    })
    /*废弃function*/
/*    //check child
    function checkchild(tid, checknode) {
        var array = [];
        if (checknode.nodes) array = getid(checknode.nodes);
        $(tid).treeview('checkNode', [array]);
    }

    //uncheck child
    function uncheckchild(tid, checknode) {
        var array = [];
        if (checknode.nodes) array = getid(checknode.nodes);
        $(tid).treeview('uncheckNode', [array]);
    }*/
});
/*测试接口连接情况*/
function connectCmd(ipNum) {
    ipNum = ip2int(ipNum);
    $.ajax({
        url: "Manage/connect/ip/" + ipNum,
        timeout: 5000,
        beforeSend: function () {
            toastr.info("正在连接");
        },
        success: function (data) {
            if (data.code == 1) {
               loadCmd(ipNum);
            } else {
                toastr.error(data.msg);
                return false;
                //window.location = "Manage/detail/ip/" + ipNum + "/cmd/1";

            }
        },
        error: function (data) {
            console.log(data);
            toastr.error("请求超时");
            return false;
        }
    });
}
function loadCmd(ip){
    var url = "Manage/detail/ip/" + ip +
        "/cmd/1";
    $('#cmdDetail').html("");
    $("#contentCmd").load(url);

}
function ip2int(ip) {
    var num = 0;
    ip = ip.split(".");
    num = Number(ip[0]) * 256 * 256 * 256 + Number(ip[1]) * 256 * 256 + Number(ip[2]) * 256 + Number(ip[3]);
    num = num >>> 0;
    return num;
}