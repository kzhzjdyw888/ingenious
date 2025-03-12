<?php /*a:1:{s:62:"C:\DATA\MyMotion\think-eflow\view\admin\wf\favorite\index.html";i:1740713693;}*/ ?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>我的收藏</title>
    <link rel="stylesheet" href="/static/component/pear/css/pear.css"/>
    <link rel="stylesheet" href="/static/admin/css/reset.css"/>
    <style>
        .custom-card {
            width: 100%;
            border: 1px solid #ddd;
            background-color: #fff;
            border-radius: 4px;
            padding: 10px;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
        }

        .card-header {
            display: flex;
            align-items: center;
            justify-content: flex-start;
            /* 从起点开始对齐 */
        }

        .avatar {
            width: 60px;
            height: 60px;
            line-height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #76dbb4;
            color: #fff;
            font-size: 20px;
            font-weight: bold;
            border-radius: 4px;
            margin-right: 10px;
            /* 为头像和标题之间添加间距 */
        }

        .header-info {
            display: flex;
            flex-direction: column;
            max-width: calc(100% - 70px);
            /* 限制标题的最大宽度，为头像留出空间 */
        }

        .title {
            white-space: nowrap;
            /* 防止标题换行 */
            overflow: hidden;
            /* 隐藏溢出部分 */
            text-overflow: ellipsis;
            /* 用省略号表示溢出部分 */
            margin-bottom: 0;
        }

        .card-footer {
            margin-top: auto;
            text-align: right;
        }

        .button {
            padding: 5px 10px;
            background-color: #f5f5f5;
            border: 1px solid #ddd;
            color: #333;
            border-radius: 4px;
            cursor: pointer;
        }

        .button:hover {
            background-color: #e5e5e5;
        }

        .remove-btn {
            position: absolute;
            top: 15px;
            right: 25px;
            color: #ab0c0c;
            font-weight: bold;
            font-size: 12px;
        }
    </style>
</head>

<body class="pear-container">
<div class="layui-row" style="margin-bottom: 20px;">
    <div class="layui-card-body">
        <button type="submit" class="pear-btn pear-btn-primary pear-btn-md edit-btn" lay-submit="" lay-active="favoriteEdit">
            <i class="layui-icon layui-icon-edit"></i>
            编辑
        </button>
        <button type="submit" class="pear-btn pear-btn-primary pear-btn-md finish-btn" lay-submit="" style="display: none;" lay-active="favoriteFinish">
            <i class="layui-icon layui-icon-edit"></i>
            完成
        </button>
        <button type="button" class="pear-btn pear-btn-md add-btn" lay-active="favoriteAdd">

            <i class="layui-icon layui-icon-add-circle-fine"></i>
            添加
        </button>
    </div>
</div>
<!-- 卡片渲染 -->
<div class="layui-row" style="margin-bottom: 20px;" id="collection-list"></div>

<script src="/static/component/layui/layui.js"></script>
<script src="/static/component/pear/pear.js"></script>
<script src="/static/admin/js/permission.js"></script>
<script src="/static/admin/js/common.js"></script>
<script>

    const PRIMARY_KEY = "id";

    const SELECT_API = "/admin/wf.favorite/select";
    const DELETE_API = "/admin/wf.favorite/delete";
    const INTERNAL_DOCUMENT_API = "/admin/wf.form/internalDocument";

    const INSERT_URL = "/admin/wf.favorite/insert";


    layui.use(['jquery', 'layer', 'util', 'common'], function () {
        let $ = layui.jquery;
        let layer = layui.layer;
        let util = layui.util;
        let common = layui.common;
        let deleteIds = [];

        //视图渲染
        function renderCard() {
            $.ajax({
                url: SELECT_API,
                type: 'get',
                dataType: 'json',
                data: {page: 0, limit: 0},
                success: function (ret) {
                    if (ret.code == 0) {
                        let data = ret.data['items'] != undefined ? ret.data['items'] : [];
                        template(data)
                    }
                }
            });
        }

        //模板渲染
        function template(data) {
            // 遍历数据生成列表项
            const container = document.getElementById('collection-list');
            container.innerHTML = '';
            $.each(data, function (index, item) {
                container.innerHTML += `
                    <div class="layui-col-md3 layui-card-body" data-item='${JSON.stringify(item)}'>
                        <div class="custom-card">
                            <span class="remove-btn" style="display: none;"  lay-active="deleteCard">
                                <i class="layui-icon layui-icon-reduce-circle"></i>
                            </span>
                            <div class="card-header">
                                <div class="avatar">
                                <span>${item.define?.display_name ? item.define?.display_name.substring(0, 2) : ''}</span>
                                </div>
                                <div class="header-info">
                                    <h4>${'v' + item.define?.version ? item.define?.version : '1.0'}</h4>
                                    <h3 class="title">${item.define?.display_name ? item.define?.display_name : ''}</h3>
                                </div>
                            </div>
                            <div class="card-footer">
                                <span style="cursor: pointer;">
                                    <i class="layui-icon">&#xe642;</i>
                                </span>
                                &nbsp;&nbsp;
                                <button class="layui-btn-xs" data-item='${JSON.stringify(item)}'
                                    style="border: solid 1px #edd8ac;color: #d1ae66;padding: 1px 10px 2px 10px;cursor: pointer;" lay-active="launchApplication" >发起申请</button>
                            </div>
                        </div>
                    </div>`;
            });

        }


        //编辑模式
        function edit(self) {
            $(self).hide(); // 隐藏编辑按钮
            $('.remove-btn').show(); // 显示所有移除按钮
            $('.finish-btn').show(); // 显示所有移除按钮
            $('.add-btn').hide();
        }

        function add(self) {
            layer.open({
                type: 2,
                title: '选择流程',
                anim: 0,
                maxmin: false,
                shade: 0.1,
                shadeClose: true,
                area: [common.isModile() ? '100%' : '900px', common.isModile() ? '100%' : '480px'],
                content: INSERT_URL,
                success: function (layero, index) {

                }, end: function (layero, index) {
                    renderCard();
                }
            })
        }

        //删除card 数据
        function deleteCard(self) {
            let cardBody = self.parentNode.parentNode;
            let itemData = JSON.parse(cardBody.getAttribute('data-item'));
            cardBody.parentNode.removeChild(cardBody);
            deleteIds.push(itemData.id);
        }

        //点击完成提交操作
        function finish(self) {
            $(self).hide();
            $('.remove-btn').hide();
            $('.edit-btn').show();
            $('.add-btn').show();
            if (deleteIds.length == 0) {
                return false;
            }
            $.ajax({
                url: DELETE_API,
                type: 'POST',
                dataType: 'json',
                data: {
                    data: deleteIds
                },
                success: function (ret) {
                    renderCard();
                }
            });
        }

        /**
         * 发起申请
         * @param self
         */
        function launchApplication(self) {
            let item = JSON.parse(self.getAttribute('data-item'));
            let type = item?.define?.content?.instance_type || 1;//默认json表单
            if (type == 1) {
                //传流程定义id 动态表单
                parent.layui.admin.addTab(item?.define?.id, item?.define?.display_name, "/admin/wf.launch/launch_application?id=" + item?.define?.id);
            } else {
                //内置表单
                parent.layui.admin.addTab(item?.define?.id, item?.define?.display_name, "/admin/wf.launch/launch_application_idf?id=" + item?.define?.id + "&instance_url=" + item?.define?.content.instance_url);
            }
        }

        //事件
        util.event('lay-active', {
            launchApplication: function () {
                launchApplication(this)
            },
            deleteCard: function () {
                deleteCard(this)
            },
            favoriteEdit: function () {
                edit(this)
            },
            favoriteFinish: function () {
                finish(this)
            },
            favoriteAdd: function () {
                add(this)
            }
        });

        /**
         * 获取内置表单
         * @param param
         * @returns {*[]}
         */
        let internalDocument = function (param) {
            let data = [];
            $.ajax({
                url: INTERNAL_DOCUMENT_API,
                type: 'POST',
                data: {instance_url: param},
                async: false,
                success: function (res) {
                    //渲染模板
                    data = res.data != undefined ? res.data : [];
                },
                error: function (xhr, status, error) {

                }
            });
            return data;
        }

        renderCard();


    })
</script>
</body>

</html>
