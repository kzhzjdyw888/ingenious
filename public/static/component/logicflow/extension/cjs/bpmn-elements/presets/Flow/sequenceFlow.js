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
Object.defineProperty(exports, "__esModule", { value: true });
exports.sequenceFlowFactory = void 0;
/* eslint-disable @typescript-eslint/naming-convention */
var core_1 = require("@logicflow/core");
var utils_1 = require("../../utils");
function sequenceFlowFactory(props) {
    var model = /** @class */ (function (_super) {
        __extends(model, _super);
        function model(data, graphModel) {
            var _this = this;
            if (!data.id) {
                data.id = "Flow_" + utils_1.genBpmnId();
            }
            var properties = __assign(__assign(__assign({}, (props || {})), data.properties), { 
                // panels: ['condition'],
                isDefaultFlow: false });
            data.properties = properties;
            _this = _super.call(this, data, graphModel) || this;
            return _this;
        }
        model.extendKey = 'SequenceFlowModel';
        return model;
    }(core_1.PolylineEdgeModel));
    var view = /** @class */ (function (_super) {
        __extends(view, _super);
        function view() {
            return _super !== null && _super.apply(this, arguments) || this;
        }
        view.prototype.getStartArrow = function () {
            // eslint-disable-next-line no-shadow
            var model = this.props.model;
            var isDefaultFlow = model.properties.isDefaultFlow;
            return isDefaultFlow
                ? core_1.h('path', {
                    refX: 15,
                    stroke: '#000000',
                    strokeWidth: 2,
                    d: 'M 20 5 10 -5 z',
                })
                : core_1.h('path', {
                    d: '',
                });
        };
        view.extendKey = 'SequenceFlowEdge';
        return view;
    }(core_1.PolylineEdge));
    return {
        type: 'bpmn:sequenceFlow',
        view: view,
        model: model,
    };
}
exports.sequenceFlowFactory = sequenceFlowFactory;
