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
import { EventType, formateAnchorConnectValidateData } from '@logicflow/core';
import { cloneDeep } from 'lodash-es';
import { isNodeInSegment } from './edge';
var InsertNodeInPolyline = /** @class */ (function () {
    function InsertNodeInPolyline(_a) {
        var lf = _a.lf;
        this._lf = lf;
        // fix https://github.com/didi/LogicFlow/issues/754
        this.deviation = 20;
        this.dndAdd = true;
        this.dropAdd = true;
        this.eventHandler();
    }
    InsertNodeInPolyline.prototype.eventHandler = function () {
        var _this = this;
        // 监听事件
        if (this.dndAdd) {
            this._lf.on('node:dnd-add', function (_a) {
                var data = _a.data;
                _this.insetNode(data);
            });
        }
        if (this.dropAdd) {
            this._lf.on('node:drop', function (_a) {
                var data = _a.data;
                var edges = _this._lf.graphModel.edges;
                var id = data.id;
                // 只有游离节点才能插入到连线上
                var pureNode = true;
                for (var i = 0; i < edges.length; i++) {
                    if (edges[i].sourceNodeId === id || edges[i].targetNodeId === id) {
                        pureNode = false;
                        break;
                    }
                }
                if (pureNode) {
                    _this.insetNode(data);
                }
            });
        }
    };
    /**
     * 插入节点前校验规则
     * @param sourceNodeId
     * @param targetNodeId
     * @param sourceAnchorId
     * @param targetAnchorId
     * @param nodeData
     */
    // fix: https://github.com/didi/LogicFlow/issues/1078
    InsertNodeInPolyline.prototype.checkRuleBeforeInsetNode = function (sourceNodeId, targetNodeId, sourceAnchorId, targetAnchorId, nodeData) {
        var sourceNodeModel = this._lf.getNodeModelById(sourceNodeId);
        var targetNodeModel = this._lf.getNodeModelById(targetNodeId);
        var sourceAnchorInfo = sourceNodeModel.getAnchorInfo(sourceAnchorId);
        var targetAnchorInfo = targetNodeModel.getAnchorInfo(targetAnchorId);
        var sourceRuleResultData = sourceNodeModel.isAllowConnectedAsSource(nodeData, sourceAnchorInfo, targetAnchorInfo);
        var targetRuleResultData = targetNodeModel.isAllowConnectedAsTarget(nodeData, sourceAnchorInfo, targetAnchorInfo);
        var _a = formateAnchorConnectValidateData(sourceRuleResultData), isSourcePass = _a.isAllPass, sourceMsg = _a.msg;
        var _b = formateAnchorConnectValidateData(targetRuleResultData), isTargetPass = _b.isAllPass, targetMsg = _b.msg;
        return {
            isPass: isSourcePass && isTargetPass,
            sourceMsg: sourceMsg,
            targetMsg: targetMsg,
        };
    };
    InsertNodeInPolyline.prototype.insetNode = function (nodeData) {
        var _this = this;
        var edges = this._lf.graphModel.edges;
        var nodeModel = this._lf.getNodeModelById(nodeData.id);
        for (var i = 0; i < edges.length; i++) {
            // eslint-disable-next-line max-len
            var _a = isNodeInSegment(nodeModel, edges[i], this.deviation), crossIndex = _a.crossIndex, crossPoints = _a.crossPoints;
            if (crossIndex >= 0) {
                var _b = edges[i], sourceNodeId = _b.sourceNodeId, targetNodeId = _b.targetNodeId, id = _b.id, type = _b.type, pointsList = _b.pointsList, sourceAnchorId = _b.sourceAnchorId, targetAnchorId = _b.targetAnchorId;
                // fix https://github.com/didi/LogicFlow/issues/996
                var startPoint = cloneDeep(pointsList[0]);
                var endPoint = cloneDeep(crossPoints.startCrossPoint);
                this._lf.deleteEdge(id);
                var checkResult = this.checkRuleBeforeInsetNode(sourceNodeId, targetNodeId, sourceAnchorId, targetAnchorId, nodeData);
                this._lf.addEdge({
                    type: type,
                    sourceNodeId: sourceNodeId,
                    targetNodeId: nodeData.id,
                    startPoint: startPoint,
                    endPoint: endPoint,
                    pointsList: __spread(pointsList.slice(0, crossIndex), [
                        crossPoints.startCrossPoint,
                    ]),
                });
                this._lf.addEdge({
                    type: type,
                    sourceNodeId: nodeData.id,
                    targetNodeId: targetNodeId,
                    startPoint: cloneDeep(crossPoints.endCrossPoint),
                    endPoint: cloneDeep(pointsList[pointsList.length - 1]),
                    pointsList: __spread([
                        crossPoints.endCrossPoint
                    ], pointsList.slice(crossIndex)),
                });
                if (!checkResult.isPass) {
                    this._lf.graphModel.eventCenter.emit(EventType.CONNECTION_NOT_ALLOWED, {
                        data: nodeData,
                        msg: checkResult.targetMsg || checkResult.sourceMsg,
                    });
                    // FIXME:在关闭了历史记录的情况下，撤销操作会不生效。
                    setTimeout(function () {
                        _this._lf.undo();
                    }, 200);
                    break;
                }
                else {
                    break;
                }
            }
        }
    };
    InsertNodeInPolyline.pluginName = 'insertNodeInPolyline';
    return InsertNodeInPolyline;
}());
export { InsertNodeInPolyline };
export default InsertNodeInPolyline;
