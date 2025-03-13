class TaskModel extends RectNodeModel {
    static extendKey = 'TaskModel';

    constructor(data, graphModel) {
        super(data, graphModel)
        if (data.properties) {
            let temporaryStyle = data.properties.style ?? {};
            this.width = (temporaryStyle.width ? temporaryStyle.width : 120)
            this.height = (temporaryStyle.height ? temporaryStyle.height : 40)
        }
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

class TaskView extends RectNode {
    static extendKey = 'TaskNode';

    getLabelShape() {
        const {model} = this.props
        const {x, y, width, height} = model
        const style = model.getNodeStyle()
        return h(
            'svg',
            {
                x: x - width / 2 + 5,
                y: y - height / 2 + 5,
                width: 25,
                height: 25,
                viewBox: '0 0 1274 1024'
            },
            h('path', {
                fill: style.stroke,
                d:
                    'M655.807326 287.35973m-223.989415 0a218.879 218.879 0 1 0 447.978829 0 218.879 218.879 0 1 0-447.978829 0ZM1039.955839 895.482975c-0.490184-212.177424-172.287821-384.030443-384.148513-384.030443-211.862739 0-383.660376 171.85302-384.15056 384.030443L1039.955839 895.482975z'
            })
        )
    }

    getShape() {
        const {model} = this.props
        const {x, y, width, height, radius} = model
        const style = model.getNodeStyle()
        return h('g', {}, [
            h('rect', {
                ...style,
                x: x - width / 2,
                y: y - height / 2,
                rx: radius,
                ry: radius,
                width,
                height
            }),
            this.getLabelShape()
        ])
    }
}


const Task = {
    type: "ingenious:task",
    view: TaskView,
    model: TaskModel
}
