import LogicFlow from '@logicflow/core';
declare class InsertNodeInPolyline {
    static pluginName: string;
    _lf: LogicFlow;
    dndAdd: boolean;
    dropAdd: boolean;
    deviation: number;
    constructor({ lf }: {
        lf: any;
    });
    eventHandler(): void;
    /**
     * 插入节点前校验规则
     * @param sourceNodeId
     * @param targetNodeId
     * @param sourceAnchorId
     * @param targetAnchorId
     * @param nodeData
     */
    checkRuleBeforeInsetNode(sourceNodeId: any, targetNodeId: any, sourceAnchorId: any, targetAnchorId: any, nodeData: any): {
        isPass: any;
        sourceMsg: any;
        targetMsg: any;
    };
    insetNode(nodeData: any): void;
}
export { InsertNodeInPolyline };
export default InsertNodeInPolyline;
