"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
exports.registerEventNodes = void 0;
var EndEventFactory_1 = require("./EndEventFactory");
var IntermediateCatchEvent_1 = require("./IntermediateCatchEvent");
var StartEventFactory_1 = require("./StartEventFactory");
var boundaryEventFactory_1 = require("./boundaryEventFactory");
var IntermediateThrowEvent_1 = require("./IntermediateThrowEvent");
function registerEventNodes(lf) {
    lf.register(StartEventFactory_1.StartEventFactory(lf));
    lf.register(EndEventFactory_1.EndEventFactory(lf));
    lf.register(IntermediateCatchEvent_1.IntermediateCatchEventFactory(lf));
    lf.register(IntermediateThrowEvent_1.IntermediateThrowEventFactory(lf));
    lf.register(boundaryEventFactory_1.BoundaryEventFactory(lf));
}
exports.registerEventNodes = registerEventNodes;
