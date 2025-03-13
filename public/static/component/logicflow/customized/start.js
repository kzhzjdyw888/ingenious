class StartModel extends CircleNodeModel {
    static extendKey = 'StartModel';

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

    getConnectedTargetRules() {
        const rules = super.getConnectedTargetRules()
        const notAsTarget = {
            message: '起始节点不能作为边的终点',
            validate: () => false
        }
        rules.push(notAsTarget)
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

class StartView extends CircleNode {
    static extendKey = 'StartNode';
}

const Start = {
    type: 'ingenious:start',
    view: StartView,
    model: StartModel
}
