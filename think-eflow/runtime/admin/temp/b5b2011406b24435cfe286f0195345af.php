<?php /*a:1:{s:63:"C:\DATA\MyMotion\think-eflow\view\admin\wf\favorite\insert.html";i:1740713742;}*/ ?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>添加收藏</title>
    <link rel="stylesheet" href="/static/component/pear/css/pear.css"/>
    <link rel="stylesheet" href="/static/admin/css/reset.css"/>
    <style>
        .container {
            display: flex;
        }

        .left {
            flex: 1;
            padding: 20px;
            margin-bottom: 0px !important;
        }

        .right {
            width: 250px;
            padding: 20px;
            margin-bottom: 0px !important;
            /*border-left: 1px solid #e6e6e6;*/
        }

        .item {
            padding: 5px 15px 5px 15px;
            border: 1px solid #e6e6e6;
            border-radius: 4px;
            margin-bottom: 1px;
            display: flex;
            justify-content: space-between;
        }

        .item-title {
            font-size: 18px;
        }

        .item-meta {
            font-size: 14px;
            color: #999;
        }

        .item-actions {
            display: flex;
            align-items: center;
        }

        .item-actions button {
            margin-left: 10px;
        }

        .page-controls {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 20px;
        }

        .page-controls select {
            width: 80px;
        }

        .hidden {
            display: none !important;
        }


        .list-item.selected {
            background-color: #007bff; /* 选中时的颜色 */
            color: white; /* 选中时的文字颜色 */
        }

    </style>
</head2
<body class="pear-container">
<div class="layui-form">
    <div class="mainBox">
        <div class="container">
            <div class="layui-card left">
                <div id="content" style="min-height: 300px;"></div>
                <div class="page-controls layui-hide" id="page-controls">
                </div>
            </div>

            <!--    右侧搜索部分-->
            <div class="layui-card right">
                <div class="layui-tab-item layui-show">
                    <input type="text" name="display_name" placeholder="搜索流程" autocomplete="off" class="layui-input layui-size-min">
                </div>
                <!-- tags标签容器-->
                <div class="layui-btn-container tag" lay-newtags="true" style="margin-top: 10px;" id="type-tags"></div>
            </div>
        </div>
    </div>
    <div class="bottom">
        <div class="button-container">
            <button type="reset" class="pear-btn pear-btn-md">
                取消
            </button>
            <button type="submit" class="pear-btn pear-btn-primary pear-btn-md" lay-submit=""
                    lay-filter="save">
                确认
            </button>
        </div>
    </div>
</div>


<script src="/static/component/layui/layui.js?v=2.8.12"></script>
<script src="/static/component/pear/pear.js"></script>
<script src="/static/admin/js/permission.js"></script>
<script src="/static/admin/js/common.js"></script>
<script>

    const TYPE_API = "/admin/wf.category/select";
    const SELECT_API = "/admin/wf.define/select"
    const INSERT_FAVORITE_API = "/admin/wf.favorite/insert"
    const SELECT_FAVORITE_API = "/admin/wf.favorite/select";

    layui.use(['element', 'laypage', 'form', 'jquery', 'popup'], function () {
        let element = layui.element;
        let laypage = layui.laypage;
        let form = layui.form;
        let popup = layui.popup;
        let $ = layui.jquery;

        // 初始化分页
        let pageSize = 5; // 每页显示数量
        let currentPage = 1; // 当前页码
        let isRenderPage = false; // 防止重新渲染分页组件时再次触发jump事件
        let selectId = '';
        let favoriteList = [];
        let renderData = [];


        getFavoriteList();
        typeRender();

        /**
         * 右侧tags渲染
         */
        function typeRender() {
            $.ajax({
                url: TYPE_API,
                type: 'GET',
                dataType: 'json',
                success: function (ret) {
                    let data = ret.data['items'] != undefined ? ret.data['items'] : [];
                    if (ret.code == 0) {
                        const container = document.getElementById('type-tags');
                        container.innerHTML = '';
                        $.each(data, function (index, item) {
                            container.innerHTML += `<button lay-id="${item.id}" type="button" class="tag-item tag-item-normal layui-btn layui-btn-primary layui-btn-xs" >${item.name}</button>`;
                        });
                    }
                }
            });
        }


        /**
         * 获取收藏列表
         */
        function getFavoriteList() {
            $.ajax({
                url: SELECT_FAVORITE_API,
                type: 'GET',
                data: {
                    page: 1,
                    limit: 9999,
                },
                dataType: 'json',
                async: false,
                success: function (ret) {
                    let data = ret.data['items'] != undefined ? ret.data['items'] : [];
                    if (ret.code == 0) {
                        favoriteList = data.map(item => item.process_define_id);
                    }
                }
            });
        }


        /**
         * 模板渲染
         * @param data
         */
        function template(data) {
            selectId = '';
            // 遍历数据生成列表项
            const container = document.getElementById('content');
            container.innerHTML = '';
            $.each(data, function (index, item) {
                container.innerHTML += `<div class="item list-item" data-item='${JSON.stringify(item)}'>
                                            <div>
                                                <div class="item-title">${item.name}</div>
                                                <div class="item-meta">${item.display_name}</div>
                                            </div>
                                            <div class="item-actions">
                                                <div style="width: 35px;">${item.version}</div>
                                                <div style="width: 35px;" class="favorite" >
                                                    <i class="layui-icon layui-font-12;" style="color:red;cursor: pointer;">
                                                        ${favoriteList.includes(item.id) ? '&#xe67a;' : '&#xe67b;'}
                                                    </i>
                                                </div>
                                            </div>
                                        </div>`;
            });
        };

        /**
         * 左侧内容渲染
         */
        function renderContent(param) {
            $.ajax({
                url: SELECT_API,
                type: 'GET',
                data: {
                    ...param,
                    page: currentPage,
                    limit: pageSize,
                    is_active:1
                },
                success: function (res) {
                    //渲染模板
                    let data = res.data.items != undefined ? res.data.items : [];
                    if (res.data.total > 0) {
                        $('#page-controls').removeClass('layui-hide');
                    } else {
                        $('#page-controls').addClass('layui-hide');
                    }
                    renderData = data;
                    template(data);

                    //分页重新渲染
                    laypage.render({
                        elem: 'page-controls',
                        count: res.data.total ?? 0,
                        limit: pageSize,
                        curr: currentPage,
                        limits: [5, 10, 20, 30, 40, 50, 100],
                        layout: ['count', 'prev', 'page', 'next', 'limit', 'refresh', 'skip'],
                        jump: function (obj, first) {
                            isRenderPage = false;
                            if (!first) {
                                currentPage = obj.curr;
                                pageSize = obj.limit;
                                renderContent(param);
                            }
                        }
                    });
                    if (res.data.count == 0) {
                        $('#page-controls').addClass('hidden');
                    } else {
                        $('#page-controls').removeClass('hidden');
                    }
                },
                error: function (xhr, status, error) {
                    console.log(error);
                }
            });
            isRenderPage = true;
        }


        // 点击标签搜索
        $(document).on('click', '.tag-item', function () {
            currentPage = 1;//搜索从第一页开始
            let param = {type_id: $(this).attr('lay-id')};
            renderContent(param);
        });

        // 输入框流程搜索
        $('input[name="display_name"]').on('keyup', function () {
            currentPage = 1;//搜索从第一页开始
            let param = {display_name: $(this).val()};
            renderContent(param);
        });


        $(document).ready(function () {
            // 事件委托，处理点击事件
            $('#content').on('click', '.list-item', function () {
                // 移除其他项的选中状态
                $('.list-item').removeClass('selected');
                // 为当前项添加选中状态
                let self = this;
                let item = JSON.parse(self.getAttribute('data-item'));
                if (item.id !== undefined && item.id !== '') {
                    selectId = item.id;
                }
                $(this).addClass('selected');
            });

            // 事件委托，处理收藏图标点击事件
            // $('#content').on('click', '.favorite', function (event) {
            //     event.stopPropagation(); // 阻止事件冒泡
            //     const itemData = $(this).closest('.list-item').data('item');
            // });
        });


        /**
         * 提交收藏
         */
        layui.form.on("submit(save)", function (obj) {
            if (selectId == undefined || selectId == null || selectId == '') {
                return false;
            }
            $.ajax({
                url: INSERT_FAVORITE_API,
                type: 'POST',
                dataType: 'json',
                data: {process_define_id: selectId},
                success: function (ret) {
                    if (ret.code == 0) {
                        return layui.popup.success("操作成功", function () {
                            //重新渲染还是原来的页码
                            getFavoriteList();//更新收藏列表集合
                            template(renderData);//更新渲染
                        });

                    }
                }
            });

        });


        renderContent({});
    });
</script>
</body>
</html>
