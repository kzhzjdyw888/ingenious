/**
 * 浏览页面顶部搜索框展开收回控制
 */
function toggleSearchFormShow() {
    let $ = layui.$;
    let items = $('.top-search-from .layui-form-item');
    if (items.length <= 2) {
        if (items.length <= 1) $('.top-search-from').parent().parent().remove();
        return;
    }
    let btns = $('.top-search-from .toggle-btn a');
    let toggle = toggleSearchFormShow;
    if (typeof toggle.hide === 'undefined') {
        btns.on('click', function () {
            toggle();
        });
    }
    let countPerRow = parseInt($('.top-search-from').width() / $('.layui-form-item').width());
    if (items.length <= countPerRow) {
        return;
    }
    btns.removeClass('layui-hide');
    toggle.hide = !toggle.hide;
    if (toggle.hide) {
        for (let i = countPerRow - 1; i < items.length - 1; i++) {
            $(items[i]).hide();
        }
        return $('.top-search-from .toggle-btn a:last').addClass('layui-hide');
    }
    items.show();
    $('.top-search-from .toggle-btn a:first').addClass('layui-hide');
}


/**
 * 将时间戳转换为指定格式的字符串时间
 * @param {(number|null|undefined)} timestamp - 时间戳（毫秒），如果是null或undefined则返回defaultValue
 * @param {string} [format='YYYY-MM-DD HH:mm:ss'] - 目标格式，但此版本中未使用到小时、分钟、秒部分
 * @param {string} [defaultValue='Invalid Timestamp'] - 当timestamp为空或无效时的默认返回值
 * @returns {string} - 格式化后的时间字符串或默认值
 * @throws {Error} - 如果timestamp不是有效的数字（即不是有效的时间戳）
 */
function formatTimestamp(timestamp, format = 'YYYY-MM-DD HH:mm:ss', defaultValue = '') {
    // 检查timestamp是否为有效的数字
    if (typeof timestamp !== 'number' || isNaN(timestamp)) {
        return defaultValue;
    }

    // 判断时间戳是10位还是13位，并转换为毫秒级
    const msTimestamp = timestamp * 1000; // 假设总是将秒级时间戳转换为毫秒级

    // 创建一个 Date 对象
    const date = new Date(msTimestamp);

    // 如果日期无效（例如，因为时间戳太大或太小而无法表示为一个有效的 Date 对象）
    if (isNaN(date.getTime())) {
        return defaultValue;
    }

    // 定义格式映射，包括时间部分
    const formatMap = {
        'YYYY': date.getFullYear(),
        'MM': String(date.getMonth() + 1).padStart(2, '0'),
        'DD': String(date.getDate()).padStart(2, '0'),
        'HH': String(date.getHours()).padStart(2, '0'),
        'mm': String(date.getMinutes()).padStart(2, '0'),
        'ss': String(date.getSeconds()).padStart(2, '0')
    };

    // 特殊处理只包含时间部分的格式字符串
    let hasDatePart = false;
    for (const key of Object.keys(formatMap)) {
        if (format.toUpperCase().includes(key)) {
            if (['YYYY', 'MM', 'DD'].includes(key)) {
                hasDatePart = true;
            }
            break; // 只要找到一个匹配项就足以确定是否有日期部分
        }
    }

    let formattedDate = format;
    if (!hasDatePart) {
        // 如果没有日期部分，则只替换时间部分，并假设用户想要当前日期的时间
        // 但这里为了简化，我们仍然只使用传入的时间戳的时间部分
        formattedDate = format
            .replace(/YYYY|MM|DD/g, '') // 移除日期占位符（如果有的话）
            .replace(/HH/g, formatMap['HH'])
            .replace(/mm/g, formatMap['mm'])
            .replace(/ss/g, formatMap['ss']);
    } else {
        // 如果有日期部分，则正常替换所有占位符
        for (const key in formatMap) {
            formattedDate = formattedDate.replace(new RegExp(key, 'gi'), formatMap[key]);
        }
    }

    // 返回格式化后的字符串（可能只包含时间部分）
    return formattedDate;
}

layui.$(function () {
    toggleSearchFormShow();
});

