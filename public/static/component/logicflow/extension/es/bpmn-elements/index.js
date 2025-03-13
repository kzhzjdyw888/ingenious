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
/* eslint-disable @typescript-eslint/naming-convention */
/* eslint-disable react-hooks/rules-of-hooks */
import { registerEventNodes } from './presets/Event';
import { registerGatewayNodes } from './presets/Gateway';
import { registerTaskNodes } from './presets/Task';
// import { registerPoolNodes } from './presets/Pool';
import { registerFlows } from './presets/Flow';
import { timerIcon } from './presets/icons';
import * as icons from './presets/icons';
import * as bpmnUtils from './utils';
var definitionConfig = [
    {
        nodes: ['startEvent', 'intermediateCatchEvent', 'boundaryEvent'],
        definition: [
            {
                type: 'bpmn:timerEventDefinition',
                icon: timerIcon,
                properties: {
                    definitionType: 'bpmn:timerEventDefinition',
                    timerValue: '',
                    timerType: '',
                },
            },
        ],
    },
];
export function useDefinition(definition) {
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
var BPMNElements = /** @class */ (function () {
    function BPMNElements(_a) {
        var lf = _a.lf;
        lf.definition = {};
        lf.useDefinition = useDefinition(lf.definition);
        var _b = __read(lf.useDefinition(), 2), _definition = _b[0], setDefinition = _b[1];
        setDefinition(definitionConfig);
        registerEventNodes(lf);
        registerGatewayNodes(lf);
        registerFlows(lf);
        registerTaskNodes(lf);
        lf.setDefaultEdgeType('bpmn:sequenceFlow');
    }
    BPMNElements.pluginName = 'BpmnElementsPlugin';
    return BPMNElements;
}());
export { BPMNElements };
export * from './presets/Event/EndEventFactory';
export * from './presets/Event/IntermediateCatchEvent';
export * from './presets/Event/StartEventFactory';
export * from './presets/Event/boundaryEventFactory';
export * from './presets/Event/IntermediateThrowEvent';
export * from './presets/Flow/sequenceFlow';
export * from './presets/Task/task';
export * from './presets/Task/subProcess';
export * from './presets/Gateway/gateway';
export { icons, bpmnUtils };
