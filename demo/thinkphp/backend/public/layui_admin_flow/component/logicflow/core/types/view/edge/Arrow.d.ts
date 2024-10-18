import { h, Component } from 'preact';
import { ArrowInfo } from '../../type/index';
export declare type ArrowStyle = {
    stroke?: string;
    fill?: string;
    strokeWidth?: number;
    offset: number;
    refX?: number;
    refY?: number;
    verticalLength: number;
};
declare type ArrowAttributesType = {
    d: string;
} & ArrowStyle;
declare type IProps = {
    arrowInfo: ArrowInfo;
    style: ArrowStyle;
};
export default class Arrow extends Component<IProps> {
    getArrowAttributes(): ArrowAttributesType;
    getShape(): h.JSX.Element;
    render(): h.JSX.Element;
}
export {};
