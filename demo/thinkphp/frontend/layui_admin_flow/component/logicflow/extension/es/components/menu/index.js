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
var DefaultNodeMenuKey = 'lf:defaultNodeMenu';
var DefaultEdgeMenuKey = 'lf:defaultEdgeMenu';
var DefaultGraphMenuKey = 'lf:defaultGraphMenu';
var DefaultSelectionMenuKey = 'lf:defaultSelectionMenu';
var Menu = /** @class */ (function () {
    function Menu(_a) {
        var _this = this;
        var lf = _a.lf;
        this.lf = lf;
        var isSilentMode = lf.options.isSilentMode;
        if (!isSilentMode) {
            this.__menuDOM = document.createElement('ul');
            this.menuTypeMap = new Map();
            this.init();
            this.lf.setMenuConfig = function (config) {
                _this.setMenuConfig(config);
            };
            this.lf.addMenuConfig = function (config) {
                _this.addMenuConfig(config);
            };
            this.lf.setMenuByType = function (config) {
                _this.setMenuByType(config);
            };
        }
    }
    /**
     * 初始化设置默认内置菜单栏
     */
    Menu.prototype.init = function () {
        var _this = this;
        var defaultNodeMenu = [
            {
                text: '删除',
                callback: function (node) {
                    _this.lf.deleteNode(node.id);
                },
            },
            {
                text: '编辑文本',
                callback: function (node) {
                    _this.lf.graphModel.editText(node.id);
                },
            },
            {
                text: '复制',
                callback: function (node) {
                    _this.lf.cloneNode(node.id);
                },
            },
        ];
        this.menuTypeMap.set(DefaultNodeMenuKey, defaultNodeMenu);
        var defaultEdgeMenu = [
            {
                text: '删除',
                callback: function (edge) {
                    _this.lf.deleteEdge(edge.id);
                },
            },
            {
                text: '编辑文本',
                callback: function (edge) {
                    _this.lf.graphModel.editText(edge.id);
                },
            },
        ];
        this.menuTypeMap.set(DefaultEdgeMenuKey, defaultEdgeMenu);
        this.menuTypeMap.set(DefaultGraphMenuKey, []);
        var DefaultSelectionMenu = [
            {
                text: '删除',
                callback: function (elements) {
                    _this.lf.clearSelectElements();
                    elements.edges.forEach(function (edge) { return _this.lf.deleteEdge(edge.id); });
                    elements.nodes.forEach(function (node) { return _this.lf.deleteNode(node.id); });
                },
            },
        ];
        this.menuTypeMap.set(DefaultSelectionMenuKey, DefaultSelectionMenu);
    };
    Menu.prototype.render = function (lf, container) {
        var _this = this;
        this.__container = container;
        this.__currentData = null; // 当前展示的菜单所属元素的model数据
        this.__menuDOM.className = 'lf-menu';
        container.appendChild(this.__menuDOM);
        // 将选项的click事件委托至menu容器
        // 在捕获阶段拦截并执行
        this.__menuDOM.addEventListener('click', function (event) {
            event.stopPropagation();
            var target = event.target;
            // 菜单有多层dom，需要精确获取菜单项所对应的dom
            // 除菜单项dom外，应考虑两种情况
            // 1. 菜单项的子元素 2. 菜单外层容器
            while (Array.from(target.classList).indexOf('lf-menu-item') === -1 && Array.from(target.classList).indexOf('lf-menu') === -1) {
                target = target.parentElement;
            }
            if (Array.from(target.classList).indexOf('lf-menu-item') > -1) {
                // 如果点击区域在菜单项内
                target.onclickCallback(_this.__currentData);
                // 点击后隐藏menu
                _this.__menuDOM.style.display = 'none';
                _this.__currentData = null;
            }
            else {
                // 如果点击区域不在菜单项内
                console.warn('点击区域不在菜单项内，请检查代码！');
            }
        }, true);
        // 通过事件控制菜单的显示和隐藏
        this.lf.on('node:contextmenu', function (_a) {
            var data = _a.data, position = _a.position, e = _a.e;
            var _b = position.domOverlayPosition, x = _b.x, y = _b.y;
            var id = data.id;
            var model = _this.lf.graphModel.getNodeModelById(id);
            var menuList = [];
            var typeMenus = _this.menuTypeMap.get(model.type);
            // 如果单个节点自定义了节点，以单个节点自定义为准
            if (model && model.menu && Array.isArray(model.menu)) {
                menuList = model.menu;
            }
            else if (typeMenus) { // 如果定义当前节点类型的元素
                menuList = typeMenus;
            }
            else { // 最后取全局默认
                menuList = _this.menuTypeMap.get(DefaultNodeMenuKey);
            }
            _this.__currentData = data;
            _this.showMenu(x, y, menuList, {
                width: model.width,
                height: model.height,
                clientX: e.clientX,
                clientY: e.clientY,
            });
        });
        this.lf.on('edge:contextmenu', function (_a) {
            var data = _a.data, position = _a.position, e = _a.e;
            var _b = position.domOverlayPosition, x = _b.x, y = _b.y;
            var id = data.id;
            var model = _this.lf.graphModel.getEdgeModelById(id);
            var menuList = [];
            var typeMenus = _this.menuTypeMap.get(model.type);
            // 如果单个节点自定义了边
            if (model && model.menu && Array.isArray(model.menu)) {
                menuList = model.menu;
            }
            else if (typeMenus) { // 如果定义当前边类型的元素
                menuList = typeMenus;
            }
            else { // 最后取全局默认
                menuList = _this.menuTypeMap.get(DefaultEdgeMenuKey);
            }
            _this.__currentData = data;
            _this.showMenu(x, y, menuList, {
                width: model.width,
                height: model.height,
                clientX: e.clientX,
                clientY: e.clientY,
            });
        });
        this.lf.on('blank:contextmenu', function (_a) {
            var position = _a.position;
            var menuList = _this.menuTypeMap.get(DefaultGraphMenuKey);
            var _b = position.domOverlayPosition, x = _b.x, y = _b.y;
            _this.showMenu(x, y, menuList);
        });
        this.lf.on('selection:contextmenu', function (_a) {
            var data = _a.data, position = _a.position;
            var menuList = _this.menuTypeMap.get(DefaultSelectionMenuKey);
            var _b = position.domOverlayPosition, x = _b.x, y = _b.y;
            _this.__currentData = data;
            _this.showMenu(x, y, menuList);
        });
        this.lf.on('node:mousedown', function () {
            _this.__menuDOM.style.display = 'none';
        });
        this.lf.on('edge:click', function () {
            _this.__menuDOM.style.display = 'none';
        });
        this.lf.on('blank:click', function () {
            _this.__menuDOM.style.display = 'none';
        });
    };
    Menu.prototype.destroy = function () {
        var _a;
        (_a = this === null || this === void 0 ? void 0 : this.__container) === null || _a === void 0 ? void 0 : _a.removeChild(this.__menuDOM);
        this.__menuDOM = null;
    };
    Menu.prototype.showMenu = function (x, y, menuList, options) {
        if (!menuList || !menuList.length)
            return;
        var menu = this.__menuDOM;
        // 菜单容器不变，需要先清空内部的菜单项
        menu.innerHTML = '';
        menu.append.apply(menu, __spread(this.__getMenuDom(menuList)));
        // 菜单中没有项，不显示
        if (!menu.children.length)
            return;
        menu.style.display = 'block';
        if (!options) {
            menu.style.top = y + "px";
            menu.style.left = x + "px";
            return;
        }
        // https://github.com/didi/LogicFlow/issues/1019
        // 根据边界判断菜单的left 和 top
        var width = options.width, height = options.height, clientX = options.clientX, clientY = options.clientY;
        var graphModel = this.lf.graphModel;
        var menuWidth = menu.offsetWidth;
        var menuIsRightShow = true;
        // ======先进行可视屏幕范围的判断=======
        // 浏览器窗口可视区域兼容性写法
        // eslint-disable-next-line max-len
        var windowMaxX = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;
        var rightDistance = windowMaxX - clientX;
        // ======先进行可视屏幕范围的判断=======
        // ========再进行画布范围的判断========
        var graphRect = graphModel.rootEl.getBoundingClientRect();
        var graphMaxX = graphRect.left + graphRect.width;
        if (graphMaxX < windowMaxX) {
            // 画布右边小于可视屏幕范围的最右边，取画布右边作为极限值，计算出当前触摸点距离右边极限值的距离
            rightDistance = graphMaxX - clientX;
        }
        // ========再进行画布范围的判断========
        // 根据当前触摸点距离右边的距离 跟 menuWidth进行比较
        if (rightDistance < menuWidth) {
            // 空间不足够，显示在左边
            menuIsRightShow = false;
        }
        if (menuIsRightShow) {
            menu.style.left = x + "px";
        }
        else {
            menu.style.left = (x - width) + "px";
        }
        var menuHeight = menu.offsetHeight;
        var menuIsBottomShow = true;
        // ======先进行可视屏幕范围的判断=======
        // 浏览器窗口可视区域兼容性写法
        // eslint-disable-next-line max-len
        var windowMaxY = window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight;
        var bottomDistance = windowMaxY - clientY;
        // ======先进行可视屏幕范围的判断=======
        // ========再进行画布范围的判断========
        var graphMaxY = graphRect.top + graphRect.height;
        if (graphMaxY < windowMaxY) {
            // 画布底部小于可视屏幕范围的最底边，取画布底部作为极限值，计算出当前触摸点距离底部极限值的距离
            bottomDistance = graphMaxY - clientY;
        }
        // ========再进行画布范围的判断========
        if (bottomDistance < menuHeight) {
            // 如果下边距离太小，无法显示menu，则向上显示
            menuIsBottomShow = false;
        }
        if (menuIsBottomShow) {
            menu.style.top = y + "px";
        }
        else {
            menu.style.top = (y - height) + "px";
        }
    };
    /**
     * 设置指定类型元素的菜单
     */
    Menu.prototype.setMenuByType = function (config) {
        if (!config.type || !config.menu) {
            return;
        }
        this.menuTypeMap.set(config.type, config.menu);
    };
    /**
     * 获取 Menu DOM
     * @param list 菜单项
     * @return 菜单项 DOM
     */
    Menu.prototype.__getMenuDom = function (list) {
        var menuList = [];
        list && list.length > 0 && list.forEach(function (item) {
            var element = document.createElement('li');
            if (item.className) {
                element.className = "lf-menu-item " + item.className;
            }
            else {
                element.className = 'lf-menu-item';
            }
            if (item.icon === true) {
                var icon = document.createElement('span');
                icon.className = 'lf-menu-item-icon';
                element.appendChild(icon);
            }
            var text = document.createElement('span');
            text.className = 'lf-menu-item-text';
            if (item.text) {
                text.innerText = item.text;
            }
            element.appendChild(text);
            element.onclickCallback = item.callback;
            menuList.push(element);
        });
        return menuList;
    };
    // 复写菜单
    Menu.prototype.setMenuConfig = function (config) {
        if (!config) {
            return;
        }
        // node
        config.nodeMenu !== undefined
            && this.menuTypeMap.set(DefaultNodeMenuKey, config.nodeMenu ? config.nodeMenu : []);
        // edge
        config.edgeMenu !== undefined
            && this.menuTypeMap.set(DefaultEdgeMenuKey, config.edgeMenu ? config.edgeMenu : []);
        // graph
        config.graphMenu !== undefined
            && this.menuTypeMap.set(DefaultGraphMenuKey, config.graphMenu ? config.graphMenu : []);
    };
    // 在默认菜单后面追加菜单项
    Menu.prototype.addMenuConfig = function (config) {
        if (!config) {
            return;
        }
        // 追加项时，只支持数组类型，对false不做操作
        if (Array.isArray(config.nodeMenu)) {
            var menuList = this.menuTypeMap.get(DefaultNodeMenuKey);
            this.menuTypeMap.set(DefaultNodeMenuKey, menuList.concat(config.nodeMenu));
        }
        if (Array.isArray(config.edgeMenu)) {
            var menuList = this.menuTypeMap.get(DefaultEdgeMenuKey);
            this.menuTypeMap.set(DefaultEdgeMenuKey, menuList.concat(config.edgeMenu));
        }
        if (Array.isArray(config.graphMenu)) {
            var menuList = this.menuTypeMap.get(DefaultGraphMenuKey);
            this.menuTypeMap.set(DefaultGraphMenuKey, menuList.concat(config.graphMenu));
        }
    };
    /**
     * @deprecated
     * 复写添加
     */
    Menu.prototype.changeMenuItem = function (type, config) {
        if (type === 'add')
            this.addMenuConfig(config);
        else if (type === 'reset')
            this.setMenuConfig(config);
        else {
            throw new Error('The first parameter of changeMenuConfig should be \'add\' or \'reset\'');
        }
    };
    Menu.pluginName = 'menu';
    return Menu;
}());
export default Menu;
export { Menu, };
