import { PolylineEdge, PolylineEdgeModel } from '@logicflow/core';
declare class CurvedEdge extends PolylineEdge {
    pointFilter(points: any): any;
    getEdge(): import("preact").VNode<any>;
}
declare class CurvedEdgeModel extends PolylineEdgeModel {
}
export { CurvedEdge, CurvedEdgeModel, };
