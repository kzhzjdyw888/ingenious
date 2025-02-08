"use strict";
var __values = (this && this.__values) || function(o) {
    var s = typeof Symbol === "function" && Symbol.iterator, m = s && o[s], i = 0;
    if (m) return m.call(o);
    if (o && typeof o.length === "number") return {
        next: function () {
            if (o && i >= o.length) o = void 0;
            return { value: o && o[i++], done: !o };
        }
    };
    throw new TypeError(s ? "Object is not iterable." : "Symbol.iterator is not defined.");
};
Object.defineProperty(exports, "__esModule", { value: true });
exports.MiniMap = void 0;
var lodash_es_1 = require("lodash-es");
var MiniMap = /** @class */ (function () {
    function MiniMap(_a) {
        var _this = this;
        var lf = _a.lf, LogicFlow = _a.LogicFlow, options = _a.options;
        this.lf = null;
        this.container = null;
        this.miniMapWrap = null;
        this.miniMapContainer = null;
        this.lfMap = null;
        this.viewport = null;
        this.width = 150;
        this.height = 220;
        this.leftPosition = undefined;
        this.topPosition = undefined;
        this.rightPosition = undefined;
        this.bottomPosition = undefined;
        this.miniMapWidth = 450;
        this.miniMapHeight = 660;
        this.viewPortTop = 0;
        this.viewPortLeft = 0;
        this.startPosition = null;
        this.viewPortScale = 1;
        this.viewPortWidth = 150;
        this.viewPortHeight = 75;
        this.resetDataX = 0;
        this.resetDataY = 0;
        this.LogicFlow = null;
        this.isShow = false;
        this.isShowHeader = true;
        this.isShowCloseIcon = true;
        this.dragging = false;
        this.disabledPlugins = ['miniMap', 'control', 'selectionSelect'];
        /**
         * 显示mini map
        */
        this.show = function (leftPosition, topPosition) {
            _this.setView();
            if (!_this.isShow) {
                _this.createMiniMap(leftPosition, topPosition);
            }
            _this.isShow = true;
        };
        /**
         * 隐藏mini map
         */
        this.hide = function () {
            if (_this.isShow) {
                _this.removeMiniMap();
            }
            _this.isShow = false;
        };
        this.reset = function () {
            _this.lf.resetTranslate();
            _this.lf.resetZoom();
            _this.hide();
            _this.show();
        };
        this.startDrag = function (e) {
            document.addEventListener('mousemove', _this.drag);
            document.addEventListener('mouseup', _this.drop);
            _this.startPosition = {
                x: e.x,
                y: e.y,
            };
        };
        this.moveViewport = function (top, left) {
            var viewStyle = _this.viewport.style;
            _this.viewPortTop = top;
            _this.viewPortLeft = left;
            viewStyle.top = _this.viewPortTop + "px";
            viewStyle.left = _this.viewPortLeft + "px";
        };
        this.drag = function (e) {
            _this.dragging = true;
            var top = _this.viewPortTop + e.y - _this.startPosition.y;
            var left = _this.viewPortLeft + e.x - _this.startPosition.x;
            _this.moveViewport(top, left);
            _this.startPosition = {
                x: e.x,
                y: e.y,
            };
            var centerX = (_this.viewPortLeft + _this.viewPortWidth / 2)
                / _this.viewPortScale;
            var centerY = (_this.viewPortTop + _this.viewPortHeight / 2)
                / _this.viewPortScale;
            _this.lf.focusOn({
                coordinate: {
                    x: centerX + _this.resetDataX,
                    y: centerY + _this.resetDataY,
                },
            });
        };
        this.drop = function () {
            document.removeEventListener('mousemove', _this.drag);
            document.removeEventListener('mouseup', _this.drop);
            var top = _this.viewPortTop;
            var left = _this.viewPortLeft;
            if (_this.viewPortLeft > _this.width) {
                left = _this.width - _this.viewPortWidth;
            }
            if (_this.viewPortTop > _this.height) {
                top = _this.height - _this.viewPortHeight;
            }
            if (_this.viewPortLeft < -_this.width) {
                left = 0;
            }
            if (_this.viewPortTop < -_this.height) {
                top = 0;
            }
            _this.moveViewport(top, left);
        };
        this.mapClick = function (e) {
            if (_this.dragging) {
                _this.dragging = false;
            }
            else {
                var layerX = e.layerX, layerY = e.layerY;
                var ViewPortCenterX = layerX;
                var ViewPortCenterY = layerY;
                var graphData = _this.lf.getGraphRawData();
                var _a = _this.getBounds(graphData), left = _a.left, top_1 = _a.top;
                var resetGraphX = left + ViewPortCenterX / _this.viewPortScale;
                var resetGraphY = top_1 + ViewPortCenterY / _this.viewPortScale;
                _this.lf.focusOn({ coordinate: { x: resetGraphX, y: resetGraphY } });
            }
        };
        this.lf = lf;
        if (options && options.MiniMap) {
            this.setOption(options);
        }
        this.miniMapWidth = lf.graphModel.width;
        this.miniMapHeight = (lf.graphModel.width * this.height) / this.width;
        this.LogicFlow = LogicFlow;
        this.initMiniMap();
    }
    MiniMap.prototype.render = function (lf, container) {
        var _this = this;
        this.container = container;
        this.lf.on('history:change', function () {
            if (_this.isShow) {
                _this.setView();
            }
        });
        this.lf.on('graph:transform', lodash_es_1.throttle(function () {
            // 小地图已展示，并且没有拖拽小地图视口
            if (_this.isShow && !_this.dragging) {
                _this.setView();
            }
        }, 300));
    };
    MiniMap.prototype.init = function (option) {
        this.disabledPlugins = this.disabledPlugins.concat(option.disabledPlugins || []);
    };
    MiniMap.prototype.setOption = function (options) {
        var _a = options.MiniMap, _b = _a.width, width = _b === void 0 ? 150 : _b, _c = _a.height, height = _c === void 0 ? 220 : _c, _d = _a.isShowHeader, isShowHeader = _d === void 0 ? true : _d, _e = _a.isShowCloseIcon, isShowCloseIcon = _e === void 0 ? true : _e, _f = _a.leftPosition, leftPosition = _f === void 0 ? 0 : _f, _g = _a.topPosition, topPosition = _g === void 0 ? 0 : _g, rightPosition = _a.rightPosition, bottomPosition = _a.bottomPosition;
        this.width = width;
        this.height = height;
        this.isShowHeader = isShowHeader;
        this.isShowCloseIcon = isShowCloseIcon;
        this.viewPortWidth = width;
        this.leftPosition = leftPosition;
        this.topPosition = topPosition;
        this.rightPosition = rightPosition;
        this.bottomPosition = bottomPosition;
    };
    MiniMap.prototype.initMiniMap = function () {
        var miniMapWrap = document.createElement('div');
        miniMapWrap.className = 'lf-mini-map-graph';
        miniMapWrap.style.width = this.width + 4 + "px";
        miniMapWrap.style.height = this.height + "px";
        this.lfMap = new this.LogicFlow({
            container: miniMapWrap,
            isSilentMode: true,
            stopZoomGraph: true,
            stopScrollGraph: true,
            stopMoveGraph: true,
            hideAnchors: true,
            hoverOutline: false,
            disabledPlugins: this.disabledPlugins,
        });
        // minimap中禁用adapter。
        this.lfMap.adapterIn = function (a) { return a; };
        this.lfMap.adapterOut = function (a) { return a; };
        this.miniMapWrap = miniMapWrap;
        this.createViewPort();
        miniMapWrap.addEventListener('click', this.mapClick);
    };
    MiniMap.prototype.createMiniMap = function (left, top) {
        var miniMapContainer = document.createElement('div');
        miniMapContainer.appendChild(this.miniMapWrap);
        if (typeof left !== 'undefined' || typeof top !== 'undefined') {
            miniMapContainer.style.left = (left || 0) + "px";
            miniMapContainer.style.top = (top || 0) + "px";
        }
        else {
            if (typeof this.rightPosition !== 'undefined') {
                miniMapContainer.style.right = this.rightPosition + "px";
            }
            else if (typeof this.leftPosition !== 'undefined') {
                miniMapContainer.style.left = this.leftPosition + "px";
            }
            if (typeof this.bottomPosition !== 'undefined') {
                miniMapContainer.style.bottom = this.bottomPosition + "px";
            }
            else if (typeof this.topPosition !== 'undefined') {
                miniMapContainer.style.top = this.topPosition + "px";
            }
        }
        miniMapContainer.style.position = 'absolute';
        miniMapContainer.className = 'lf-mini-map';
        if (!this.isShowCloseIcon) {
            miniMapContainer.classList.add('lf-mini-map-no-close-icon');
        }
        if (!this.isShowHeader) {
            miniMapContainer.classList.add('lf-mini-map-no-header');
        }
        this.container.appendChild(miniMapContainer);
        this.miniMapWrap.appendChild(this.viewport);
        var header = document.createElement('div');
        header.className = 'lf-mini-map-header';
        header.innerText = MiniMap.headerTitle;
        miniMapContainer.appendChild(header);
        var close = document.createElement('span');
        close.className = 'lf-mini-map-close';
        close.addEventListener('click', this.hide);
        miniMapContainer.appendChild(close);
        this.miniMapContainer = miniMapContainer;
    };
    MiniMap.prototype.removeMiniMap = function () {
        this.container.removeChild(this.miniMapContainer);
    };
    /**
     * 计算所有图形一起，占领的区域范围。
     * @param data
     */
    MiniMap.prototype.getBounds = function (data) {
        var left = 0;
        var right = this.miniMapWidth;
        var top = 0;
        var bottom = this.miniMapHeight;
        var nodes = data.nodes;
        if (nodes && nodes.length > 0) {
            // 因为获取的节点不知道真实的宽高，这里需要补充一点数值
            nodes.forEach(function (_a) {
                var x = _a.x, y = _a.y, _b = _a.width, width = _b === void 0 ? 200 : _b, _c = _a.height, height = _c === void 0 ? 200 : _c;
                var nodeLeft = x - width / 2;
                var nodeRight = x + width / 2;
                var nodeTop = y - height / 2;
                var nodeBottom = y + height / 2;
                left = nodeLeft < left ? nodeLeft : left;
                right = nodeRight > right ? nodeRight : right;
                top = nodeTop < top ? nodeTop : top;
                bottom = nodeBottom > bottom ? nodeBottom : bottom;
            });
        }
        return {
            left: left,
            top: top,
            bottom: bottom,
            right: right,
        };
    };
    /**
     * 将负值的平移转换为正值。
     * 保证渲染的时候，minimap能完全展示。
     * 获取将画布所有元素平移到0，0开始时，所有节点数据
     */
    MiniMap.prototype.resetData = function (data) {
        var nodes = data.nodes, edges = data.edges;
        var left = 0;
        var top = 0;
        if (nodes && nodes.length > 0) {
            // 因为获取的节点不知道真实的宽高，这里需要补充一点数值
            nodes.forEach(function (_a) {
                var x = _a.x, y = _a.y, _b = _a.width, width = _b === void 0 ? 200 : _b, _c = _a.height, height = _c === void 0 ? 200 : _c;
                var nodeLeft = x - width / 2;
                var nodeTop = y - height / 2;
                left = nodeLeft < left ? nodeLeft : left;
                top = nodeTop < top ? nodeTop : top;
            });
            if (left < 0 || top < 0) {
                this.resetDataX = left;
                this.resetDataY = top;
                nodes.forEach(function (node) {
                    node.x = node.x - left;
                    node.y = node.y - top;
                    if (node.text) {
                        node.text.x = node.text.x - left;
                        node.text.y = node.text.y - top;
                    }
                });
                edges.forEach(function (edge) {
                    if (edge.startPoint) {
                        edge.startPoint.x = edge.startPoint.x - left;
                        edge.startPoint.y = edge.startPoint.y - top;
                    }
                    if (edge.endPoint) {
                        edge.endPoint.x = edge.endPoint.x - left;
                        edge.endPoint.y = edge.endPoint.y - top;
                    }
                    if (edge.text) {
                        edge.text.x = edge.text.x - left;
                        edge.text.y = edge.text.y - top;
                    }
                    if (edge.pointsList) {
                        edge.pointsList.forEach(function (point) {
                            point.x = point.x - left;
                            point.y = point.y - top;
                        });
                    }
                });
            }
        }
        return data;
    };
    /**
     * 显示导航
     * 显示视口范围
     * 1. 基于画布的范围比例，设置视口范围比例。宽度默认为导航宽度。
     */
    MiniMap.prototype.setView = function () {
        var e_1, _a;
        // 1. 获取到图中所有的节点中的位置，将其偏移到原点开始（避免节点位置为负的时候无法展示问题）。
        var graphData = this.lf.getGraphRawData();
        var data = this.resetData(graphData);
        // 由于随时都会有新节点注册进来，需要同步将注册的
        var viewMap = this.lf.viewMap;
        var modelMap = this.lf.graphModel.modelMap;
        var minimapViewMap = this.lfMap.viewMap;
        try {
            // todo: no-restricted-syntax
            for (var _b = __values(viewMap.keys()), _c = _b.next(); !_c.done; _c = _b.next()) {
                var key = _c.value;
                if (!minimapViewMap.has(key)) {
                    this.lfMap.setView(key, viewMap.get(key));
                    this.lfMap.graphModel.modelMap.set(key, modelMap.get(key));
                }
            }
        }
        catch (e_1_1) { e_1 = { error: e_1_1 }; }
        finally {
            try {
                if (_c && !_c.done && (_a = _b.return)) _a.call(_b);
            }
            finally { if (e_1) throw e_1.error; }
        }
        this.lfMap.render(data);
        // 2. 将偏移后的数据渲染到minimap画布上
        // 3. 计算出所有节点在一起的边界。
        var _d = this.getBounds(data), left = _d.left, top = _d.top, right = _d.right, bottom = _d.bottom;
        // 4. 计算所有节点的边界与minimap看板的边界的比例.
        var realWidthScale = this.width / (right - left);
        var realHeightScale = this.height / (bottom - top);
        // 5. 取比例最小的值，将渲染的画布缩小对应比例。
        var innerStyle = this.miniMapWrap.firstChild.style;
        var scale = Math.min(realWidthScale, realHeightScale);
        innerStyle.transform = "matrix(" + scale + ", 0, 0, " + scale + ", 0, 0)";
        innerStyle.transformOrigin = 'left top';
        innerStyle.height = bottom - Math.min(top, 0) + "px";
        innerStyle.width = right - Math.min(left, 0) + "px";
        this.viewPortScale = scale;
        this.setViewPort(scale, {
            left: left,
            top: top,
            right: right,
            bottom: bottom,
        });
    };
    // 设置视口
    MiniMap.prototype.setViewPort = function (scale, _a) {
        var left = _a.left, right = _a.right, top = _a.top, bottom = _a.bottom;
        var viewStyle = this.viewport.style;
        viewStyle.width = this.viewPortWidth + "px";
        viewStyle.height = (this.viewPortWidth) / (this.lf.graphModel.width / this.lf.graphModel.height) + "px";
        var _b = this.lf.getTransform(), TRANSLATE_X = _b.TRANSLATE_X, TRANSLATE_Y = _b.TRANSLATE_Y, SCALE_X = _b.SCALE_X, SCALE_Y = _b.SCALE_Y;
        var realWidth = right - left;
        // 视口宽 = 小地图宽 / (所有元素一起占据的真实宽 / 绘布宽)
        var viewPortWidth = (this.width) / (realWidth / this.lf.graphModel.width);
        // 实际视口宽 = 小地图宽 * 占宽度比例
        var realViewPortWidth = this.width * (viewPortWidth / this.width);
        var graphRatio = (this.lf.graphModel.width / this.lf.graphModel.height);
        // 视口实际高 = 视口实际宽 / (绘布宽 / 绘布高)
        var realViewPortHeight = realViewPortWidth / graphRatio;
        var graphData = this.lf.getGraphRawData();
        var _c = this.getBounds(graphData), graphLeft = _c.left, graphTop = _c.top;
        var viewportLeft = graphLeft;
        var viewportTop = graphTop;
        viewportLeft += TRANSLATE_X / SCALE_X;
        viewportTop += TRANSLATE_Y / SCALE_Y;
        this.viewPortTop = viewportTop > 0 ? 0 : (-viewportTop * scale);
        this.viewPortLeft = viewportLeft > 0 ? 0 : (-viewportLeft * scale);
        this.viewPortWidth = realViewPortWidth;
        this.viewPortHeight = realViewPortHeight;
        viewStyle.top = this.viewPortTop + "px";
        viewStyle.left = this.viewPortLeft + "px";
        viewStyle.width = realViewPortWidth / SCALE_X + "px";
        viewStyle.height = realViewPortHeight / SCALE_Y + "px";
    };
    // 预览视窗
    MiniMap.prototype.createViewPort = function () {
        var div = document.createElement('div');
        div.className = 'lf-minimap-viewport';
        div.addEventListener('mousedown', this.startDrag);
        this.viewport = div;
    };
    MiniMap.pluginName = 'miniMap';
    MiniMap.width = 150;
    MiniMap.height = 220;
    MiniMap.viewPortWidth = 150;
    MiniMap.viewPortHeight = 75;
    MiniMap.isShowHeader = true;
    MiniMap.isShowCloseIcon = true;
    MiniMap.leftPosition = 0;
    MiniMap.topPosition = 0;
    MiniMap.rightPosition = null;
    MiniMap.bottomPosition = null;
    MiniMap.headerTitle = '导航';
    return MiniMap;
}());
exports.MiniMap = MiniMap;
exports.default = MiniMap;
