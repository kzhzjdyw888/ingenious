"use strict";
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
Object.defineProperty(exports, "__esModule", { value: true });
exports.BoundaryEventFactory = void 0;
/* eslint-disable max-len */
/* eslint-disable @typescript-eslint/naming-convention */
var core_1 = require("@logicflow/core");
var utils_1 = require("../../utils");
function BoundaryEventFactory(lf) {
    var _a = __read(lf.useDefinition(), 1), definition = _a[0];
    var view = /** @class */ (function (_super) {
        __extends(view, _super);
        function view() {
            return _super !== null && _super.apply(this, arguments) || this;
        }
        view.prototype.getAnchorStyle = function () {
            return {
                visibility: 'hidden',
            };
        };
        view.prototype.getShape = function () {
            var _a;
            // @ts-ignore
            var model = this.props.model;
            var style = model.getNodeStyle();
            var x = model.x, y = model.y, r = model.r, width = model.width, height = model.height, properties = model.properties;
            var definitionType = properties.definitionType, cancelActivity = properties.cancelActivity;
            var icon = (((_a = definition.boundaryEvent) === null || _a === void 0 ? void 0 : _a.get(definitionType)) || {}).icon;
            var i = Array.isArray(icon)
                ? core_1.h.apply(void 0, __spread(['g', {
                        transform: "matrix(1 0 0 1 " + (x - width / 2) + " " + (y - height / 2) + ")",
                    }], icon)) : core_1.h('path', {
                transform: "matrix(1 0 0 1 " + (x - width / 2) + " " + (y - height / 2) + ")",
                d: icon,
            });
            return core_1.h('g', {}, core_1.h('circle', __assign(__assign({}, style), { cx: x, cy: y, r: r, strokeDasharray: cancelActivity ? '' : '5,5', strokeWidth: 1.5 })), core_1.h('circle', __assign(__assign({}, style), { cx: x, cy: y, r: r - 3, strokeDasharray: cancelActivity ? '' : '5,5', strokeWidth: 1.5 })), i);
        };
        return view;
    }(core_1.CircleNode));
    var model = /** @class */ (function (_super) {
        __extends(model, _super);
        function model(data, graphModel) {
            var _a, _b, _c;
            var _this = this;
            if (!data.id) {
                data.id = "Event_" + utils_1.genBpmnId();
            }
            if (!data.text) {
                data.text = '';
            }
            if (data.text && typeof data.text === 'string') {
                data.text = {
                    value: data.text,
                    x: data.x,
                    y: data.y + 40,
                };
            }
            var _d = (((_a = definition.boundaryEvent) === null || _a === void 0 ? void 0 : _a.get((_b = data.properties) === null || _b === void 0 ? void 0 : _b.definitionType)) || {}).properties, properties = _d === void 0 ? {} : _d;
            data.properties = __assign(__assign({ attachedToRef: '', cancelActivity: true }, properties), data.properties);
            ((_c = data.properties) === null || _c === void 0 ? void 0 : _c.definitionType) && (data.properties.definitionId = "Definition_" + utils_1.genBpmnId());
            _this = _super.call(this, data, graphModel) || this;
            utils_1.groupRule.call(_this);
            return _this;
        }
        model.prototype.initNodeData = function (data) {
            _super.prototype.initNodeData.call(this, data);
            this.r = 20;
            this.autoToFront = false; // 不自动设置到最顶部，而是使用自己的zIndex
            this.zIndex = 99999; // 保证边界事件节点用于在最上方
        };
        model.prototype.setAttributes = function () {
            this.r = 18;
        };
        return model;
    }(core_1.CircleNodeModel));
    return {
        type: 'bpmn:boundaryEvent',
        view: view,
        model: model,
    };
}
exports.BoundaryEventFactory = BoundaryEventFactory;
