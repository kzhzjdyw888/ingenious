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
var __assign = (this && this.__assign) || function () {
    __assign = Object.assign || function(t) {
        for (var s, i = 1, n = arguments.length; i < n; i++) {
            s = arguments[i];
            for (var p in s) if (Object.prototype.hasOwnProperty.call(s, p))
                t[p] = s[p];
        }
        return t;
    };
    return __assign.apply(this, arguments);
};
var __read = (this && this.__read) || function (o, n) {
    var m = typeof Symbol === "function" && o[Symbol.iterator];
    if (!m) return o;
    var i = m.call(o), r, ar = [], e;
    try {
        while ((n === void 0 || n-- > 0) && !(r = i.next()).done) ar.push(r.value);
    }
    catch (error) { e = { error: error }; }
    finally {
        try {
            if (r && !r.done && (m = i["return"])) m.call(i);
        }
        finally { if (e) throw e.error; }
    }
    return ar;
};
import { h, Component } from 'preact';
import { LogicFlowUtil } from '@logicflow/core';
import Rect from '../BasicShape/Rect';
import { ModelType } from './Util';
var StepDrag = LogicFlowUtil.StepDrag;
var Control = /** @class */ (function (_super) {
    __extends(Control, _super);
    function Control(props) {
        var _this = _super.call(this) || this;
        // 更新中心点位置，更新文案位置
        _this.updatePosition = function (_a) {
            var deltaX = _a.deltaX, deltaY = _a.deltaY;
            var _b = _this.nodeModel, x = _b.x, y = _b.y;
            _this.nodeModel.x = x + deltaX / 2;
            _this.nodeModel.y = y + deltaY / 2;
            _this.nodeModel.moveText(deltaX / 2, deltaY / 2);
        };
        // 计算control拖动后，节点的宽高
        _this.getResize = function (_a) {
            var index = _a.index, deltaX = _a.deltaX, deltaY = _a.deltaY, width = _a.width, height = _a.height, PCTResizeInfo = _a.PCTResizeInfo, _b = _a.pct, pct = _b === void 0 ? 1 : _b;
            var resize = { width: width, height: height, deltaX: deltaX, deltaY: deltaY };
            if (PCTResizeInfo) {
                var sensitivity = 4; // 越低越灵敏
                var deltaScale = 0;
                var combineDelta = 0;
                switch (index) {
                    case 0:
                        combineDelta = (deltaX * -1 - deltaY) / sensitivity;
                        break;
                    case 1:
                        combineDelta = (deltaX - deltaY) / sensitivity;
                        break;
                    case 2:
                        combineDelta = (deltaX + deltaY) / sensitivity;
                        break;
                    case 3:
                        combineDelta = (deltaX * -1 + deltaY) / sensitivity;
                        break;
                    default:
                        break;
                }
                if (combineDelta !== 0) {
                    deltaScale = Math.round((combineDelta / PCTResizeInfo.ResizeBasis.basisHeight)
                        * 100000) / 1000;
                }
                PCTResizeInfo.ResizePCT.widthPCT = Math.max(Math.min(PCTResizeInfo.ResizePCT.widthPCT + deltaScale, PCTResizeInfo.ScaleLimit.maxScaleLimit), PCTResizeInfo.ScaleLimit.minScaleLimit);
                PCTResizeInfo.ResizePCT.hightPCT = Math.max(Math.min(PCTResizeInfo.ResizePCT.hightPCT + deltaScale, PCTResizeInfo.ScaleLimit.maxScaleLimit), PCTResizeInfo.ScaleLimit.minScaleLimit);
                var spcWidth = Math.round((PCTResizeInfo.ResizePCT.widthPCT
                    * PCTResizeInfo.ResizeBasis.basisWidth) / 100);
                var spcHeight = Math.round((PCTResizeInfo.ResizePCT.hightPCT
                    * PCTResizeInfo.ResizeBasis.basisHeight) / 100);
                switch (index) {
                    case 0:
                        deltaX = width - spcWidth;
                        deltaY = height - spcHeight;
                        break;
                    case 1:
                        deltaX = spcWidth - width;
                        deltaY = height - spcHeight;
                        break;
                    case 2:
                        deltaX = spcWidth - width;
                        deltaY = spcHeight - height;
                        break;
                    case 3:
                        deltaX = width - spcWidth;
                        deltaY = spcHeight - height;
                        break;
                    default:
                        break;
                }
                resize.width = spcWidth;
                resize.height = spcHeight;
                resize.deltaX = deltaX / pct;
                resize.deltaY = deltaY / pct;
                return resize;
            }
            switch (index) {
                case 0:
                    resize.width = width - deltaX * pct;
                    resize.height = height - deltaY * pct;
                    break;
                case 1:
                    resize.width = width + deltaX * pct;
                    resize.height = height - deltaY * pct;
                    break;
                case 2:
                    resize.width = width + deltaX * pct;
                    resize.height = height + deltaY * pct;
                    break;
                case 3:
                    resize.width = width - deltaX * pct;
                    resize.height = height + deltaY * pct;
                    break;
                default:
                    break;
            }
            return resize;
        };
        _this.updateEdgePointByAnchors = function () {
            // https://github.com/didi/LogicFlow/issues/807
            // https://github.com/didi/LogicFlow/issues/875
            // 之前的做法，比如Rect是使用getRectResizeEdgePoint()计算边的point缩放后的位置
            // getRectResizeEdgePoint()考虑了瞄点在四条边以及在4个圆角的情况
            // 使用的是一种等比例缩放的模式，比如：
            // const pct = (y - beforeNode.y) / (beforeNode.height / 2 - radius)
            // afterPoint.y = afterNode.y + (afterNode.height / 2 - radius) * pct
            // 但是用户自定义的getDefaultAnchor()不一定是按照比例编写的
            // 它可能是 x: x + 20：每次缩放都会保持在x右边20的位置，因此用户自定义瞄点时，然后产生无法跟随的问题
            // 现在的做法是：直接获取用户自定义瞄点的位置，然后用这个位置作为边的新的起点，而不是自己进行计算
            var _a = _this.nodeModel, id = _a.id, anchors = _a.anchors;
            var edges = _this.getNodeEdges(id);
            // 更新边
            edges.sourceEdges.forEach(function (item) {
                var anchorItem = anchors.find(function (anchor) { return anchor.id === item.sourceAnchorId; });
                item.updateStartPoint({
                    x: anchorItem.x,
                    y: anchorItem.y,
                });
            });
            edges.targetEdges.forEach(function (item) {
                var anchorItem = anchors.find(function (anchor) { return anchor.id === item.targetAnchorId; });
                item.updateEndPoint({
                    x: anchorItem.x,
                    y: anchorItem.y,
                });
            });
        };
        // 矩形更新
        _this.updateRect = function (_a) {
            var deltaX = _a.deltaX, deltaY = _a.deltaY;
            var _b = _this.nodeModel, id = _b.id, x = _b.x, y = _b.y, width = _b.width, height = _b.height, radius = _b.radius, PCTResizeInfo = _b.PCTResizeInfo;
            // 更新中心点位置，更新文案位置
            var index = _this.index;
            var size = _this.getResize({
                index: index,
                deltaX: deltaX,
                deltaY: deltaY,
                width: width,
                height: height,
                PCTResizeInfo: PCTResizeInfo,
                pct: 1,
            });
            // 限制放大缩小的最大最小范围
            var _c = _this.nodeModel, minWidth = _c.minWidth, minHeight = _c.minHeight, maxWidth = _c.maxWidth, maxHeight = _c.maxHeight;
            if (size.width < minWidth
                || size.width > maxWidth
                || size.height < minHeight
                || size.height > maxHeight) {
                // 为了避免放到和缩小位置和鼠标不一致的问题
                _this.dragHandler.cancelDrag();
                return;
            }
            _this.updatePosition({ deltaX: size.deltaX, deltaY: size.deltaY });
            // 更新宽高
            _this.nodeModel.width = size.width;
            _this.nodeModel.height = size.height;
            _this.nodeModel.setProperties({
                nodeSize: {
                    width: size.width,
                    height: size.height,
                },
            });
            var edges = _this.getNodeEdges(id);
            var beforeNode = {
                x: x,
                y: y,
                width: width,
                height: height,
                radius: radius,
            };
            var afterNode = {
                x: _this.nodeModel.x,
                y: _this.nodeModel.y,
                width: _this.nodeModel.width,
                height: _this.nodeModel.height,
                radius: radius,
            };
            // 更新边
            _this.updateEdgePointByAnchors();
            _this.eventEmit({ beforeNode: beforeNode, afterNode: afterNode });
        };
        // 椭圆更新
        _this.updateEllipse = function (_a) {
            var deltaX = _a.deltaX, deltaY = _a.deltaY;
            var _b = _this.nodeModel, id = _b.id, rx = _b.rx, ry = _b.ry, x = _b.x, y = _b.y, PCTResizeInfo = _b.PCTResizeInfo;
            var index = _this.index;
            var width = rx;
            var height = ry;
            var size = _this.getResize({
                index: index,
                deltaX: deltaX,
                deltaY: deltaY,
                width: width,
                height: height,
                PCTResizeInfo: PCTResizeInfo,
                pct: 1 / 2,
            });
            // 限制放大缩小的最大最小范围
            var _c = _this.nodeModel, minWidth = _c.minWidth, minHeight = _c.minHeight, maxWidth = _c.maxWidth, maxHeight = _c.maxHeight;
            if (size.width < (minWidth / 2)
                || size.width > (maxWidth / 2)
                || size.height < (minHeight / 2)
                || size.height > (maxHeight / 2)) {
                _this.dragHandler.cancelDrag();
                return;
            }
            // 更新中心点位置，更新文案位置
            _this.updatePosition({ deltaX: size.deltaX, deltaY: size.deltaY });
            // 更新rx ry,宽高为计算属性自动更新
            // @ts-ignore
            _this.nodeModel.rx = size.width;
            // @ts-ignore
            _this.nodeModel.ry = size.height;
            _this.nodeModel.setProperties({
                nodeSize: {
                    rx: size.width,
                    ry: size.height,
                },
            });
            var edges = _this.getNodeEdges(id);
            var beforeNode = { x: x, y: y };
            var afterNode = {
                rx: size.width,
                ry: size.height,
                x: _this.nodeModel.x,
                y: _this.nodeModel.y,
            };
            // 更新边
            _this.updateEdgePointByAnchors();
            _this.eventEmit({ beforeNode: __assign(__assign({}, beforeNode), { rx: rx, ry: ry }), afterNode: afterNode });
        };
        // 菱形更新
        _this.updateDiamond = function (_a) {
            var deltaX = _a.deltaX, deltaY = _a.deltaY;
            var _b = _this.nodeModel, id = _b.id, rx = _b.rx, ry = _b.ry, x = _b.x, y = _b.y, PCTResizeInfo = _b.PCTResizeInfo;
            var index = _this.index;
            var width = rx;
            var height = ry;
            var size = _this.getResize({
                index: index,
                deltaX: deltaX,
                deltaY: deltaY,
                width: width,
                height: height,
                PCTResizeInfo: PCTResizeInfo,
                pct: 1 / 2,
            });
            // 限制放大缩小的最大最小范围
            var _c = _this.nodeModel, minWidth = _c.minWidth, minHeight = _c.minHeight, maxWidth = _c.maxWidth, maxHeight = _c.maxHeight;
            if (size.width < (minWidth / 2)
                || size.width > (maxWidth / 2)
                || size.height < (minHeight / 2)
                || size.height > (maxHeight / 2)) {
                _this.dragHandler.cancelDrag();
                return;
            }
            // 更新中心点位置，更新文案位置
            _this.updatePosition({ deltaX: size.deltaX, deltaY: size.deltaY });
            // 更新rx ry,宽高为计算属性自动更新
            // @ts-ignore
            _this.nodeModel.rx = size.width;
            // @ts-ignore
            _this.nodeModel.ry = size.height;
            _this.nodeModel.setProperties({
                nodeSize: {
                    rx: size.width,
                    ry: size.height,
                },
            });
            var beforeNode = { x: x, y: y, rx: rx, ry: ry };
            var afterNode = {
                rx: size.width,
                ry: size.height,
                x: _this.nodeModel.x,
                y: _this.nodeModel.y,
            };
            // 更新边
            _this.updateEdgePointByAnchors();
            _this.eventEmit({ beforeNode: beforeNode, afterNode: afterNode });
        };
        _this.eventEmit = function (_a) {
            var beforeNode = _a.beforeNode, afterNode = _a.afterNode;
            var _b = _this.nodeModel, id = _b.id, modelType = _b.modelType, type = _b.type;
            var oldNodeSize = __assign({ id: id, modelType: modelType, type: type }, beforeNode);
            var newNodeSize = __assign({ id: id, modelType: modelType, type: type }, afterNode);
            _this.graphModel.eventCenter.emit('node:resize', { oldNodeSize: oldNodeSize, newNodeSize: newNodeSize });
        };
        _this.onDragging = function (_a) {
            var _b;
            var deltaX = _a.deltaX, deltaY = _a.deltaY;
            var transformModel = _this.graphModel.transformModel;
            var modelType = _this.nodeModel.modelType;
            _b = __read(transformModel.fixDeltaXY(deltaX, deltaY), 2), deltaX = _b[0], deltaY = _b[1];
            // html和矩形的计算方式是一样的，共用一个方法
            if (modelType === ModelType.RECT_NODE || modelType === ModelType.HTML_NODE) {
                _this.updateRect({ deltaX: deltaX, deltaY: deltaY });
                // this.nodeModel.resize(deltaX, deltaY);
            }
            else if (modelType === ModelType.ELLIPSE_NODE) {
                _this.updateEllipse({ deltaX: deltaX, deltaY: deltaY });
            }
            else if (modelType === ModelType.DIAMOND_NODE) {
                _this.updateDiamond({ deltaX: deltaX, deltaY: deltaY });
            }
        };
        /**
         * 由于将拖拽放大缩小改成丝滑模式，这个时候需要在拖拽结束的时候，将节点的位置更新到grid上.
         */
        _this.onDragEnd = function () {
            // 先触发onDragging()->更新边->再触发用户自定义的getDefaultAnchor()，所以onDragging()拿到的anchors是滞后的
            // 为了正确设置最终的位置，应该在拖拽结束的时候，再设置一次边的Point位置，此时拿到的anchors是最新的
            _this.updateEdgePointByAnchors();
            var _a = _this.graphModel.gridSize, gridSize = _a === void 0 ? 1 : _a;
            var x = gridSize * Math.round(_this.nodeModel.x / gridSize);
            var y = gridSize * Math.round(_this.nodeModel.y / gridSize);
            _this.nodeModel.moveTo(x, y);
        };
        _this.index = props.index;
        _this.nodeModel = props.model;
        _this.graphModel = props.graphModel;
        // 为保证对齐线功能正常使用，step默认是网格grid的两倍，
        // 没有网格设置，默认为2，保证坐标是整数
        // let step = 2;
        // if (gridSize > 1) {
        //   step = 2 * gridSize;
        // }
        // if (this.nodeModel.gridSize) {
        //   step = 2 * this.nodeModel.gridSize;
        // }
        _this.state = {};
        _this.dragHandler = new StepDrag({
            onDragging: _this.onDragging,
            onDragEnd: _this.onDragEnd,
            step: 1,
        });
        return _this;
    }
    Control.prototype.getNodeEdges = function (nodeId) {
        var graphModel = this.graphModel;
        var edges = graphModel.edges;
        var sourceEdges = [];
        var targetEdges = [];
        for (var i = 0; i < edges.length; i++) {
            var edgeModel = edges[i];
            if (edgeModel.sourceNodeId === nodeId) {
                sourceEdges.push(edgeModel);
            }
            else if (edges[i].targetNodeId === nodeId) {
                targetEdges.push(edgeModel);
            }
        }
        return { sourceEdges: sourceEdges, targetEdges: targetEdges };
    };
    Control.prototype.render = function () {
        var _a = this.props, x = _a.x, y = _a.y, index = _a.index, model = _a.model;
        var style = model.getControlPointStyle();
        return (h("g", { className: "lf-resize-control-" + index },
            h(Rect, __assign({ className: "lf-node-control" }, { x: x, y: y }, style, { onMouseDown: this.dragHandler.handleMouseDown }))));
    };
    return Control;
}(Component));
export default Control;
