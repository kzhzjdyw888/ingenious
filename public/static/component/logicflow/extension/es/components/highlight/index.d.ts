import LogicFlow from '@logicflow/core';
declare type IMode = 'single' | 'path';
declare class Highlight {
    lf: LogicFlow;
    static pluginName: string;
    mode: IMode;
    manual: boolean;
    tempStyles: {};
    constructor({ lf }: {
        lf: any;
    });
    setMode(mode: IMode): void;
    setManual(manual: boolean): void;
    private highlightSingle;
    private highlightPath;
    highlight(id: string, mode?: IMode): void;
    restoreHighlight(): void;
    render(lf: any, domContainer: any): void;
    destroy(): void;
}
export { Highlight };
