var __extends = (this && this.__extends) || (function () {
    var extendStatics = function (d, b) {
        extendStatics = Object.setPrototypeOf ||
            ({ __proto__: [] } instanceof Array && function (d, b) { d.__proto__ = b; }) ||
            function (d, b) { for (var p in b) if (b.hasOwnProperty(p)) d[p] = b[p]; };
        return extendStatics(d, b);
    };
    return function (d, b) {
        extendStatics(d, b);
        function __() { this.constructor = d; }
        d.prototype = b === null ? Object.create(b) : (__.prototype = b.prototype, new __());
    };
})();
import { h, RectNode, RectNodeModel } from '@logicflow/core';
import ControlGroup from '../Control/ControlGroup';
var RectResizeModel = /** @class */ (function (_super) {
    __extends(RectResizeModel, _super);
    function RectResizeModel(data, graphModel) {
        var _this = _super.call(this, data, graphModel) || this;
        var nodeSize = _this.properties.nodeSize;
        if (nodeSize) {
            _this.width = nodeSize.width;
            _this.height = nodeSize.height;
        }
        return _this;
    }
    RectResizeModel.prototype.initNodeData = function (data) {
        _super.prototype.initNodeData.call(this, data);
        this.minWidth = 30;
        this.minHeight = 30;
        this.maxWidth = 2000;
        this.maxHeight = 2000;
    };
    RectResizeModel.prototype.getOutlineStyle = function () {
        var style = _super.prototype.getOutlineStyle.call(this);
        var isSilentMode = this.graphModel.editConfigModel.isSilentMode;
        if (isSilentMode)
            return style;
        style.stroke = 'none';
        if (style.hover) {
            style.hover.stroke = 'none';
        }
        return style;
    };
    RectResizeModel.prototype.getResizeOutlineStyle = function () {
        return {
            fill: 'none',
            stroke: 'transparent',
            strokeWidth: 1,
            strokeDasharray: '3,3',
        };
    };
    RectResizeModel.prototype.getControlPointStyle = function () {
        return {
            width: 7,
            height: 7,
            fill: '#FFFFFF',
            stroke: '#000000',
        };
    };
    RectResizeModel.prototype.resize = function (deltaX, deltaY) {
        console.log(deltaX, deltaY);
    };
    // 该方法需要在重设宽高和最大、最小限制后被调用，不建议在 initNodeData() 方法中使用
    RectResizeModel.prototype.enableProportionResize = function (turnOn) {
        if (turnOn === void 0) { turnOn = true; }
        if (turnOn) {
            var ResizePCT = { widthPCT: 100, hightPCT: 100 };
            var ResizeBasis = { basisWidth: this.width, basisHeight: this.height };
            var ScaleLimit = {
                maxScaleLimit: Math.min((this.maxWidth / this.width) * 100, (this.maxHeight / this.height) * 100),
                minScaleLimit: Math.max((this.minWidth / this.width) * 100, (this.minHeight / this.height) * 100),
            };
            this.PCTResizeInfo = { ResizePCT: ResizePCT, ResizeBasis: ResizeBasis, ScaleLimit: ScaleLimit };
        }
        else {
            delete this.PCTResizeInfo;
        }
    };
    return RectResizeModel;
}(RectNodeModel));
var RectResizeView = /** @class */ (function (_super) {
    __extends(RectResizeView, _super);
    function RectResizeView() {
        return _super !== null && _super.apply(this, arguments) || this;
    }
    RectResizeView.prototype.getControlGroup = function () {
        var _a = this.props, model = _a.model, graphModel = _a.graphModel;
        return (h(ControlGroup, { model: model, graphModel: graphModel }));
    };
    // getResizeShape绘制图形，功能等同于基础矩形的getShape功能，可以通过复写此方法，进行节点自定义
    RectResizeView.prototype.getResizeShape = function () {
        return _super.prototype.getShape.call(this);
    };
    RectResizeView.prototype.getShape = function () {
        var _a = this.props, isSelected = _a.model.isSelected, isSilentMode = _a.graphModel.editConfigModel.isSilentMode;
        return (h("g", null,
            this.getResizeShape(),
            isSelected && !isSilentMode ? this.getControlGroup() : ''));
    };
    return RectResizeView;
}(RectNode));
var RectResize = {
    type: 'rect',
    view: RectResizeView,
    model: RectResizeModel,
};
export default RectResize;
