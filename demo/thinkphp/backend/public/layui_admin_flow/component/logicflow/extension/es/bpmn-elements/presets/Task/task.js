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
var __spread = (this && this.__spread) || function () {
    for (var ar = [], i = 0; i < arguments.length; i++) ar = ar.concat(__read(arguments[i]));
    return ar;
};
/* eslint-disable @typescript-eslint/naming-convention */
import { RectNode, RectNodeModel, h, } from '@logicflow/core';
import { parallelMarker, sequentialMarker, loopMarker } from '../icons';
import { genBpmnId, groupRule } from '../../utils';
export var multiInstanceIcon = {
    parallel: parallelMarker,
    sequential: sequentialMarker,
    loop: loopMarker,
};
/**
 * @param type 任务节点的type, 对应其XML定义中的节点名，如<bpmn:userTask /> 其type为bpmn:userTask
 * @param icon 任务节点左上角的图标，可以是svg path，也可以是h函数生成的svg
 * @param props (可选) 任务节点的属性
 * @returns { type: string, model: any, view: any }
 */
export function TaskNodeFactory(type, icon, props) {
    var view = /** @class */ (function (_super) {
        __extends(view, _super);
        function view() {
            return _super !== null && _super.apply(this, arguments) || this;
        }
        view.prototype.getLabelShape = function () {
            // @ts-ignore
            var model = this.props.model;
            var x = model.x, y = model.y, width = model.width, height = model.height;
            var style = model.getNodeStyle();
            var i = Array.isArray(icon)
                ? h.apply(void 0, __spread(['g', {
                        transform: "matrix(1 0 0 1 " + (x - width / 2) + " " + (y - height / 2) + ")",
                    }], icon)) : h('path', {
                fill: style.stroke,
                d: icon,
            });
            return h('svg', {
                x: x - width / 2 + 5,
                y: y - height / 2 + 5,
                width: 25,
                height: 25,
                viewBox: '0 0 1274 1024',
            }, i);
        };
        view.prototype.getShape = function () {
            // @ts-ignore
            var model = this.props.model;
            var x = model.x, y = model.y, width = model.width, height = model.height, radius = model.radius, properties = model.properties;
            var style = model.getNodeStyle();
            return h('g', {}, [
                h('rect', __assign(__assign({}, style), { x: x - width / 2, y: y - height / 2, rx: radius, ry: radius, width: width,
                    height: height, opacity: 0.95 })),
                this.getLabelShape(),
                h('g', {
                    transform: "matrix(1 0 0 1 " + (x - width / 2) + " " + (y - height / 2) + ")",
                }, h('path', {
                    fill: 'white',
                    strokeLinecap: 'round',
                    strokeLinejoin: 'round',
                    stroke: 'rgb(34, 36, 42)',
                    strokeWidth: '2',
                    d: multiInstanceIcon[properties.multiInstanceType] || '',
                })),
            ]);
        };
        return view;
    }(RectNode));
    var model = /** @class */ (function (_super) {
        __extends(model, _super);
        function model(data, graphModel) {
            var _a;
            var _this = this;
            if (!data.id) {
                data.id = "Activity_" + genBpmnId();
            }
            var properties = __assign(__assign({}, (props || {})), data.properties);
            data.properties = properties;
            _this = _super.call(this, data, graphModel) || this;
            (_a = properties === null || properties === void 0 ? void 0 : properties.boundaryEvents) === null || _a === void 0 ? void 0 : _a.forEach(function (id) {
                _this.addBoundaryEvent(id);
            });
            _this.deleteProperty('boundaryEvents');
            groupRule.call(_this);
            return _this;
        }
        model.prototype.initNodeData = function (data) {
            _super.prototype.initNodeData.call(this, data);
            this.isTaskNode = true; // 标识此节点是任务节点，可以被附件边界事件
            this.boundaryEvents = []; // 记录自己附加的边界事件
        };
        model.prototype.getNodeStyle = function () {
            var style = _super.prototype.getNodeStyle.call(this);
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
    }(RectNodeModel));
    // @ts-ignore
    return {
        type: type,
        view: view,
        model: model,
    };
}
