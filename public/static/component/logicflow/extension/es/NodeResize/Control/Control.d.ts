import { h, Component } from 'preact';
import { BaseNodeModel, GraphModel, LogicFlowUtil } from '@logicflow/core';
interface IProps {
    index: number;
    x: number;
    y: number;
    model: BaseNodeModel;
    graphModel: GraphModel;
    style?: CSSStyleDeclaration;
}
declare class Control extends Component<IProps> {
    index: number;
    nodeModel: BaseNodeModel;
    graphModel: GraphModel;
    dragHandler: LogicFlowUtil.StepDrag;
    constructor(props: any);
    getNodeEdges(nodeId: any): {
        sourceEdges: any[];
        targetEdges: any[];
    };
    updatePosition: ({ deltaX, deltaY }: {
        deltaX: any;
        deltaY: any;
    }) => void;
    getResize: ({ index, deltaX, deltaY, width, height, PCTResizeInfo, pct }: {
        index: any;
        deltaX: any;
        deltaY: any;
        width: any;
        height: any;
        PCTResizeInfo: any;
        pct?: number;
    }) => {
        width: any;
        height: any;
        deltaX: any;
        deltaY: any;
    };
    updateEdgePointByAnchors: () => void;
    updateRect: ({ deltaX, deltaY }: {
        deltaX: any;
        deltaY: any;
    }) => void;
    updateEllipse: ({ deltaX, deltaY }: {
        deltaX: any;
        deltaY: any;
    }) => void;
    updateDiamond: ({ deltaX, deltaY }: {
        deltaX: any;
        deltaY: any;
    }) => void;
    eventEmit: ({ beforeNode, afterNode }: {
        beforeNode: any;
        afterNode: any;
    }) => void;
    onDragging: ({ deltaX, deltaY }: {
        deltaX: any;
        deltaY: any;
    }) => void;
    /**
     * 由于将拖拽放大缩小改成丝滑模式，这个时候需要在拖拽结束的时候，将节点的位置更新到grid上.
     */
    onDragEnd: () => void;
    render(): h.JSX.Element;
}
export default Control;
