import { h } from 'preact';
import BaseEdge from './BaseEdge';
export default class LineEdge extends BaseEdge {
    /**
     * @overridable 支持重写, 此方法为获取边的形状，如果需要自定义边的形状，请重写此方法。
     * @example https://docs.logic-flow.cn/docs/#/zh/guide/basic/edge?id=%e5%9f%ba%e4%ba%8e-react-%e7%bb%84%e4%bb%b6%e8%87%aa%e5%ae%9a%e4%b9%89%e8%be%b9
     */
    getEdge(): h.JSX.Element;
    /**
     * @overridable 可重写，在完全自定义边的时候，可以重写此方法，来自定义边的选区。
     */
    getAppendWidth(): h.JSX.Element;
}
