import { h } from 'preact';
import BaseNode from './BaseNode';
export default class TextNode extends BaseNode {
    getBackground(): h.JSX.Element;
    getShape(): h.JSX.Element;
}
