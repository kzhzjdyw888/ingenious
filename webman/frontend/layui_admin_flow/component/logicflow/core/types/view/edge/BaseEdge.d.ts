import { h, Component } from 'preact';
import BaseEdgeModel from '../../model/edge/BaseEdgeModel';
import GraphModel from '../../model/GraphModel';
import { ArrowInfo } from '../../type/index';
declare type IProps = {
    model: BaseEdgeModel;
    graphModel: GraphModel;
};
export default class BaseEdge extends Component<IProps> {
    startTime: number;
    contextMenuTime: number;
    clickTimer: number;
    textRef: import("preact").RefObject<any>;
    /**
     * 不支持重写，请使用getEdge
     */
    getShape(): h.JSX.Element;
    /**
     * @deprecated 请使用model.getTextStyle
     */
    getTextStyle(): void;
    /**
     * @overridable 可重写，自定义边文本DOM
     */
    getText(): h.JSX.Element | null;
    /**
     * @deprecated
     */
    getArrowInfo(): ArrowInfo;
    getLastTwoPoints(): any[];
    /**
     * @deprecated 请使用model.getArrowStyle
     */
    getArrowStyle(): any;
    /**
     * 定义边的箭头，不支持重写。请使用getStartArrow和getEndArrow
     */
    private getArrow;
    /**
     * @overridable 可重写，自定义边起点箭头形状。
     * @example
     * getStartArrow() {
     *  const { model } = this.props;
     *  const { stroke, strokeWidth, offset, verticalLength } = model.getArrowStyle();
     *  return (
     *    h('path', {
     *      d: ''
     *    })
     *  )
     * }
     */
    getStartArrow(): h.JSX.Element | null;
    /**
     * @overridable 可重写，自定义边终点箭头形状。
     * @example
     * getEndArrow() {
     *  const { model } = this.props;
     *  const { stroke, strokeWidth, offset, verticalLength } = model.getArrowStyle();
     *  return (
     *    h('path', {
     *      d: ''
     *    })
     *  )
     * }
     */
    getEndArrow(): h.JSX.Element | null;
    /**
     * @overridable 可重写，自定义调整边连接节点形状。在开启了adjustEdgeStartAndEnd的时候，会显示调整点。
     * @param x 调整点x坐标
     * @param y 调整点y坐标
     * @example
     * getAdjustPointShape(x, y) {
     *  const { model } = this.props;
     *  const style = model.getAdjustPointStyle();
     *  return (
     *    h('circle', {
     *      ...style,
     *     x,
     *     y
     *    })
     *  )
     * }
     */
    getAdjustPointShape(x: any, y: any, model: any): h.JSX.Element | null;
    /**
     * 不支持重写。请使用getAdjustPointShape
     */
    private getAdjustPoints;
    /**
     * @deprecated
     */
    getAnimation(): void;
    /**
     * @overridable 可重写，在完全自定义边的时候，可以重写此方法，来自定义边的选区。
     */
    getAppendWidth(): h.JSX.Element;
    /**
     * 不建议重写，此方法为扩大边选区，方便用户点击选中边。
     * 如果需要自定义边选区，请使用getAppendWidth。
     */
    getAppend(): h.JSX.Element;
    /**
     * 不支持重写，如果想要基于hover状态设置不同的样式，请在model中使用isHovered属性。
     */
    handleHover: (hovered: any, ev: any) => void;
    /**
     * 不支持重写，如果想要基于hover状态设置不同的样式，请在model中使用isHovered属性。
     */
    setHoverON: (ev: any) => void;
    /**
     * 不支持重写，如果想要基于hover状态设置不同的样式，请在model中使用isHovered属性。
     */
    setHoverOFF: (ev: any) => void;
    /**
     * 不支持重写，如果想要基于contextmenu事件做处理，请监听edge:contextmenu事件。
     */
    handleContextMenu: (ev: MouseEvent) => void;
    /**
     * 不支持重写
     */
    handleMouseDown: (e: any) => void;
    /**
     * 不支持重写
     */
    handleMouseUp: (e: MouseEvent) => void;
    /**
     * @overridable 支持重写, 此方法为获取边的形状，如果需要自定义边的形状，请重写此方法。
     * @example https://docs.logic-flow.cn/docs/#/zh/guide/basic/edge?id=%e5%9f%ba%e4%ba%8e-react-%e7%bb%84%e4%bb%b6%e8%87%aa%e5%ae%9a%e4%b9%89%e8%be%b9
     */
    getEdge(): h.JSX.Element | null;
    /**
     * @overridable 支持重写, 此方法为边在被选中时将其置顶，如果不需要此功能，可以重写此方法。
     */
    toFront(): void;
    /**
     * 不建议重写，如果要自定义边的形状，请重写getEdge方法。
     */
    render(): h.JSX.Element;
}
export {};
