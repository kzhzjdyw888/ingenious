import { Component, h } from 'preact';
import { GraphModel, BaseNodeModel } from '../model';
import { Vector } from '../util';
import EventEmitter from '../event/eventEmitter';
import { CommonTheme } from '../constant/DefaultTheme';
interface IProps {
    graphModel: GraphModel;
    nodeModel: BaseNodeModel;
    eventCenter: EventEmitter;
    style: CommonTheme;
}
declare class RotateControlPoint extends Component<IProps> {
    private style;
    private defaultAngle;
    normal: Vector;
    stepperDrag: any;
    constructor(props: IProps);
    onDragging: ({ event }: any) => void;
    render(): h.JSX.Element;
}
export default RotateControlPoint;
