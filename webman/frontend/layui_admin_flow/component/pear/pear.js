window.rootPath = (function (src) {
	src = document.currentScript
		? document.currentScript.src
		: document.scripts[document.scripts.length - 1].src;
	return src.substring(0, src.lastIndexOf("/") + 1);
})();

layui.config({
	base: rootPath + "module/",
	version: "4.0.3"
}).extend({
	admin: "admin",
	page: "page",
	tabPage: "tabPage",
	menu: "menu",
	fullscreen: "fullscreen",
	messageCenter: "messageCenter",
	menuSearch: "menuSearch",
	button: "button",
	tools: "tools",
	popup: "extends/popup",
	count: "extends/count",
	toast: "extends/toast",
	nprogress: "extends/nprogress",
	echarts: "extends/echarts",
	echartsTheme: "extends/echartsTheme",
	yaml: "extends/yaml",
	encrypt:"extends/encrypt",
    theme: "other/theme",//主题
    common: "other/common",
    dtree: "other/dtree/dtree",// 树结构
    xmSelect: "other/xm-select",	// 下拉多选组件
    iconPicker: "other/iconPicker",
    notice: "other/notice",	// 消息提示组件
    design: "other/design",		// 表单设计

}).use([], function () { });