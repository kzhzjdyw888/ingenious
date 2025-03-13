import { h, Component } from 'preact';
import GraphModel from '../../model/GraphModel';
declare type IProps = {
    graphModel: GraphModel;
    tool: any;
};
export default class ToolOverlay extends Component<IProps> {
    componentDidMount(): void;
    componentDidUpdate(): void;
    /**
     * 外部传入的一般是HTMLElement
     */
    getTools(): any;
    triggerToolRender(): void;
    render(): h.JSX.Element;
}
export {};
