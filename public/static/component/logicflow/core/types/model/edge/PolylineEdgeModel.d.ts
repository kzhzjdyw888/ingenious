import { ModelType } from '../../constant/constant';
import { Point } from '../../type';
import BaseEdgeModel from './BaseEdgeModel';
export { PolylineEdgeModel };
export default class PolylineEdgeModel extends BaseEdgeModel {
    modelType: ModelType;
    draggingPointList: any;
    dbClickPosition: Point;
    initEdgeData(data: any): void;
    getEdgeStyle(): {
        [x: string]: any;
        fill?: string;
        stroke?: string;
        strokeWidth?: number;
    };
    getTextPosition(): {
        x: number;
        y: number;
    };
    getAfterAnchor(direction: any, position: any, anchorList: any): any;
    getCrossPoint(direction: any, start: any, end: any): any;
    removeCrossPoints(startIndex: any, endIndex: any, pointList: any): any;
    getDraggingPoints(direction: any, positionType: any, position: any, anchorList: any, draggingPointList: any): any;
    updateCrossPoints(pointList: any): any;
    getData(): import("../../type").EdgeData & {
        pointsList: {
            x: any;
            y: any;
        }[];
    };
    initPoints(): void;
    updatePoints(): void;
    updateStartPoint(anchor: any): void;
    moveStartPoint(deltaX: any, deltaY: any): void;
    updateEndPoint(anchor: any): void;
    moveEndPoint(deltaX: any, deltaY: any): void;
    dragAppendStart(): void;
    dragAppendSimple(appendInfo: any, dragInfo: any): {
        start: any;
        end: any;
        startIndex: any;
        endIndex: any;
        direction: any;
    };
    dragAppend(appendInfo: any, dragInfo: any): {
        start: any;
        end: any;
        startIndex: any;
        endIndex: any;
        direction: any;
    };
    dragAppendEnd(): void;
    updatePointsAfterDrag(pointsList: any): void;
    getAdjustStart(): any;
    getAdjustEnd(): any;
    updateAfterAdjustStartAndEnd({ startPoint, endPoint, sourceNode, targetNode }: {
        startPoint: any;
        endPoint: any;
        sourceNode: any;
        targetNode: any;
    }): void;
}
