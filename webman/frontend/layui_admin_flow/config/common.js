function isString(str) {
    if (typeof (str) === 'string') return true;
    return false;
}

/**
 * 修正中文乱码问题
 * @param name
 * @returns {null|string}
 */
function getQueryString(name) {
    let reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)");
    let r = window.location.search.substr(1).match(reg);
    if (r != null) return decodeURIComponent(r[2]);
    return null;
}

/**
 * 数组in
 * @param search
 * @param array
 * @returns {boolean}
 */
function in_array(search, array) {
    for (let i in array) {
        if (array[i] == search) {
            return true;
        }
    }
    return false;
}


/**
 * 鹰：OK
 */
//保存、获取 用户信息
const setUserInfo = (userinfo) => {
    //localStorage 持久化存储：layui.data(table, settings)，数据会永久存在，除非物理删除。
    //sessionStorage 会话性存储：layui.sessionData(table, settings)，页面关闭后即失效。注：layui 2.2.5 新增
    if (userinfo === undefined || userinfo === null) layui.data('lms', {
        key: 'userInfo', remove: true
    }); else layui.data('lms', {
        key: 'userInfo', value: userinfo
    });
}
const getUserInfo = () => {
    return layui.data('lms').userInfo;
}

/**
 * 检查登录，没有登录返回false，否则返回 用户信息对象
 * 鹰：OK
 * options.nav：为true则没有登录时跳转至登录界面。
 * @return {Boolean}
 */
const checkLogin = (options = {}) => {
    const userInfo = getUserInfo();
    if (typeof (userInfo) === 'object' && userInfo !== null) {
        return userInfo;
    }

    //是否需要自动跳转到login页面
    if (options.nav === true) {
        options.nav = './login.html';
    }
    if (isString(options.nav)) {
        location.href = options.nav;
    }
    return false;
}


//制作菜单
//rules为服务器返回的所有权限数组（平行）
//type为1则只返回 菜单类型 的菜单，否则也返回 菜单和API 的菜单
//setKeysById：给特定id新增一个key并赋值value。如果为null则忽略
//  格式：[ids, key, value]	//ids是列表，Permission的id只要在这个列表内就赋值
const fMakeMenu = function (rules, type = 1, setKeysById = null) {
    let retMenu = []; // 返回值（也是顶层menu）
    let tmpMenu = new Map(); // 按id为key的menu
    rules.forEach((r) => {
        if (type === 1 && r.auth_type !== 1) {
            // api
            return;
        }
        if (r.is_show !== 1) {
            // 是否显示
            return;
        }

        // 制作菜单：
        let menu = tmpMenu.get(r.id) || {type: 1};
        menu.id = r.id;
        menu.title = r.menu_name;
        menu.icon = 'layui-icon ' + r.icon;
        menu.openType = "_iframe";
        menu.href = r.menu_path;

        // 加入额外的keys
        if (setKeysById) {
            // 如果这个id在给定的Id列表内
            if (setKeysById[0].includes(r.id.toString())) {
                menu[setKeysById[1]] = setKeysById[2]; // 给特定key赋值
            }
        }

        if (r.pid === '-1') {
            // 顶层
            retMenu.push(menu);
        } else {
            let parentMenu = tmpMenu.get(r.pid) || {children: []}; // 父菜单
            parentMenu.type = 0; // 菜单
            parentMenu.children = parentMenu.children || [];
            parentMenu.children.push(menu); // 加入父菜单 children
            tmpMenu.set(r.pid, parentMenu);
        }

        tmpMenu.set(r.id, menu);
    });

    return retMenu;
};


const fGetLastNodes = function (rules) {

    let parentNodes = [];
    let lastNodes = [];
    let allNodes = [];


    for (let r of rules) {
        allNodes.push(r.id);
        if (parentNodes.indexOf(r.pid) < 0) parentNodes.push(r.pid);
    }


    lastNodes = allNodes.filter(item => {
        //差集
        return !parentNodes.includes(item);
        //交集
        //return parentNodes.includes(item)
    });

    return lastNodes;
}


function screen() {
    if (typeof width !== 'number' || width === 0) {
        width = $(window).width() * 0.8;
    }
    if (typeof height !== 'number' || height === 0) {
        height = $(window).height() - 20;
    }
    return [width + 'px', height + 'px'];
}

function getColumnValues(data, columnName) {
    return data.map(function (item) {
        return item[columnName];
    });
}

/**
 * 递归lay-ui Tree 数据
 * @param data
 * @param parentId
 * @param idField
 * @param titleField
 * @param parentField
 * @returns {*[]}
 */
function convertToLayuiTree(data, parentId = '0', idField = 'id', titleField = 'title', parentField = 'parent_id') {
    let result = [];

    for (let i = 0; i < data.length; i++) {
        if (data[i][parentField] === parentId) {
            let item = {
                id: data[i][idField],
                parentId: data[i][parentField],
                title: data[i][titleField],
                children: convertToLayuiTree(data, data[i][idField], idField, titleField, parentField)
            };
            result.push(item);
        }
    }

    return result;
}

/**
 * 返回Lay-uiTree 最后节点IDS
 * @param ids
 * @param treeData
 * @returns {*[]}
 */
const getSelectedLastNodeIds = function (ids, treeData) {
    let lastNodeIds = [];
    // 递归遍历树节点
    const traverseTree = function (nodes) {
        for (let node of nodes) {
            // 如果当前节点有子节点，则继续递归遍历子节点
            if (node.children && node.children.length > 0) {
                traverseTree(node.children);
            } else {
                // 如果当前节点是最后一级节点，并且在ids数组中，则将其id添加到lastNodeIds数组中
                if (ids.includes(node.id)) {
                    lastNodeIds.push(node.id);
                }
            }
        }
    };

    // 遍历树节点
    traverseTree(treeData);
    return lastNodeIds;
};

/**
 * 提取二维数组字段集合v1.11
 * @param array
 * @param column
 * @returns {*}
 */
const array_column = function (array, column) {
    return array.map(item => item[column]);
}

/**
 * 树形数据追加checkArr 适配dtree v1.11
 * @param data
 */
const appendCheckArr = function (data, level = 0) {
    if (!Array.isArray(data)) {
        return;
    }
    data.forEach(function (node) {
        node.checkArr = [{"type": "0", "checked": level, 'half': false}];
        if (Array.isArray(node.children) && node.children.length > 0) {
            appendCheckArr(node.children, level);
        }
    });
}

/**
 * 扁平数组转树形v1.11
 * @param data
 * @param parentId
 * @param idKey
 * @param parentKey
 * @param childrenKey
 * @returns {*[]}
 */
const flattenToTree = function (data, parentId = '-1', idKey = 'id', parentKey = 'pid', childrenKey = 'children') {
    let tree = [];
    // 遍历数据，将每个节点添加到对应的父节点下
    layui.$.each(data, function (index, item) {
        if (item[parentKey] === parentId) {
            let children = flattenToTree(data, item[idKey], idKey, parentKey, childrenKey);
            if (children.length > 0) {
                item[childrenKey] = children;
            }
            tree.push(item);
        }
    });
    return tree;
};


/**
 * 浏览页面顶部搜索框展开收回控制
 */
// function toggleSearchFormShow() {
//     let $ = layui.$;
//     let items = $('.top-search-from .layui-form-item');
//     if (items.length <= 2) {
//         if (items.length <= 1) $('.top-search-from').parent().parent().remove();
//         return;
//     }
//     let btns = $('.top-search-from .toggle-btn a');
//     let toggle = toggleSearchFormShow;
//     if (typeof toggle.hide === 'undefined') {
//         btns.on('click', function () {
//             toggle();
//         });
//     }
//     let countPerRow = parseInt($('.top-search-from').width() / $('.layui-form-item').width());
//     if (items.length <= countPerRow) {
//         return;
//     }
//     btns.removeClass('layui-hide');
//     toggle.hide = !toggle.hide;
//     if (toggle.hide) {
//         for (let i = countPerRow - 1; i < items.length - 1; i++) {
//             $(items[i]).hide();
//         }
//         return $('.top-search-from .toggle-btn a:last').addClass('layui-hide');
//     }
//     items.show();
//     $('.top-search-from .toggle-btn a:first').addClass('layui-hide');
// }

// layui.$(function () {
//     toggleSearchFormShow();
// });
