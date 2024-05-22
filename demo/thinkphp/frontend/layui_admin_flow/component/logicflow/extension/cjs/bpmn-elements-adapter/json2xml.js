"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
exports.handleAttributes = exports.lfJson2Xml = void 0;
/* eslint-disable guard-for-in */
function type(obj) {
    return Object.prototype.toString.call(obj);
}
function addSpace(depth) {
    return '  '.repeat(depth);
}
function handleAttributes(obj) {
    if (type(obj) === '[object Object]') {
        return Object.keys(obj).reduce(function (tmp, key) {
            var tmpKey = key;
            if (key.charAt(0) === '-') {
                tmpKey = key.substring(1);
            }
            tmp[tmpKey] = handleAttributes(obj[key]);
            return tmp;
        }, {});
    }
    if (Array.isArray(obj)) {
        return obj.map(function (item) { return handleAttributes(item); });
    }
    return obj;
}
exports.handleAttributes = handleAttributes;
function getAttributes(obj) {
    var tmp = obj;
    try {
        if (typeof tmp !== 'string') {
            tmp = JSON.parse(obj);
        }
    }
    catch (error) {
        tmp = JSON.stringify(handleAttributes(obj)).replace(/"/g, '\'');
    }
    return tmp;
}
var tn = '\t\n';
// @see issue https://github.com/didi/LogicFlow/issues/718, refactoring of function toXml
function toXml(obj, name, depth) {
    var frontSpace = addSpace(depth);
    var str = '';
    var prefix = tn + frontSpace;
    if (name === '-json')
        return '';
    if (name === '#text') {
        return prefix + obj;
    }
    if (name === '#cdata-section') {
        return prefix + "<![CDATA[" + obj + "]]>";
    }
    if (name === '#comment') {
        return prefix + "<!--" + obj + "-->";
    }
    if (("" + name).charAt(0) === '-') {
        return " " + name.substring(1) + "=\"" + getAttributes(obj) + "\"";
    }
    if (Array.isArray(obj)) {
        str += obj.map(function (item) { return toXml(item, name, depth + 1); }).join('');
    }
    else if (type(obj) === '[object Object]') {
        var keys = Object.keys(obj);
        var attributes_1 = '';
        var children_1 = obj['-json']
            ? tn + addSpace(depth + 1) + obj['-json']
            : '';
        str += (depth === 0 ? '' : prefix) + "<" + name;
        keys.forEach(function (k) {
            k.charAt(0) === '-'
                ? (attributes_1 += toXml(obj[k], k, depth + 1))
                : (children_1 += toXml(obj[k], k, depth + 1));
        });
        str
            += attributes_1
                + (children_1 !== '' ? ">" + children_1 + prefix + "</" + name + ">" : ' />');
    }
    else {
        str += prefix + "<" + name + ">" + obj.toString() + "</" + name + ">";
    }
    return str;
}
function lfJson2Xml(obj) {
    var xmlStr = '';
    for (var key in obj) {
        xmlStr += toXml(obj[key], key, 0);
    }
    return xmlStr;
}
exports.lfJson2Xml = lfJson2Xml;
