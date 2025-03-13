import { Point } from '../type/index';
export declare const getCrossPointOfLine: (a: Point, b: Point, c: Point, d: Point) => false | {
    x: number;
    y: number;
};
export declare const isInSegment: (point: any, start: any, end: any) => boolean;
