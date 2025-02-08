declare function toXmlJson(retainedFields?: string[]): (json: string | any[] | Object) => any;
/**
 * 将xmlJson转换为普通的json，在内部使用。
 */
declare function toNormalJson(xmlJson: any): {};
declare class BpmnAdapter {
    static pluginName: string;
    static shapeConfigMap: Map<any, any>;
    processAttributes: {
        ['-isExecutable']: string;
        ['-id']: string;
    };
    definitionAttributes: {
        ['-id']: string;
        ['-xmlns:xsi']: string;
        ['-xmlns:bpmn']: string;
        ['-xmlns:bpmndi']: string;
        ['-xmlns:dc']: string;
        ['-xmlns:di']: string;
        ['-targetNamespace']: string;
        ['-exporter']: string;
        ['-exporterVersion']: string;
        [key: string]: any;
    };
    constructor({ lf }: {
        lf: any;
    });
    setCustomShape(key: any, val: any): void;
    /**
     * @param retainedFields?: string[] (可选)属性保留字段，retainedField会和默认的defaultRetainedFields:
     * ["properties", "startPoint", "endPoint", "pointsList"]合并，
     * 这意味着出现在这个数组里的字段当它的值是数组或是对象时不会被视为一个节点而是一个属性。
     */
    adapterOut: (data: any, retainedFields?: string[]) => {
        'bpmn:definitions': {
            [key: string]: any;
            "-id": string;
            "-xmlns:xsi": string;
            "-xmlns:bpmn": string;
            "-xmlns:bpmndi": string;
            "-xmlns:dc": string;
            "-xmlns:di": string;
            "-targetNamespace": string;
            "-exporter": string;
            "-exporterVersion": string;
        };
    };
    adapterIn: (bpmnData: any) => {
        nodes: any[];
        edges: any[];
    };
}
declare class BpmnXmlAdapter extends BpmnAdapter {
    static pluginName: string;
    constructor(data: any);
    adapterXmlIn: (bpmnData: any) => {
        nodes: any[];
        edges: any[];
    };
    adapterXmlOut: (data: any, retainedFields?: string[]) => string;
}
export { BpmnAdapter, BpmnXmlAdapter, toXmlJson, toNormalJson };
export default BpmnAdapter;
