declare class Base extends Array {
    x: number;
    y: number;
    z: number;
    constructor(x: number, y: number, z: number);
    add(v1: Vector | Point): Vector | Point;
    subtract(v1: Vector | Point): Vector | Point;
}
declare class Vector extends Base {
    constructor(x: number, y: number, z?: number);
    toString(): string;
    dot(v1: Vector): any;
    cross(v1: Vector): Vector;
    getLength(): number;
    normalize(): Vector;
    crossZ(v1: Vector): number;
    angle(v1: Vector): number;
}
declare class Point extends Base {
    constructor(x: number, y: number);
    toString(): string;
}
export { Vector, Point };
