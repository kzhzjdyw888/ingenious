class EndModel extends CircleNodeModel {
    static extendKey = 'EndModel';

    constructor(data, graphModel) {
        if (!data.text) {
            data.text = ''
        }
        if (data.text && typeof data.text === 'string') {
            data.text = {
                value: data.text,
                x: data.x,
                y: data.y + 40
            }
        }
        super(data, graphModel)
    }

    setAttributes() {
        this.r = 18
    }

    getConnectedSourceRules() {
        const rules = super.getConnectedSourceRules()
        const notAsSource = {
            message: '结束节点不能作为边的起点',
            validate: () => false
        }
        rules.push(notAsSource)
        return rules
    }

    getTextStyle() {
        const style = super.getTextStyle();
        const properties = super.getProperties();
        style.color = properties.color ?? '#000000';
        return style;
    }

    getNodeStyle() {
        const style = super.getNodeStyle();
        const properties = super.getProperties();
        if (this.properties.state === 'active') {
            style.stroke = '#00ff00'
        } else if (this.properties.state === 'history') {
            style.stroke = '#ff0000'
        } else {
            style.fill = properties.theme ?? '#FFFFFF';
            style.stroke = properties.stroke ?? '#000000';
            style.strokeWidth = properties.stroke_width ?? 2;
        }
        return style;
    }
}

class EndView extends CircleNode {
    static extendKey = 'EndView';

    getAnchorStyle() {
        return {
            visibility: 'hidden'
        }
    }

    getShape() {
        const {model} = this.props
        const style = model.getNodeStyle()
        const {x, y, r} = model
        const outCircle = super.getShape()
        return h(
            'g',
            {},
            outCircle,
            h('circle', {
                ...style,
                cx: x,
                cy: y,
                r: r - 5
            })
        )
    }
}

const End = {
    type: 'ingenious:end',
    view: EndView,
    model: EndModel
}
