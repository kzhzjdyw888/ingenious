<?php /*a:1:{s:56:"D:\MyMotion\think-eflow\view\admin\wf\define\detail.html";i:1740665705;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>轨迹</title>
    <link rel="stylesheet" href="/static/component/pear/css/pear.css"/>
    <link rel="stylesheet" href="/static/admin/css/reset.css"/>
</head>
<body>
<div class="layui-tab layui-tab-brief">
    <ul class="layui-tab-title">
        <li class="layui-this" data-id="1">流程图</li>
        <li data-id="2">流程数据</li>
    </ul>
    <div class="layui-tab-content">
        <div class="layui-tab-item layui-show" style="height: calc(100vh - 95px);overflow: hidden">
            <iframe id="myIframe" style="width: 100%; height: 100%;" frameborder="0"></iframe>
        </div>
    </div>
</div>

<script src="/static/component/layui/layui.js"></script>
<script src="/static/component/pear/pear.js"></script>
<script src="/static/admin/js/permission.js"></script>
<script src="/static/admin/js/common.js"></script>

<script>
    const PRIMARY_KEY = "id";


    const FLOW_DATA_URL = "/admin/wf.define/flowData" + location.search;
    const FLOW_CHART_URL = "/admin/wf.define/flowChart" + location.search;

    layui.use(['form', 'jquery', 'element'], function () {

        /**
         * 给tab添加点击事件
         */
        document.querySelectorAll('.layui-tab-title li').forEach(tab => {
            tab.addEventListener('click', function () {
                const scene = parseInt(this.getAttribute('data-id'));
                formsRendering(scene);
            });
        });


        /**
         * 渲染对应的模板
         * @param scene
         */
        function formsRendering(scene = 1) {
            const iframe = document.getElementById('myIframe');
            switch (scene) {
                case 1:
                    iframe.src = FLOW_CHART_URL;
                    break;
                case 2:
                    iframe.src = FLOW_DATA_URL;
                    break;
            }
        }


        /**
         * 默认渲染tab
         */
        formsRendering(1);
    });
</script>

</body>
</html>
