/**
 * index 0 排他网关
 * index 1 包容网关
 * index 2 并行网关
 */
export declare const gatewayComposable: number[][];
/**
 * @param type 网关节点的type, 对应其XML定义中的节点名，如<bpmn:inclusiveGateway /> 其type为bpmn:inclusiveGateway
 * @param icon 网关节点左上角的图标，可以是svg path，也可以是h函数生成的svg
 * @param props (可选) 网关节点的属性
 * @returns { type: string, model: any, view: any }
 */
export declare function GatewayNodeFactory(type: string, icon: string | object, props?: any): {
    type: string;
    model: any;
    view: any;
};
