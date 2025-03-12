<?php /*a:1:{s:54:"D:\MyMotion\think-eflow\view\admin\wf\form\update.html";i:1740654403;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>修改</title>
    <link rel="stylesheet" href="/static/component/pear/css/pear.css"/>
    <link rel="stylesheet" href="/static/admin/css/reset.css"/>
</head>
<body>

<form class="layui-form" action="" lay-filter="form-data">

    <div class="mainBox">
        <div class="main-container mr-5">

            <div class="layui-form-item">
                <label class="layui-form-label required">流程类型</label>
                <div class="layui-input-block">
                    <ul id="designer-add-tree" class="dtree" data-id="0"></ul>
                </div>
            </div>

            <div class="layui-form-item">
                <label class="layui-form-label required">唯一编码</label>
                <div class="layui-input-block">
                    <input type="text" name="name" required lay-verify="required" autocomplete="off" class="layui-input" placeholder="请输入名称">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label required">表单名称</label>
                <div class="layui-input-block">
                    <input type="text" name="display_name" required lay-verify="required" autocomplete="off" class="layui-input" placeholder="请输入名称">
                </div>
            </div>

            <div class="layui-form-item layui-form-text">
                <label class="layui-form-label">描述</label>
                <div class="layui-input-block">
                    <textarea placeholder="" class="layui-textarea" name="description"></textarea>
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
            <button type="reset" class="pear-btn pear-btn-md" onclick="resetInput()">
                重置
            </button>
        </div>
    </div>
</form>

<script src="/static/component/layui/layui.js"></script>
<script src="/static/component/pear/pear.js"></script>
<script src="/static/admin/js/permission.js"></script>
<script>

    const PRIMARY_KEY = "id";
    const CATEGORY_API = "/admin/wf.category/select";
    const UPDATE_API = "/admin/wf.form/update";
    const SELECT_API = "/admin/wf.form/show" + location.search;

    //提交事件
    layui.use(["form", "popup", "dtree", "jquery"], function () {
        let Dtree = layui.dtree;
        let form = layui.form;
        let $ = layui.jquery;
        let superiorData = [];
        /**
         * 所属上级获取
         */
        layui.$.ajax({
            url: CATEGORY_API,
            type: 'GET',
            dataType: 'json',
            async: false,
            data: {page: 1, limit: 9999},
            success: function (ret) {
                let data = ret.data.items != undefined ? ret.data.items : [];
                superiorData = data;
            },
            error: function (ret) {
                superiorData = [];
            },
        });

        layui.$.ajax({
            url: SELECT_API,
            type: 'GET',
            dataType: 'json',
            data: {page: 1, limit: 9999},
            success: function (ret) {
                let data = ret.data != undefined ? ret.data : [];
                layui.each(data, function (key, value) {
                    let obj = $('*[name="' + key + '"]');//匹配表单key
                    if (typeof obj[0] === "undefined" || !obj[0].nodeName) return;
                    if (obj[0].nodeName.toLowerCase() === "textarea") {
                        obj.val(layui.util.escape(value));
                    } else {
                        obj.attr("value", value);
                    }
                });
                //赋值Tree
                Dtree.renderSelect({
                    elem: "#designer-add-tree",
                    data: superiorData,
                    accordion: true,
                    icon: "-1",  // 隐藏二级图标
                    skin: "layui",
                    width: '100%',
                    selectCardHeight: "200",
                    selectInitVal: data.type_id,//默认值顶层
                    response: {
                        treeId: "id", //节点ID（必填）
                        parentId: "pid", //父节点ID（必填）
                        title: "name", //节点名称（必填）
                    },
                    selectInputName: {
                        nodeId: "type_id",
                        context: "请选择父级"
                    },
                    done: function (res, $ul, first) {
                        if (first) {
                            //首次赋值顶层
                            Dtree.dataInit("designer-add-tree", '0');
                            Dtree.selectVal("designer-add-tree");
                        }
                    }
                });

                form.render();
            }
        });

        $("body").on("click", function (event) {
            $("div[dtree-id][dtree-select]").removeClass("layui-form-selected");
            $("div[dtree-id][dtree-card]").removeClass("dtree-select-show layui-anim layui-anim-upbit");
        });
    });


    //提交事件
    layui.use(["form", "popup"], function () {
        layui.form.on("submit(save)", function (data) {
            data.field[PRIMARY_KEY] = layui.url().search[PRIMARY_KEY];
            layui.$.ajax({
                url: UPDATE_API,
                type: "POST",
                dateType: "json",
                data: data.field,
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
    });
</script>

</body>
</html>
