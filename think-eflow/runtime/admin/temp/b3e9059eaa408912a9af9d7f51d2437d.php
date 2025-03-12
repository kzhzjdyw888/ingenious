<?php /*a:1:{s:73:"C:\DATA\MyMotion\think-eflow\view\admin\wf\launch\launch_application.html";i:1740704225;}*/ ?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>启动流程</title>
    <link rel="stylesheet" href="/static/component/luminar/component/luminar/css/luminar.css"/>
    <style>
        body {
            margin: 0;
            padding: 0;
        }

        .container {
            display: flex;
            justify-content: center;
            align-items: center; /* 垂直居中 */
            min-height: 100vh; /* 使用min-height来确保容器至少占满整个视口 */
            background-color: #f2f2f2;
            padding: 20px;
            box-sizing: border-box; /* 计算边框和内边距在内部的宽度和高度 */
            overflow: auto; /* 添加overflow属性来实现垂直滚动 */
        }

        .form-container {
            width: 210mm; /* A4宽度 */
            background-color: #fff;
            padding: 25px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .form-container .layui-form-item {
            margin-bottom: 20px;
        }

        .form-container .form-buttons {
            text-align: right; /* 将按钮放置在右侧 */
        }

        .form-footer {
            /*background-image: url('your-background-image-url'); !* 设置背景图 *!*/
            background-size: cover;
            padding: 20px;
            text-align: center;
        }

        .side-toolbar {
            right: 20px;
            bottom: 120px;
            z-index: 1001;
            margin-top: -60px;
            position: fixed;
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-orient: vertical;
            -webkit-box-direction: normal;
            -ms-flex-direction: column;
            flex-direction: column;
            -webkit-box-align: end;
            -ms-flex-align: end;
            align-items: flex-end;
        }

        .layui-btn-circle {
            border-radius: 50%;
            width: 40px;
            height: 40px;
            line-height: 40px;
            text-align: center;
            background-color: #ffffff;
            border: #dcdfe6;
            border-color: #dcdfe6;
        }

        /* 按钮hover颜色 */
        .layui-btn-circle:hover {
            background-color: #6081ef; /* 设置hover时的背景色 */
        }

        /* 自定义tooltip样式 */
        .tooltip {
            position: relative;
            display: inline-block;
        }

        .tooltip .tooltiptext {
            visibility: hidden;
            background-color: #555;
            color: #fff;
            text-align: center;
            border-radius: 6px;
            padding: 5px;
            position: absolute;
            z-index: 1;
            top: 50%;
            right: 100%;
            font-size: 12px;
            transform: translate(0, -50%);
            opacity: 0;
            transition: opacity 0.3s;
            white-space: nowrap; /* 文字不换行显示 */
        }

        .tooltip .tooltiptext::after {
            content: "";
            position: absolute;
            top: 50%;
            left: 100%;
            transform: translateY(-50%);
            border-width: 5px;
            border-style: solid;
            border-color: transparent transparent transparent #555;
        }

        .tooltip:hover .tooltiptext {
            visibility: visible;
            opacity: 1;
        }

        .mt10 {
            margin-top: 10px;
        }
    </style>
</head>
<body>
<div class="container">
    <!--startprint-->
    <div class="form-container layui-form">
        <!--        标题-->
        <div style="display: flex; flex-direction: column; align-items: center; margin-top: 20px; margin-bottom: 20px;">
            <h2 style="margin-bottom: 10px;" id="form-name"></h2>
            <span style="align-self: flex-end;" id="form-number"></span>
        </div>

        <!--        内容-->
        <div id="form-container"></div>
    </div>
    <!--endprint-->
    <div class="side-toolbar">
        <div class="tooltip">
            <button type="button" class="layui-btn-circle launch">
                <i class="layui-icon">&#xe609;</i>
            </button>
            <span class="tooltiptext">发起</span>
        </div>
        <div class="tooltip mt10">
            <button type="button" class="layui-btn-circle track">
                <i class="layui-icon">&#xe629;</i>
            </button>
            <span class="tooltiptext">预览</span>
        </div>
        <div class="tooltip mt10">
            <button type="button" class="layui-btn-circle exit">
                <i class="layui-icon">&#xe682;</i>
            </button>
            <span class="tooltiptext">退出</span>
        </div>
    </div>
</div>

<!--引入layui模块-->
<script type="text/javascript" src="/static/component/luminar/component/layui/layui.js"></script>
<script type="text/javascript" src="/static/component/luminar/component/luminar/luminar.js"></script>
<!--外部扩展js-->
<script type="text/javascript" src="/static/component/luminar/component/luminar/js/Sortable/Sortable.js"></script>
<script type="text/javascript" src="/static/component/luminar/component/luminar/js/htmlformat.js"></script>
<script type="text/javascript" src="/static/component/luminar/component/luminar/js/jsformat.js"></script>
<script type="text/javascript" src="/static/component/luminar/component/luminar/js/iceEditor/iceEditor.js"></script>

<script>
    const PRIMARY_KEY = "id";
    const PROCESS_DEFINE_KEY = 'process_define_id';
    const SELECT_API = "/admin/wf.define/show/" + location.search;
    const SELECT_FORM_API = "/admin/wf.form/getByName";
    const START_AND_EXECUTE_API = "/admin/wf.define/startAndExecute";


    const DETAIL_URL = "/admin/wf.define/detail";//流程定义详情

    layui.use(['form', 'jquery', 'formDesigner', 'layer', 'upload'], function () {
        let layer = layui.layer;
        let $ = layui.jquery;
        let upload = layui.upload;
        let formDesigner = layui.formDesigner;
        let form = layui.form;
        let render;
        let data = [];

        //如果id没有传关闭当前页面
        let id = layui.url().search[PRIMARY_KEY];
        if (id == undefined || id == '') {
            parent.layui.admin.closeCurrentTab();
        }

        /**
         * 获取表单
         */
        layui.$.ajax({
            url: SELECT_API,
            type: 'GET',
            dataType: 'json',
            async: false,
            data: {page: 1, limit: 9999},
            success: function (ret) {
                if (ret.code == 0) {
                    let row = ret.data != undefined ? ret.data : [];
                    let content = row.content ?? [];
                    getForms(content.instance_url);
                    $('#form-name').html(row.display_name ?? '申请单');
                    $('#form-number').html(generateSerialWithoutSequence());
                }
            }
        });


        function getForms(name = '') {
            layui.$.ajax({
                url: SELECT_FORM_API,
                type: 'GET',
                dataType: 'json',
                async: false,
                data: {name: name},
                success: function (ret) {
                    if (ret.code == 0) {
                        let obj = ret.data.latest_history !== undefined ? ret.data.latest_history : {};
                        let content = obj.content ?? {};
                        for (let key in content) {
                            data.push(content[key]);
                        }
                    }
                }
            });
        }


        /**
         * 表单渲染
         */
        render = formDesigner.render({
            elem: '#form-container',
            data: data,
            viewOrDesign: true,
            formDefaultButton: false,
            formData: {}
        });

        let images = render.getImages();
        for (let i = 0; i < images.length; i++) {
            upload.render({
                elem: '#' + images[i].select
                , url: '' + images[i].uploadUrl + ''
                , multiple: true
                , before: function (obj) {
                    layer.msg('图片上传中...', {
                        icon: 16,
                        shade: 0.01,
                        time: 0
                    })
                }
                , done: function (res) {
                    layer.close(layer.msg());//关闭上传提示窗口
                    //上传完毕
                    $('#uploader-list-' + item.id).append(
                        '<div id="" class="file-iteme">' +
                        '<div class="handle"><i class="layui-icon layui-icon-delete"></i></div>' +
                        '<img style="width: 100px;height: 100px;" src=' + res.data.src + '>' +
                        '<div class="info">' + res.data.title + '</div>' +
                        '</div>'
                    );
                }
            });
        }

        let filesData = render.getFiles();
        for (let i = 0; i < filesData.length; i++) {
            upload.render({
                elem: '#' + filesData[i].select
                , elemList: $('#list-' + filesData[i].select) //列表元素对象
                , url: '' + filesData[i].uploadUrl + ''
                , accept: 'file'
                , multiple: true
                , number: 3
                , auto: false
                , bindAction: '#listAction-' + filesData[i].select
                , choose: function (obj) {
                    var that = this;
                    var files = this.files = obj.pushFile(); //将每次选择的文件追加到文件队列
                    //读取本地文件
                    obj.preview(function (index, file, result) {
                        var tr = $(['<tr id="upload-' + index + '">'
                            , '<td>' + file.name + '</td>'
                            , '<td>' + (file.size / 1014).toFixed(1) + 'kb</td>'
                            , '<td><div class="layui-progress" lay-filter="progress-demo-' + index + '"><div class="layui-progress-bar" lay-percent=""></div></div></td>'
                            , '<td>'
                            , '<button class="layui-btn layui-btn-xs demo-reload layui-hide">重传</button>'
                            , '<button class="layui-btn layui-btn-xs layui-btn-danger demo-delete">删除</button>'
                            , '</td>'
                            , '</tr>'].join(''));

                        //单个重传
                        tr.find('.demo-reload').on('click', function () {
                            obj.upload(index, file);
                        });

                        //删除
                        tr.find('.demo-delete').on('click', function () {
                            delete files[index]; //删除对应的文件
                            tr.remove();
                            uploadListIns.config.elem.next()[0].value = ''; //清空 input file 值，以免删除后出现同名文件不可选
                        });

                        that.elemList.append(tr);
                        element.render('progress'); //渲染新加的进度条组件
                    });
                }
                , done: function (res, index, upload) { //成功的回调
                    var that = this;
                    //if(res.code == 0){ //上传成功
                    var tr = that.elemList.find('tr#upload-' + index)
                        , tds = tr.children();
                    tds.eq(3).html(''); //清空操作
                    delete this.files[index]; //删除文件队列已经上传成功的文件
                    return;
                    //}
                    this.error(index, upload);
                }
                , allDone: function (obj) { //多文件上传完毕后的状态回调
                    console.log(obj)
                }
                , error: function (index, upload) { //错误回调
                    var that = this;
                    var tr = that.elemList.find('tr#upload-' + index)
                        , tds = tr.children();
                    tds.eq(3).find('.demo-reload').removeClass('layui-hide'); //显示重传
                }
                , progress: function (n, elem, e, index) {
                    element.progress('progress-demo-' + index, n + '%'); //执行进度条。n 即为返回的进度百分比
                }
            });
        }

        /**
         * 发起申请
         */
        $('.launch').on('click', function () {
            let formData = render.getFormData();
            let obj = addPrefixToObject(formData, 'f_');
            obj[PROCESS_DEFINE_KEY] = id;

            layer.confirm('提交流程申请, 是否继续?', {
                icon: 3,
                title: '提示'
            }, function (index) {
                layui.$.ajax({
                    url: START_AND_EXECUTE_API,
                    type: 'POST',
                    dataType: 'json',
                    contentType: 'application/json',
                    data: JSON.stringify(obj),
                    success: function (ret) {
                        if (ret.code == 0) {
                            layer.msg(ret.msg, {
                                icon: 6,
                                time: 2000, // 设置消息显示2秒后自动关闭
                            }, function () {
                                parent.layui.admin.closeCurrentTab();
                            });
                        } else {
                            layer.msg(ret.msg, {icon: 2});
                        }
                    }
                });
            });
        });


        /**
         * 流程预览
         */
        $('.track').on('click', function () {
            //提供流程定义id 预览流程
            top.layer.open({
                type: 2,
                offset: 'r',
                anim: 'slideLeft', // 从右往左
                area: ['60%', '100%'],
                shade: 0.1,
                move: false,
                title: '流程预览',
                shadeClose: true,
                content: DETAIL_URL + "?" + PRIMARY_KEY + "=" + id
            });

        });


        /**
         * 退出
         */
        $('.exit').on('click', function () {
            parent.layui.admin.closeCurrentTab();
        });

        /**
         * 给表单添加前缀
         * @param obj
         * @param prefix
         * @returns {{}}
         */
        function addPrefixToObject(obj, prefix = 'f_') {
            let newObj = {};
            for (let key in obj) {
                if (obj.hasOwnProperty(key)) {
                    newObj[prefix + key] = obj[key];
                }
            }
            return newObj;
        }

        /**
         * 生成序列号
         * @returns {string}
         */
        function generateSerialWithoutSequence() {
            // 获取当前日期和时间（UTC）
            let now = new Date();
            // 格式化日期和时间
            let year = now.getUTCFullYear();
            let month = ("0" + (now.getUTCMonth() + 1)).slice(-2);
            let day = ("0" + now.getUTCDate()).slice(-2);
            let hours = ("0" + now.getUTCHours()).slice(-2);
            let minutes = ("0" + now.getUTCMinutes()).slice(-2);
            let seconds = ("0" + now.getUTCSeconds()).slice(-2);
            // 返回流水号（无序列号）
            return `${year}${month}${day}${hours}${minutes}${seconds}`;
        }
    });
</script>
</body>
</html>
