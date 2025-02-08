declare type TransformerType = {
    [key: string]: {
        in?: (key: string, data: any) => any;
        out?: (data: any) => any;
    };
};
declare type MappingType = {
    in?: {
        [key: string]: string;
    };
    out?: {
        [key: string]: string;
    };
};
declare type excludeFieldsType = {
    in?: Set<string>;
    out?: Set<string>;
};
declare type ExtraPropsType = {
    retainedAttrsFields?: string[];
    excludeFields?: excludeFieldsType;
    transformer?: TransformerType;
    mapping?: MappingType;
};
/**
 * 将普通json转换为xmlJson
 * xmlJson中property会以“-”开头
 * 如果没有“-”表示为子节点
 * fix issue https://github.com/didi/LogicFlow/issues/718, contain the process of #text/#cdata and array
 * @reference node type reference https://www.w3schools.com/xml/dom_nodetype.asp
 * @param retainedAttrsFields retainedAttrsFields会和默认的defaultRetainedProperties:
 * ["properties", "startPoint", "endPoint", "pointsList"]合并
 * 这意味着出现在这个数组里的字段当它的值是数组或是对象时不会被视为一个节点而是一个属性
 * @param excludeFields excludeFields会和默认的defaultExcludeFields合并，出现在这个数组中的字段在转换时会被忽略
 * @param transformer 对应节点或者边的子内容转换规则
 */
declare function convertNormalToXml(other?: ExtraPropsType): (object: {
    nodes: any;
    edges: any;
}) => any;
/**
 * 将xmlJson转换为普通的json，在内部使用。
 */
declare function convertXmlToNormal(xmlJson: any): any;
declare class BPMNBaseAdapter {
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
    constructor({ lf }: any);
    setCustomShape(key: string, val: any): void;
    /**
     * @param retainedAttrsFields?: string[] (可选)属性保留字段，retainedField会和默认的defaultRetainedFields:
     * ["properties", "startPoint", "endPoint", "pointsList"]合并，
     * 这意味着出现在这个数组里的字段当它的值是数组或是对象时不会被视为一个节点而是一个属性。
     * @param excludeFields excludeFields会和默认的defaultExcludeFields合并，出现在这个数组中的字段在转换时会被忽略
     * @param transformer 对应节点或者边的内容转换规则
     */
    adapterOut: (data: any, other?: ExtraPropsType) => {
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
    adapterIn: (bpmnData: any, other?: ExtraPropsType) => {
        nodes: any[];
        edges: any[];
    };
}
declare class BPMNAdapter extends BPMNBaseAdapter {
    static pluginName: string;
    private props;
    constructor(data: any);
    adapterXmlIn: (bpmnData: any) => {
        nodes: any[];
        edges: any[];
    };
    adapterXmlOut: (data: any) => string;
}
export { BPMNBaseAdapter, BPMNAdapter, convertNormalToXml, convertXmlToNormal };
export default BPMNAdapter;
