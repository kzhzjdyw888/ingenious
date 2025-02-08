const config = {
    api_url: "http://127.0.0.1:8080",
    base_path: "/layui_admin_flow/",
}

window.errors = function (html) {
    let MODULE_PATH = 'view/exception/' + html;
    layer.open({
        type: 2,
        title: false,
        skin: 'layui-layer-win10', // 2.8+
        shadeClose: true, // 点击遮罩区域，关闭弹层
        closeBtn: false,
        maxmin: false,
        shade: 0.1,
        area: ['100%', '100%'],
        content: MODULE_PATH,
        cancel: function () {
        }
    });
}
