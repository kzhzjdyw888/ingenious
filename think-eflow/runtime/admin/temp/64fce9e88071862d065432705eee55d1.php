<?php /*a:1:{s:58:"C:\DATA\MyMotion\think-eflow\view\admin\wf\form\index.html";i:1740644480;}*/ ?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>表单设计</title>
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
                <label class="layui-form-label">唯一编码</label>
                <div class="layui-input-block">
                    <input type="text" name="name" value="" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">名称</label>
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
    <button class="pear-btn pear-btn-primary pear-btn-md" lay-event="add" permission="app.wf.form.insert">
        <i class="layui-icon layui-icon-add-1"></i>新增
    </button>
    <button class="pear-btn pear-btn-danger pear-btn-md" lay-event="batchRemove" permission="app.wf.form.delete">
        <i class="layui-icon layui-icon-delete"></i>删除
    </button>
</script>

<!-- 表格行工具栏 -->
<script type="text/html" id="table-bar">
    <button class="pear-btn pear-btn-xs tool-btn" lay-event="edit" permission="app.wf.form.update">编辑</button>
    <button class="pear-btn pear-btn-xs tool-btn" lay-event="design" permission="app.wf.form.design">设计</button>
    <button class="pear-btn pear-btn-xs tool-btn" lay-event="preview" permission="app.wf.form.preview">预览</button>
    <button class="pear-btn pear-btn-xs tool-btn" lay-event="remove" permission="app.wf.form.delete">删除</button>
</script>

<script src="/static/component/layui/layui.js?v=2.8.12"></script>
<script src="/static/component/pear/pear.js"></script>
<script src="/static/admin/js/permission.js"></script>
<script src="/static/admin/js/common.js"></script>

<script>
    // 相关常量
    const PRIMARY_KEY = "id";
    const SELECT_API = "/admin/wf.form/select";
    const UPDATE_API = "/admin/wf.form/update";
    const DELETE_API = "/admin/wf.form/delete";
    const INSERT_URL = "/admin/wf.form/insert";
    const UPDATE_URL = "/admin/wf.form/update";
    const DESIGN_URL = "/admin/wf.form/design";
    const PREVIEW_URL = "/admin/wf.form/preview";

    layui.use(['table', 'form', 'jquery', 'util', 'popup', 'common'], function () {
        let table = layui.table;
        let form = layui.form;
        let $ = layui.jquery;
        let popup = layui.popup;
        let common = layui.common;

        let state = {};

        let cols = [
            {
                type: 'checkbox'
            },
            {
                title: 'ID',
                field: 'id',
                align: 'left',
                sort: true,
                hide: true,
                width: 300
            },
            {
                title: '唯一编码',
                field: 'name',
                align: 'left',
                width: 140,
            },
            {
                title: '表单名称',
                field: 'display_name',
                align: 'left',
                width: 170
            },
            {
                title: '表单描述',
                field: 'description',
                align: 'left',
                minWidth: 150
            },
            {
                title: '操作',
                toolbar: '#table-bar',
                align: "center",
                width: 190,
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

        table.on('tool(data-table)', function (obj) {
            if (obj.event === "remove") {
                remove(obj);
            } else if (obj.event === "edit") {
                edit(obj);
            } else if (obj.event === 'design') {
                design(obj)
            } else if (obj.event === "preview") {
                preview(obj)
            }
        });

        table.on('toolbar(data-table)', function (obj) {
            if (obj.event === "add") {
                add();
            } else if (obj.event === "refresh") {
                refreshTable();
            } else if (obj.event === "batchRemove") {
                batchRemove(obj);
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

        // 表格排序事件
        table.on("sort(data-table)", function (obj) {
            table.reload("data-table", {
                initSort: obj,
                scrollPos: "fixed",
                where: {
                    field: obj.field,
                    order: obj.type
                }
            });
        });

        // 表格新增数据
        let add = function () {
            layer.open({
                type: 2,
                title: "新增",
                shade: 0.1,
                area: [common.isModile() ? "100%" : "500px", common.isModile() ? "100%" : "450px"],
                content: INSERT_URL
            });
        }

        // 表格编辑数据
        let edit = function (obj) {
            let value = obj.data[PRIMARY_KEY];
            layer.open({
                type: 2,
                title: "修改",
                shade: 0.1,
                area: [common.isModile() ? "100%" : "500px", common.isModile() ? "100%" : "450px"],
                content: UPDATE_URL + "?" + PRIMARY_KEY + "=" + value
            });
        }

        let design = function (obj) {
            let value = obj.data[PRIMARY_KEY];
            top.layer.open({
                type: 2,
                title: false,
                closeBtn: 0,
                shade: 0.1,
                area: [common.isModile() ? "100%" : "100%", common.isModile() ? "100%" : "100%"],
                content: DESIGN_URL + "?" + PRIMARY_KEY + "=" + value
                // content: DESIGN_URL
            });
        }


        /**
         * 表单预览
         * @param obj
         */
        let preview = function (obj) {
            let value = obj.data[PRIMARY_KEY];
            layer.open({
                type: 2,
                title: obj.data.display_name ? obj.data.display_name + '【预览】' : '表单预览',
                shade: 0.1,
                area: [common.isModile() ? "100%" : "900px", common.isModile() ? "100%" : "450px"],
                content: PREVIEW_URL + "?" + PRIMARY_KEY + "=" + value
            });


        }

        // 删除一行
        let remove = function (obj) {
            return doRemove(obj.data[PRIMARY_KEY]);
        }

        // 删除多行
        let batchRemove = function (obj) {
            let checkIds = common.checkField(obj, PRIMARY_KEY);
            if (checkIds === "") {
                layui.popup.warning("未选中数据");
                return false;
            }
            doRemove(checkIds.split(","));
        }

        // 执行删除
        let doRemove = function (ids) {
            let data = {};
            data[PRIMARY_KEY] = ids;
            layer.confirm("确定删除?", {
                icon: 3,
                title: "提示"
            }, function (index) {
                layer.close(index);
                let loading = layer.load();
                $.ajax({
                    url: DELETE_API,
                    data: data,
                    dataType: "json",
                    type: "post",
                    success: function (res) {
                        layer.close(loading);
                        if (res.code !== 0) {
                            return layui.popup.failure(res.msg);
                        }
                        return layui.popup.success("操作成功", refreshTable);
                    }
                })
            });
        }

        window.refreshTable = function () {
            table.reloadData("data-table", {
                scrollPos: "fixed",
                done: function (res, curr) {
                    if (curr > 1 && res.data && !res.data.length) {
                        curr = curr - 1;
                        table.reloadData("data-table", {
                            page: {
                                curr: curr
                            },
                        })
                    }
                }
            });
        }

        render();
    })
</script>
</body>

</html>
