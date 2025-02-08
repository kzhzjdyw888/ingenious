class SubProcessView extends GroupNode.view {

}

class SubProcessModel extends GroupNode.model {
    initNodeData(data) {
        super.initNodeData(data)
        this.isRestrict = true
        this.resizable = true
        this.foldable = false
        this.width = 500
        this.height = 300
        this.foldedWidth = 50
        this.foldedHeight = 50
    }

    getTextStyle() {
        const style = super.getTextStyle();
        const properties = super.getProperties();
        style.color = properties.color ?? '#262626';
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

const SubProcess = {
    type: 'ingenious:subProcess',
    view: SubProcessView,
    model: SubProcessModel
}
