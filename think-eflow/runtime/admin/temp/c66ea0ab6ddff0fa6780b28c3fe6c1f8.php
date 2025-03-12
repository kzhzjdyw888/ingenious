<?php /*a:1:{s:68:"C:\DATA\MyMotion\think-eflow\view\admin\wf\common\luminar\index.html";i:1740646565;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=0,maximum-scale=0,user-scalable=yes,shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>表单设计器</title>
    <link rel="stylesheet" href="/static/component/luminar/component/luminar/css/luminar.css"/>
</head>

<body class="layui-layout-body">
<div style="height: 100%; width: 100%;" id="formdesigner">

</div>

<!--引入layui模块-->
<script type="text/javascript" src="/static/component/luminar/component/layui/layui.js"></script>
<script type="text/javascript" src="/static/component/luminar/component/luminar/luminar.js"></script>
<!--外部扩展模块-->
<script type="text/javascript" src="/static/component/luminar/component/luminar/js/Sortable/Sortable.js"></script>
<script type="text/javascript" src="/static/component/luminar/component/luminar/js/htmlformat.js"></script>
<script type="text/javascript" src="/static/component/luminar/component/luminar/js/jsformat.js"></script>
<script type="text/javascript" src="/static/component/luminar/component/luminar/js/iceEditor/iceEditor.js"></script>
<script>

    const PRIMARY_KEY = "id";
    const PREVIEW_URL = "/admin/wf.form/design_preview";
    const UPDATE_API = "/admin/wf.form/updateForm";
    const SELECT_API = "/admin/wf.form/show" + location.search;

    layui.use(['layer', 'jquery', 'formField', 'formDesigner'], function () {
        let $ = layui.jquery;
        let formDesigner = layui.formDesigner;
        let data = [];
        let formId = layui.url().search[PRIMARY_KEY];
        //获取表单设计json
        if (formId !== undefined && formId !== '' && formId !== null) {
            layui.$.ajax({
                url: SELECT_API,
                type: "POST",
                dateType: "json",
                async: false,
                contentType: 'application/json',
                data: JSON.stringify(data),
                success: function (ret) {
                    if (ret.code === 0) {
                        //取最后版本的表单数据
                        let obj = ret?.data?.latest_history?.content ?? {};
                        for (let key in obj) {
                            data.push(obj[key]);
                        }
                    }
                }
            });
        }


        /**
         * 渲染表单设计
         */
        let render = formDesigner.render({
            data: data,
            elem: '#formdesigner'
        });

        navigation();


        /**
         * 导航栏处理
         */
        function navigation() {
            let layuiLayoutRight = document.querySelector('.layui-layout-right');
            // 获取父元素下所有的 a 元素
            let aElements = layuiLayoutRight.querySelectorAll('a.generateCode');
            // 遍历所有 a 元素，找到其父元素 li 并移除
            aElements.forEach(function (aElement) {
                let liElement = aElement.closest('li.layui-nav-item');
                if (liElement) {
                    liElement.remove();
                }
            });

            //追加退出按钮
            let newLiElement = document.createElement('li');
            newLiElement.classList.add('layui-nav-item');
            newLiElement.innerHTML = '<a href="#" class="close">退出</a>';
            layuiLayoutRight.appendChild(newLiElement);
        }


        /**
         * 保存设计
         */
        $('.saveJson').on('click', function () {
            let data = {
                [PRIMARY_KEY]: layui.url().search[PRIMARY_KEY],
                'form_data': render.getData()
            };
            layui.$.ajax({
                url: UPDATE_API,
                type: "POST",
                dateType: "json",
                contentType: 'application/json',
                data: JSON.stringify(data),
                success: function (res) {
                    if (res.code !== 0) {
                        return layer.msg(res.msg, {icon: 2});
                    }
                    return layer.msg('操作成功', {icon: 6});
                }
            });

        });


        // 移除内置预览使用自定义
        $('.previewForm').off('click').on('click', previewClick);
        $('.close').off('click').on('click', exitClick)


        /**
         * 预览
         */
        function previewClick() {
            window.localStorage.setItem('layui_form_json', JSON.stringify(render.getData()));
            layer.open({
                type: 2,
                title: "预览",
                shade: 0.1,
                area: ["100%", "100%"],
                content: PREVIEW_URL
            });
        }

        /**
         * 退出设计事件
         */
        function exitClick() {
            layer.confirm('请确认是否退出当前设计？', {
                btn: ['确认', '取消']
            }, function () {
                parent.layer.close(parent.layer.getFrameIndex(window.name));
            });
        }
    });
</script>

</body>

</html>
