function type(obj) {
    return Object.prototype.toString.call(obj);
}
function addSpace(depth) {
    return "  ".repeat(depth);
}
function handleAttributes(o) {
    var t = o;
    if (type(o) === "[object Object]") {
        t = {};
        Object.keys(o).forEach(function (k) {
            var tk = k;
            if (k.charAt(0) === "-") {
                tk = k.substring(1);
            }
            t[tk] = handleAttributes(o[k]);
        });
    }
    else if (Array.isArray(o)) {
        t = [];
        o.forEach(function (item, index) {
            t[index] = handleAttributes(item);
        });
    }
    return t;
}
;
function getAttributes(obj) {
    var tmp = obj;
    try {
        if (typeof tmp !== "string") {
            tmp = JSON.parse(obj);
        }
    }
    catch (error) {
        tmp = JSON.stringify(handleAttributes(obj)).replace(/"/g, "'");
    }
    return tmp;
}
var tn = "\t\n";
// @see issue https://github.com/didi/LogicFlow/issues/718, refactoring of function toXml
function toXml(obj, name, depth) {
    var frontSpace = addSpace(depth);
    var str = "";
    if (name === "#text") {
        return tn + frontSpace + obj;
    }
    else if (name === "#cdata-section") {
        return tn + frontSpace + "<![CDATA[" + obj + "]]>";
    }
    else if (name === "#comment") {
        return tn + frontSpace + "<!--" + obj + "-->";
    }
    if (("" + name).charAt(0) === "-") {
        return " " + name.substring(1) + '="' + getAttributes(obj) + '"';
    }
    else {
        if (Array.isArray(obj)) {
            obj.forEach(function (item) {
                str += toXml(item, name, depth + 1);
            });
        }
        else if (type(obj) === "[object Object]") {
            var keys = Object.keys(obj);
            var attributes_1 = "";
            var children_1 = "";
            str += (depth === 0 ? "" : tn + frontSpace) + "<" + name;
            keys.forEach(function (k) {
                k.charAt(0) === "-"
                    ? (attributes_1 += toXml(obj[k], k, depth + 1))
                    : (children_1 += toXml(obj[k], k, depth + 1));
            });
            str +=
                attributes_1 +
                    (children_1 !== "" ? ">" + children_1 + (tn + frontSpace) + "</" + name + ">" : " />");
        }
        else {
            str += tn + frontSpace + ("<" + name + ">" + obj.toString() + "</" + name + ">");
        }
    }
    return str;
}
function lfJson2Xml(o) {
    var xmlStr = "";
    for (var m in o) {
        xmlStr += toXml(o[m], m, 0);
    }
    return xmlStr;
}
export { lfJson2Xml, handleAttributes };
