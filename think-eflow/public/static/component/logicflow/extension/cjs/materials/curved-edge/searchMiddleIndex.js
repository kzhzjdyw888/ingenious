"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
function searchMiddleIndex(arr) {
    if (arr.length <= 1)
        return false;
    var first = 0;
    var last = arr.length - 1;
    while (first !== last && first + 1 !== last && last - 1 !== first) {
        first++;
        last--;
    }
    if (first === last) {
        return [--first, last];
    }
    return [first, last];
}
exports.default = searchMiddleIndex;
