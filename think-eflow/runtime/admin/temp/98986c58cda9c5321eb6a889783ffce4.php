<?php /*a:1:{s:64:"C:\DATA\MyMotion\think-eflow\view\admin\wf\surrogate\update.html";i:1740708363;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>编辑</title>
    <link rel="stylesheet" href="/static/component/pear/css/pear.css"/>
    <link rel="stylesheet" href="/static/admin/css/reset.css"/>
</head>
<body>

<div class="layui-form">

    <div class="mainBox">
        <div class="main-container mr-5">
            <div class="layui-form-item">
                <label class="layui-form-label required">流程名称</label>
                <div class="layui-input-block">
                    <div id="process_name"></div>
                </div>
            </div>

            <div class="layui-form-item">
                <label class="layui-form-label">起始时间</label>
                <div class="layui-input-block">
                    <input type="text" class="layui-input" name="start_time" id="start_time" placeholder="yyyy-MM-dd">
                </div>
            </div>

            <div class="layui-form-item">
                <label class="layui-form-label">结束时间</label>
                <div class="layui-input-block">
                    <input type="text" class="layui-input" name="end_time" id="end_time" placeholder="yyyy-MM-dd">
                </div>
            </div>

            <div class="layui-form-item">
                <label class="layui-form-label required">代理用户</label>
                <div class="layui-input-block">
                    <div id="surrogate"></div>
                </div>
            </div>

            <div class="layui-form-item">
                <label class="layui-form-label">状态</label>
                <div class="layui-input-block">
                    <input type="checkbox" name="enabled" lay-skin="switch" lay-filter="enabled" value="1">
                </div>
            </div>
        </div>
    </div>

    <div class="bottom">
        <div class="button-container">
            <button type="submit" class="pear-btn pear-btn-primary pear-btn-md" lay-submit=""
                    lay-filter="save">
                保存
            </button>
            <button type="reset" class="pear-btn pear-btn-md">
                重置
            </button>
        </div>
    </div>
</div>

<script src="/static/component/layui/layui.js"></script>
<script src="/static/component/pear/pear.js"></script>
<script src="/static/admin/js/permission.js"></script>
<script src="/static/admin/js/common.js"></script>
<script>

    const PRIMARY_KEY = "id";
    const UPDATE_API = "/admin/wf.surrogate/update";

    const SELECT_API = "/admin/wf.surrogate/show/";

    const DEFINE_SELECT_API = "/admin/wf.define/select";
    const ADMINS_SELECT_API = "/admin/admin.admin/index";

    //提交事件
    layui.use(["form", "popup", "xmSelect", "jquery", "laydate"], function () {
        let xmSelect = layui.xmSelect;
        let $ = layui.jquery;
        let laydate = layui.laydate;
        let popup = layui.popup;
        let form = layui.form;

        //起始时间
        laydate.render({
            elem: '#start_time'
        });

        //结束时间
        laydate.render({
            elem: '#end_time'
        });

        // 监听复选框状态变化
        form.on('switch(enabled)', function (data) {
            if (data.elem.checked) {
                data.elem.value = "1"; // 复选框选中时设置值为1
            } else {
                data.elem.value = "0"; // 复选框未选中时设置值为0
            }
        });

        /**
         * 流程选择
         */
        let process = xmSelect.render({
            el: '#process_name',
            name: 'process_define_id',
            filterable: true,
            remoteSearch: true,
            paging: true,//开启分页
            pageRemote: true,//远程分页
            radio: true, // 设置为单选模式
            pageEmptyShow: false,//没有数据不展示分页
            prop: {
                name: 'display_name',
                value: 'id',
            },
            remoteMethod: function (val, cb, show, pageIndex) {
                $.ajax({
                    url: DEFINE_SELECT_API, // test.html文件的路径
                    type: 'GET',
                    dataType: 'json',
                    data: {display_name: val, limit: 5, page: pageIndex, is_active: 1},
                    success: function (ret) {
                        let data = ret.data['items'] != undefined ? ret.data['items'] : [];
                        let size = Math.ceil(ret.data['total'] / 5)

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
                    stringArr.push(item.value)
                });
                $('input[name="process_define_id"]').val(stringArr.join(','))
            }
        });


        let surrogate = xmSelect.render({
            el: '#surrogate',
            name: 'surrogate',
            filterable: true,
            remoteSearch: true,
            paging: true,//开启分页
            pageRemote: true,//远程分页
            radio: true, // 设置为单选模式
            pageEmptyShow: false,//没有数据不展示分页
            prop: {
                name: 'nickname',
                value: 'id',
            },
            remoteMethod: function (val, cb, show, pageIndex) {
                $.ajax({
                    url: ADMINS_SELECT_API, // test.html文件的路径
                    type: 'GET',
                    dataType: 'json',
                    data: {username: val, limit: 5, page: pageIndex},
                    success: function (ret) {
                        let data = ret.data != undefined ? ret.data : [];
                        let size = Math.ceil(ret.count / 5)
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
                    stringArr.push(item.value)
                });
                $('input[name="surrogate"]').val(stringArr.join(','))
            }
        })


        /**
         * 获取详情渲染表单
         */
        layui.$.ajax({
            url: SELECT_API,
            type: 'GET',
            dataType: 'json',
            data: {id: layui.url().search[PRIMARY_KEY]},
            success: function (ret) {
                let data = ret.data != undefined ? ret.data : [];
                // 给表单初始化数据
                layui.each(data, function (key, value) {
                    let obj = $('*[name="' + key + '"]');
                    if (typeof obj[0] === "undefined" || !obj[0].nodeName) return;
                    if (key == 'start_time'||key == 'end_time') {
                        value = formatTimestamp(value,'YYYY-MM-DD');
                    }
                    obj.attr("value", value);
                });
                //给下拉框赋值
                surrogate.setValue(data.surrogateData ?? []);

                // 循环遍历数据，并追加版本编号
                let flowData = data.define ?? [];
                process.setValue([flowData]);

                // 设置复选框为选中状态
                let checkbox = document.querySelector('input[name="enabled"]');
                if (data.enabled == 1) {
                    checkbox.checked = true;
                    form.render('checkbox'); // 重新渲染复选框，让设置生效
                }
            }
        });


        /**
         * 表单提交
         */
        layui.form.on("submit(save)", function (obj) {
            let data = obj.field ?? [];
            data[PRIMARY_KEY] = layui.url().search[PRIMARY_KEY];
            //将字符串时间转时间戳
            // if (data.start_time !== '') {
            //     data.start_time = convertStringToTimestamp(data.start_time);
            // }
            // if (data.end_time !== '') {
            //     data.end_time = convertStringToTimestamp(data.end_time);
            // }
            if (data.enabled == undefined) {
                data.enabled = 0;
            }

            if (data.process_define_id == '' || data.process_define_id == null) {
                return layui.popup.failure('请选择要委托的流程');
            }

            if (data.surrogate === '') {
                return layui.popup.failure('请选择要委托的代理人');
            }

            layui.$.ajax({
                url: UPDATE_API,
                type: "POST",
                dateType: "json",
                contentType: 'application/json',
                data: JSON.stringify(data),
                success: function (res) {
                    if (res.code !== 0) {
                        return layui.popup.failure(res.msg);
                    }
                    return layui.popup.success("操作成功", function () {
                        parent.refreshTable();
                        parent.layer.close(parent.layer.getFrameIndex(window.name));
                    });
                }
            });
            return false;
        });


        /**
         * 将字符串日期转换时间戳
         * @param str
         * @returns {number}
         */
        function convertStringToTimestamp(str) {
            let timestamp = Math.floor(new Date(str).getTime() / 1000); // 将字符串时间转换为10位时间戳
            return timestamp;
        }
    });
</script>

</body>
</html>
