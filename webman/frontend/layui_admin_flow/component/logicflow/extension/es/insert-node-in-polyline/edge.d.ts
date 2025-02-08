import { Point, PolylineEdgeModel, BaseNodeModel } from '@logicflow/core';
/**
 * 判断一个点是否在线段中
 * @param point 判断的点
 * @param start 线段的起点
 * @param end 线段的终点
 * @param deviation 误差范围
 * @returns boolean
 */
export declare const isInSegment: (point: any, start: any, end: any, deviation?: number) => boolean;
export declare const distToSegmentSquared: (p: any, v: any, w: any) => number;
export declare const distToSegment: (point: Point, start: Point, end: Point) => number;
export declare const crossPointInSegment: (node: BaseNodeModel, start: Point, end: Point) => {
    startCrossPoint: {
        x: number;
        y: number;
    };
    endCrossPoint: {
        x: number;
        y: number;
    };
};
interface SegmentCross {
    crossIndex: number;
    crossPoints: {
        startCrossPoint: Point;
        endCrossPoint: Point;
    };
}
export declare const isNodeInSegment: (node: BaseNodeModel, polyline: PolylineEdgeModel, deviation?: number) => SegmentCross;
export {};
