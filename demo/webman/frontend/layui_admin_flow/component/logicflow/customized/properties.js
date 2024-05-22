/**
 * 表单字段过滤
 * @returns {{}}
 */
window.propertyKeysSet = function (newData) {
    let data = {};
    Object.entries(newData).forEach(([i, j]) => {
        let keys = propertyKeys();
        if (inArray(i, keys[extractValueAfterDelimiter(newData.type, ':')])) {
            data[i] = j;
        }
    });
    //调用父级方法
    window.parent.handlePropertyChange(data);
};


/**
 * 表单属性对应keys
 * @returns {{process: string[], fork: string[], task: string[], custom: string[], start: string[], end: string[], join: string[], decimal: string[], transition: string[]}}
 */
function propertyKeys() {
    // 表单属性keys
    return {
        //流程属性
        process: ['type', 'name', 'display_name', 'expire_time', 'instance_url', 'instance_no_class'],
        //任务节点属性
        task: ['id', 'display_name', 'type', 'width', 'height', 'theme', 'stroke', 'color', 'stroke_width', 'back_permission', 'pre_interceptors', 'post_interceptors', 'form', 'scope', 'assignee', 'assignment_handler', 'task_type', 'perform_type', 'reminder_time', 'reminder_repeat', 'expire_time', 'auto_execute', 'callback', 'field'],
        //决策点属性
        decision: ['id', 'type', 'handle_class', 'pre_interceptors', 'post_interceptors', 'expr'],
        //开始节点属性
        start: ['id', 'display_name', 'type', 'width', 'height', 'theme', 'stroke', 'color', 'stroke_width', 'pre_interceptors', 'post_interceptors'],
        //结束节点属性
        end: ['id', 'display_name', 'type', 'width', 'height', 'theme', 'stroke', 'color', 'stroke_width', 'pre_interceptors', 'post_interceptors'],
        //自定义节点属性
        custom: ['id', 'display_name', 'type', 'width', 'height', 'theme', 'stroke', 'color', 'stroke_width', 'pre_interceptors', 'post_interceptors', 'clazz', 'method_name', 'args', 'pre_interceptors', 'post_interceptors'],
        //分支节点属性
        fork: ['id', 'display_name', 'type', 'width', 'height', 'theme', 'stroke', 'color', 'stroke_width', 'pre_interceptors', 'post_interceptors'],
        //合并节点属性
        join: ['id', 'display_name', 'type', 'width', 'height', 'theme', 'stroke', 'color', 'stroke_width', 'pre_interceptors', 'post_interceptors'],
        //边线属性
        transition: ['id', 'display_name', 'type', 'width', 'height', 'theme', 'stroke', 'color', 'stroke_width', 'pre_interceptors', 'post_interceptors', 'expr'],
        subProcess: ['id', 'display_name', 'type', 'width', 'height', 'theme', 'stroke', 'color', 'stroke_width', 'pre_interceptors', 'post_interceptors', 'name', 'display_name', 'expire_time', 'instance_url', 'instance_no_class'],
        wfSubProcess: ['id', 'display_name', 'type', 'width', 'height', 'theme', 'stroke', 'color', 'stroke_width', 'form', 'process_name', 'version'],
        //扩展字段属性
        field: ['user_key', 'group_key', 'candidate_ext_users', 'candidate_ext_groups', 'candidate_ext_handler', 'attr1', 'attr2', 'attr3','countersign_type','countersign_completion_condition']
    };
}

/**
 * 数组in
 * @param search
 * @param array
 * @returns {boolean}
 */
function inArray(search, array) {
    return array.includes(search);
}

/**
 * 替换key
 * @param obj
 * @param oldKey
 * @param newKey
 * @returns {*}
 */
function renameKey(obj, oldKey, newKey) {
    Object.entries(obj).forEach(([key, value]) => {
        if (key === oldKey) {
            delete obj[key];
            obj[newKey] = value;
        }
    });
    return obj;
}


/**
 * 初始化对象对应的表单数据
 * @param obj
 * @returns {*|{}}
 */
function initializationData(obj) {
    let newObj = Object.assign({}, obj.data, obj.data.properties, obj.data.text != undefined ? obj.data.text : {});
    newObj.display_name = newObj.display_name != undefined ? newObj.display_name : newObj.value != undefined ? newObj.value : '';
    return newObj != undefined ? newObj : {};
}

/**
 * 对传入类型进行截取
 * @param string
 * @param delimiter
 * @returns {*}
 */
function extractValueAfterDelimiter(string, delimiter) {
    // 找到冒号在字符串中的位置
    let delimiterIndex = string.indexOf(delimiter);
    // 如果冒号存在于字符串中，则截取冒号后面的值
    if (delimiterIndex !== -1) {
        let value = string.slice(delimiterIndex + delimiter.length);
        return value;
    } else {
        return string;
    }
}
