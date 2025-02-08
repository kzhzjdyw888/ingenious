import { sequenceFlowFactory } from './sequenceFlow';
export var SequenceFlow = sequenceFlowFactory();
export function registerFlows(lf) {
    lf.register(SequenceFlow);
}
