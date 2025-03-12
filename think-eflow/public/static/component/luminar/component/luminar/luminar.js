window.rootPath = (function (src) {
    src = document.currentScript
        ? document.currentScript.src
        : document.scripts[document.scripts.length - 1].src;
    return src.substring(0, src.lastIndexOf("/") + 1);
})();


layui.config({
    base: rootPath + "modules/",
}).extend({
    formField: "formField",
    staicField: "staicField",
    cron: 'cron',
    iconPicker: 'iconPicker',
    labelGeneration: 'labelGeneration',
    numberInput: 'numberInput',
    xmSelect:'xmSelect',
    formDesigner: "formDesigner",
}).use([], function () {
});
