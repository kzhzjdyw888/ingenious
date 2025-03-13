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
/* eslint-disable no-shadow */
/* eslint-disable @typescript-eslint/naming-convention */
import { h } from '@logicflow/core';
import { GroupNode } from '../../../materials/group/index';
export function SubProcessFactory() {
    var view = /** @class */ (function (_super) {
        __extends(view, _super);
        function view() {
            return _super !== null && _super.apply(this, arguments) || this;
        }
        view.prototype.getFoldIcon = function () {
            var model = this.props.model;
            var x = model.x, y = model.y, width = model.width, height = model.height, properties = model.properties, foldable = model.foldable;
            var foldX = model.x - model.width / 2 + 5;
            var foldY = model.y - model.height / 2 + 5;
            if (!foldable)
                return null;
            var iconIcon = h('path', {
                fill: 'none',
                stroke: '#818281',
                strokeWidth: 2,
                'pointer-events': 'none',
                d: properties.isFolded
                    ? "M " + (foldX + 3) + "," + (foldY + 6) + " " + (foldX + 11) + "," + (foldY + 6) + " M" + (foldX + 7) + "," + (foldY + 2) + " " + (foldX + 7) + "," + (foldY + 10)
                    : "M " + (foldX + 3) + "," + (foldY + 6) + " " + (foldX + 11) + "," + (foldY + 6) + " ",
            });
            return h('g', {}, [
                h('rect', {
                    height: 12,
                    width: 14,
                    rx: 2,
                    ry: 2,
                    strokeWidth: 1,
                    fill: '#F4F5F6',
                    stroke: '#CECECE',
                    cursor: 'pointer',
                    x: x - width / 2 + 5,
                    y: y - height / 2 + 5,
                    onClick: function (e) {
                        e.stopPropagation();
                        model.foldGroup(!properties.isFolded);
                    },
                }),
                iconIcon,
            ]);
        };
        view.prototype.getResizeShape = function () {
            var model = this.props.model;
            var x = model.x, y = model.y, width = model.width, height = model.height;
            var style = model.getNodeStyle();
            var foldRectAttrs = __assign(__assign({}, style), { x: x - width / 2, y: y - height / 2, width: width,
                height: height, stroke: 'black', strokeWidth: 2, strokeDasharray: '0 0' });
            return h('g', {}, [
                // this.getAddAbleShape(),
                h('rect', __assign({}, foldRectAttrs)),
                this.getFoldIcon(),
            ]);
        };
        return view;
    }(GroupNode.view));
    var model = /** @class */ (function (_super) {
        __extends(model, _super);
        function model() {
            return _super !== null && _super.apply(this, arguments) || this;
        }
        model.prototype.initNodeData = function (data) {
            _super.prototype.initNodeData.call(this, data);
            this.foldable = true;
            // this.isFolded = true;
            this.resizable = true;
            this.width = 400;
            this.height = 200;
            // 根据 properties中的配置重设 宽高
            this.resetWidthHeight();
            this.isTaskNode = true; // 标识此节点是任务节点，可以被附件边界事件
            this.boundaryEvents = []; // 记录自己附加的边界事件
        };
        // 自定义根据properties.iniProp
        model.prototype.resetWidthHeight = function () {
            var _a, _b;
            var width = (_a = this.properties.iniProp) === null || _a === void 0 ? void 0 : _a.width;
            var height = (_b = this.properties.iniProp) === null || _b === void 0 ? void 0 : _b.height;
            width && (this.width = width);
            height && (this.height = height);
        };
        model.prototype.getNodeStyle = function () {
            var style = _super.prototype.getNodeStyle.call(this);
            style.stroke = '#989891';
            style.strokeWidth = 1;
            style.strokeDasharray = '3 3';
            if (this.isSelected) {
                style.stroke = 'rgb(124, 15, 255)';
            }
            // isBoundaryEventTouchingTask属性用于标识拖动边界节点是否靠近此节点
            // 如果靠近，则高亮提示
            // style.fill = 'rgb(255, 230, 204)';
            var isBoundaryEventTouchingTask = this.properties.isBoundaryEventTouchingTask;
            if (isBoundaryEventTouchingTask) {
                style.stroke = '#00acff';
                style.strokeWidth = 2;
            }
            return style;
        };
        model.prototype.addChild = function (id) {
            var model = this.graphModel.getElement(id);
            model.setProperties({
                parent: this.id,
            });
            _super.prototype.addChild.call(this, id);
        };
        // 隐藏锚点而不是设置锚点数为0
        // 因为分组内部节点与外部节点相连时，
        // 如果折叠分组，需要分组代替内部节点与外部节点相连。
        model.prototype.getAnchorStyle = function () {
            var style = _super.prototype.getAnchorStyle.call(this, {});
            style.stroke = '#000';
            style.fill = '#fff';
            style.hover.stroke = 'transparent';
            return style;
        };
        model.prototype.getOutlineStyle = function () {
            var style = _super.prototype.getOutlineStyle.call(this);
            style.stroke = 'transparent';
            !style.hover && (style.hover = {});
            style.hover.stroke = 'transparent';
            return style;
        };
        /**
         * 提供方法给插件在判断此节点被拖动边界事件节点靠近时调用，从而触发高亮
         */
        model.prototype.setTouching = function (flag) {
            this.setProperty('isBoundaryEventTouchingTask', flag);
        };
        /**
         * 附加后记录被附加的边界事件节点Id
         */
        model.prototype.addBoundaryEvent = function (nodeId) {
            this.setTouching(false);
            if (this.boundaryEvents.find(function (item) { return item === nodeId; })) {
                return false;
            }
            var boundaryEvent = this.graphModel.getNodeModelById(nodeId);
            boundaryEvent === null || boundaryEvent === void 0 ? void 0 : boundaryEvent.setProperties({
                attachedToRef: this.id,
            });
            this.boundaryEvents.push(nodeId);
            return true;
        };
        /**
         * 被附加的边界事件节点被删除时，移除记录
         */
        model.prototype.deleteBoundaryEvent = function (nodeId) {
            this.boundaryEvents = this.boundaryEvents.filter(function (item) { return item !== nodeId; });
        };
        return model;
    }(GroupNode.model));
    return {
        type: 'bpmn:subProcess',
        view: view,
        model: model,
    };
}
