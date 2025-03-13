import EventEmitter from '../event/eventEmitter';
import { IBaseModel } from '../model';
declare function createDrag({ onDragStart, onDragging, onDragEnd, step, isStopPropagation, }: {
    onDragStart?: (...args: any[]) => void;
    onDragging?: (...args: any[]) => void;
    onDragEnd?: (...args: any[]) => void;
    step?: number;
    isStopPropagation?: boolean;
}): (e: MouseEvent) => void;
declare class StepDrag {
    onDragStart: Function;
    onDragging: Function;
    onDragEnd: Function;
    step: number;
    isStopPropagation: boolean;
    isDragging: boolean;
    isStartDragging: boolean;
    startX: number;
    startY: number;
    sumDeltaX: number;
    sumDeltaY: number;
    eventType: string;
    eventCenter: EventEmitter | null;
    model?: IBaseModel;
    data?: object;
    startTime?: number;
    constructor({ onDragStart, onDragging, onDragEnd, eventType, eventCenter, step, isStopPropagation, model, data, }: {
        onDragStart?: (...args: any[]) => void;
        onDragging?: (...args: any[]) => void;
        onDragEnd?: (...args: any[]) => void;
        eventType?: string;
        eventCenter?: any;
        step?: number;
        isStopPropagation?: boolean;
        model?: any;
        data?: any;
    });
    setStep(step: number): void;
    handleMouseDown: (e: MouseEvent) => void;
    handleMouseMove: (e: MouseEvent) => void;
    handleMouseUp: (e: MouseEvent) => void;
    cancelDrag: () => void;
}
export { createDrag, StepDrag, };
