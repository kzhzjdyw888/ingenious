"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
exports.isNodeInSegment = exports.crossPointInSegment = exports.distToSegment = exports.distToSegmentSquared = exports.isInSegment = void 0;
// 这个里面的函数有些在core中已经存在，为了解耦关系，没有引用
var SegmentDirection;
(function (SegmentDirection) {
    SegmentDirection["HORIZONTAL"] = "horizontal";
    SegmentDirection["VERTICAL"] = "vertical";
})(SegmentDirection || (SegmentDirection = {}));
/**
 * 判断一个点是否在线段中
 * @param point 判断的点
 * @param start 线段的起点
 * @param end 线段的终点
 * @param deviation 误差范围
 * @returns boolean
 */
exports.isInSegment = function (point, start, end, deviation) {
    if (deviation === void 0) { deviation = 0; }
    var distance = exports.distToSegment(point, start, end);
    return distance <= deviation;
};
function sqr(x) {
    return x * x;
}
function dist2(v, w) {
    return sqr(v.x - w.x) + sqr(v.y - w.y);
}
exports.distToSegmentSquared = function (p, v, w) {
    var l2 = dist2(v, w);
    if (l2 === 0)
        return dist2(p, v);
    var t = ((p.x - v.x) * (w.x - v.x) + (p.y - v.y) * (w.y - v.y)) / l2;
    t = Math.max(0, Math.min(1, t));
    return dist2(p, {
        x: v.x + t * (w.x - v.x),
        y: v.y + t * (w.y - v.y),
    });
};
exports.distToSegment = function (point, start, end) { return Math.sqrt(exports.distToSegmentSquared(point, start, end)); };
/* 获取节点bbox */
var getNodeBBox = function (node) {
    var x = node.x, y = node.y, width = node.width, height = node.height;
    var bBox = {
        minX: x - width / 2,
        minY: y - height / 2,
        maxX: x + width / 2,
        maxY: y + height / 2,
        x: x,
        y: y,
        width: width,
        height: height,
        centerX: x,
        centerY: y,
    };
    return bBox;
};
/* 判断线段的方向 */
var segmentDirection = function (start, end) {
    var direction;
    if (start.x === end.x) {
        direction = SegmentDirection.VERTICAL;
    }
    else if (start.y === end.y) {
        direction = SegmentDirection.HORIZONTAL;
    }
    return direction;
};
// 节点是够在线段内，求出节点与线段的交点
exports.crossPointInSegment = function (node, start, end) {
    var bBox = getNodeBBox(node);
    var direction = segmentDirection(start, end);
    var maxX = Math.max(start.x, end.x);
    var minX = Math.min(start.x, end.x);
    var maxY = Math.max(start.y, end.y);
    var minY = Math.min(start.y, end.y);
    var x = node.x, y = node.y, width = node.width, height = node.height;
    if (direction === SegmentDirection.HORIZONTAL) {
        // 同一水平线
        if (maxX >= bBox.maxX && minX <= bBox.minX) {
            return {
                startCrossPoint: {
                    x: start.x > end.x ? x + (width / 2) : x - (width / 2),
                    y: start.y,
                },
                endCrossPoint: {
                    x: start.x > end.x ? x - (width / 2) : x + (width / 2),
                    y: start.y,
                },
            };
        }
    }
    else if (direction === SegmentDirection.VERTICAL) {
        // 同一垂直线
        if (maxY >= bBox.maxY && minY <= bBox.minY) {
            return {
                startCrossPoint: {
                    x: start.x,
                    y: start.y > end.y ? y + (height / 2) : y - (height / 2),
                },
                endCrossPoint: {
                    x: start.x,
                    y: start.y > end.y ? y - (height / 2) : y + (height / 2),
                },
            };
        }
    }
};
// 节点是否在线段内
// eslint-disable-next-line max-len
exports.isNodeInSegment = function (node, polyline, deviation) {
    if (deviation === void 0) { deviation = 0; }
    var x = node.x, y = node.y;
    var pointsList = polyline.pointsList;
    for (var i = 0; i < pointsList.length - 1; i++) {
        if (exports.isInSegment({ x: x, y: y }, pointsList[i], pointsList[i + 1], deviation)) {
            var bBoxCross = exports.crossPointInSegment(node, pointsList[i], pointsList[i + 1]);
            if (bBoxCross) {
                return {
                    crossIndex: i + 1,
                    crossPoints: bBoxCross,
                };
            }
        }
    }
    return {
        crossIndex: -1,
        crossPoints: {
            startCrossPoint: { x: 0, y: 0 },
            endCrossPoint: { x: 0, y: 0 },
        },
    };
};
