/* eslint-disable no-bitwise */
export function groupRule() {
    var rule = {
        message: '分组外的节点不允许连接分组内的',
        validate: function (_sourceNode, _targetNode, _sourceAnchor, _targetAnchor) {
            var isSourceNodeInsideTheGroup = !!_sourceNode.properties.parent;
            var isTargetNodeInsideTheGroup = !!_targetNode.properties.parent;
            return !(!isSourceNodeInsideTheGroup && isTargetNodeInsideTheGroup);
        },
    };
    this.targetRules.push(rule);
}
/* eslint-disable no-bitwise */
var IDS = /** @class */ (function () {
    function IDS() {
        globalThis._ids = this;
        this._ids = new Set();
    }
    IDS.prototype.generateId = function () {
        var id = 'xxxxxxx'.replace(/[x]/g, function (c) {
            var r = (Math.random() * 16) | 0;
            var v = c === 'x' ? r : (r & 0x3) | 0x8;
            return v.toString(16);
        });
        return id;
    };
    IDS.prototype.next = function () {
        var id = this.generateId();
        while (this._ids.has(id)) {
            id = this.generateId();
        }
        this._ids.add(id);
        return id;
    };
    return IDS;
}());
var ids = (globalThis === null || globalThis === void 0 ? void 0 : globalThis._ids) || new IDS();
export function genBpmnId() {
    return ids.next();
}
