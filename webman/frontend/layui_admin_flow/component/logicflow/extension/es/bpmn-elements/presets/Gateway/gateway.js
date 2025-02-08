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
/* eslint-disable @typescript-eslint/naming-convention */
import { PolygonNode, PolygonNodeModel, h, } from '@logicflow/core';
import { genBpmnId, groupRule } from '../../utils';
var gateway = {
    exclusive: 0,
    inclusive: 1,
    parallel: 2,
};
/**
 * index 0 排他网关
 * index 1 包容网关
 * index 2 并行网关
 */
export var gatewayComposable = [
    [1, 1, 0],
    [0, 0, 1],
    [0, 1, 1],
];
/**
 * @param type 网关节点的type, 对应其XML定义中的节点名，如<bpmn:inclusiveGateway /> 其type为bpmn:inclusiveGateway
 * @param icon 网关节点左上角的图标，可以是svg path，也可以是h函数生成的svg
 * @param props (可选) 网关节点的属性
 * @returns { type: string, model: any, view: any }
 */
export function GatewayNodeFactory(type, icon, props) {
    var view = /** @class */ (function (_super) {
        __extends(view, _super);
        function view() {
            return _super !== null && _super.apply(this, arguments) || this;
        }
        view.prototype.getShape = function () {
            // @ts-ignore
            var model = this.props.model;
            var x = model.x, y = model.y, width = model.width, height = model.height, points = model.points;
            var style = model.getNodeStyle();
            return h('g', {
                transform: "matrix(1 0 0 1 " + (x - width / 2) + " " + (y - height / 2) + ")",
            }, h('polygon', __assign(__assign({}, style), { x: x,
                y: y,
                points: points })), typeof icon === 'string'
                ? h('path', __assign(__assign({ d: icon }, style), { fill: 'rgb(34, 36, 42)', strokeWidth: 1 }))
                : icon);
        };
        return view;
    }(PolygonNode));
    var model = /** @class */ (function (_super) {
        __extends(model, _super);
        function model(data, graphModel) {
            var _this = this;
            if (!data.id) {
                data.id = "Gateway_" + genBpmnId();
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
            data.properties = __assign(__assign({}, (props || {})), data.properties);
            _this = _super.call(this, data, graphModel) || this;
            _this.points = [
                [25, 0],
                [50, 25],
                [25, 50],
                [0, 25],
            ];
            groupRule.call(_this);
            return _this;
        }
        return model;
    }(PolygonNodeModel));
    return {
        type: type,
        view: view,
        model: model,
    };
}
