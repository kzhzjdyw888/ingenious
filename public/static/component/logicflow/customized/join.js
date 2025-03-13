class JoinModel extends PolygonNodeModel {
    static extendKey = 'JoinModel';

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
        this.points = [
            [25, 0],
            [50, 25],
            [25, 50],
            [0, 25]
        ]
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

class JoinView extends PolygonNode {
    static extendKey = 'JoinNode';

    getShape() {
        const {model} = this.props
        const {x, y, width, height, points} = model
        const style = model.getNodeStyle()
        return h(
            'g',
            {
                transform: `matrix(1 0 0 1 ${x - width / 2} ${y - height / 2})`
            },
            h('polygon', {
                ...style,
                x,
                y,
                points
            }),
            h(
                'svg',
                {
                    x: (width - 28) / 2,
                    y: (height - 28) / 2,
                    width: 28,
                    height: 28,
                    viewBox: '0 0 1024 1024'
                },
                h('path', {
                    fill: style.stroke,
                    d: 'M256 298.666667a42.666667 42.666667 0 1 0 0-85.333334 42.666667 42.666667 0 0 0 0 85.333334z m0 85.333333a128 128 0 1 0 0-256 128 128 0 0 0 0 256zM256 810.666667a42.666667 42.666667 0 1 0 0-85.333334 42.666667 42.666667 0 0 0 0 85.333334z m0 85.333333a128 128 0 1 0 0-256 128 128 0 0 0 0 256zM768 810.666667a42.666667 42.666667 0 1 0 0-85.333334 42.666667 42.666667 0 0 0 0 85.333334z m0 85.333333a128 128 0 1 0 0-256 128 128 0 0 0 0 256z'
                }),
                h('path', {
                    fill: style.stroke,
                    d: 'M213.333333 341.333333h85.333334v341.333334H213.333333V341.333333z'
                }),
                h('path', {
                    fill: style.stroke,
                    d: 'M213.333333 341.333333h85.333334a128 128 0 0 0 128 128h170.666666a213.333333 213.333333 0 0 1 213.333334 213.333334h-85.333334a128 128 0 0 0-128-128h-170.666666a213.333333 213.333333 0 0 1-213.333334-213.333334z'
                })
            )
        )
    }
}

const Join = {
    type: 'ingenious:join',
    view: JoinView,
    model: JoinModel
}
