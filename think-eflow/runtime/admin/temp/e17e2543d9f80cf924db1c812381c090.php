<?php /*a:1:{s:75:"C:\DATA\MyMotion\main\module\thinkphp-eflow\view\admin\wf\carbon\index.html";i:1741744529;}*/ ?>
<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="utf-8">
    <title>待阅</title>
    <link rel="stylesheet" href="/static/component/pear/css/pear.css"/>
    <link rel="stylesheet" href="/static/admin/css/reset.css"/>
    <style>
        .layui-table-view {
            border: none !important;
        }
    </style>
</head>

<body class="pear-container">

<!-- 顶部查询表单 -->
<div class="layui-card">
    <div class="layui-card-body">
        <form class="layui-form top-search-from">
            <div class="layui-form-item">
                <label class="layui-form-label">单据编号</label>
                <div class="layui-input-block">
                    <input type="text" name="business_no" value="" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">流程名称</label>
                <div class="layui-input-block">
                    <input type="text" name="display_name" value="" class="layui-input">
                </div>
            </div>


            <div class="layui-form-item layui-inline">
                <label class="layui-form-label"></label>
                <button class="pear-btn pear-btn-md pear-btn-primary" lay-submit lay-filter="table-query">
                    <i class="layui-icon layui-icon-search"></i>查询
                </button>
                <button type="reset" class="pear-btn pear-btn-md" lay-submit lay-filter="table-reset">
                    <i class="layui-icon layui-icon-refresh"></i>重置
                </button>
            </div>
            <div class="toggle-btn">
                <a class="layui-hide">展开<i class="layui-icon layui-icon-down"></i></a>
                <a class="layui-hide">收起<i class="layui-icon layui-icon-up"></i></a>
            </div>
        </form>
    </div>
</div>


<!-- 数据表格 -->
<div class="layui-card">
    <div class="layui-card-body">
        <table id="data-table" lay-filter="data-table"></table>
    </div>
</div>

<!-- 表格顶部工具栏 -->
<script type="text/html" id="table-toolbar">
</script>


<script type="text/html" id="table-bar">
    <button class="pear-btn pear-btn-xs tool-btn" lay-event="details" permission="app.wf.carbon.details">详情</button>
</script>

<script src="/static/component/layui/layui.js"></script>
<script src="/static/component/pear/pear.js"></script>
<script src="/static/admin/js/permission.js"></script>
<script src="/static/admin/js/common.js"></script>

<script>

    // 相关常量
    const PRIMARY_KEY = "id";
    const SELECT_API = "/admin/wf.carbon/select";

    const INSTANCE_DETAIL_URL = "/admin/wf.instance/detail";
    const INSTANCE_DETAIL_IDF_URL = "/admin/wf.instance/detail_idf";

    layui.use(['table', 'form', 'jquery', 'util', 'popup'], function () {
        let table = layui.table;
        let form = layui.form;
        let popup = layui.popup;
        let util = layui.util;
        let $ = layui.jquery;

        const state = {
            10: '进行中',
            20: '已完成',
            30: '已撤回',
            40: '强行终止',
            45: '已拒绝',
            50: '挂起',
            99: '已废弃'
        };


        let cols = [
            {
                title: 'ID',
                field: 'id',
                align: 'left',
                sort: true,
                hide: true,
                width: 300
            },
            {
                title: '单据编号',
                field: 'business_no',
                align: 'left',
                width: 180
            },
            {
                title: '流程名称',
                field: 'display_name',
                align: 'left',
                minWidth: 120
            },
            {
                title: '标题',
                field: 'ext',
                align: 'left',
                minWidth: 200,
                templet: function (d) {
                    let data = d['ext'] !== undefined ? d['ext'] : [];
                    let value = data['process_summary'] != undefined ? data['process_summary'] : '';
                    return value != '' ? value : '(无标题)';
                }
            },
            {
                title: '发起时间',
                field: 'create_time',
                align: 'left',
                width: 170
            },
            {
                title: '部门',
                field: 'ext',
                align: 'left',
                width: 140,
                hide: true,
                templet: function (d) {
                    let data = d['ext'] !== undefined ? d['ext'] : [];
                    let value = data['u_dept_name'] != undefined ? data['u_dept_name'] : '';
                    return value;
                }
            },
            {
                title: '职位',
                field: 'order_state',
                align: 'left',
                width: 140,
                hide: true,
                templet: function (d) {
                    let data = d['ext'] !== undefined ? d['ext'] : [];
                    let value = data['u_post_name'] != undefined ? data['u_post_name'] : '';
                    return value;
                }
            },
            {
                title: '状态',
                field: 'center',
                align: 'center',
                width: 80,
                templet: function (d) {
                    let field = "state";
                    let value = state[d[field]] || d[field];
                    let css = {
                        "进行中": "layui-bg-blue",
                        "已完成": "layui-bg-green",
                        "已撤回": "layui-bg-purple",
                        "强行终止": "layui-bg-red",
                        "已拒绝": "layui-bg-black",
                        "挂起": "layui-bg-gray",
                        "已废弃": "layui-bg-red"
                    }[value];
                    return '<span class="layui-badge ' + css + '">' + util.escape(value) + '</span>';
                }
            },
            {
                title: '流程版本',
                field: 'version',
                align: 'left',
                width: 150,
                hide: true
            },
            {
                title: '操作',
                toolbar: '#table-bar',
                align: "center",
                width: 170
            }
        ]


        function render() {
            table.render({
                elem: '#data-table',
                url: SELECT_API,
                page: true,
                cols: [cols],
                skin: "line",
                size: "lg",
                toolbar: "#table-toolbar",
                height: 'full-162',
                autoSort: false,
                parseData: function (ret) {
                    return {
                        "code": ret.code, // 解析接口状态
                        "msg": ret.msg, // 解析提示文本
                        "count": ret.data.total, // 解析数据长度
                        "data": ret.data.items // 解析数据列表
                    };
                },
                defaultToolbar: [{
                    title: "刷新",
                    layEvent: "refresh",
                    icon: "layui-icon-refresh",
                }, "filter", "print", "exports"]
            })
        }

        render();

        table.on('tool(data-table)', function (obj) {
            if (obj.event === 'details') {
                detail(obj)
            }
        });

        table.on('toolbar(data-table)', function (obj) {
            if (obj.event === 'refresh') {
                window.refreshTable()
            }
        });

        // 表格顶部搜索事件
        form.on("submit(table-query)", function (data) {
            table.reload("data-table", {
                page: {
                    curr: 1
                },
                where: data.field
            })
            return false;
        });

        // 表格顶部搜索重置事件
        form.on("submit(table-reset)", function (data) {
            table.reload("data-table", {
                where: []
            })
        });

        function detail(obj) {
            let instance_ext = obj.data.ext ?? {};
            let is_internal_form = instance_ext.cfg_is_internal_form !== undefined ? instance_ext.cfg_is_internal_form : false;
            if (is_internal_form) {
                //内置静态html表单
                parent.layui.admin.addTab(obj.data.process_instance_id, obj.data.process_name, INSTANCE_DETAIL_IDF_URL + "?id=" + obj.data.process_instance_id + "&instance_url=" + instance_ext.cfg_instance_url + '&operate=detail');
            } else {
                //动态表单
                parent.layui.admin.addTab(obj.data.process_instance_id, obj.data.display_name, INSTANCE_DETAIL_URL + "?id=" + obj.data.process_instance_id);
            }
        }

        /**
         * 刷新
         */
        window.refreshTable = function () {
            table.reloadData('data-table', {
                scrollPos: "fixed"
            });
        }

    });
</script>
</body>

</html>
