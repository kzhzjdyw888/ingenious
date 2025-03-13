import { h, Component } from 'preact';
import GraphModel from '../../model/GraphModel';
import { StepDrag } from '../../util/drag';
import { IBaseModel } from '../../model/BaseModel';
declare type IProps = {
    model: IBaseModel;
    graphModel: GraphModel;
    draggable: boolean;
    editable: boolean;
};
declare type IState = {
    isHovered: boolean;
};
export default class BaseText extends Component<IProps, IState> {
    dragHandler: (ev: MouseEvent) => void;
    sumDeltaX: number;
    sumDeltaY: number;
    stepDrag: StepDrag;
    constructor(config: any);
    getShape(): h.JSX.Element;
    onDragging: ({ deltaX, deltaY }: {
        deltaX: any;
        deltaY: any;
    }) => void;
    dblClickHandler: () => void;
    mouseDownHandle: (ev: MouseEvent) => void;
    render(): h.JSX.Element;
}
export {};
