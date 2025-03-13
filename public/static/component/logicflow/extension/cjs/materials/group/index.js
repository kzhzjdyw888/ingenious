"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
exports.GroupNode = exports.Group = void 0;
var GroupNode_1 = require("./GroupNode");
exports.GroupNode = GroupNode_1.default;
var DEFAULT_TOP_Z_INDEX = -1000;
var DEFAULT_BOTTOM_Z_INDEX = -10000;
var Group = /** @class */ (function () {
    function Group(_a) {
        var _this = this;
        var lf = _a.lf;
        this.topGroupZIndex = DEFAULT_BOTTOM_Z_INDEX;
        this.nodeGroupMap = new Map();
        this.graphRendered = function (data) {
            // 如果节点
            if (data && data.nodes) {
                data.nodes.forEach(function (node) {
                    if (node.children) {
                        node.children.forEach(function (nodeId) {
                            _this.nodeGroupMap.set(nodeId, node.id);
                        });
                    }
                });
            }
        };
        this.appendNodeToGroup = function (_a) {
            var data = _a.data;
            // 如果这个节点之前已经在group中了，则将其从之前的group中移除
            var preGroupId = _this.nodeGroupMap.get(data.id);
            if (preGroupId) {
                var preGroup = _this.lf.getNodeModelById(preGroupId);
                preGroup.removeChild(data.id);
                _this.nodeGroupMap.delete(data.id);
                preGroup.setAllowAppendChild(false);
            }
            // 然后再判断这个节点是否在某个group中，如果在，则将其添加到对应的group中
            var nodeModel = _this.lf.getNodeModelById(data.id);
            var bounds = nodeModel.getBounds();
            var group = _this.getGroup(bounds, data);
            // https://github.com/didi/LogicFlow/issues/1261
            // 当使用SelectionSelect框选后触发lf.addNode(Group)
            // 会触发appendNodeToGroup()的执行
            // 由于this.getGroup()会判断node.id !== nodeData.id
            // 因此当addNode是Group类型时，this.getGroup()会一直返回空
            // 导致了下面这段代码无法执行，也就是无法将当前添加的Group添加到this.nodeGroupMap中
            // 这导致了折叠分组时触发的foldEdge()无法正确通过getNodeGroup()拿到正确的groupId
            // 从而导致折叠分组时一直都会创建一个虚拟边
            // 而初始化分组时由于正确设置了nodeGroupMap的数据，因此不会产生虚拟边的错误情况
            if (nodeModel.isGroup) {
                // 如果这个节点是分组，那么将其子节点也记录下来
                data.children.forEach(function (nodeId) {
                    _this.nodeGroupMap.set(nodeId, data.id);
                });
                _this.nodeSelected({ data: data, isSelected: false, isMultiple: false });
            }
            if (!group)
                return;
            var isAllowAppendIn = group.isAllowAppendIn(data);
            if (!isAllowAppendIn) {
                _this.lf.emit('group:not-allowed', {
                    group: group.getData(),
                    node: data,
                });
                return;
            }
            group.addChild(data.id);
            _this.nodeGroupMap.set(data.id, group.id);
            group.setAllowAppendChild(false);
        };
        this.deleteGroupChild = function (_a) {
            var data = _a.data;
            // 如果删除的是分组节点，则同时删除分组的子节点
            if (data.children) {
                data.children.forEach(function (nodeId) {
                    _this.nodeGroupMap.delete(nodeId);
                    _this.lf.deleteNode(nodeId);
                });
            }
            var groupId = _this.nodeGroupMap.get(data.id);
            if (groupId) {
                var group = _this.lf.getNodeModelById(groupId);
                group.removeChild(data.id);
                _this.nodeGroupMap.delete(data.id);
            }
        };
        this.setActiveGroup = function (_a) {
            var data = _a.data;
            var nodeModel = _this.lf.getNodeModelById(data.id);
            var bounds = nodeModel.getBounds();
            var newGroup = _this.getGroup(bounds, data);
            if (_this.activeGroup) {
                _this.activeGroup.setAllowAppendChild(false);
            }
            if (!newGroup || (nodeModel.isGroup && newGroup.id === data.id))
                return;
            var isAllowAppendIn = newGroup.isAllowAppendIn(data);
            if (!isAllowAppendIn) {
                return;
            }
            _this.activeGroup = newGroup;
            _this.activeGroup.setAllowAppendChild(true);
        };
        /**
         * 1. 分组节点默认在普通节点下面。
         * 2. 分组节点被选中后，会将分组节点以及其内部的其他分组节点放到其余分组节点的上面。
         * 3. 分组节点取消选中后，不会将分组节点重置为原来的高度。
         * 4. 由于LogicFlow核心目标是支持用户手动绘制流程图，所以不考虑一张流程图超过1000个分组节点的情况。
         */
        this.nodeSelected = function (_a) {
            var data = _a.data, isMultiple = _a.isMultiple, isSelected = _a.isSelected;
            var nodeModel = _this.lf.getNodeModelById(data.id);
            _this.toFrontGroup(nodeModel);
            // 重置所有的group zIndex,防止group节点zIndex增长为正。
            if (_this.topGroupZIndex > DEFAULT_TOP_Z_INDEX) {
                _this.topGroupZIndex = DEFAULT_BOTTOM_Z_INDEX;
                var allGroups = _this.lf.graphModel.nodes
                    .filter(function (node) { return node.isGroup; })
                    .sort(function (a, b) { return a.zIndex - b.zIndex; });
                var preZIndex = 0;
                for (var i = 0; i < allGroups.length; i++) {
                    var group = allGroups[i];
                    if (group.zIndex !== preZIndex) {
                        _this.topGroupZIndex++;
                        preZIndex = group.zIndex;
                    }
                    group.setZIndex(_this.topGroupZIndex);
                }
            }
            // FIX #1004
            // 如果节点被多选，
            // 这个节点是分组，则将分组的所有子节点取消选中
            // 这个节点是分组的子节点，且其所属分组节点已选，则取消选中
            if (isMultiple && isSelected) {
                if (nodeModel.isGroup) {
                    nodeModel.children.forEach(function (child) {
                        var childModel = _this.lf.graphModel.getElement(child);
                        childModel.setSelected(false);
                    });
                }
                else {
                    var groupId = _this.nodeGroupMap.get(data.id);
                    if (groupId) {
                        var groupModel = _this.lf.getNodeModelById(groupId);
                        groupModel.isSelected && nodeModel.setSelected(false);
                    }
                }
            }
        };
        this.toFrontGroup = function (model) {
            if (!model || !model.isGroup) {
                return;
            }
            _this.topGroupZIndex++;
            model.setZIndex(_this.topGroupZIndex);
            if (model.children) {
                model.children.forEach(function (nodeId) {
                    var node = _this.lf.getNodeModelById(nodeId);
                    _this.toFrontGroup(node);
                });
            }
        };
        lf.register(GroupNode_1.default);
        this.lf = lf;
        lf.graphModel.addNodeMoveRules(function (model, deltaX, deltaY) {
            if (model.isGroup) { // 如果移动的是分组，那么分组的子节点也跟着移动。
                var nodeIds = _this.getNodeAllChild(model);
                lf.graphModel.moveNodes(nodeIds, deltaX, deltaY, true);
                return true;
            }
            var groupModel = lf.getNodeModelById(_this.nodeGroupMap.get(model.id));
            if (groupModel && groupModel.isRestrict) { // 如果移动的节点存在分组中，且这个分组禁止子节点移出去。
                var _a = model.getBounds(), x1 = _a.x1, y1 = _a.y1, x2 = _a.x2, y2 = _a.y2;
                var r = groupModel.isAllowMoveTo({
                    x1: x1 + deltaX,
                    y1: y1 + deltaY,
                    x2: x2 + deltaX,
                    y2: y2 + deltaY,
                });
                return r;
            }
            return true;
        });
        lf.graphModel.group = this;
        lf.on('node:add,node:drop,node:dnd-add', this.appendNodeToGroup);
        lf.on('node:delete', this.deleteGroupChild);
        lf.on('node:dnd-drag,node:drag', this.setActiveGroup);
        lf.on('node:click', this.nodeSelected);
        lf.on('graph:rendered', this.graphRendered);
    }
    /**
     * 获取一个节点内部所有的子节点，包裹分组的子节点
     */
    Group.prototype.getNodeAllChild = function (model) {
        var _this = this;
        var nodeIds = [];
        if (model.children) {
            model.children.forEach(function (nodeId) {
                nodeIds.push(nodeId);
                var nodeModel = _this.lf.getNodeModelById(nodeId);
                if (nodeModel.isGroup) {
                    nodeIds = nodeIds.concat(_this.getNodeAllChild(nodeModel));
                }
            });
        }
        return nodeIds;
    };
    /**
     * 获取自定位置其所属分组
     * 当分组重合时，优先返回最上层的分组
     */
    Group.prototype.getGroup = function (bounds, nodeData) {
        var nodes = this.lf.graphModel.nodes;
        var groups = nodes.filter(function (node) { return node.isGroup && node.isInRange(bounds) && node.id !== nodeData.id; });
        if (groups.length === 0)
            return;
        if (groups.length === 1)
            return groups[0];
        var topGroup = groups[groups.length - 1];
        for (var i = groups.length - 2; i >= 0; i--) {
            if (groups[i].zIndex > topGroup.zIndex) {
                topGroup = groups[i];
            }
        }
        return topGroup;
    };
    /**
     * 获取某个节点所属的groupModel
     */
    Group.prototype.getNodeGroup = function (nodeId) {
        var groupId = this.nodeGroupMap.get(nodeId);
        if (groupId) {
            return this.lf.getNodeModelById(groupId);
        }
    };
    Group.pluginName = 'group';
    return Group;
}());
exports.Group = Group;
