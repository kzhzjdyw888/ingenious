<?php /*a:1:{s:62:"C:\DATA\MyMotion\think-eflow\view\admin\wf\instance\index.html";i:1740712890;}*/ ?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>我的申请</title>
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
                <label class="layui-form-label">流水号</label>
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


<!-- 表格行工具栏 -->
<script type="text/html" id="table-bar">
    <button class="pear-btn pear-btn-xs tool-btn" lay-event="detail">详情</button>
    <button class="pear-btn pear-btn-xs tool-btn {{# if(d.state !== 10){ }}layui-btn-disabled{{# } }}" {{# if(d.state !== 10){ }}disabled{{# } }} lay-event="back">撤回</button>
    <button class="pear-btn pear-btn-xs tool-btn  {{# if(d.state == 10||d.state == 20){ }}layui-btn-disabled{{# } }}" lay-event="delete" {{# if(d.state== 10||d.state== 20){ }}disabled{{# } }}>删除</button>
</script>

<script src="/static/component/layui/layui.js"></script>
<script src="/static/component/pear/pear.js"></script>
<script src="/static/admin/js/permission.js"></script>
<script src="/static/admin/js/common.js"></script>

<script>
    // 相关常量
    const PRIMARY_KEY = "id";
    const PROCESS_TASK_ID = "process_task_id";
    const PROCESS_INSTANCE_ID = "process_instance_id";

    const SELECT_API = "/admin/wf.instance/select";
    const WITHDRAW_API = "/admin/wf.instance/withdraw";
    const DELETE_API = "/admin/wf.instance/delete";

    const INSTANCE_DETAIL_URL = "/admin/wf.instance/detail";
    const INSTANCE_DETAIL_IDF_URL = "/admin/wf.instance/detail_idf";

    layui.use(['table', 'form', 'jquery', 'common', 'popup', 'util'], function () {
            let table = layui.table;
            let form = layui.form;
            let $ = layui.jquery;
            let popup = layui.popup;
            let util = layui.util;
            let state = {
                10: '进行中',
                20: '已完成',
                30: '已撤回',
                40: '强制终止',
                45: '拒绝',
                50: '挂起',
                99: '已废弃',
            };


            let cols = [
                {
                    title: '序号',
                    type: 'numbers',
                    width: 60,
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
                    title: '流水号',
                    field: 'business_no',
                    align: 'left',
                    minWidth: 180
                },
                {
                    title: '流程名称',
                    field: 'process_name',
                    align: 'left'
                },
                {
                    title: '标题',
                    field: 'ext',
                    align: 'left',
                    minWidth: 140,
                    templet: function (d) {
                        let data = d['ext'] !== undefined ? d['ext'] : [];
                        let value = data['process_summary'] != undefined ? data['process_summary'] : '';
                        return value != '' ? value : '(无标题)';
                    }
                },
                {
                    title: '状态',
                    field: 'center',
                    align: 'center',
                    width: 110,
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
                    title: '发起人',
                    field: 'ext',
                    align: 'left',
                    width: 150,
                    templet: function (d) {
                        let data = d['ext'] !== undefined ? d['ext'] : [];
                        let value = data['u_real_name'] != undefined ? data['u_real_name'] : '';
                        return value;
                    }
                },
                {
                    title: '发起时间',
                    field: 'create_date',
                    align: 'left',
                    width: 170
                },
                {
                    title: '操作',
                    toolbar: '#table-bar',
                    align: "left",
                    width: 200,
                }
            ];


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

            render();

            table.on('tool(data-table)', function (obj) {
                if (obj.event === 'detail') {
                    detail(obj);
                } else if (obj.event === 'back') {
                    withdraw(obj);
                } else if (obj.event == 'delete') {
                    del(obj)
                }
            });

            table.on('toolbar(data-table)', function (obj) {
                if (obj.event === 'refresh') {
                    window.refreshTable()
                } else if (obj.event === 'batchRemove') {
                    batchRemove(obj);
                }
            });

            // 头部搜索栏
            form.on('submit(table-query)', function (data) {
                table.reload('data-table', {
                    page: {
                        curr: 1
                    },
                    where: data.field
                })
                return false;
            });


            /**
             * 实例详情
             * @param obj
             */
            function detail(obj) {
                let title = obj.data.display_name || '';
                let define = obj?.data?.define ?? {};
                let content = define?.content || {};
                let type = content?.instance_type || 1;//默认1动态json 表单
                let instanceUrl = content?.instance_url || '';
                if (type == 2) {
                    //内置html表单
                    parent.layui.admin.addTab(obj.data.id, obj.data.process_name, INSTANCE_DETAIL_IDF_URL + "?id=" + obj.data.id + "&instance_url=" + instanceUrl + '&operate=detail');
                } else {
                    //动态表单
                    parent.layui.admin.addTab(obj.data.id, obj.data.process_name, INSTANCE_DETAIL_URL + "?id=" + obj.data.id);
                }
            }

            /**
             * 撤回流程
             * @param obj
             */
            function withdraw(obj) {
                layer.confirm('此操作将撤回流程, 是否继续?', {
                    icon: 3,
                    title: '提示'
                }, function (index) {
                    $.ajax({
                        url: WITHDRAW_API,
                        type: 'POST',
                        dataType: 'json',
                        contentType: 'application/json',
                        data: JSON.stringify({id: obj.data['id']}),
                        success: function (ret) {
                            if (ret && ret.code == 0) {
                                popup.success(ret.msg, function () {
                                    window.refreshTable();
                                })
                            } else {
                                popup.failure(ret.msg);
                            }
                        },
                        error: function (ret) {
                            alert("出错" + ret.status + "：" + ret.responseText);
                        }
                    })
                });
            }


            /**
             * 删除指定数据
             * @param obj
             */
            function del(obj) {
                layer.confirm('此操作将永久删除该记录, 是否继续?', {
                    icon: 3,
                    title: '提示'
                }, function (index) {
                    $.ajax({
                        url: DELETE_API,
                        type: 'POST',
                        dataType: 'json',
                        contentType: 'application/json',
                        data: JSON.stringify({id: obj.data['id']}),
                        success: function (ret) {
                            if (ret && ret.code == 0) {
                                popup.success(ret.msg, function () {
                                    obj.del();
                                })
                            } else {
                                popup.failure(ret.msg);
                            }
                        },
                        error: function (ret) {
                            alert("出错" + ret.status + "：" + ret.responseText);
                        }
                    })
                });
            }

            /**
             * 刷新
             */
            window.refreshTable = function () {
                table.reloadData('data-table', {
                    scrollPos: "fixed"
                });
            }
        }
    )
</script>
</body>

</html>
