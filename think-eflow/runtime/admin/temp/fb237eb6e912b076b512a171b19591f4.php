<?php /*a:1:{s:79:"C:\DATA\MyMotion\think-eflow\view\admin\wf\common\track\template\timetable.html";i:1740722273;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>时间表</title>
    <link rel="stylesheet" href="/static/component/pear/css/pear.css"/>
    <link rel="stylesheet" href="/static/admin/css/reset.css"/>
    <style>
        .layui-table-view {
            border: none !important;
        }
    </style>
</head>
<body>
<div style="padding-left: 20px;padding-right: 20px">
    <table class="layui-hide" id="data-table" lay-filter="data-table"></table>
</div>

<script src="/static/component/layui/layui.js"></script>
<script src="/static/component/pear/pear.js"></script>
<script src="/static/admin/js/permission.js"></script>
<script src="/static/admin/js/common.js"></script>
<script>

    const SELECT_API = "/admin/wf.instance/approvalRecord";

    layui.use(['table', 'form', 'jquery', 'util', 'popup', 'common'], function () {
        let table = layui.table;
        let id = layui.url().search['id'] ?? '';//流程实例id

        let submitType = {
            0: '发起申请',
            1: '同意申请',
            2: '拒绝申请',
            3: '退回上一步',
            4: '跳转',
            5: '重新提交',
            6: '退回发起人',
            20: '拒绝申请'
        };

        let cols = [
            {
                title: '#',
                type: 'number',
                width: 60
            },
            {
                title: '步骤',
                field: 'display_name',
                align: 'left',
                width: 140,
            },
            {
                title: '处理人',
                field: 'display_name',
                align: 'left',
                width: 170,
                templet: function (d) {
                    let field = "u_real_name";
                    let value = d.variable[field] ?? '';
                    return value;
                }
            }, {
                title: '执行',
                field: 'submit_type',
                align: 'left',
                minWidth: 150,
                templet: function (d) {
                    let field = "submit_type";
                    let ext = d.variable ?? [];
                    let value = submitType[ext[field]] ?? '';
                    return value;
                }
            },
            {
                title: '处理意见',
                field: 'tf_approval_comment',
                align: 'left',
                minWidth: 150,
                templet: function (d) {
                    let field = "tf_approval_comment";
                    let ext = d.variable ?? [];
                    let value = ext[field] ?? '';
                    return value;
                }
            },
            {
                title: '开始时间',
                field: 'create_date',
                align: "center",
                width: 170,
            },
            {
                field: 'finish_date',
                title: '完成时间',
                align: "center",
                width: 170,
            }
        ];

        function render() {
            table.render({
                elem: "#data-table",
                url: SELECT_API,
                page: false,
                cols: [cols],
                skin: "line",
                size: "lg",
                where: {id: id},
                autoSort: false,
                parseData: function (ret) {
                    return {
                        "code": ret.code, // 解析接口状态
                        "msg": ret.msg, // 解析提示文本
                        "count": 0, // 解析数据长度
                        "data": ret.data // 解析数据列表
                    };
                },
                toolbar: false,
            })
        }

        render();
    });

</script>
</body>
</html>
