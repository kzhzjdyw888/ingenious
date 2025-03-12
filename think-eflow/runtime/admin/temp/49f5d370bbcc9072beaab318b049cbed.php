<?php /*a:1:{s:75:"C:\DATA\MyMotion\think-eflow\view\admin\wf\common\design\panel\process.html";i:1740701113;}*/ ?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>属性面板</title>
    <link rel="stylesheet" href="/static/component/pear/css/pear.css"/>
    <link rel="stylesheet" href="/static/admin/css/reset.css"/>
</head>

<body>
<div class="layui-tab layui-tab-brief layui-form" lay-filter="setting-form" id="setting-form">
    <ul class="layui-tab-title">
        <li class="layui-this" lay-id="1">流程属性</li>
    </ul>
    <div class="layui-tab-content">
        <div class="layui-tab-item layui-show">
            <div class="main-container">
                <h5>基本属性</h5>
                <div class="layui-form-item">
                    <label class="layui-form-label">唯一编码:</label>
                    <div class="layui-input-block">
                        <input type="text" lay-verify="required" placeholder="" autocomplete="off" name="name"
                               class="layui-input" readonly>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">显示名称:</label>
                    <div class="layui-input-block">
                        <input type="text" lay-verify="required" placeholder="" autocomplete="off"
                               name="display_name" class="layui-input" readonly>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">期望时间:</label>
                    <div class="layui-input-block">
                        <input type="text" lay-verify="required" placeholder="60s/1m/1h/1d 格式" autocomplete="off"
                               name="expire_time" class="layui-input">
                    </div>
                </div>

                <div class="layui-form-item">
                    <label class="layui-form-label">表单类型:</label>
                    <div class="layui-input-block">
                        <select name="instance_type" lay-filter="instance_type">
                            <option value="1">动态表单</option>
                            <option value="2">静态表单</option>
                        </select>
                    </div>
                </div>

                <div class="layui-form-item">
                    <label class="layui-form-label">表单url:</label>
                    <div class="layui-input-block">
                        <select name="instance_url" lay-filter="instance_url">
                            <option value="">请选择</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!--layui依赖-->
<script src="/static/component/layui/layui.js"></script>
<script src="/static/component/pear/pear.js"></script>
<script src="/static/admin/js/permission.js"></script>
<script src="/static/admin/js/common.js"></script>
<script src="/static/component/logicflow/customized/properties.js"></script>
<script>
    const PROCESS_APPROVE_FORM_API = "/admin/wf.designer/formOptions";

    window.child = function (obj) {
        layui.use(['element', 'jquery', 'form'], function () {
            let $ = layui.jquery;
            let form = layui.form;
            let newObj = initializationData(obj)

            //渲染单据
            getForms(newObj.instance_type ?? 1);
            form.val('setting-form', newObj)
            form.render();

            /**
             * 监听input change事件
             */
            $(function () {
                $("#setting-form input").off("change").change(function () {
                    let newData = form.val('setting-form');
                    newData.type = 'ingenious:process'
                    propertyKeysSet(newData)
                })
            })


            function getForms(param) {
                let selectElem = $('select[name="instance_url"]');
                // 清空现有选项
                selectElem.empty();
                // 添加空的“请选择”选项
                selectElem.append('<option value="">请选择</option>');
                $.ajax({
                    url: PROCESS_APPROVE_FORM_API,
                    type: 'GET',
                    dataType: 'json',
                    data: {instance_type: param ?? 0},
                    async: false,
                    success: function (ret) {
                        let data = ret.data != undefined ? ret.data : [];
                        $.each(data, function (index, item) {
                            let option = '<option value="' + item.value + '">' + item.label + '</option>';
                            selectElem.append(option);
                        });
                    },
                    error: function (ret) {
                    }
                });
            }

            form.on('select(instance_type)', function (data) {
                let newData = form.val('setting-form');
                newData.type = 'ingenious:process'
                newData.instance_url = '';//切换类型清空表单选项
                $('[name="instance_url"]').val('');
                form.render();
                getForms(data.value)
                form.render();
                propertyKeysSet(newData)
            });

            form.on('select(instance_url)', function (data) {
                let newData = form.val('setting-form');
                newData.type = 'ingenious:process'
                propertyKeysSet(newData)
            });
        })
    };
</script>


</body>

</html>
