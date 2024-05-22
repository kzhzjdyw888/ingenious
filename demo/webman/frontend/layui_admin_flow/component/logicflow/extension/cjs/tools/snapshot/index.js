"use strict";
/* eslint-disable operator-linebreak */
/* eslint-disable implicit-arrow-linebreak */
/**
 * 快照插件，生成视图
 */
Object.defineProperty(exports, "__esModule", { value: true });
exports.Snapshot = void 0;
var Snapshot = /** @class */ (function () {
    function Snapshot(_a) {
        var _this = this;
        var lf = _a.lf;
        this.lf = lf;
        this.customCssRules = '';
        this.useGlobalRules = true;
        /* 下载快照 */
        lf.getSnapshot = function (fileName, backgroundColor) {
            _this.getSnapshot(fileName, backgroundColor);
        };
        /* 获取Blob对象，用户图片上传 */
        lf.getSnapshotBlob = function (backgroundColor) {
            return _this.getSnapshotBlob(backgroundColor);
        };
        /* 获取Base64对象，用户图片上传 */
        lf.getSnapshotBase64 = function (backgroundColor) {
            return _this.getSnapshotBase64(backgroundColor);
        };
    }
    /* 获取svgRoot对象 */
    Snapshot.prototype.getSvgRootElement = function (lf) {
        var svgRootElement = lf.container.querySelector('.lf-canvas-overlay');
        return svgRootElement;
    };
    Snapshot.prototype.triggerDownload = function (imgURI) {
        var evt = new MouseEvent('click', {
            view: window,
            bubbles: false,
            cancelable: true,
        });
        var a = document.createElement('a');
        a.setAttribute('download', this.fileName);
        a.setAttribute('href', imgURI);
        a.setAttribute('target', '_blank');
        a.dispatchEvent(evt);
    };
    Snapshot.prototype.removeAnchor = function (element) {
        var childNodes = element.childNodes;
        var childLength = element.childNodes && element.childNodes.length;
        for (var i = 0; i < childLength; i++) {
            var child = childNodes[i];
            var classList = (child.classList && Array.from(child.classList)) || [];
            if (classList.indexOf('lf-anchor') > -1) {
                element.removeChild(element.childNodes[i]);
                childLength--;
                i--;
            }
        }
    };
    Snapshot.prototype.removeRotateControl = function (element) {
        var childNodes = element.childNodes;
        var childLength = element.childNodes && element.childNodes.length;
        for (var i = 0; i < childLength; i++) {
            var child = childNodes[i];
            var classList = (child.classList && Array.from(child.classList)) || [];
            if (classList.indexOf('lf-rotate-control') > -1) {
                element.removeChild(element.childNodes[i]);
                childLength--;
                i--;
            }
        }
    };
    /* 下载图片 */
    Snapshot.prototype.getSnapshot = function (fileName, backgroundColor) {
        var _this = this;
        this.fileName = fileName || "logic-flow." + Date.now() + ".png";
        var svg = this.getSvgRootElement(this.lf);
        this.getCanvasData(svg, backgroundColor).then(function (canvas) {
            var imgURI = canvas
                .toDataURL('image/png')
                .replace('image/png', 'image/octet-stream');
            _this.triggerDownload(imgURI);
        });
    };
    /* 获取base64对象 */
    Snapshot.prototype.getSnapshotBase64 = function (backgroundColor) {
        var _this = this;
        var svg = this.getSvgRootElement(this.lf);
        return new Promise(function (resolve) {
            _this.getCanvasData(svg, backgroundColor).then(function (canvas) {
                var base64 = canvas.toDataURL('image/png');
                // 输出图片数据以及图片宽高
                resolve({ data: base64, width: canvas.width, height: canvas.height });
            });
        });
    };
    /* 获取Blob对象 */
    Snapshot.prototype.getSnapshotBlob = function (backgroundColor) {
        var _this = this;
        var svg = this.getSvgRootElement(this.lf);
        return new Promise(function (resolve) {
            _this.getCanvasData(svg, backgroundColor).then(function (canvas) {
                canvas.toBlob(function (blob) {
                    // 输出图片数据以及图片宽高
                    resolve({ data: blob, width: canvas.width, height: canvas.height });
                }, 'image/png');
            });
        });
    };
    Snapshot.prototype.getClassRules = function () {
        var rules = '';
        if (this.useGlobalRules) {
            var styleSheets = document.styleSheets;
            for (var i = 0; i < styleSheets.length; i++) {
                var sheet = styleSheets[i];
                for (var j = 0; j < sheet.cssRules.length; j++) {
                    rules += sheet.cssRules[j].cssText;
                }
            }
        }
        if (this.customCssRules) {
            rules += this.customCssRules;
        }
        return rules;
    };
    // 获取图片生成中中间产物canvas对象，用户转换为其他需要的格式
    Snapshot.prototype.getCanvasData = function (svg, backgroundColor) {
        var _this = this;
        var copy = svg.cloneNode(true);
        var graph = copy.lastChild;
        var childLength = graph.childNodes && graph.childNodes.length;
        if (childLength) {
            for (var i = 0; i < childLength; i++) {
                var lfLayer = graph.childNodes[i];
                // 只保留包含节点和边的基础图层进行下载，其他图层删除
                var layerClassList = lfLayer.classList && Array.from(lfLayer.classList);
                if (layerClassList && layerClassList.indexOf('lf-base') < 0) {
                    graph.removeChild(graph.childNodes[i]);
                    childLength--;
                    i--;
                }
                else {
                    // 删除锚点
                    var lfBase = graph.childNodes[i];
                    lfBase &&
                        lfBase.childNodes.forEach(function (item) {
                            var element = item;
                            _this.removeAnchor(element.firstChild);
                            _this.removeRotateControl(element.firstChild);
                        });
                }
            }
        }
        var dpr = window.devicePixelRatio || 1;
        if (dpr < 1) {
            // https://github.com/didi/LogicFlow/issues/1222
            // canvas.width = bboxWidth * dpr配合ctx.scale(dpr, dpr)是为了解决绘制模糊
            // 比如dpr=2，先让canvas.width放大到等同于屏幕的物理像素宽高，然后自适应缩放适配canvas.style.width
            // 由于所有元素都缩放了一半，因此需要ctx.scale(dpr, dpr)放大2倍整体绘制的内容
            // 当用户缩放浏览器时，window.devicePixelRatio会随着变小
            // 当window.devicePixelRatio变小到一定程度，会导致canvas.width<canvas.style.width
            // 由于导出图片的svg的大小是canvas.style.width+canvas.style.height
            // 因此会导致导出的svg图片无法完整绘制到canvas（因为canvas.width小于svg的宽）
            // 从而导致canvas导出图片是缺失的svg
            // 而dpr>=1就能保证canvas.width>=canvas.style.width
            // 当dpr小于1的时候，我们强制转化为1，并不会产生绘制模糊等问题
            dpr = 1;
        }
        var canvas = document.createElement('canvas');
        /*
        为了计算真实宽高需要取图的真实dom
        真实dom存在缩放影响其宽高数值
        在得到真实宽高后除以缩放比例即可得到正常宽高
        */
        var base = this.lf.graphModel.rootEl.querySelector('.lf-base');
        var bbox = base.getBoundingClientRect();
        var layout = document
            .querySelector('.lf-canvas-overlay')
            .getBoundingClientRect();
        var offsetX = bbox.x - layout.x;
        var offsetY = bbox.y - layout.y;
        var graphModel = this.lf.graphModel;
        var transformModel = graphModel.transformModel;
        var SCALE_X = transformModel.SCALE_X, SCALE_Y = transformModel.SCALE_Y, TRANSLATE_X = transformModel.TRANSLATE_X, TRANSLATE_Y = transformModel.TRANSLATE_Y;
        // offset值加10，保证图形不会紧贴着下载图片的左边和上边
        copy.lastChild.style.transform = "matrix(1, 0, 0, 1, " + ((-offsetX + TRANSLATE_X) * (1 / SCALE_X) + 10) + ", " + ((-offsetY + TRANSLATE_Y) * (1 / SCALE_Y) + 10) + ")";
        var bboxWidth = Math.ceil(bbox.width / SCALE_X);
        var bboxHeight = Math.ceil(bbox.height / SCALE_Y);
        // width,height 值加40，保证图形不会紧贴着下载图片的右边和下边
        canvas.style.width = bboxWidth + "px";
        canvas.style.height = bboxHeight + "px";
        canvas.width = bboxWidth * dpr + 80;
        canvas.height = bboxHeight * dpr + 80;
        var ctx = canvas.getContext('2d');
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        ctx.scale(dpr, dpr);
        // 如果有背景色，设置流程图导出的背景色
        if (backgroundColor) {
            ctx.fillStyle = backgroundColor;
            ctx.fillRect(0, 0, bboxWidth * dpr + 80, bboxHeight * dpr + 80);
        }
        else {
            ctx.clearRect(0, 0, bboxWidth, bboxHeight);
        }
        var img = new Image();
        var style = document.createElement('style');
        style.innerHTML = this.getClassRules();
        var foreignObject = document.createElement('foreignObject');
        foreignObject.appendChild(style);
        copy.appendChild(foreignObject);
        return new Promise(function (resolve) {
            img.onload = function () {
                var isFirefox = navigator.userAgent.indexOf('Firefox') > -1;
                try {
                    if (isFirefox) {
                        createImageBitmap(img, {
                            resizeWidth: canvas.width,
                            resizeHeight: canvas.height,
                        }).then(function (imageBitmap) {
                            // 在回调函数中使用 drawImage() 方法绘制图像
                            ctx.drawImage(imageBitmap, 0, 0);
                            resolve(canvas);
                        });
                    }
                    else {
                        ctx.drawImage(img, 0, 0);
                        resolve(canvas);
                    }
                }
                catch (e) {
                    ctx.drawImage(img, 0, 0);
                    resolve(canvas);
                }
            };
            /*
            因为svg中存在dom存放在foreignObject元素中
            SVG图形转成img对象
            todo: 会导致一些清晰度问题这个需要再解决
            fixme: XMLSerializer的中的css background url不会下载图片
            */
            var svg2Img = "data:image/svg+xml;charset=utf-8," + new XMLSerializer().serializeToString(copy);
            var imgSrc = svg2Img
                .replace(/\n/g, '')
                .replace(/\t/g, '')
                .replace(/#/g, '%23');
            img.src = imgSrc;
        });
    };
    Snapshot.pluginName = 'snapshot';
    return Snapshot;
}());
exports.Snapshot = Snapshot;
exports.default = Snapshot;
