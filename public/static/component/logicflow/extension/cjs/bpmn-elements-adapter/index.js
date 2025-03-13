"use strict";
var __extends = (this && this.__extends) || (function () {
    var extendStatics = function (d, b) {
        extendStatics = Object.setPrototypeOf ||
            ({ __proto__: [] } instanceof Array && function (d, b) { d.__proto__ = b; }) ||
            function (d, b) { for (var p in b) if (b.hasOwnProperty(p)) d[p] = b[p]; };
        return extendStatics(d, b);
    };
    return function (d, b) {
        extendStatics(d, b);
        function __() { this.constructor = d; }
        d.prototype = b === null ? Object.create(b) : (__.prototype = b.prototype, new __());
    };
})();
var __assign = (this && this.__assign) || function () {
    __assign = Object.assign || function(t) {
        for (var s, i = 1, n = arguments.length; i < n; i++) {
            s = arguments[i];
            for (var p in s) if (Object.prototype.hasOwnProperty.call(s, p))
                t[p] = s[p];
        }
        return t;
    };
    return __assign.apply(this, arguments);
};
var __rest = (this && this.__rest) || function (s, e) {
    var t = {};
    for (var p in s) if (Object.prototype.hasOwnProperty.call(s, p) && e.indexOf(p) < 0)
        t[p] = s[p];
    if (s != null && typeof Object.getOwnPropertySymbols === "function")
        for (var i = 0, p = Object.getOwnPropertySymbols(s); i < p.length; i++) {
            if (e.indexOf(p[i]) < 0 && Object.prototype.propertyIsEnumerable.call(s, p[i]))
                t[p[i]] = s[p[i]];
        }
    return t;
};
var __values = (this && this.__values) || function(o) {
    var s = typeof Symbol === "function" && Symbol.iterator, m = s && o[s], i = 0;
    if (m) return m.call(o);
    if (o && typeof o.length === "number") return {
        next: function () {
            if (o && i >= o.length) o = void 0;
            return { value: o && o[i++], done: !o };
        }
    };
    throw new TypeError(s ? "Object is not iterable." : "Symbol.iterator is not defined.");
};
var __read = (this && this.__read) || function (o, n) {
    var m = typeof Symbol === "function" && o[Symbol.iterator];
    if (!m) return o;
    var i = m.call(o), r, ar = [], e;
    try {
        while ((n === void 0 || n-- > 0) && !(r = i.next()).done) ar.push(r.value);
    }
    catch (error) { e = { error: error }; }
    finally {
        try {
            if (r && !r.done && (m = i["return"])) m.call(i);
        }
        finally { if (e) throw e.error; }
    }
    return ar;
};
var __spread = (this && this.__spread) || function () {
    for (var ar = [], i = 0; i < arguments.length; i++) ar = ar.concat(__read(arguments[i]));
    return ar;
};
Object.defineProperty(exports, "__esModule", { value: true });
exports.convertXmlToNormal = exports.convertNormalToXml = exports.BPMNAdapter = exports.BPMNBaseAdapter = void 0;
/* eslint-disable func-names */
/* eslint-disable no-continue */
/* eslint-disable @typescript-eslint/naming-convention */
/* eslint-disable no-cond-assign */
/* eslint-disable no-shadow */
var lodash_es_1 = require("lodash-es");
var constant_1 = require("./constant");
var xml2json_1 = require("./xml2json");
var json2xml_1 = require("./json2xml");
var BpmnElements;
(function (BpmnElements) {
    BpmnElements["START"] = "bpmn:startEvent";
    BpmnElements["END"] = "bpmn:endEvent";
    BpmnElements["INTERMEDIATE_CATCH"] = "bpmn:intermediateCatchEvent";
    BpmnElements["INTERMEDIATE_THROW"] = "bpmn:intermediateThrowEvent";
    BpmnElements["BOUNDARY"] = "bpmn:boundaryEvent";
    BpmnElements["PARALLEL_GATEWAY"] = "bpmn:parallelGateway";
    BpmnElements["INCLUSIVE_GATEWAY"] = "bpmn:inclusiveGateway";
    BpmnElements["EXCLUSIVE_GATEWAY"] = "bpmn:exclusiveGateway";
    BpmnElements["USER"] = "bpmn:userTask";
    BpmnElements["SYSTEM"] = "bpmn:serviceTask";
    BpmnElements["FLOW"] = "bpmn:sequenceFlow";
    BpmnElements["SUBPROCESS"] = "bpmn:subProcess";
})(BpmnElements || (BpmnElements = {}));
var defaultAttrsForInput = [
    '-name',
    '-id',
    'bpmn:incoming',
    'bpmn:outgoing',
    '-sourceRef',
    '-targetRef',
    '-children',
];
var defaultRetainedProperties = [
    'properties',
    'startPoint',
    'endPoint',
    'pointsList',
];
var defaultExcludeFields = {
    in: [],
    out: [
        'properties.panels',
        'properties.nodeSize',
        'properties.definitionId',
        'properties.timerValue',
        'properties.timerType',
        'properties.definitionType',
        'properties.parent',
        'properties.isBoundaryEventTouchingTask',
    ],
};
var mergeInNOutObject = function (target, source) {
    var sourceKeys = Object.keys(source || {});
    sourceKeys.forEach(function (key) {
        if (target[key]) {
            var _a = source[key], fnIn = _a.in, fnOut = _a.out;
            if (fnIn) {
                target[key].in = fnIn;
            }
            if (fnOut) {
                target[key].out = fnOut;
            }
        }
        else {
            target[key] = source[key];
        }
    });
    return target;
};
// @ts-ignore
var defaultTransformer = {
    'bpmn:startEvent': {
        out: function (data) {
            var _a, _b;
            var properties = data.properties;
            return ((_b = (_a = defaultTransformer[properties.definitionType]) === null || _a === void 0 ? void 0 : _a.out) === null || _b === void 0 ? void 0 : _b.call(_a, data)) || {};
        },
    },
    // 'bpmn:endEvent': undefined,
    'bpmn:intermediateCatchEvent': {
        out: function (data) {
            var _a, _b;
            var properties = data.properties;
            return ((_b = (_a = defaultTransformer[properties.definitionType]) === null || _a === void 0 ? void 0 : _a.out) === null || _b === void 0 ? void 0 : _b.call(_a, data)) || {};
        },
    },
    'bpmn:intermediateThrowEvent': {
        out: function (data) {
            var _a, _b;
            var properties = data.properties;
            return ((_b = (_a = defaultTransformer[properties.definitionType]) === null || _a === void 0 ? void 0 : _a.out) === null || _b === void 0 ? void 0 : _b.call(_a, data)) || {};
        },
    },
    'bpmn:boundaryEvent': {
        out: function (data) {
            var _a, _b;
            var properties = data.properties;
            return ((_b = (_a = defaultTransformer[properties.definitionType]) === null || _a === void 0 ? void 0 : _a.out) === null || _b === void 0 ? void 0 : _b.call(_a, data)) || {};
        },
    },
    // 'bpmn:userTask': undefined,
    'bpmn:sequenceFlow': {
        out: function (data) {
            var _a = data.properties, expressionType = _a.expressionType, condition = _a.condition;
            if (condition) {
                if (expressionType === 'cdata') {
                    return {
                        json: "<bpmn:conditionExpression xsi:type=\"bpmn2:tFormalExpression\"><![CDATA[${" + condition + "}]]></bpmn:conditionExpression>",
                    };
                }
                return {
                    json: "<bpmn:conditionExpression xsi:type=\"bpmn2:tFormalExpression\">" + condition + "</bpmn:conditionExpression>",
                };
            }
            return {
                json: '',
            };
        },
    },
    // 'bpmn:subProcess': undefined,
    // 'bpmn:participant': undefined,
    'bpmn:timerEventDefinition': {
        out: function (data) {
            var _a = data.properties, timerType = _a.timerType, timerValue = _a.timerValue, definitionId = _a.definitionId;
            var typeFunc = function () { return "<bpmn:" + timerType + " xsi:type=\"bpmn:tFormalExpression\">" + timerValue + "</bpmn:" + timerType + ">"; };
            return {
                json: "<bpmn:timerEventDefinition id=\"" + definitionId + "\"" + (timerType && timerValue
                    ? ">" + typeFunc() + "</bpmn:timerEventDefinition>"
                    : '/>'),
            };
        },
        in: function (key, data) {
            var e_1, _a, _b;
            var _c;
            var definitionType = key;
            var definitionId = data['-id'];
            var timerType = '';
            var timerValue = '';
            try {
                for (var _d = __values(Object.keys(data)), _e = _d.next(); !_e.done; _e = _d.next()) {
                    var key_1 = _e.value;
                    if (key_1.includes('bpmn:')) {
                        _b = __read(key_1.split(':'), 2), timerType = _b[1];
                        timerValue = (_c = data[key_1]) === null || _c === void 0 ? void 0 : _c['#text'];
                    }
                }
            }
            catch (e_1_1) { e_1 = { error: e_1_1 }; }
            finally {
                try {
                    if (_e && !_e.done && (_a = _d.return)) _a.call(_d);
                }
                finally { if (e_1) throw e_1.error; }
            }
            return {
                '-definitionId': definitionId,
                '-definitionType': definitionType,
                '-timerType': timerType,
                '-timerValue': timerValue,
            };
        },
    },
    'bpmn:conditionExpression': {
        in: function (_key, data) {
            var _a;
            var condition = '';
            var expressionType = '';
            if (data['#cdata-section']) {
                expressionType = 'cdata';
                condition = ((_a = /^\$\{(.*)\}$/g.exec(data['#cdata-section'])) === null || _a === void 0 ? void 0 : _a[1]) || '';
            }
            else if (data['#text']) {
                expressionType = 'normal';
                condition = data['#text'];
            }
            return {
                '-condition': condition,
                '-expressionType': expressionType,
            };
        },
    },
};
/**
 * 将普通json转换为xmlJson
 * xmlJson中property会以“-”开头
 * 如果没有“-”表示为子节点
 * fix issue https://github.com/didi/LogicFlow/issues/718, contain the process of #text/#cdata and array
 * @reference node type reference https://www.w3schools.com/xml/dom_nodetype.asp
 * @param retainedAttrsFields retainedAttrsFields会和默认的defaultRetainedProperties:
 * ["properties", "startPoint", "endPoint", "pointsList"]合并
 * 这意味着出现在这个数组里的字段当它的值是数组或是对象时不会被视为一个节点而是一个属性
 * @param excludeFields excludeFields会和默认的defaultExcludeFields合并，出现在这个数组中的字段在转换时会被忽略
 * @param transformer 对应节点或者边的子内容转换规则
 */
function convertNormalToXml(other) {
    var _a = other !== null && other !== void 0 ? other : {}, retainedAttrsFields = _a.retainedAttrsFields, excludeFields = _a.excludeFields, transformer = _a.transformer;
    var retainedAttrsSet = new Set(__spread(defaultRetainedProperties, (retainedAttrsFields || [])));
    var excludeFieldsSet = {
        in: new Set(__spread(defaultExcludeFields.in, ((excludeFields === null || excludeFields === void 0 ? void 0 : excludeFields.in) || []))),
        out: new Set(__spread(defaultExcludeFields.out, ((excludeFields === null || excludeFields === void 0 ? void 0 : excludeFields.out) || []))),
    };
    defaultTransformer = mergeInNOutObject(defaultTransformer, transformer);
    return function (object) {
        var nodes = object.nodes;
        var edges = object.edges;
        function ToXmlJson(obj, path) {
            var e_2, _a;
            var _b;
            if ((obj === null || obj === void 0 ? void 0 : obj.flag) === 1) {
                return;
            }
            var fn;
            // @ts-ignore
            if ((fn = defaultTransformer[obj.type]) && fn.out) {
                var output_1 = fn.out(obj);
                var keys = Object.keys(output_1);
                if (keys.length > 0) {
                    keys.forEach(function (key) {
                        obj[key] = output_1[key];
                    });
                }
            }
            if (obj === null || obj === void 0 ? void 0 : obj.children) {
                obj.children = obj.children.map(function (key) {
                    var target = nodes.find(function (item) { return item.id === key; })
                        || edges.find(function (item) { return item.id === key; });
                    return target || {};
                });
            }
            var xmlJson = {};
            if (typeof obj === 'string') {
                return obj;
            }
            if (Array.isArray(obj)) {
                return (obj
                    .map(function (item) { return ToXmlJson(item, ''); })
                    // eslint-disable-next-line eqeqeq
                    .filter(function (item) { return item != undefined; }));
            }
            try {
                for (var _c = __values(Object.entries(obj)), _d = _c.next(); !_d.done; _d = _c.next()) {
                    var _e = __read(_d.value, 2), key = _e[0], value = _e[1];
                    if (((_b = value) === null || _b === void 0 ? void 0 : _b['flag']) === 1) {
                        return;
                    }
                    var newPath = [path, key].filter(function (item) { return item; }).join('.');
                    if (excludeFieldsSet.out.has(newPath)) {
                        continue;
                    }
                    else if (typeof value !== 'object') {
                        // node type reference https://www.w3schools.com/xml/dom_nodetype.asp
                        if (key.indexOf('-') === 0
                            || ['#text', '#cdata-section', '#comment'].includes(key)) {
                            xmlJson[key] = value;
                        }
                        else {
                            xmlJson["-" + key] = value;
                        }
                    }
                    else if (retainedAttrsSet.has(newPath)) {
                        xmlJson["-" + key] = ToXmlJson(value, newPath);
                    }
                    else {
                        xmlJson[key] = ToXmlJson(value, newPath);
                    }
                }
            }
            catch (e_2_1) { e_2 = { error: e_2_1 }; }
            finally {
                try {
                    if (_d && !_d.done && (_a = _c.return)) _a.call(_c);
                }
                finally { if (e_2) throw e_2.error; }
            }
            return xmlJson;
        }
        return ToXmlJson(object, '');
    };
}
exports.convertNormalToXml = convertNormalToXml;
/**
 * 将xmlJson转换为普通的json，在内部使用。
 */
function convertXmlToNormal(xmlJson) {
    var e_3, _a;
    var json = {};
    try {
        for (var _b = __values(Object.entries(xmlJson)), _c = _b.next(); !_c.done; _c = _b.next()) {
            var _d = __read(_c.value, 2), key = _d[0], value = _d[1];
            if (key.indexOf('-') === 0) {
                json[key.substring(1)] = json2xml_1.handleAttributes(value);
            }
            else if (typeof value === 'string') {
                json[key] = value;
            }
            else if (Object.prototype.toString.call(value) === '[object Object]') {
                json[key] = convertXmlToNormal(value);
            }
            else if (Array.isArray(value)) {
                // contain the process of array
                json[key] = value.map(function (v) { return convertXmlToNormal(v); });
            }
            else {
                json[key] = value;
            }
        }
    }
    catch (e_3_1) { e_3 = { error: e_3_1 }; }
    finally {
        try {
            if (_c && !_c.done && (_a = _b.return)) _a.call(_b);
        }
        finally { if (e_3) throw e_3.error; }
    }
    return json;
}
exports.convertXmlToNormal = convertXmlToNormal;
/**
 * 设置bpmn process信息
 * 目标格式请参考examples/bpmn.json
 * bpmn因为是默认基于xml格式的，其特点与json存在差异。
 * 1) 如果是xml的属性，json中属性用'-'开头
 * 2）如果只有一个子元素，json中表示为正常属性
 * 3）如果是多个子元素，json中使用数组存储
 */
function convertLf2ProcessData(bpmnData, data, other) {
    var _a;
    var nodeIdMap = new Map();
    var xmlJsonData = convertNormalToXml(other)(data);
    xmlJsonData.nodes.forEach(function (node) {
        var nodeId = node["-id"], nodeType = node["-type"], text = node.text, children = node.children, otherProps = __rest(node, ['-id', '-type', "text", "children"]);
        var processNode = { '-id': nodeId };
        if (text === null || text === void 0 ? void 0 : text['-value']) {
            processNode['-name'] = text['-value'];
        }
        if (otherProps['-json']) {
            processNode['-json'] = otherProps['-json'];
        }
        if (otherProps['-properties']) {
            Object.assign(processNode, otherProps['-properties']);
        }
        if (children) {
            processNode.children = children;
        }
        // (bpmnData[nodeType] ??= []).push(processNode);
        if (!bpmnData[nodeType]) {
            bpmnData[nodeType] = [];
        }
        bpmnData[nodeType].push(processNode);
        nodeIdMap.set(nodeId, processNode);
    });
    var sequenceFlow = xmlJsonData.edges.map(function (edge) {
        var id = edge["-id"], type = edge["-type"], sourceNodeId = edge["-sourceNodeId"], targetNodeId = edge["-targetNodeId"], text = edge.text, otherProps = __rest(edge, ['-id', '-type', '-sourceNodeId', '-targetNodeId', "text"]);
        var targetNode = nodeIdMap.get(targetNodeId);
        // (targetNode['bpmn:incoming'] ??= []).push(id);
        if (!targetNode['bpmn:incoming']) {
            targetNode['bpmn:incoming'] = [];
        }
        targetNode['bpmn:incoming'].push(id);
        var edgeConfig = {
            '-id': id,
            '-sourceRef': sourceNodeId,
            '-targetRef': targetNodeId,
        };
        if (text === null || text === void 0 ? void 0 : text['-value']) {
            edgeConfig['-name'] = text['-value'];
        }
        if (otherProps['-json']) {
            edgeConfig['-json'] = otherProps['-json'];
        }
        if (otherProps['-properties']) {
            Object.assign(edgeConfig, otherProps['-properties']);
        }
        return edgeConfig;
    });
    // @see https://github.com/didi/LogicFlow/issues/325
    // 需要保证incoming在outgoing之前
    data.edges.forEach(function (_a) {
        var sourceNodeId = _a.sourceNodeId, id = _a.id;
        var sourceNode = nodeIdMap.get(sourceNodeId);
        // (sourceNode['bpmn:outgoing'] ??= []).push(id);
        if (!sourceNode['bpmn:outgoing']) {
            sourceNode['bpmn:outgoing'] = [];
        }
        sourceNode['bpmn:outgoing'].push(id);
    });
    (_a = bpmnData['bpmn:subProcess']) === null || _a === void 0 ? void 0 : _a.forEach(function (item) {
        var setMap = {
            'bpmn:incoming': new Set(),
            'bpmn:outgoing': new Set(),
        };
        var edgesInSubProcess = [];
        item.children.forEach(function (child) {
            var _a;
            var target = nodeIdMap.get(child['-id']);
            ['bpmn:incoming', 'bpmn:outgoing'].forEach(function (key) {
                target[key]
                    && target[key].forEach(function (value) {
                        setMap[key].add(value);
                    });
            });
            var index = (_a = bpmnData[child['-type']]) === null || _a === void 0 ? void 0 : _a.findIndex(function (_item) { return _item['-id'] === child['-id']; });
            if (index >= 0) {
                bpmnData[child['-type']].splice(index, 1);
            }
            nodeIdMap.delete(child['-id']);
            // (item[child['-type']] ??= []).push(target);
            if (!item[child['-type']]) {
                item[child['-type']] = [];
            }
            item[child['-type']].push(target);
        });
        var incomingSet = setMap["bpmn:incoming"], outgoingSet = setMap["bpmn:outgoing"];
        outgoingSet.forEach(function (value) {
            incomingSet.has(value) && edgesInSubProcess.push(value);
        });
        var _loop_1 = function (i) {
            var index = sequenceFlow.findIndex(function (item) { return item['-id'] === edgesInSubProcess[i]; });
            if (index >= 0) {
                // (item['bpmn:sequenceFlow'] ??= []).push(sequenceFlow[index]);
                if (!item['bpmn:sequenceFlow']) {
                    item['bpmn:sequenceFlow'] = [];
                }
                item['bpmn:sequenceFlow'].push(sequenceFlow[index]);
                sequenceFlow.splice(index, 1);
            }
            else {
                i++;
            }
            out_i_1 = i;
        };
        var out_i_1;
        for (var i = 0; i < edgesInSubProcess.length;) {
            _loop_1(i);
            i = out_i_1;
        }
        delete item.children;
    });
    bpmnData[BpmnElements.FLOW] = sequenceFlow;
    return bpmnData;
}
/**
 * adapterOut 设置bpmn diagram信息
 */
function convertLf2DiagramData(bpmnDiagramData, data) {
    bpmnDiagramData['bpmndi:BPMNEdge'] = data.edges.map(function (edge) {
        var _a;
        var edgeId = edge.id;
        var pointsList = edge.pointsList.map(function (_a) {
            var x = _a.x, y = _a.y;
            return ({
                '-x': x,
                '-y': y,
            });
        });
        var diagramData = {
            '-id': edgeId + "_di",
            '-bpmnElement': edgeId,
            'di:waypoint': pointsList,
        };
        if ((_a = edge.text) === null || _a === void 0 ? void 0 : _a.value) {
            diagramData['bpmndi:BPMNLabel'] = {
                'dc:Bounds': {
                    '-x': edge.text.x - (edge.text.value.length * 10) / 2,
                    '-y': edge.text.y - 7,
                    '-width': edge.text.value.length * 10,
                    '-height': 14,
                },
            };
        }
        return diagramData;
    });
    bpmnDiagramData['bpmndi:BPMNShape'] = data.nodes.map(function (node) {
        var _a;
        var nodeId = node.id;
        var width = 100;
        var height = 80;
        var x = node.x, y = node.y;
        // bpmn坐标是基于左上角，LogicFlow基于中心点，此处处理一下。
        var shapeConfig = BPMNBaseAdapter.shapeConfigMap.get(node.type);
        if (shapeConfig) {
            width = shapeConfig.width;
            height = shapeConfig.height;
        }
        x -= width / 2;
        y -= height / 2;
        var diagramData = {
            '-id': nodeId + "_di",
            '-bpmnElement': nodeId,
            'dc:Bounds': {
                '-x': x,
                '-y': y,
                '-width': width,
                '-height': height,
            },
        };
        if ((_a = node.text) === null || _a === void 0 ? void 0 : _a.value) {
            diagramData['bpmndi:BPMNLabel'] = {
                'dc:Bounds': {
                    '-x': node.text.x - (node.text.value.length * 10) / 2,
                    '-y': node.text.y - 7,
                    '-width': node.text.value.length * 10,
                    '-height': 14,
                },
            };
        }
        return diagramData;
    });
}
var ignoreType = ['bpmn:incoming', 'bpmn:outgoing'];
/**
 * 将bpmn数据转换为LogicFlow内部能识别数据
 */
function convertBpmn2LfData(bpmnData, other) {
    var nodes = [];
    var edges = [];
    var eleMap = new Map();
    var _a = other !== null && other !== void 0 ? other : {}, transformer = _a.transformer, excludeFields = _a.excludeFields;
    var excludeFieldsSet = {
        in: new Set(__spread(defaultExcludeFields.in, ((excludeFields === null || excludeFields === void 0 ? void 0 : excludeFields.in) || []))),
        out: new Set(__spread(defaultExcludeFields.out, ((excludeFields === null || excludeFields === void 0 ? void 0 : excludeFields.out) || []))),
    };
    defaultTransformer = mergeInNOutObject(defaultTransformer, transformer);
    var definitions = bpmnData['bpmn:definitions'];
    if (definitions) {
        var process_1 = definitions['bpmn:process'];
        (function (data, callbacks) {
            callbacks.forEach(function (callback) {
                try {
                    Object.keys(data).forEach(function (key) {
                        try {
                            callback(key);
                        }
                        catch (error) {
                            console.error(error);
                        }
                    });
                }
                catch (error) {
                    console.error(error);
                }
            });
        }(process_1, [
            function (key) {
                // 将bpmn:subProcess中的数据提升到process中
                function subProcessProcessing(data) {
                    // data['-children'] ??= [];
                    if (!data['-children']) {
                        data['-children'] = [];
                    }
                    Object.keys(data).forEach(function (key) {
                        var _a;
                        if (key.indexOf('bpmn:') === 0 && !ignoreType.includes(key)) {
                            // process[key] ??= [];
                            if (!process_1[key]) {
                                process_1[key] = [];
                            }
                            !Array.isArray(process_1[key]) && (process_1[key] = [process_1[key]]);
                            Array.isArray(data[key])
                                ? (_a = process_1[key]).push.apply(_a, __spread(data[key])) : process_1[key].push(data[key]);
                            if (Array.isArray(data[key])) {
                                data[key].forEach(function (item) {
                                    !key.includes('Flow') && data['-children'].push(item['-id']);
                                });
                            }
                            else {
                                !key.includes('Flow')
                                    && data['-children'].push(data[key]['-id']);
                            }
                            delete data[key];
                        }
                    });
                }
                if (key === 'bpmn:subProcess') {
                    var data = process_1[key];
                    if (Array.isArray(data)) {
                        data.forEach(function (item) {
                            key === 'bpmn:subProcess' && subProcessProcessing(item);
                        });
                    }
                    else {
                        subProcessProcessing(data);
                    }
                }
            },
            function (key) {
                // 处理被提升的节点、边, 主要是通过definitionTransformer处理出节点的属性
                var fn = function (obj) {
                    Object.keys(obj).forEach(function (key) {
                        var _a, _b;
                        if (key.includes('bpmn:')) {
                            var props_1 = {};
                            if (defaultTransformer[key] && defaultTransformer[key].in) {
                                props_1 = (_b = (_a = defaultTransformer[key]).in) === null || _b === void 0 ? void 0 : _b.call(_a, key, lodash_es_1.default.cloneDeep(obj[key]));
                                delete obj[key];
                            }
                            else {
                                func(obj[key]);
                            }
                            var keys = void 0;
                            if ((keys = Reflect.ownKeys(props_1)).length > 0) {
                                keys.forEach(function (key) {
                                    Reflect.set(obj, key, props_1[key]);
                                });
                            }
                        }
                    });
                };
                function func(data) {
                    eleMap.set(data['-id'], data);
                    if (Array.isArray(data)) {
                        data.forEach(function (item) {
                            func(item);
                        });
                    }
                    else if (typeof data === 'object') {
                        fn(data);
                    }
                }
                func(process_1[key]);
            },
            function (key) {
                if (key.indexOf('bpmn:') === 0) {
                    var value = process_1[key];
                    if (key === 'bpmn:sequenceFlow') {
                        var bpmnEdges = definitions['bpmndi:BPMNDiagram']['bpmndi:BPMNPlane']['bpmndi:BPMNEdge'];
                        edges = getLfEdges(value, bpmnEdges);
                    }
                    else {
                        var shapes = definitions['bpmndi:BPMNDiagram']['bpmndi:BPMNPlane']['bpmndi:BPMNShape'];
                        if (key === 'bpmn:boundaryEvent') {
                            var data = process_1[key];
                            var fn_1 = function (item) {
                                var attachedToRef = item["-attachedToRef"];
                                var attachedToNode = eleMap.get(attachedToRef);
                                // attachedToNode['-boundaryEvents'] ??= [];
                                if (!attachedToNode['-boundaryEvents']) {
                                    attachedToNode['-boundaryEvents'] = [];
                                }
                                attachedToNode['-boundaryEvents'].push(item['-id']);
                            };
                            if (Array.isArray(data)) {
                                data.forEach(function (item) {
                                    fn_1(item);
                                });
                            }
                            else {
                                fn_1(data);
                            }
                        }
                        nodes = nodes.concat(getLfNodes(value, shapes, key));
                    }
                }
            },
        ]));
    }
    var ignoreFields = function (obj, filterSet, path) {
        Object.keys(obj).forEach(function (key) {
            var tmpPath = path ? path + "." + key : key;
            if (filterSet.has(tmpPath)) {
                delete obj[key];
            }
            else if (typeof obj[key] === 'object') {
                ignoreFields(obj[key], filterSet, tmpPath);
            }
        });
    };
    nodes.forEach(function (node) {
        var _a, _b;
        if ((_a = other === null || other === void 0 ? void 0 : other.mapping) === null || _a === void 0 ? void 0 : _a.in) {
            var mapping = (_b = other === null || other === void 0 ? void 0 : other.mapping) === null || _b === void 0 ? void 0 : _b.in;
            var type = node.type;
            if (mapping[type]) {
                node.type = mapping[type];
            }
        }
        ignoreFields(node, excludeFieldsSet.in, '');
        // Object.keys(node.properties).forEach((key) => {
        //   excludeFieldsSet.in.has(key) && delete node.properties[key];
        // });
    });
    edges.forEach(function (edge) {
        var _a, _b;
        if ((_a = other === null || other === void 0 ? void 0 : other.mapping) === null || _a === void 0 ? void 0 : _a.in) {
            var mapping = (_b = other === null || other === void 0 ? void 0 : other.mapping) === null || _b === void 0 ? void 0 : _b.in;
            var type = edge.type;
            if (mapping[type]) {
                edge.type = mapping[type];
            }
        }
        ignoreFields(edge, excludeFieldsSet.in, '');
        // Object.keys(edge.properties).forEach((key) => {
        //   excludeFieldsSet.in.has(key) && delete edge.properties[key];
        // });
    });
    return {
        nodes: nodes,
        edges: edges,
    };
}
function getLfNodes(value, shapes, key) {
    var nodes = [];
    if (Array.isArray(value)) {
        // 数组
        value.forEach(function (val) {
            var shapeValue;
            if (Array.isArray(shapes)) {
                shapeValue = shapes.find(function (shape) { return shape['-bpmnElement'] === val['-id']; });
            }
            else {
                shapeValue = shapes;
            }
            var node = getNodeConfig(shapeValue, key, val);
            nodes.push(node);
        });
    }
    else {
        var shapeValue = void 0;
        if (Array.isArray(shapes)) {
            shapeValue = shapes.find(function (shape) { return shape['-bpmnElement'] === value['-id']; });
        }
        else {
            shapeValue = shapes;
        }
        var node = getNodeConfig(shapeValue, key, value);
        nodes.push(node);
    }
    return nodes;
}
function getNodeConfig(shapeValue, type, processValue) {
    var x = Number(shapeValue['dc:Bounds']['-x']);
    var y = Number(shapeValue['dc:Bounds']['-y']);
    var children = processValue["-children"];
    var name = processValue['-name'];
    var shapeConfig = BPMNBaseAdapter.shapeConfigMap.get(type);
    if (shapeConfig) {
        x += shapeConfig.width / 2;
        y += shapeConfig.height / 2;
    }
    var properties = {};
    // 判断是否存在额外的属性，将额外的属性放到properties中
    Object.entries(processValue).forEach(function (_a) {
        var _b = __read(_a, 2), key = _b[0], value = _b[1];
        if (!defaultAttrsForInput.includes(key)) {
            properties[key] = value;
        }
    });
    properties = convertXmlToNormal(properties);
    var text;
    if (name) {
        text = {
            x: x,
            y: y,
            value: name,
        };
        // 自定义文本位置
        if (shapeValue['bpmndi:BPMNLabel'] && shapeValue['bpmndi:BPMNLabel']['dc:Bounds']) {
            var textBounds = shapeValue['bpmndi:BPMNLabel']['dc:Bounds'];
            text.x = Number(textBounds['-x']) + Number(textBounds['-width']) / 2;
            text.y = Number(textBounds['-y']) + Number(textBounds['-height']) / 2;
        }
    }
    var nodeConfig = {
        id: shapeValue['-bpmnElement'],
        type: type,
        x: x,
        y: y,
        properties: properties,
    };
    children && (nodeConfig.children = children);
    if (text) {
        nodeConfig.text = text;
    }
    return nodeConfig;
}
function getLfEdges(value, bpmnEdges) {
    var edges = [];
    if (Array.isArray(value)) {
        value.forEach(function (val) {
            var edgeValue;
            if (Array.isArray(bpmnEdges)) {
                edgeValue = bpmnEdges.find(function (edge) { return edge['-bpmnElement'] === val['-id']; });
            }
            else {
                edgeValue = bpmnEdges;
            }
            edges.push(getEdgeConfig(edgeValue, val));
        });
    }
    else {
        var edgeValue = void 0;
        if (Array.isArray(bpmnEdges)) {
            edgeValue = bpmnEdges.find(function (edge) { return edge['-bpmnElement'] === value['-id']; });
        }
        else {
            edgeValue = bpmnEdges;
        }
        edges.push(getEdgeConfig(edgeValue, value));
    }
    return edges;
}
function getEdgeConfig(edgeValue, processValue) {
    var text;
    var textVal = processValue['-name'];
    if (textVal) {
        var textBounds = edgeValue['bpmndi:BPMNLabel']['dc:Bounds'];
        // 如果边文本换行，则其偏移量应该是最长一行的位置
        var textLength_1 = 0;
        textVal.split('\n').forEach(function (textSpan) {
            if (textLength_1 < textSpan.length) {
                textLength_1 = textSpan.length;
            }
        });
        text = {
            value: textVal,
            x: Number(textBounds['-x']) + (textLength_1 * 10) / 2,
            y: Number(textBounds['-y']) + 7,
        };
    }
    var properties = {};
    // 判断是否存在额外的属性，将额外的属性放到properties中
    Object.entries(processValue).forEach(function (_a) {
        var _b = __read(_a, 2), key = _b[0], value = _b[1];
        if (!defaultAttrsForInput.includes(key)) {
            properties[key] = value;
        }
    });
    properties = convertXmlToNormal(properties);
    var pointsList = edgeValue['di:waypoint'].map(function (point) { return ({
        x: Number(point['-x']),
        y: Number(point['-y']),
    }); });
    var edge = {
        id: processValue['-id'],
        type: BpmnElements.FLOW,
        pointsList: pointsList,
        sourceNodeId: processValue['-sourceRef'],
        targetNodeId: processValue['-targetRef'],
        properties: properties,
    };
    if (text) {
        edge.text = text;
    }
    return edge;
}
var BPMNBaseAdapter = /** @class */ (function () {
    function BPMNBaseAdapter(_a) {
        var _this = this;
        var lf = _a.lf;
        /**
         * @param retainedAttrsFields?: string[] (可选)属性保留字段，retainedField会和默认的defaultRetainedFields:
         * ["properties", "startPoint", "endPoint", "pointsList"]合并，
         * 这意味着出现在这个数组里的字段当它的值是数组或是对象时不会被视为一个节点而是一个属性。
         * @param excludeFields excludeFields会和默认的defaultExcludeFields合并，出现在这个数组中的字段在转换时会被忽略
         * @param transformer 对应节点或者边的内容转换规则
         */
        this.adapterOut = function (data, other) {
            var _a, _b;
            var bpmnProcessData = __assign({}, _this.processAttributes);
            convertLf2ProcessData(bpmnProcessData, data, other);
            var bpmnDiagramData = {
                '-id': 'BPMNPlane_1',
                '-bpmnElement': bpmnProcessData['-id'],
            };
            convertLf2DiagramData(bpmnDiagramData, data);
            var definitions = _this.definitionAttributes;
            definitions['bpmn:process'] = bpmnProcessData;
            definitions['bpmndi:BPMNDiagram'] = {
                '-id': 'BPMNDiagram_1',
                'bpmndi:BPMNPlane': bpmnDiagramData,
            };
            var bpmnData = {
                'bpmn:definitions': definitions,
            };
            if ((_a = other === null || other === void 0 ? void 0 : other.mapping) === null || _a === void 0 ? void 0 : _a.out) {
                var mapping_1 = (_b = other === null || other === void 0 ? void 0 : other.mapping) === null || _b === void 0 ? void 0 : _b.out;
                var nameMapping_1 = function (obj) {
                    if (Array.isArray(obj)) {
                        obj.forEach(function (item) { return nameMapping_1(item); });
                    }
                    if (typeof obj === 'object') {
                        Object.keys(obj).forEach(function (key) {
                            var mappingName;
                            if (mappingName = mapping_1[key]) {
                                obj[mappingName] = lodash_es_1.default.cloneDeep(obj[key]);
                                delete obj[key];
                                nameMapping_1(obj[mappingName]);
                            }
                            else {
                                nameMapping_1(obj[key]);
                            }
                        });
                    }
                };
                nameMapping_1(bpmnData);
            }
            return bpmnData;
        };
        this.adapterIn = function (bpmnData, other) {
            if (bpmnData) {
                return convertBpmn2LfData(bpmnData, other);
            }
        };
        lf.adapterIn = this.adapterIn;
        lf.adapterOut = this.adapterOut;
        this.processAttributes = {
            '-isExecutable': 'true',
            '-id': 'Process',
        };
        this.definitionAttributes = {
            '-id': 'Definitions',
            '-xmlns:xsi': 'http://www.w3.org/2001/XMLSchema-instance',
            '-xmlns:bpmn': 'http://www.omg.org/spec/BPMN/20100524/MODEL',
            '-xmlns:bpmndi': 'http://www.omg.org/spec/BPMN/20100524/DI',
            '-xmlns:dc': 'http://www.omg.org/spec/DD/20100524/DC',
            '-xmlns:di': 'http://www.omg.org/spec/DD/20100524/DI',
            '-targetNamespace': 'http://logic-flow.org',
            '-exporter': 'logicflow',
            '-exporterVersion': '1.2.10',
        };
    }
    BPMNBaseAdapter.prototype.setCustomShape = function (key, val) {
        BPMNBaseAdapter.shapeConfigMap.set(key, val);
    };
    BPMNBaseAdapter.pluginName = 'bpmn-adapter';
    BPMNBaseAdapter.shapeConfigMap = new Map();
    return BPMNBaseAdapter;
}());
exports.BPMNBaseAdapter = BPMNBaseAdapter;
BPMNBaseAdapter.shapeConfigMap.set(BpmnElements.START, {
    width: constant_1.StartEventConfig.width,
    height: constant_1.StartEventConfig.height,
});
BPMNBaseAdapter.shapeConfigMap.set(BpmnElements.END, {
    width: constant_1.EndEventConfig.width,
    height: constant_1.EndEventConfig.height,
});
BPMNBaseAdapter.shapeConfigMap.set(BpmnElements.INTERMEDIATE_CATCH, {
    width: constant_1.IntermediateEventConfig.width,
    height: constant_1.IntermediateEventConfig.height,
});
BPMNBaseAdapter.shapeConfigMap.set(BpmnElements.INTERMEDIATE_THROW, {
    width: constant_1.IntermediateEventConfig.width,
    height: constant_1.IntermediateEventConfig.height,
});
BPMNBaseAdapter.shapeConfigMap.set(BpmnElements.BOUNDARY, {
    width: constant_1.BoundaryEventConfig.width,
    height: constant_1.BoundaryEventConfig.height,
});
BPMNBaseAdapter.shapeConfigMap.set(BpmnElements.PARALLEL_GATEWAY, {
    width: constant_1.ParallelGatewayConfig.width,
    height: constant_1.ParallelGatewayConfig.height,
});
BPMNBaseAdapter.shapeConfigMap.set(BpmnElements.INCLUSIVE_GATEWAY, {
    width: constant_1.InclusiveGatewayConfig.width,
    height: constant_1.InclusiveGatewayConfig.height,
});
BPMNBaseAdapter.shapeConfigMap.set(BpmnElements.EXCLUSIVE_GATEWAY, {
    width: constant_1.ExclusiveGatewayConfig.width,
    height: constant_1.ExclusiveGatewayConfig.height,
});
BPMNBaseAdapter.shapeConfigMap.set(BpmnElements.SYSTEM, {
    width: constant_1.ServiceTaskConfig.width,
    height: constant_1.ServiceTaskConfig.height,
});
BPMNBaseAdapter.shapeConfigMap.set(BpmnElements.USER, {
    width: constant_1.UserTaskConfig.width,
    height: constant_1.UserTaskConfig.height,
});
BPMNBaseAdapter.shapeConfigMap.set(BpmnElements.SUBPROCESS, {
    width: constant_1.SubProcessConfig.width,
    height: constant_1.SubProcessConfig.height,
});
var BPMNAdapter = /** @class */ (function (_super) {
    __extends(BPMNAdapter, _super);
    function BPMNAdapter(data) {
        var _this = _super.call(this, data) || this;
        _this.adapterXmlIn = function (bpmnData) {
            var json = xml2json_1.lfXml2Json(bpmnData);
            return _this.adapterIn(json, _this.props);
        };
        _this.adapterXmlOut = function (data) {
            var outData = _this.adapterOut(data, _this.props);
            return json2xml_1.lfJson2Xml(outData);
        };
        var lf = data.lf, props = data.props;
        lf.adapterIn = _this.adapterXmlIn;
        lf.adapterOut = _this.adapterXmlOut;
        _this.props = props;
        return _this;
    }
    BPMNAdapter.pluginName = 'BPMNAdapter';
    return BPMNAdapter;
}(BPMNBaseAdapter));
exports.BPMNAdapter = BPMNAdapter;
exports.default = BPMNAdapter;
