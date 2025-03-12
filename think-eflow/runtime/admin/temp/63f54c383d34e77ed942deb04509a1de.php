<?php /*a:1:{s:69:"D:\MyMotion\think-eflow\view\admin\wf\common\design\panel\detail.html";i:1740658312;}*/ ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>查看流程数据</title>
    <link rel="stylesheet" href="/static/component/pear/css/pear.css"/>
    <link rel="stylesheet" href="/static/admin/css/reset.css"/>
</head>
<body>
<div class="layui-form" lay-filter="detail">
    <div class="mainBox">
        <div class="main-container">
            <pre id="json"></pre>
        </div>
    </div>
    <div class="bottom">
        <div class="button-container">
            <button type="button" class="pear-btn pear-btn-primary pear-btn-md btn" id="copyButton">
                <i class="pear-icon pear-icon-copy"></i>
                复制
            </button>
            <button type="button" class="pear-btn pear-btn-md" lay-on="export">
                <i class="pear-icon pear-icon-export"></i>
                导出
            </button>
        </div>
    </div>
</div>
<form class="layui-form" action="">

</form>
<!--layui依赖-->
<script src="/static/component/layui/layui.js"></script>
<script src="/static/component/pear/pear.js"></script>
<script src="/static/admin/js/permission.js"></script>
<script src="/static/admin/js/common.js"></script>
<script src="/static/component/logicflow/customized/properties.js"></script>
<script src="/static/admin/js/clipboard.js"></script>
<script>
    function child(data) {
        layui.use(['jquery', 'util', 'popup'], function () {
            let $ = layui.jquery
            let util = layui.util
            let popup = layui.popup


            let clipboard = new ClipboardJS('.btn', {
                text: function () {
                    return $('#json').text()
                }
            });

            clipboard.on('success', function (e) {
                popup.success('复制成功')
                e.clearSelection();
            });

            clipboard.on('error', function (e) {
                console.error('Action:', e.action);
                console.error('Trigger:', e.trigger);
                popup.failed('复制失败')
            });


            let jsonStrAfter = JSON.stringify(data, null, '\t')
            $('#json').html(jsonStrAfter)


            /**
             * 点击事件
             */
            util.on('lay-on', {
                copy: function () {
                    //在服务器上无法使用
                    // navigator.clipboard.writeText(jsonStrAfter).then(function () {
                    //     popup.success('复制成功')
                    // }, function (err) {
                    //     popup.failed('复制失败')
                    //     console.error(err);
                    // });
                },
                export: function () {
                    exportToNotepad(jsonStrAfter)
                }
            })

            /**
             * 导出文件text
             * @param text
             */
            function exportToNotepad(text) {
                let blob = new Blob([text], {type: "text/plain;charset=utf-8"});
                let url = URL.createObjectURL(blob);
                let link = document.createElement("a");
                link.href = url;
                let name = Math.floor(Math.random() * 100000) + 10000;
                link.download = name + ".txt";
                link.click();
                URL.revokeObjectURL(url);
            }
        });
    }
</script>
</body>
</html>
