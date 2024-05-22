export declare const multiInstanceIcon: any;
/**
 * @param type 任务节点的type, 对应其XML定义中的节点名，如<bpmn:userTask /> 其type为bpmn:userTask
 * @param icon 任务节点左上角的图标，可以是svg path，也可以是h函数生成的svg
 * @param props (可选) 任务节点的属性
 * @returns { type: string, model: any, view: any }
 */
export declare function TaskNodeFactory(type: string, icon: string | any[], props?: any): {
    type: string;
    model: any;
    view: any;
};
