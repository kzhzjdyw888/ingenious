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
import { getBpmnId } from './bpmnIds';
import { handleAttributes, lfJson2Xml } from './json2xml';
import { lfXml2Json } from './xml2json';
import { ExclusiveGatewayConfig, StartEventConfig, EndEventConfig, ServiceTaskConfig, UserTaskConfig, } from '../bpmn/constant';
var BpmnElements;
(function (BpmnElements) {
    BpmnElements["START"] = "bpmn:startEvent";
    BpmnElements["END"] = "bpmn:endEvent";
    BpmnElements["GATEWAY"] = "bpmn:exclusiveGateway";
    BpmnElements["USER"] = "bpmn:userTask";
    BpmnElements["SYSTEM"] = "bpmn:serviceTask";
    BpmnElements["FLOW"] = "bpmn:sequenceFlow";
})(BpmnElements || (BpmnElements = {}));
var defaultAttrs = [
    '-name',
    '-id',
    'bpmn:incoming',
    'bpmn:outgoing',
    '-sourceRef',
    '-targetRef',
];
/**
 * 将普通json转换为xmlJson
 * xmlJson中property会以“-”开头
 * 如果没有“-”表示为子节点
 * fix issue https://github.com/didi/LogicFlow/issues/718, contain the process of #text/#cdata and array
 * @param retainedFields retainedField会和默认的defaultRetainedFields:
 * ["properties", "startPoint", "endPoint", "pointsList"]合并
 * 这意味着出现在这个数组里的字段当它的值是数组或是对象时不会被视为一个节点而是一个属性
 * @reference node type reference https://www.w3schools.com/xml/dom_nodetype.asp
 */
var defaultRetainedFields = ['properties', 'startPoint', 'endPoint', 'pointsList'];
function toXmlJson(retainedFields) {
    var fields = retainedFields
        ? defaultRetainedFields.concat(retainedFields) : defaultRetainedFields;
    return function (json) {
        function ToXmlJson(obj) {
            var xmlJson = {};
            if (typeof obj === 'string') {
                return obj;
            }
            if (Array.isArray(obj)) {
                return obj.map(function (j) { return ToXmlJson(j); });
            }
            Object.entries(obj).forEach(function (_a) {
                var _b = __read(_a, 2), key = _b[0], value = _b[1];
                if (typeof value !== 'object') {
                    // node type reference https://www.w3schools.com/xml/dom_nodetype.asp
                    if (key.indexOf('-') === 0
                        || ['#text', '#cdata-section', '#comment'].includes(key)) {
                        xmlJson[key] = value;
                    }
                    else {
                        xmlJson["-" + key] = value;
                    }
                }
                else if (fields.includes(key)) {
                    xmlJson["-" + key] = ToXmlJson(value);
                }
                else {
                    xmlJson[key] = ToXmlJson(value);
                }
            });
            return xmlJson;
        }
        return ToXmlJson(json);
    };
}
/**
 * 将xmlJson转换为普通的json，在内部使用。
 */
function toNormalJson(xmlJson) {
    var json = {};
    Object.entries(xmlJson).forEach(function (_a) {
        var _b = __read(_a, 2), key = _b[0], value = _b[1];
        if (key.indexOf('-') === 0) {
            json[key.substring(1)] = handleAttributes(value);
        }
        else if (typeof value === 'string') {
            json[key] = value;
        }
        else if (Object.prototype.toString.call(value) === '[object Object]') {
            json[key] = toNormalJson(value);
        }
        else if (Array.isArray(value)) {
            // contain the process of array
            json[key] = value.map(function (v) { return toNormalJson(v); });
        }
        else {
            json[key] = value;
        }
    });
    return json;
}
/**
 * 设置bpmn process信息
 * 目标格式请参考examples/bpmn.json
 * bpmn因为是默认基于xml格式的，其特点与json存在差异。
 * 1) 如果是xml的属性，json中属性用'-'开头
 * 2）如果只有一个子元素，json中表示为正常属性
 * 3）如果是多个子元素，json中使用数组存储
 */
function convertLf2ProcessData(bpmnProcessData, data, retainedFields) {
    var nodeMap = new Map();
    data.nodes.forEach(function (node) {
        var _a;
        var processNode = {
            '-id': node.id,
        };
        if ((_a = node.text) === null || _a === void 0 ? void 0 : _a.value) {
            processNode['-name'] = node.text.value;
        }
        if (node.properties) {
            var properties = toXmlJson(retainedFields)(node.properties);
            Object.assign(processNode, properties);
        }
        nodeMap.set(node.id, processNode);
        if (!bpmnProcessData[node.type]) {
            bpmnProcessData[node.type] = processNode; // 如果只有一个子元素，json中表示为正常属性
        }
        else if (Array.isArray(bpmnProcessData[node.type])) {
            // 如果是多个子元素，json中使用数组存储
            bpmnProcessData[node.type].push(processNode);
        }
        else {
            // 如果是多个子元素，json中使用数组存储
            bpmnProcessData[node.type] = [bpmnProcessData[node.type], processNode];
        }
    });
    var sequenceFlow = data.edges.map(function (edge) {
        var _a, _b;
        var targetNode = nodeMap.get(edge.targetNodeId);
        if (!targetNode['bpmn:incoming']) {
            targetNode['bpmn:incoming'] = edge.id;
        }
        else if (Array.isArray(targetNode['bpmn:incoming'])) {
            targetNode['bpmn:incoming'].push(edge.id);
        }
        else {
            targetNode['bpmn:incoming'] = [targetNode['bpmn:incoming'], edge.id];
        }
        var edgeConfig = {
            '-id': edge.id,
            '-sourceRef': edge.sourceNodeId,
            '-targetRef': edge.targetNodeId,
        };
        if ((_a = edge.text) === null || _a === void 0 ? void 0 : _a.value) {
            edgeConfig['-name'] = (_b = edge.text) === null || _b === void 0 ? void 0 : _b.value;
        }
        if (edge.properties) {
            var properties = toXmlJson(retainedFields)(edge.properties);
            Object.assign(edgeConfig, properties);
        }
        return edgeConfig;
    });
    // @see https://github.com/didi/LogicFlow/issues/325
    // 需要保证incoming在outgoing之前
    data.edges.forEach(function (edge) {
        var sourceNode = nodeMap.get(edge.sourceNodeId);
        if (!sourceNode['bpmn:outgoing']) {
            sourceNode['bpmn:outgoing'] = edge.id;
        }
        else if (Array.isArray(sourceNode['bpmn:outgoing'])) {
            sourceNode['bpmn:outgoing'].push(edge.id);
        }
        else {
            // 字符串转数组
            sourceNode['bpmn:outgoing'] = [sourceNode['bpmn:outgoing'], edge.id];
        }
    });
    bpmnProcessData[BpmnElements.FLOW] = sequenceFlow;
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
        var shapeConfig = BpmnAdapter.shapeConfigMap.get(node.type);
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
/**
 * 将bpmn数据转换为LogicFlow内部能识别数据
 */
function convertBpmn2LfData(bpmnData) {
    var nodes = [];
    var edges = [];
    var definitions = bpmnData['bpmn:definitions'];
    if (definitions) {
        var process_1 = definitions['bpmn:process'];
        Object.keys(process_1).forEach(function (key) {
            if (key.indexOf('bpmn:') === 0) {
                var value = process_1[key];
                if (key === BpmnElements.FLOW) {
                    var bpmnEdges = definitions['bpmndi:BPMNDiagram']['bpmndi:BPMNPlane']['bpmndi:BPMNEdge'];
                    edges = getLfEdges(value, bpmnEdges);
                }
                else {
                    var shapes = definitions['bpmndi:BPMNDiagram']['bpmndi:BPMNPlane']['bpmndi:BPMNShape'];
                    nodes = nodes.concat(getLfNodes(value, shapes, key));
                }
            }
        });
    }
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
    var name = processValue['-name'];
    var shapeConfig = BpmnAdapter.shapeConfigMap.get(type);
    if (shapeConfig) {
        x += shapeConfig.width / 2;
        y += shapeConfig.height / 2;
    }
    var properties;
    // 判断是否存在额外的属性，将额外的属性放到properties中
    Object.entries(processValue).forEach(function (_a) {
        var _b = __read(_a, 2), key = _b[0], value = _b[1];
        if (defaultAttrs.indexOf(key) === -1) {
            if (!properties)
                properties = {};
            properties[key] = value;
        }
    });
    if (properties) {
        properties = toNormalJson(properties);
    }
    var text;
    if (name) {
        text = {
            x: x,
            y: y,
            value: name,
        };
        // 自定义文本位置
        if (shapeValue['bpmndi:BPMNLabel']
            && shapeValue['bpmndi:BPMNLabel']['dc:Bounds']) {
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
    var properties;
    // 判断是否存在额外的属性，将额外的属性放到properties中
    Object.entries(processValue).forEach(function (_a) {
        var _b = __read(_a, 2), key = _b[0], value = _b[1];
        if (defaultAttrs.indexOf(key) === -1) {
            if (!properties)
                properties = {};
            properties[key] = value;
        }
    });
    if (properties) {
        properties = toNormalJson(properties);
    }
    var edge = {
        id: processValue['-id'],
        type: BpmnElements.FLOW,
        pointsList: edgeValue['di:waypoint'].map(function (point) { return ({
            x: Number(point['-x']),
            y: Number(point['-y']),
        }); }),
        sourceNodeId: processValue['-sourceRef'],
        targetNodeId: processValue['-targetRef'],
        properties: properties,
    };
    if (text) {
        edge.text = text;
    }
    return edge;
}
var BpmnAdapter = /** @class */ (function () {
    function BpmnAdapter(_a) {
        var _this = this;
        var lf = _a.lf;
        /**
         * @param retainedFields?: string[] (可选)属性保留字段，retainedField会和默认的defaultRetainedFields:
         * ["properties", "startPoint", "endPoint", "pointsList"]合并，
         * 这意味着出现在这个数组里的字段当它的值是数组或是对象时不会被视为一个节点而是一个属性。
         */
        this.adapterOut = function (data, retainedFields) {
            var bpmnProcessData = __assign({}, _this.processAttributes);
            convertLf2ProcessData(bpmnProcessData, data, retainedFields);
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
            return bpmnData;
        };
        this.adapterIn = function (bpmnData) {
            if (bpmnData) {
                return convertBpmn2LfData(bpmnData);
            }
        };
        lf.adapterIn = function (data) { return _this.adapterIn(data); };
        lf.adapterOut = function (data, retainedFields) { return _this.adapterOut(data, retainedFields); };
        this.processAttributes = {
            '-isExecutable': 'true',
            '-id': "Process_" + getBpmnId(),
        };
        this.definitionAttributes = {
            '-id': "Definitions_" + getBpmnId(),
            '-xmlns:xsi': 'http://www.w3.org/2001/XMLSchema-instance',
            '-xmlns:bpmn': 'http://www.omg.org/spec/BPMN/20100524/MODEL',
            '-xmlns:bpmndi': 'http://www.omg.org/spec/BPMN/20100524/DI',
            '-xmlns:dc': 'http://www.omg.org/spec/DD/20100524/DC',
            '-xmlns:di': 'http://www.omg.org/spec/DD/20100524/DI',
            '-targetNamespace': 'http://logic-flow.org',
            '-exporter': 'logicflow',
            '-exporterVersion': '1.2.0',
        };
    }
    BpmnAdapter.prototype.setCustomShape = function (key, val) {
        BpmnAdapter.shapeConfigMap.set(key, val);
    };
    BpmnAdapter.pluginName = 'bpmn-adapter';
    BpmnAdapter.shapeConfigMap = new Map();
    return BpmnAdapter;
}());
BpmnAdapter.shapeConfigMap.set(BpmnElements.START, {
    width: StartEventConfig.width,
    height: StartEventConfig.height,
});
BpmnAdapter.shapeConfigMap.set(BpmnElements.END, {
    width: EndEventConfig.width,
    height: EndEventConfig.height,
});
BpmnAdapter.shapeConfigMap.set(BpmnElements.GATEWAY, {
    width: ExclusiveGatewayConfig.width,
    height: ExclusiveGatewayConfig.height,
});
BpmnAdapter.shapeConfigMap.set(BpmnElements.SYSTEM, {
    width: ServiceTaskConfig.width,
    height: ServiceTaskConfig.height,
});
BpmnAdapter.shapeConfigMap.set(BpmnElements.USER, {
    width: UserTaskConfig.width,
    height: UserTaskConfig.height,
});
var BpmnXmlAdapter = /** @class */ (function (_super) {
    __extends(BpmnXmlAdapter, _super);
    function BpmnXmlAdapter(data) {
        var _this = _super.call(this, data) || this;
        _this.adapterXmlIn = function (bpmnData) {
            var json = lfXml2Json(bpmnData);
            return _this.adapterIn(json);
        };
        _this.adapterXmlOut = function (data, retainedFields) {
            var outData = _this.adapterOut(data, retainedFields);
            return lfJson2Xml(outData);
        };
        var lf = data.lf;
        lf.adapterIn = _this.adapterXmlIn;
        lf.adapterOut = _this.adapterXmlOut;
        return _this;
    }
    BpmnXmlAdapter.pluginName = 'bpmnXmlAdapter';
    return BpmnXmlAdapter;
}(BpmnAdapter));
export { BpmnAdapter, BpmnXmlAdapter, toXmlJson, toNormalJson };
export default BpmnAdapter;
