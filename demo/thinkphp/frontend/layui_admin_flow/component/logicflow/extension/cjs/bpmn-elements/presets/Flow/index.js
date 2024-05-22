"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
exports.registerFlows = exports.SequenceFlow = void 0;
var sequenceFlow_1 = require("./sequenceFlow");
exports.SequenceFlow = sequenceFlow_1.sequenceFlowFactory();
function registerFlows(lf) {
    lf.register(exports.SequenceFlow);
}
exports.registerFlows = registerFlows;
