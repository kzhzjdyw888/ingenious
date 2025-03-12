<?php /*a:1:{s:80:"C:\DATA\MyMotion\think-eflow\view\admin\wf\common\task\handle\handleApprove.html";i:1740723530;}*/ ?>
<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="UTF-8">
    <title>审批</title>
    <link rel="stylesheet" href="/static/component/pear/css/pear.css"/>
    <link rel="stylesheet" href="/static/admin/css/reset.css"/>
    <style>
        .hidden {
            display: none;
        }
    </style>
</head>
<body>

<div class="layui-form" lay-filter="operating">
    <!--            内容-->
    <div class="mainBox" style="padding-bottom: 50px;">
        <div class="main-container mr-5">
            <div class="layui-form-item">
                <label class="layui-form-label required">审批意见</label>
                <div class="layui-input-block">
                    <textarea name="tf_approval_comment" placeholder="" class="layui-textarea"></textarea>
                </div>
            </div>

            <div class="layui-form-item">
                <div class="layui-input-block">
                    <input type="checkbox" name="tf_is_assign_next_node_operator" title="指定下一节点处理人" lay-filter="tf_is_assign_next_node_operator">
                    <input type="checkbox" name="tf_is_cc" title="是否抄送" lay-filter="tf_is_cc">
                    <input type="checkbox" name="tf_is_jump" title="是否跳转" lay-filter="tf_is_jump">
                </div>
            </div>

            <div class="layui-form-item hidden" id="tf_next_node_operator">
                <label class="layui-form-label">下一节点处理人</label>
                <div class="layui-input-block">
                    <div id="next_node_operator"></div>
                </div>
            </div>

            <div class="layui-form-item hidden" id="tf_cc_actors">
                <label class="layui-form-label">抄送给</label>
                <div class="layui-input-block">
                    <div id="cc_actors"></div>
                </div>
            </div>

            <div class="layui-form-item hidden" id="task_name">
                <label class="layui-form-label">跳转节点</label>
                <div class="layui-input-block">
                    <select name="task_name"></select>
                </div>
            </div>


        </div>
    </div>

    <!-- 底部按钮-->
    <div class="bottom">
        <div class="button-container">
            <button class="pear-btn pear-btn-primary pear-btn-md"
                    lay-active="consent">
                <i class="layui-icon layui-icon-ok"></i>
                同意
            </button>
            <button class="pear-btn pear-btn-md layui-bg-red" lay-active="refuse">
                <i class="layui-icon layui-icon-close"></i>
                拒绝
            </button>
            <button class="pear-btn pear-btn-md layui-bg-orange" lay-active="takeBefore">
                <i class="layui-icon layui-icon-return"></i>
                退回上一步
            </button>
            <button class="pear-btn pear-btn-md layui-bg-purple" lay-active="takeInitiator">
                <i class="layui-icon layui-icon-release"></i>
                退回发起人
            </button>
            <button class="pear-btn pear-btn-md" lay-active="jump">
                <i class="layui-icon layui-icon-circle-dot"></i>
                跳转
            </button>
        </div>
    </div>
</div>

<script src="/static/component/layui/layui.js"></script>
<script src="/static/component/pear/pear.js"></script>
<script src="/static/admin/js/permission.js"></script>
<script>

    const ASSIGNEE_SELECT_API = "/admin/wf.designer/assignee";//参与者
    const JUMP_ABLETASK_NAME_API = "/admin/wf.todo/jumpAbleTaskNameList";//跳转名称list

    const PRIMARY_KEY = "id";//taskId
    const PROCESS_INSTANCE_ID = "process_instance_id";//instanceId
    // 选择场景
    layui.use(["form", "util", "popup", "xmSelect", "jquery"], function () {
        let form = layui.form;
        let util = layui.util;
        let xmSelect = layui.xmSelect;
        let popup = layui.popup;
        let $ = layui.jquery;
        let process_task_id = layui.url().search[PRIMARY_KEY];
        let process_instance_id = layui.url().search[PROCESS_INSTANCE_ID];


        form.on('checkbox(tf_is_assign_next_node_operator)', function (data) {
            let elem = data.elem; // 获得 checkbox 原始 DOM 对象
            let checked = elem.checked; // 获得 checkbox 选中状态
            if (checked) {
                layui.$('#tf_next_node_operator').removeClass('hidden');
            } else {
                layui.$('#tf_next_node_operator').addClass('hidden');
            }
        });

        form.on('checkbox(tf_is_cc)', function (data) {
            let elem = data.elem; // 获得 checkbox 原始 DOM 对象
            let checked = elem.checked; // 获得 checkbox 选中状态
            if (checked) {
                layui.$('#tf_cc_actors').removeClass('hidden');
            } else {
                layui.$('#tf_cc_actors').addClass('hidden');
            }
        });

        form.on('checkbox(tf_is_jump)', function (data) {
            let elem = data.elem; // 获得 checkbox 原始 DOM 对象
            let checked = elem.checked; // 获得 checkbox 选中状态
            if (checked) {
                layui.$('#task_name').removeClass('hidden');
                //显示跳转下拉框
                getJumpAbleTaskNameList(process_instance_id)
            } else {
                layui.$('#task_name').addClass('hidden');
                let selectElem = $('select[name="task_name"]');
                selectElem.empty();
                form.render();
            }
        });

        /**
         * 获取跳转列表
         * @param processInstanceId
         */
        function getJumpAbleTaskNameList(processInstanceId) {
            let selectElem = $('select[name="task_name"]');
            // 清空现有选项
            selectElem.empty();
            // 添加空的“请选择”选项
            selectElem.append('<option value="">请选择</option>');
            layui.$.ajax({
                url: JUMP_ABLETASK_NAME_API,
                type: 'post',
                dataType: 'json',
                data: {process_instance_id: processInstanceId},
                success: function (ret) {
                    if (ret.code === 0) {
                        let data = ret.data != undefined ? ret.data : [];
                        $.each(data, function (index, item) {
                            let option = '<option value="' + item.value + '">' + item.label + '</option>';
                            selectElem.append(option);
                        });
                        form.render();
                    }
                },
                error: function (ret) {

                }
            });
        }


        /**
         * 处理任务
         */
        util.event('lay-active', {
            consent: function (othis) {
                let data = form.val('operating');
                data.submit_type = 1;
                if (verification(data)) {
                    window.parent.execute(data);
                }
                return;
            }
            , refuse: function (othis) {
                let data = form.val('operating');
                data.submit_type = 2;
                if (verification(data)) {
                    window.parent.execute(data);
                }
            }
            , takeBefore: function (othis) {
                let data = form.val('operating');
                data.submit_type = 3;
                if (verification(data)) {
                    window.parent.execute(data);
                }
            }
            , takeInitiator: function (othis) {
                let data = form.val('operating');
                data.submit_type = 6;
                if (verification(data)) {
                    window.parent.execute(data);
                }

            }
            , jump: function (othis) {
                let data = form.val('operating');
                data.submit_type = 4;
                if (verification(data)) {
                    if (data.task_name === '') {
                        popup.failure('请选择跳转节点');
                        return false;
                    }
                    window.parent.execute(data);
                }
            }
        });


        /**
         * 下一节点参与者
         */
        let nextNodeOperator = xmSelect.render({
            el: '#next_node_operator',
            name: 'tf_next_node_operator',
            filterable: true,
            remoteSearch: true,
            paging: true,//开启分页
            pageRemote: true,//远程分页
            pageEmptyShow: false,//没有数据不展示分页
            radio: true, // 设置为单选模式
            prop: {
                name: 'nickname',
                value: 'id',
            },
            remoteMethod: function (val, cb, show, pageIndex) {
                $.ajax({
                    url: ASSIGNEE_SELECT_API,
                    type: 'GET',
                    dataType: 'json',
                    data: {name: val, limit: 5, page: pageIndex},
                    success: function (ret) {
                        let data = ret.data['list'] != undefined ? ret.data['list'] : [];
                        let size = Math.ceil(ret.data['count'] / 5)
                        cb(data, size)
                    },
                    error: function (ret) {
                        cb([], 0)
                    }
                });
            },
            data: [],
            on: function (data) {
                let arr = data.arr
                let stringArr = []
                $.each(arr, function (index, item) {
                    stringArr.push(item.id)
                });
                $('input[name="tf_next_node_operator"]').val(stringArr.join(','))
            }
        });

        /**
         * 下一节点参与者
         */
        let ccActors = xmSelect.render({
            el: '#cc_actors',
            name: 'tf_cc_actors',
            filterable: true,
            remoteSearch: true,
            paging: true,//开启分页
            pageRemote: true,//远程分页
            pageEmptyShow: false,//没有数据不展示分页
            radio: false, // 设置为单选模式
            prop: {
                name: 'nickname',
                value: 'id',
            },
            remoteMethod: function (val, cb, show, pageIndex) {
                $.ajax({
                    url: ASSIGNEE_SELECT_API,
                    type: 'GET',
                    dataType: 'json',
                    data: {name: val, limit: 5, page: pageIndex},
                    success: function (ret) {
                        let data = ret.data['list'] != undefined ? ret.data['list'] : [];
                        let size = Math.ceil(ret.data['count'] / 5)
                        cb(data, size)
                    },
                    error: function (ret) {
                        cb([], 0)
                    }
                });
            },
            data: [],
            on: function (data) {
                let arr = data.arr
                let stringArr = []
                $.each(arr, function (index, item) {
                    stringArr.push(item.id)
                });
                $('input[name="tf_next_node_operator"]').val(stringArr.join(','))
            }
        });


        function verification(data) {
            if (data.submit_type == undefined || data.submit_type === '') {
                return false;
            }
            if (data.submit_type == undefined || data.tf_approval_comment === '') {
                popup.failure('请填写审批意见');
                return false;
            }

            if (data.tf_is_assign_next_node_operator !== undefined && data.tf_is_assign_next_node_operator !== '') {
                if (data.tf_next_node_operator === undefined || data.tf_next_node_operator === '') {
                    popup.failure('下一节点处理人不能为空');
                    return false;
                }
            }
            if (data.tf_is_cc !== undefined && data.tf_is_cc !== '') {
                if (data.tf_cc_actors === undefined || data.tf_cc_actors === '') {
                    popup.failure('抄送处理人不能为空');
                    return false;
                }
            }
            return true;
        }
    })
</script>

</body>
</html>
