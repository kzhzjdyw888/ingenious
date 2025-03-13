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
exports.registerTaskNodes = void 0;
var icons_1 = require("../icons");
var task_1 = require("./task");
var subProcess_1 = require("./subProcess");
function boundaryEvent(lf) {
    lf.on('node:drag,node:dnd-drag', checkAppendBoundaryEvent);
    lf.on('node:drop,node:dnd-add', appendBoundaryEvent);
    lf.graphModel.addNodeMoveRules(function (model, deltaX, deltaY) {
        if (model.isTaskNode) {
            // 如果移动的是分组，那么分组的子节点也跟着移动。
            var nodeIds = model.boundaryEvents;
            lf.graphModel.moveNodes(nodeIds, deltaX, deltaY, true);
            return true;
        }
        return true;
    });
    function appendBoundaryEvent(_a) {
        var e_1, _b;
        var data = _a.data;
        var type = data.type, id = data.id;
        if (type !== 'bpmn:boundaryEvent') {
            return;
        }
        var nodes = lf.graphModel.nodes;
        try {
            for (var nodes_1 = __values(nodes), nodes_1_1 = nodes_1.next(); !nodes_1_1.done; nodes_1_1 = nodes_1.next()) {
                var node = nodes_1_1.value;
                if (node.isTaskNode) {
                    var nodeId = null;
                    if ((nodeId = isBoundaryEventCloseToTask(node, data)) !== null) {
                        var eventModel = lf.graphModel.getNodeModelById(id);
                        var nodeModel = lf.graphModel.getNodeModelById(nodeId);
                        var attachedToRef = eventModel.properties.attachedToRef;
                        if (attachedToRef && attachedToRef !== nodeId) {
                            lf.graphModel.getNodeModelById(attachedToRef).deleteBoundaryEvent(id);
                        }
                        nodeModel.addBoundaryEvent(id);
                        return;
                    }
                }
            }
        }
        catch (e_1_1) { e_1 = { error: e_1_1 }; }
        finally {
            try {
                if (nodes_1_1 && !nodes_1_1.done && (_b = nodes_1.return)) _b.call(nodes_1);
            }
            finally { if (e_1) throw e_1.error; }
        }
    }
    // 判断此节点是否在某个节点的边界上
    // 如果在，且这个节点model存在属性isTaskNode，则调用这个方法
    function checkAppendBoundaryEvent(_a) {
        var e_2, _b;
        var data = _a.data;
        var type = data.type;
        if (type !== 'bpmn:boundaryEvent') {
            return;
        }
        var nodes = lf.graphModel.nodes;
        try {
            for (var nodes_2 = __values(nodes), nodes_2_1 = nodes_2.next(); !nodes_2_1.done; nodes_2_1 = nodes_2.next()) {
                var node = nodes_2_1.value;
                if (node.isTaskNode) {
                    if (isBoundaryEventCloseToTask(node, data)) {
                        // 同时只允许在一个节点的边界上
                        node.setTouching(true);
                    }
                    else {
                        node.setTouching(false);
                    }
                }
            }
        }
        catch (e_2_1) { e_2 = { error: e_2_1 }; }
        finally {
            try {
                if (nodes_2_1 && !nodes_2_1.done && (_b = nodes_2.return)) _b.call(nodes_2);
            }
            finally { if (e_2) throw e_2.error; }
        }
    }
    function isBoundaryEventCloseToTask(task, event) {
        var offset = 5;
        var tx = task.x, ty = task.y, twidth = task.width, theight = task.height, id = task.id;
        var bbox = {
            minX: tx - twidth / 2,
            maxX: tx + twidth / 2,
            minY: ty - theight / 2,
            maxY: ty + theight / 2,
        };
        var bx = event.x, by = event.y;
        var outerBBox = {
            minX: bbox.minX - offset,
            maxX: bbox.maxX + offset,
            minY: bbox.minY - offset,
            maxY: bbox.maxY + offset,
        };
        var innerBBox = {
            minX: bbox.minX + offset,
            maxX: bbox.maxX - offset,
            minY: bbox.minY + offset,
            maxY: bbox.maxX - offset,
        };
        if (bx > outerBBox.minX && bx < outerBBox.maxX && by > outerBBox.minY && by < outerBBox.maxY) {
            if (!(bx > innerBBox.minX && bx < innerBBox.maxX && by > innerBBox.minY && by < innerBBox.maxY)) {
                return id;
            }
        }
        return null;
    }
}
function registerTaskNodes(lf) {
    var ServiceTask = task_1.TaskNodeFactory('bpmn:serviceTask', icons_1.serviceTaskIcon);
    var UserTask = task_1.TaskNodeFactory('bpmn:userTask', icons_1.userTaskIcon);
    lf.register(ServiceTask);
    lf.register(UserTask);
    lf.register(subProcess_1.SubProcessFactory());
    boundaryEvent(lf);
}
exports.registerTaskNodes = registerTaskNodes;
