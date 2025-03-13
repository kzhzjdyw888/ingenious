import { OutlineTheme } from '../../constant/DefaultTheme';
import { ModelType, ElementType } from '../../constant/constant';
import { AdditionData, NodeData, NodeConfig, NodeMoveRule, Bounds, AnchorConfig, PointAnchor, AnchorsOffsetItem, ShapeStyleAttribute, IsAllowMove, Point, AnchorInfo, DomAttributes } from '../../type';
import GraphModel from '../GraphModel';
import { IBaseModel } from '../BaseModel';
import { BaseEdgeModel } from '../edge';
export declare type ConnectRule = {
    message: string;
    validate: (source?: BaseNodeModel, target?: BaseNodeModel, sourceAnchor?: AnchorConfig, targetAnchor?: AnchorConfig, edgeId?: string) => boolean;
};
export declare type ConnectRuleResult = {
    isAllPass: boolean;
    msg?: string;
};
interface IBaseNodeModel extends IBaseModel {
    /**
     * model基础类型，固定为node
     */
    readonly BaseType: ElementType.NODE;
}
export { BaseNodeModel };
export default class BaseNodeModel implements IBaseNodeModel {
    id: string;
    type: string;
    x: number;
    y: number;
    text: {
        value: string;
        x: number;
        y: number;
        draggable: boolean;
        editable: boolean;
    };
    properties: Record<string, any>;
    private _width;
    get width(): number;
    set width(value: number);
    private _height;
    get height(): number;
    set height(value: number);
    anchorsOffset: AnchorsOffsetItem[];
    isSelected: boolean;
    isHovered: boolean;
    isShowAnchor: boolean;
    isDragging: boolean;
    isHitable: boolean;
    draggable: boolean;
    visible: boolean;
    enableRotate: boolean;
    virtual: boolean;
    graphModel: GraphModel;
    zIndex: number;
    state: number;
    autoToFront: boolean;
    style: ShapeStyleAttribute;
    gMatrix: string;
    rotate: number;
    readonly BaseType = ElementType.NODE;
    modelType: ModelType;
    additionStateData: AdditionData;
    targetRules: ConnectRule[];
    sourceRules: ConnectRule[];
    moveRules: NodeMoveRule[];
    hasSetTargetRules: boolean;
    hasSetSourceRules: boolean;
    [propName: string]: any;
    constructor(data: NodeConfig, graphModel: GraphModel);
    /**
     * 获取进入当前节点的边和节点
     */
    get incoming(): {
        nodes: BaseNodeModel[];
        edges: BaseEdgeModel[];
    };
    get outgoing(): {
        nodes: BaseNodeModel[];
        edges: BaseEdgeModel[];
    };
    /**
     * @overridable 可以重写
     * 初始化节点数据
     * initNodeData和setAttributes的区别在于
     * initNodeData只在节点初始化的时候调用，用于初始化节点的所有属性。
     * setAttributes除了初始化调用外，还会在properties发生变化了调用。
     */
    initNodeData(data: any): void;
    /**
     * 设置model属性，每次properties发生变化会触发
     * 例如设置节点的宽度
     * @example
     *
     * setAttributes () {
     *   this.width = 300
     *   this.height = 200
     * }
     *
     * @overridable 支持重写
     */
    setAttributes(): void;
    /**
     * @overridable 支持重写，自定义此类型节点默认生成方式
     * @returns string
     */
    createId(): string;
    /**
     * 初始化文本属性
     */
    private formatText;
    /**
     * 获取被保存时返回的数据
     * @overridable 支持重写
     */
    getData(): NodeData;
    /**
     * 用于在历史记录时获取节点数据，
     * 在某些情况下，如果希望某个属性变化不引起history的变化，
     * 可以重写此方法。
     */
    getHistoryData(): NodeData;
    /**
     * 获取当前节点的properties
     */
    getProperties(): Record<string, any>;
    /**
     * @overridable 支持重写
     * 获取当前节点最外层g标签Attributes, 例如className
     * @returns 自定义节点样式
     */
    getOuterGAttributes(): DomAttributes;
    /**
     * @overridable 支持重写
     * 获取当前节点样式
     * @returns 自定义节点样式
     */
    getNodeStyle(): ShapeStyleAttribute;
    /**
     * @overridable 支持重写
     * 获取当前节点文本样式
     */
    getTextStyle(): import("../../constant/DefaultTheme").NodeTextTheme;
    /**
     * @overridable 支持重写
     * 获取当前节点旋转控制点的样式
     */
    getRotateControlStyle(): import("../../constant/DefaultTheme").CommonTheme;
    /**
     * @overridable 支持重写
     * 获取当前节点锚点样式
     * @returns 自定义样式
     */
    getAnchorStyle(anchorInfo: any): Record<string, any>;
    /**
     * @overridable 支持重写
     * 获取当前节点锚点拖出连线样式
     * @returns 自定义锚点拖出样式
     */
    getAnchorLineStyle(anchorInfo: any): import("../../constant/DefaultTheme").CommonTheme;
    /**
     * @overridable 支持重写
     * 获取outline样式，重写可以定义此类型节点outline样式， 默认使用主题样式
     * @returns 自定义outline样式
     */
    getOutlineStyle(): OutlineTheme;
    /**
     * @over
     * 在边的时候，是否允许这个节点为source节点，边到target节点。
     *
     * @param edgeId 调整的边的id，在开启adjustEdgeStartAndEnd后调整边连接的节点时会传入，见https://github.com/didi/LogicFlow/issues/926#issuecomment-1371823306
     */
    isAllowConnectedAsSource(target: BaseNodeModel, sourceAnchor: AnchorConfig, targetAnchor: AnchorConfig, edgeId?: string): ConnectRuleResult | Boolean;
    /**
     * 获取当前节点作为连接的起始节点规则。
     */
    getConnectedSourceRules(): ConnectRule[];
    /**
     * 在连线的时候，是否允许这个节点为target节点
     *
     * @param edgeId 调整的边的id，在开启adjustEdgeStartAndEnd后调整边连接的节点时会传入，见https://github.com/didi/LogicFlow/issues/926#issuecomment-1371823306
     */
    isAllowConnectedAsTarget(source: BaseNodeModel, sourceAnchor: AnchorConfig, targetAnchor: AnchorConfig, edgeId?: string): ConnectRuleResult | Boolean;
    /**
     * 内部方法
     * 是否允许移动节点到新的位置
     */
    isAllowMoveNode(deltaX: any, deltaY: any): boolean | IsAllowMove;
    /**
     * 获取作为连线终点时的所有规则。
     */
    getConnectedTargetRules(): ConnectRule[];
    /**
     * @returns Point[] 锚点坐标构成的数组
     */
    getAnchorsByOffset(): PointAnchor[];
    /**
     * @overridable 子类重写此方法设置默认锚点
     * 获取节点默认情况下的锚点
     */
    getDefaultAnchor(): PointAnchor[];
    /**
     * @overridable 子类重写此方法获取手动连接边到节点时，需要连接的锚点
     * 手动连接边到节点时，需要连接的锚点
     */
    getTargetAnchor(position: Point): AnchorInfo;
    /**
     * 获取节点BBox
     */
    getBounds(): Bounds;
    get anchors(): PointAnchor[];
    getAnchorInfo(anchorId: string): PointAnchor;
    addNodeMoveRules(fn: NodeMoveRule): void;
    move(deltaX: any, deltaY: any, isIgnoreRule?: boolean): boolean;
    getMoveDistance(deltaX: number, deltaY: number, isIgnoreRule?: boolean): [number, number];
    moveTo(x: any, y: any, isIgnoreRule?: boolean): boolean;
    moveText(deltaX: any, deltaY: any): void;
    updateText(value: string): void;
    setSelected(flag?: boolean): void;
    setHovered(flag?: boolean): void;
    setIsShowAnchor(flag?: boolean): void;
    setEnableRotate(flag?: boolean): void;
    setHitable(flag?: boolean): void;
    setElementState(state: number, additionStateData?: AdditionData): void;
    setProperty(key: any, val: any): void;
    setProperties(properties: any): void;
    deleteProperty(key: string): void;
    setStyle(key: any, val: any): void;
    setStyles(styles: any): void;
    updateStyles(styles: any): void;
    setZIndex(zIndex?: number): void;
    updateAttributes(attributes: any): void;
}
