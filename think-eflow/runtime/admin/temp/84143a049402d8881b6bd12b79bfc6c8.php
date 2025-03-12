<?php /*a:1:{s:62:"C:\DATA\MyMotion\think-eflow\view\admin\wf\category\index.html";i:1740642624;}*/ ?>
<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="utf-8">
    <title>流程类型</title>
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
                <label class="layui-form-label">名称</label>
                <div class="layui-input-block">
                    <input type="text" name="name" value="" class="layui-input">
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
    <button class="pear-btn pear-btn-primary pear-btn-md" lay-event="add" permission="app.wf.category.insert">
        <i class="layui-icon layui-icon-add-1"></i>新增
    </button>
    <button class="pear-btn pear-btn-danger pear-btn-md" lay-event="batchRemove" permission="app.wf.category.delete">
        <i class="layui-icon layui-icon-delete"></i>删除
    </button>
</script>

<!-- 表格行工具栏 -->
<script type="text/html" id="table-bar">
    <button class="pear-btn pear-btn-xs tool-btn" lay-event="edit" >编辑</button>
    <button class="pear-btn pear-btn-xs tool-btn" lay-event="remove" permission="app.wf.category.delete">删除</button>
</script>

<script src="/static/component/layui/layui.js"></script>
<script src="/static/component/pear/pear.js"></script>
<script src="/static/admin/js/permission.js"></script>
<script src="/static/admin/js/common.js"></script>
<script>

    // 相关常量
    const PRIMARY_KEY = "id";
    const SELECT_API = "/admin/wf.category/select";
    const UPDATE_API = "/admin/wf.category/update";
    const DELETE_API = "/admin/wf.category/delete";
    const INSERT_URL = "/admin/wf.category/insert";
    const UPDATE_URL = "/admin/wf.category/update";

    // 字段 创建时间 created_at
    layui.use(["laydate"], function () {
        layui.laydate.render({
            elem: "#created_at",
            range: ["#created_at-date-start", "#created_at-date-end"],
        });
    })

    // 表格渲染
    layui.use(["table", "form", "common", "popup", "util"], function () {
        let table = layui.table;
        let form = layui.form;
        let $ = layui.$;
        let common = layui.common;
        let util = layui.util;

        // 表头参数
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
                title: '名称',
                field: 'name',
                align: 'left',
            },
            {
                field: 'icon', title: '图标', unresize: true, align: 'center',
                templet: function (d) {
                    return '<i class="' + d.icon + '"></i>';
                }
            },
            {
                title: '排序',
                field: 'sort',
                align: 'left',
                width: 90
            },
            {
                title: '更新时间',
                field: 'update_date',
                align: 'left',
                width: 170
            },
            {
                title: '操作',
                toolbar: '#table-bar',
                align: "center",
                minWidth: 190,
                width: 200,
            }
        ];


        // 渲染表格
        function render() {
            table.render({
                elem: "#data-table",
                url: SELECT_API,
                page: true,
                cols: [cols],
                skin: "line",
                size: "lg",
                toolbar: "#table-toolbar",
                autoSort: false,
                defaultToolbar: [{
                    title: "刷新",
                    layEvent: "refresh",
                    icon: "layui-icon-refresh",
                }, "filter", "print", "exports"],
                parseData: function (ret) {
                    return {
                        "code": ret.code, // 解析接口状态
                        "msg": ret.msg, // 解析提示文本
                        "count": ret.data.total, // 解析数据长度
                        "data": ret.data.items // 解析数据列表
                    };
                },
                done: function () {
                    layer.photos({photos: 'div[lay-id="data-table"]', anim: 5});
                }
            });
        }

        // 编辑或删除行事件
        table.on("tool(data-table)", function (obj) {
            if (obj.event === "remove") {
                remove(obj);
            } else if (obj.event === "edit") {
                edit(obj);
            }
        });

        // 表格顶部工具栏事件
        table.on("toolbar(data-table)", function (obj) {
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
                        if (res.code) {
                            return layui.popup.failure(res.msg);
                        }
                        return layui.popup.success("操作成功", refreshTable);
                    }
                })
            });
        }

        render();

        // 刷新表格数据
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
    })
</script>
</body>
</html>
