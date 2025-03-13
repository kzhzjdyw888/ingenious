import * as icons from './presets/icons';
import * as bpmnUtils from './utils';
export declare function useDefinition(definition: any): () => any[];
export declare class BPMNElements {
    static pluginName: string;
    constructor({ lf }: any);
}
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
