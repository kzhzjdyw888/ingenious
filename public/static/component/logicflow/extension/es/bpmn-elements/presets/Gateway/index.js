import { exclusiveIcon, parallelIcon, inclusiveIcon } from '../icons';
import { GatewayNodeFactory } from './gateway';
export function registerGatewayNodes(lf) {
    var ExclusiveGateway = GatewayNodeFactory('bpmn:exclusiveGateway', exclusiveIcon);
    var ParallelGateway = GatewayNodeFactory('bpmn:parallelGateway', parallelIcon);
    var InclusiveGateway = GatewayNodeFactory('bpmn:inclusiveGateway', inclusiveIcon);
    lf.register(ExclusiveGateway);
    lf.register(InclusiveGateway);
    lf.register(ParallelGateway);
}
