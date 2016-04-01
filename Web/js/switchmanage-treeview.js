/**
 * Created by King-z on 2016/1/29 0029.
 */


$(function () {
    var $tv;
    var countChecked;
    var revealSearch;
    /*var listData = [
        {
            text: "西区",
            nodes: [
                {
                    text: "西一",
                    nodes: [
                        {
                            text: "172.16.121.1"
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
                }
            ]
        }
    ];*/
    $.getJSON('/index.php/Manage/getTree',function(data){
        $tv = $('#listTree').treeview({
            data:data.data,
            showIcon: false,
            selectable: true,
            showCheckbox: true,
            tags: true,
            onNodeChecked: function (event, node) {
                if (node.nodes == undefined)
                    $('#checkable-output').append('<p name=' + node.text + '>' + node.text + '</p>');
                checkchild(event.target, node);
                countChecked = $("#checkable-output")[0].childElementCount;
                $("#btn-wait-select").attr('disabled', countChecked <= 0);

            },
            onNodeUnchecked: function (event, node) {
                if (node.nodes == undefined)
                    $('#checkable-output p[name="' + node.text + '"]').remove();
                uncheckchild(event.target, node);
                countChecked = $("#checkable-output")[0].childElementCount;
                $("#btn-wait-select").attr('disabled', countChecked <= 0);
            }
        });
    });
//初始化并失活按钮
//    $(".btn-wait-search").prop('disabled',true);
    $(".btn-wait-search").attr('disabled', true);
    $("#btn-wait-select").attr('disabled', true);

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

    //check child
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
    }

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
    var checkSel;
    /*选中 取消选中的按钮功能*/
    $("#btn-checkonselect").click(function () {
        checkSel = checkonselect("checkNode");
    });
    $("#btn-uncheckonselect").click(function () {
        checkSel = checkonselect('uncheckNode');
    });
    $("#btn-uncheakall").click(function () {
        checkSel = checkonselect('uncheckAll');
    });
    /*下一步按钮绑定*/
    $("#btn-wait-select").click(function () {
        alert("click");
        $check = document.getElementById('checkable-output');
        countChecked = $check.childElementCount;
        if (countChecked == 1) {
            var ipNum = $check.innerText;
            ipNum = ip2int(ipNum);
            document.getElementById('btn-wait-select').href = "Manage/detail/ip/" + ipNum;
        } else if (countChecked >= 2) {
            var ipNum = [];
            alert("选择了多台交换机");

        }

    })
});
function ip2int(ip) {
    var num = 0;
    ip = ip.split(".");
    num = Number(ip[0]) * 256 * 256 * 256 + Number(ip[1]) * 256 * 256 + Number(ip[2]) * 256 + Number(ip[3]);
    num = num >>> 0;
    return num;
}