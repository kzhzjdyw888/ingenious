"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
exports.registerGatewayNodes = void 0;
var icons_1 = require("../icons");
var gateway_1 = require("./gateway");
function registerGatewayNodes(lf) {
    var ExclusiveGateway = gateway_1.GatewayNodeFactory('bpmn:exclusiveGateway', icons_1.exclusiveIcon);
    var ParallelGateway = gateway_1.GatewayNodeFactory('bpmn:parallelGateway', icons_1.parallelIcon);
    var InclusiveGateway = gateway_1.GatewayNodeFactory('bpmn:inclusiveGateway', icons_1.inclusiveIcon);
    lf.register(ExclusiveGateway);
    lf.register(InclusiveGateway);
    lf.register(ParallelGateway);
}
exports.registerGatewayNodes = registerGatewayNodes;
