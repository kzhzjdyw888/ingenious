import BaseNodeModel from './BaseNodeModel';
import { ModelType } from '../../constant/constant';
declare class CircleNodeModel extends BaseNodeModel {
    modelType: ModelType;
    r: number;
    get width(): number;
    get height(): number;
    getNodeStyle(): {
        [x: string]: any;
        fill?: string;
        stroke?: string;
        strokeWidth?: number;
    };
    getDefaultAnchor(): {
        x: number;
        y: number;
        id: string;
    }[];
}
export { CircleNodeModel };
export default CircleNodeModel;
