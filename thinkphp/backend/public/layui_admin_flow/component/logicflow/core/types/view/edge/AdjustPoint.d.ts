import { h, Component } from 'preact';
import { BaseEdgeModel } from '../../model/edge';
import GraphModel from '../../model/GraphModel';
import { StepDrag } from '../../util/drag';
import { Point } from '../../type';
import { BaseNodeModel } from '../..';
interface IProps {
    x: number;
    y: number;
    type: AdjustType;
    id?: string;
    getAdjustPointShape: Function;
    graphModel: GraphModel;
    edgeModel: BaseEdgeModel;
}
interface IState {
    dragging: boolean;
    endX: number;
    endY: number;
}
interface OldEdge {
    startPoint: Point;
    endPoint: Point;
    pointsList: Point[];
}
declare enum AdjustType {
    SOURCE = "SOURCE",
    TARGET = "TARGET"
}
export default class AdjustPoint extends Component<IProps, IState> {
    stepDragData: any;
    stepDrag: StepDrag;
    oldEdge: OldEdge;
    preTargetNode: any;
    targetRuleResults: Map<any, any>;
    sourceRuleResults: Map<any, any>;
    constructor(props: any);
    handleMouseDown: (ev: MouseEvent) => void;
    onDragStart: () => void;
    onDragging: ({ deltaX, deltaY }: {
        deltaX: any;
        deltaY: any;
    }) => void;
    onDragEnd: ({ event }: {
        event: any;
    }) => void;
    recoveryEdge: () => void;
    getAdjustPointStyle: () => import("../../constant/DefaultTheme").CommonTheme;
    isAllowAdjust(info: any): {
        pass: boolean;
        msg?: string;
        newTargetNode: BaseNodeModel;
    };
    render(): h.JSX.Element;
}
export {};
