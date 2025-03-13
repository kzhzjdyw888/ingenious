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
import { PolylineEdge, PolylineEdgeModel, h } from '@logicflow/core';
import searchMiddleIndex from './searchMiddleIndex';
var CurvedEdge = /** @class */ (function (_super) {
    __extends(CurvedEdge, _super);
    function CurvedEdge() {
        return _super !== null && _super.apply(this, arguments) || this;
    }
    CurvedEdge.prototype.pointFilter = function (points) {
        var allPoints = points;
        var i = 1;
        while (i < allPoints.length - 1) {
            var _a = __read(allPoints[i - 1], 2), x = _a[0], y = _a[1];
            var _b = __read(allPoints[i], 2), x1 = _b[0], y1 = _b[1];
            var _c = __read(allPoints[i + 1], 2), x2 = _c[0], y2 = _c[1];
            if ((x === x1 && x1 === x2)
                || (y === y1 && y1 === y2)) {
                allPoints.splice(i, 1);
            }
            else {
                i++;
            }
        }
        return allPoints;
    };
    CurvedEdge.prototype.getEdge = function () {
        var model = this.props.model;
        var points = model.points, isAnimation = model.isAnimation, arrowConfig = model.arrowConfig, _a = model.radius, radius = _a === void 0 ? 5 : _a;
        var style = model.getEdgeStyle();
        var animationStyle = model.getEdgeAnimationStyle();
        var points2 = this.pointFilter(points.split(' ').map(function (p) { return p.split(',').map(function (a) { return Number(a); }); }));
        var res = searchMiddleIndex(points2);
        if (res) {
            var _b = __read(res, 2), first = _b[0], last = _b[1];
            var firstPoint = points2[first];
            var lastPoint_1 = points2[last];
            var flag = firstPoint.some(function (num, index) { return num === lastPoint_1[index]; });
            if (!flag) {
                var diff = (lastPoint_1[1] - firstPoint[1]) / 2;
                var firstNextPoint = [lastPoint_1[0], lastPoint_1[1] - diff];
                var lastPrePoint = [firstPoint[0], firstPoint[1] + diff];
                points2.splice(first + 1, 0, lastPrePoint, firstNextPoint);
            }
        }
        var _c = __read(points2[0], 2), startX = _c[0], startY = _c[1];
        var d = "M" + startX + " " + startY;
        // 1) 如果一个点不为开始和结束，则在这个点的前后增加弧度开始和结束点。
        // 2) 判断这个点与前一个点的坐标
        //    如果x相同则前一个点的x也不变，
        //    y为（这个点的y 大于前一个点的y, 则 为 这个点的y - 5；小于前一个点的y, 则为这个点的y+5）
        //    同理，判断这个点与后一个点的x,y是否相同，如果x相同，则y进行加减，如果y相同，则x进行加减
        for (var i = 1; i < points2.length - 1; i++) {
            var _d = __read(points2[i - 1], 2), preX = _d[0], preY = _d[1];
            var _e = __read(points2[i], 2), currentX = _e[0], currentY = _e[1];
            var _f = __read(points2[i + 1], 2), nextX = _f[0], nextY = _f[1];
            if (currentX === preX && currentY !== preY) {
                var y = currentY > preY ? currentY - radius : currentY + radius;
                d = d + " L " + currentX + " " + y;
            }
            if (currentY === preY && currentX !== preX) {
                var x = currentX > preX ? currentX - radius : currentX + radius;
                d = d + " L " + x + " " + currentY;
            }
            d = d + " Q " + currentX + " " + currentY;
            if (currentX === nextX && currentY !== nextY) {
                var y = currentY > nextY ? currentY - radius : currentY + radius;
                d = d + " " + currentX + " " + y;
            }
            if (currentY === nextY && currentX !== nextX) {
                var x = currentX > nextX ? currentX - radius : currentX + radius;
                d = d + " " + x + " " + currentY;
            }
        }
        var _g = __read(points2[points2.length - 1], 2), endX = _g[0], endY = _g[1];
        d = d + " L " + endX + " " + endY;
        var attrs = __assign(__assign(__assign({ d: d, style: isAnimation ? animationStyle : {} }, style), arrowConfig), { fill: 'none' });
        return h('path', __assign({ d: d }, attrs));
    };
    return CurvedEdge;
}(PolylineEdge));
var CurvedEdgeModel = /** @class */ (function (_super) {
    __extends(CurvedEdgeModel, _super);
    function CurvedEdgeModel() {
        return _super !== null && _super.apply(this, arguments) || this;
    }
    return CurvedEdgeModel;
}(PolylineEdgeModel));
export { CurvedEdge, 
// CurvedEdgeView,
CurvedEdgeModel, };
