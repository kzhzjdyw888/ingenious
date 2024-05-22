var __assign = (this && this.__assign) || function () {
    __assign = Object.assign || function(t) {
        for (var s, i = 1, n = arguments.length; i < n; i++) {
            s = arguments[i];
            for (var p in s) if (Object.prototype.hasOwnProperty.call(s, p))
                t[p] = s[p];
        }
        return t;
    };
    return __assign.apply(this, arguments);
};
var __read = (this && this.__read) || function (o, n) {
    var m = typeof Symbol === "function" && o[Symbol.iterator];
    if (!m) return o;
    var i = m.call(o), r, ar = [], e;
    try {
        while ((n === void 0 || n-- > 0) && !(r = i.next()).done) ar.push(r.value);
    }
    catch (error) { e = { error: error }; }
    finally {
        try {
            if (r && !r.done && (m = i["return"])) m.call(i);
        }
        finally { if (e) throw e.error; }
    }
    return ar;
};
var __spread = (this && this.__spread) || function () {
    for (var ar = [], i = 0; i < arguments.length; i++) ar = ar.concat(__read(arguments[i]));
    return ar;
};
// 后续并入FlowPath
var getPath = function (id, lf) {
    var el = lf.getModelById(id);
    return getNodePath(el.BaseType === 'node' ? el : el.targetNode, lf);
};
// dfs + 动态规划
// todo 算法优化
var getNodePath = function (node, lf) {
    var incomingPaths = [];
    var outgoingPaths = [];
    var getIncomingPaths = function (curNode, path, prevNode) {
        if (prevNode === void 0) { prevNode = null; }
        if (prevNode) {
            // * 上个节点和当前节点中间边
            path.unshift.apply(path, __spread(lf
                .getEdgeModels({
                sourceNodeId: curNode.id,
                targetNodeId: prevNode.id,
            })
                .map(function (item) { return item.id; })));
        }
        // * 路径中存在节点，则不再继续查找，说明出现环情况
        if (path.includes(curNode.id)) {
            incomingPaths.push(path);
            return;
        }
        // * 路径中当前加入节点
        path.unshift(curNode.id);
        if (!curNode.incoming.nodes.length) {
            incomingPaths.push(path);
            return;
        }
        // * 往下找
        curNode.incoming.nodes.forEach(function (nextNode) {
            getIncomingPaths(nextNode, path.slice(), curNode);
        });
    };
    // * 同上逻辑
    var getOutgoingPaths = function (curNode, path, prevNode) {
        if (prevNode === void 0) { prevNode = null; }
        if (prevNode) {
            path.push.apply(path, __spread(lf
                .getEdgeModels({
                sourceNodeId: prevNode.id,
                targetNodeId: curNode.id,
            })
                .map(function (item) { return item.id; })));
        }
        if (path.includes(curNode.id)) {
            outgoingPaths.push(path);
            return;
        }
        path.push(curNode.id);
        if (!curNode.outgoing.nodes.length) {
            outgoingPaths.push(path);
            return;
        }
        curNode.outgoing.nodes.forEach(function (nextNode) {
            getOutgoingPaths(nextNode, path.slice(), curNode);
        });
    };
    getIncomingPaths(node, []);
    getOutgoingPaths(node, []);
    return __spread(new Set(__spread(incomingPaths.flat(), outgoingPaths.flat())));
};
var Highlight = /** @class */ (function () {
    function Highlight(_a) {
        var lf = _a.lf;
        this.mode = 'path';
        this.manual = false;
        this.tempStyles = {};
        this.lf = lf;
    }
    Highlight.prototype.setMode = function (mode) {
        this.mode = mode;
    };
    Highlight.prototype.setManual = function (manual) {
        this.manual = manual;
    };
    Highlight.prototype.highlightSingle = function (id) {
        var model = this.lf.getModelById(id);
        if (model.BaseType === 'node') {
            // 高亮节点
            model.updateStyles(this.tempStyles[id]);
        }
        else if (model.BaseType === 'edge') {
            // 高亮边及对应的节点
            model.updateStyles(this.tempStyles[id]);
            model.sourceNode.updateStyles(this.tempStyles[model.sourceNode.id]);
            model.targetNode.updateStyles(this.tempStyles[model.targetNode.id]);
        }
    };
    Highlight.prototype.highlightPath = function (id) {
        var _this = this;
        var path = getPath(id, this.lf);
        path.forEach(function (_id) {
            // 高亮路径上所有的边和节点
            _this.lf.getModelById(_id).updateStyles(_this.tempStyles[_id]);
        });
    };
    Highlight.prototype.highlight = function (id, mode) {
        var _this = this;
        if (mode === void 0) { mode = this.mode; }
        if (this.manual)
            return;
        if (Object.keys(this.tempStyles).length) {
            this.restoreHighlight();
        }
        Object.values(this.lf.graphModel.modelsMap).forEach(function (item) {
            //  所有节点样式都进行备份
            var oStyle = item.BaseType === 'node' ? item.getNodeStyle() : item.getEdgeStyle();
            _this.tempStyles[item.id] = __assign({}, oStyle);
            //  所有节点都设置透明度为0.1
            item.setStyles({ opacity: 0.1 });
        });
        var modeTrigger = {
            single: this.highlightSingle.bind(this),
            path: this.highlightPath.bind(this),
        };
        modeTrigger[mode](id);
    };
    Highlight.prototype.restoreHighlight = function () {
        var _this = this;
        // 恢复所有节点的样式
        if (!Object.keys(this.tempStyles).length)
            return;
        Object.values(this.lf.graphModel.modelsMap).forEach(function (item) {
            var _a;
            var oStyle = (_a = _this.tempStyles[item.id]) !== null && _a !== void 0 ? _a : {};
            item.updateStyles(__assign({}, oStyle));
        });
        this.tempStyles = {};
    };
    Highlight.prototype.render = function (lf, domContainer) {
        var _this = this;
        this.lf.on('node:mouseenter', function (_a) {
            var data = _a.data;
            return _this.highlight(data.id);
        });
        this.lf.on('edge:mouseenter', function (_a) {
            var data = _a.data;
            return _this.highlight(data.id);
        });
        this.lf.on('node:mouseleave', this.restoreHighlight.bind(this));
        this.lf.on('edge:mouseleave', this.restoreHighlight.bind(this));
        this.lf.on('history:change', this.restoreHighlight.bind(this));
    };
    Highlight.prototype.destroy = function () { };
    Highlight.pluginName = 'highlight';
    return Highlight;
}());
export { Highlight };
