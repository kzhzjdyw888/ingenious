import { IBaseModel } from '../BaseModel';
import GraphModel from '../GraphModel';
import { Point, AdditionData, EdgeData, MenuConfig, EdgeConfig, ShapeStyleAttribute } from '../../type/index';
import { ModelType, ElementType } from '../../constant/constant';
import { ArrowTheme, OutlineTheme } from '../../constant/DefaultTheme';
declare class BaseEdgeModel implements IBaseModel {
    id: string;
    type: string;
    sourceNodeId: string;
    targetNodeId: string;
    startPoint: any;
    endPoint: any;
    text: {
        value: string;
        x: number;
        y: number;
        draggable: boolean;
        editable: boolean;
    };
    properties: Record<string, any>;
    points: string;
    pointsList: any[];
    isSelected: boolean;
    isHovered: boolean;
    isHitable: boolean;
    draggable: boolean;
    visible: boolean;
    virtual: boolean;
    isAnimation: boolean;
    isShowAdjustPoint: boolean;
    graphModel: GraphModel;
    zIndex: number;
    readonly BaseType = ElementType.EDGE;
    modelType: ModelType;
    state: number;
    additionStateData: AdditionData;
    sourceAnchorId: string;
    targetAnchorId: string;
    menu?: MenuConfig[];
    customTextPosition: boolean;
    style: ShapeStyleAttribute;
    arrowConfig: {
        markerEnd: string;
        markerStart: string;
    };
    [propName: string]: any;
    constructor(data: EdgeConfig, graphModel: GraphModel);
    /**
     * 初始化边数据
     * @overridable 支持重写
     * initNodeData和setAttributes的区别在于
     * initNodeData只在节点初始化的时候调用，用于初始化节点的所有属性。
     * setAttributes除了初始化调用外，还会在properties发生变化后调用。
     */
    initEdgeData(data: any): void;
    /**
     * 设置model属性
     * @overridable 支持重写
     * 每次properties发生变化会触发
     */
    setAttributes(): void;
    createId(): string;
    /**
     * 自定义边样式
     *
     * @overridable 支持重写
     * @returns 自定义边样式
     */
    getEdgeStyle(): ShapeStyleAttribute;
    /**
     * 自定义边调整点样式
     *
     * @overridable 支持重写
     * 在isShowAdjustPoint为true时会显示调整点。
     */
    getAdjustPointStyle(): {
        [x: string]: any;
        fill?: string;
        stroke?: string;
        strokeWidth?: number;
    };
    /**
     * 自定义边文本样式
     *
     * @overridable 支持重写
     */
    getTextStyle(): import("../../constant/DefaultTheme").EdgeTextTheme;
    /**
     * 自定义边动画样式
     *
     * @overridable 支持重写
     * @example
     * getEdgeAnimationStyle() {
     *   const style = super.getEdgeAnimationStyle();
     *   style.stroke = 'blue'
     *   style.animationDuration = '30s'
     *   style.animationDirection = 'reverse'
     *   return style
     * }
     */
    getEdgeAnimationStyle(): import("../../constant/DefaultTheme").EdgeAnimation;
    /**
     * 自定义边箭头样式
     *
     * @overridable 支持重写
     * @example
     * getArrowStyle() {
     *   const style = super.getArrowStyle();
     *   style.stroke = 'green';
     *   return style;
     * }
     */
    getArrowStyle(): ArrowTheme;
    /**
     * 自定义边被选中时展示其范围的矩形框样式
     *
     * @overridable 支持重写
     * @example
     * // 隐藏outline
     * getOutlineStyle() {
     *   const style = super.getOutlineStyle();
     *   style.stroke = "none";
     *   style.hover.stroke = "none";
     *   return style;
     * }
     */
    getOutlineStyle(): OutlineTheme;
    /**
     * 重新自定义文本位置
     *
     * @overridable 支持重写
     */
    getTextPosition(): Point;
    /**
     * 边的前一个节点
     */
    get sourceNode(): import("..").BaseNodeModel;
    /**
     * 边的后一个节点
     */
    get targetNode(): import("..").BaseNodeModel;
    get textPosition(): Point;
    /**
     * 内部方法，计算两个节点相连是起点位置
     */
    getBeginAnchor(sourceNode: any, targetNode: any): Point;
    /**
     * 内部方法，计算两个节点相连是终点位置
     */
    getEndAnchor(targetNode: any): Point;
    /**
     * 获取当前边的properties
     */
    getProperties(): Record<string, any>;
    /**
     * 获取被保存时返回的数据
     *
     * @overridable 支持重写
     */
    getData(): EdgeData;
    /**
     * 获取边的数据
     *
     * @overridable 支持重写
     * 用于在历史记录时获取节点数据。
     * 在某些情况下，如果希望某个属性变化不引起history的变化，
     * 可以重写此方法。
     */
    getHistoryData(): EdgeData;
    /**
     * 设置边的属性，会触发重新渲染
     * @param key 属性名
     * @param val 属性值
     */
    setProperty(key: any, val: any): void;
    /**
     * 删除边的属性，会触发重新渲染
     * @param key 属性名
     */
    deleteProperty(key: string): void;
    /**
     * 设置边的属性，会触发重新渲染
     * @param key 属性名
     * @param val 属性值
     */
    setProperties(properties: any): void;
    /**
     * 修改边的id
     */
    changeEdgeId(id: string): void;
    /**
     * 设置边样式，用于插件开发时跳过自定义边的渲染。大多数情况下，不需要使用此方法。
     * 如果需要设置边的样式，请使用 getEdgeStyle 方法自定义边样式。
     */
    setStyle(key: string, val: any): void;
    /**
     * 设置边样式，用于插件开发时跳过自定义边的渲染。大多数情况下，不需要使用此方法。
     * 如果需要设置边的样式，请使用 getEdgeStyle 方法自定义边样式。
     */
    setStyles(styles: any): void;
    /**
     * 设置边样式，用于插件开发时跳过自定义边的渲染。大多数情况下，不需要使用此方法。
     * 如果需要设置边的样式，请使用 getEdgeStyle 方法自定义边样式。
     */
    updateStyles(styles: any): void;
    /**
     * 内部方法，处理初始化文本格式
     */
    formatText(data: any): void;
    /**
     * 重置文本位置
     */
    resetTextPosition(): void;
    /**
     * 移动边上的文本
     */
    moveText(deltaX: number, deltaY: number): void;
    /**
     * 设置文本位置和值
     */
    setText(textConfig: any): void;
    /**
     * 更新文本的值
     */
    updateText(value: string): void;
    /**
     * 内部方法，计算边的起点和终点和其对于的锚点Id
     */
    setAnchors(): void;
    setSelected(flag?: boolean): void;
    setHovered(flag?: boolean): void;
    setHitable(flag?: boolean): void;
    openEdgeAnimation(): void;
    closeEdgeAnimation(): void;
    setElementState(state: number, additionStateData?: AdditionData): void;
    updateStartPoint(anchor: any): void;
    moveStartPoint(deltaX: any, deltaY: any): void;
    updateEndPoint(anchor: any): void;
    moveEndPoint(deltaX: any, deltaY: any): void;
    setZIndex(zIndex?: number): void;
    initPoints(): void;
    updateAttributes(attributes: any): void;
    getAdjustStart(): any;
    getAdjustEnd(): any;
    updateAfterAdjustStartAndEnd({ startPoint, endPoint }: {
        startPoint: any;
        endPoint: any;
    }): void;
}
export { BaseEdgeModel };
export default BaseEdgeModel;
