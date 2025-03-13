declare class MiniMap {
    static pluginName: string;
    static width: number;
    static height: number;
    static viewPortWidth: number;
    static viewPortHeight: number;
    static isShowHeader: boolean;
    static isShowCloseIcon: boolean;
    static leftPosition: number;
    static topPosition: number;
    static rightPosition: any;
    static bottomPosition: any;
    static headerTitle: string;
    private lf;
    private container;
    private miniMapWrap;
    private miniMapContainer;
    private lfMap;
    private viewport;
    private width;
    private height;
    private leftPosition;
    private topPosition;
    private rightPosition;
    private bottomPosition;
    private miniMapWidth;
    private miniMapHeight;
    private viewPortTop;
    private viewPortLeft;
    private startPosition;
    private viewPortScale;
    private viewPortWidth;
    private viewPortHeight;
    private resetDataX;
    private resetDataY;
    private LogicFlow;
    private isShow;
    private isShowHeader;
    private isShowCloseIcon;
    private dragging;
    private disabledPlugins;
    constructor({ lf, LogicFlow, options }: {
        lf: any;
        LogicFlow: any;
        options: any;
    });
    render(lf: any, container: any): void;
    init(option: any): void;
    /**
     * 显示mini map
    */
    show: (leftPosition?: number, topPosition?: number) => void;
    /**
     * 隐藏mini map
     */
    hide: () => void;
    reset: () => void;
    private setOption;
    private initMiniMap;
    private createMiniMap;
    private removeMiniMap;
    /**
     * 计算所有图形一起，占领的区域范围。
     * @param data
     */
    private getBounds;
    /**
     * 将负值的平移转换为正值。
     * 保证渲染的时候，minimap能完全展示。
     * 获取将画布所有元素平移到0，0开始时，所有节点数据
     */
    private resetData;
    /**
     * 显示导航
     * 显示视口范围
     * 1. 基于画布的范围比例，设置视口范围比例。宽度默认为导航宽度。
     */
    private setView;
    private setViewPort;
    private createViewPort;
    private startDrag;
    private moveViewport;
    private drag;
    private drop;
    private mapClick;
}
export default MiniMap;
export { MiniMap };
