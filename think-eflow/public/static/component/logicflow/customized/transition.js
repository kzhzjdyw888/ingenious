
class TransitionModel extends PolylineEdgeModel   {
    static extendKey = 'TransitionModel';
    getEdgeStyle() {
        const style = super.getEdgeStyle()
        if (this.properties.state === 'active') {
            style.stroke = '#00ff00'
        } else if (this.properties.state === 'history') {
            style.stroke = '#ff0000'
        }
        return style
    }
}

class TransitionView extends PolylineEdge {
    static extendKey = 'TransitionEdge';
}

const Transition = {
    type: 'ingenious:transition',
    view: TransitionView,
    model: TransitionModel
}
