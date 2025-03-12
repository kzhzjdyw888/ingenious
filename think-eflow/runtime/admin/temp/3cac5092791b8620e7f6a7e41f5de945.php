<?php /*a:1:{s:63:"C:\DATA\MyMotion\think-eflow\view\admin\wf\task\done\index.html";i:1740711697;}*/ ?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>我的已办</title>
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
                    <input type="text" name="process_define_display_name" value="" class="layui-input">
                </div>
            </div>


            <div class="layui-form-item layui-inline">
                <label class="layui-form-label"></label>
                <button class="pear-btn pear-btn-md pear-btn-primary" lay-submit lay-filter="done-table-query">
                    <i class="layui-icon layui-icon-search"></i>查询
                </button>
                <button type="reset" class="pear-btn pear-btn-md" lay-submit lay-filter="done-table-reset">
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


<!-- 表格行工具栏 -->
<script type="text/html" id="table-bar">
    <button class="pear-btn pear-btn-xs tool-btn" lay-event="detail">查看历史</button>
</script>

<script src="/static/component/layui/layui.js"></script>
<script src="/static/component/pear/pear.js"></script>
<script src="/static/admin/js/permission.js"></script>
<script src="/static/admin/js/common.js"></script>

<script>
    // 相关常量
    const PRIMARY_KEY = "id";
    const SELECT_API = "/admin/wf.done/select";
    const INTERNAL_DOCUMENT_API = "/admin/wf.done/formernalDocument";

    const TASK_DETAIL_URL = "/admin/wf.done/detail";
    const TASK_DETAIL_IDF_URL = "/admin/wf.done/detail_idf";

    layui.use(['table', 'form', 'jquery', 'util', 'popup'], function () {
        let table = layui.table;
        let form = layui.form;
        let $ = layui.jquery;
        let popup = layui.popup;

        let state = {};

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
                field: 'instance_ext',
                align: 'left',
                minWidth: 180,
                templet: function (d) {
                    let data = d['instance'] !== undefined ? d['instance'] : [];
                    let value = data['business_no'] != undefined ? data['business_no'] : (d['business_no'] != undefined ? d['business_no'] : '');
                    return value != '' ? value : '';
                }

            },
            {
                title: '流程名称',
                field: 'process_define_display_name',
                align: 'left',
                minWidth: 200,
                templet: function (d) {
                    let instance = d['instance'] !== undefined ? d['instance'] : [];
                    let define = instance['define'] !== undefined ? instance['define'] : [];
                    let value = define['display_name'] != undefined ? define['display_name'] : '';
                    return value != '' ? value : '';

                }
            },
            {
                title: '标题',
                field: 'ext',
                align: 'left',
                templet: function (d) {
                    //这里可以优化优先级使用表单标题没有表单标题使用自动标题或者null
                    let instance = d['instance'] !== undefined ? d['instance'] : [];
                    let ext = instance['ext'] !== undefined ? instance['ext'] : [];
                    let value = ext['auto_gen_title'] != undefined ? ext['auto_gen_title'] : '';
                    return value != '' ? value : '';
                }
            },
            {
                title: '任务名称',
                field: 'display_name',
                align: 'left',
                templet: function (d) {
                    let task = d['task'] !== undefined ? d['task'] : [];
                    let value = task['display_name'] !== undefined ? task['display_name'] : '';
                    return value != '' ? value : '';

                }
            },
            {
                title: '发起人',
                field: 'u_real_name',
                align: 'left',
                minWidth: 120,
                templet: function (d) {
                    let instance = d['instance'] !== undefined ? d['instance'] : [];
                    let ext = instance['ext'] !== undefined ? instance['ext'] : [];
                    let value = ext['u_real_name'] != undefined ? ext['u_real_name'] : '';
                    return value != '' ? value : '';
                }
            },
           {
                title: '发起时间',
                field: 'instance_create_date',
                align: 'left',
                minWidth: 170,
                templet: function (d) {
                    let instance = d['instance'] !== undefined ? d['instance'] : [];
                    let value = instance['create_date'] !== undefined ? instance['create_date'] : '';
                    return value != '' ? value : '';
                }
            },
             {
                title: '完成时间',
                field: 'task_finish_date',
                align: 'left',
                minWidth: 170,
                templet: function (d) {
                    let task = d['task'] !== undefined ? d['task'] : [];
                    let value = task['finish_date'] !== undefined ? task['finish_date'] : '';
                    return value != '' ? value : '';
                }
            },
            {
                title: '操作',
                toolbar: '#table-bar',
                align: "center",
                minWidth: 140,
            }
        ]


        function render() {
            table.render({
                elem: "#data-table",
                url: SELECT_API,
                page: true,
                cols: [cols],
                skin: "line",
                size: "lg",
                autoSort: false,
                toolbar: "#table-toolbar",
                height: 'full-162',
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
            let title = obj?.data?.instance?.define?.display_name || ''
            if (obj.event === 'detail') {
                let instance_ext = obj.data.instance_ext ?? {};
                let is_internal_form = instance_ext.cfg_is_internal_form !== undefined ? instance_ext.cfg_is_internal_form : false;
                if (is_internal_form) {
                    //内置静态html表单
                    parent.layui.admin.addTab(obj.data.id, title, TASK_DETAIL_IDF_URL + "?id=" + obj.data.process_task_id + "&instance_url=" + instance_ext.cfg_instance_url + '&operate=done');
                } else {
                    //动态表单
                    parent.layui.admin.addTab(obj.data.id, title, TASK_DETAIL_URL + "?id=" + obj.data.process_task_id + '&operate=done');
                }
            }
        });

        table.on('toolbar(data-table)', function (obj) {
            if (obj.event === 'refresh') {
                window.refreshTable();
            }
        });

        // 头部搜索栏
        form.on('submit(data-table)', function (data) {
            table.reload('data-table', {
                page: {
                    curr: 1
                },
                where: data.field
            })
            return false;
        });

        /**
         * 获取内置表单
         * @param param
         * @returns {*[]}
         */
        let internalDocument = function (param) {
            let data = [];
            $.ajax({
                url: INTERNAL_DOCUMENT_API,
                type: 'POST',
                data: {instance_url: param},
                async: false,
                success: function (res) {
                    //渲染模板
                    data = res.data != undefined ? res.data : [];
                },
                error: function (xhr, status, error) {

                }
            });
            return data;
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
