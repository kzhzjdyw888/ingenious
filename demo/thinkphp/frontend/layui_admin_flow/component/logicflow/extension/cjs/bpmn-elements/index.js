"use strict";
var __createBinding = (this && this.__createBinding) || (Object.create ? (function(o, m, k, k2) {
    if (k2 === undefined) k2 = k;
    Object.defineProperty(o, k2, { enumerable: true, get: function() { return m[k]; } });
}) : (function(o, m, k, k2) {
    if (k2 === undefined) k2 = k;
    o[k2] = m[k];
}));
var __exportStar = (this && this.__exportStar) || function(m, exports) {
    for (var p in m) if (p !== "default" && !exports.hasOwnProperty(p)) __createBinding(exports, m, p);
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
Object.defineProperty(exports, "__esModule", { value: true });
exports.bpmnUtils = exports.icons = exports.BPMNElements = exports.useDefinition = void 0;
/* eslint-disable @typescript-eslint/naming-convention */
/* eslint-disable react-hooks/rules-of-hooks */
var Event_1 = require("./presets/Event");
var Gateway_1 = require("./presets/Gateway");
var Task_1 = require("./presets/Task");
// import { registerPoolNodes } from './presets/Pool';
var Flow_1 = require("./presets/Flow");
var icons_1 = require("./presets/icons");
var icons = require("./presets/icons");
exports.icons = icons;
var bpmnUtils = require("./utils");
exports.bpmnUtils = bpmnUtils;
var definitionConfig = [
    {
        nodes: ['startEvent', 'intermediateCatchEvent', 'boundaryEvent'],
        definition: [
            {
                type: 'bpmn:timerEventDefinition',
                icon: icons_1.timerIcon,
                properties: {
                    definitionType: 'bpmn:timerEventDefinition',
                    timerValue: '',
                    timerType: '',
                },
            },
        ],
    },
];
function useDefinition(definition) {
    function setDefinition(config) {
        function set(nodes, definitions) {
            nodes.forEach(function (name) {
                if (!(definition === null || definition === void 0 ? void 0 : definition[name])) {
                    definition[name] = new Map();
                }
                var map = definition === null || definition === void 0 ? void 0 : definition[name];
                definitions.forEach(function (define) {
                    map.set(define.type, define);
                });
            });
            return definition;
        }
        config.forEach(function (define) {
            set(define.nodes, define.definition);
        });
    }
    return function () { return [definition, setDefinition]; };
}
exports.useDefinition = useDefinition;
var BPMNElements = /** @class */ (function () {
    function BPMNElements(_a) {
        var lf = _a.lf;
        lf.definition = {};
        lf.useDefinition = useDefinition(lf.definition);
        var _b = __read(lf.useDefinition(), 2), _definition = _b[0], setDefinition = _b[1];
        setDefinition(definitionConfig);
        Event_1.registerEventNodes(lf);
        Gateway_1.registerGatewayNodes(lf);
        Flow_1.registerFlows(lf);
        Task_1.registerTaskNodes(lf);
        lf.setDefaultEdgeType('bpmn:sequenceFlow');
    }
    BPMNElements.pluginName = 'BpmnElementsPlugin';
    return BPMNElements;
}());
exports.BPMNElements = BPMNElements;
__exportStar(require("./presets/Event/EndEventFactory"), exports);
__exportStar(require("./presets/Event/IntermediateCatchEvent"), exports);
__exportStar(require("./presets/Event/StartEventFactory"), exports);
__exportStar(require("./presets/Event/boundaryEventFactory"), exports);
__exportStar(require("./presets/Event/IntermediateThrowEvent"), exports);
__exportStar(require("./presets/Flow/sequenceFlow"), exports);
__exportStar(require("./presets/Task/task"), exports);
__exportStar(require("./presets/Task/subProcess"), exports);
__exportStar(require("./presets/Gateway/gateway"), exports);
