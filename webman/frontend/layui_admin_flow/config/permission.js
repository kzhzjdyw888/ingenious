/**
 * 获取控制器详细权限，并决定展示哪些按钮或dom元素
 */
layui.$(function () {
    let $ = layui.$;
    let userInfo = getUserInfo();
    if (userInfo === undefined) {
        return;
    }
    if (userInfo.isLogin != undefined && userInfo.isLogin === true) {
        let style = ''
        let isSupperAdmin = false;
        let level = userInfo.info.level != undefined && userInfo.info.level === 0 ? true : false;
        if (level) {
            $("head").append("<style>*[permission]{display: initial}</style>");
            isSupperAdmin = true;
        }
        if (isSupperAdmin) return;


        // 细分权限
        let codes = getUserInfo().unique_auth !== undefined ? getUserInfo().unique_auth : [];
        layui.each(codes, function (k, code) {
            codes[k] = '*[permission^="' + code + '"]';
        });
        if (codes.length) {
            $("head").append("<style>" + codes.join(",") + "{display: initial}</style>");
        }
    }
});

const $request = (params) => {
    let $ = layui.jquery;
    let userinfo = checkLogin({
        nav: true
    });
    if (userinfo === false) {
        return false;
    }
    return new Promise((resolve, reject) => {
        $.ajax({
            headers: {
                "Authori-zation": "Bearer " + userinfo["token"] ?? 'Bearer',
                Author: 'Leamus',
                'Access-Control-Allow-Origin': '*',
            }, ...params, success(ret) {
                // 返回成功
                if (ret && ret.status === 200) {
                    resolve(ret);
                } else {
                    reject(ret);
                }
            }, error(ret) {
                // 返回Token过期
                if (ret && ret.status === 401) {
                    if (ret["responseJSON"].code < 0) {
                        // 需要重新登录
                        setUserInfo(null);
                        location.href = 'login.html';
                    } else {
                        console.log('刷新token');
                        // 刷新处理Token
                        setUserInfo({
                            isLogin: true, "token": ret.data.token, info: ret.data.info, rules: ret.data.rules,
                        });
                        // 将原来请求重新发送
                        $request(params).then(resolve).catch(reject);
                    }
                } else {
                    reject(ret);
                }
            },
        });
    });
};

// 设置请求默认值
layui.$.ajaxSetup({
    beforeSend: function (xhr) {
        if (getUserInfo() == '' || getUserInfo() == null || getUserInfo() == undefined) {
            location.href = 'index.html';
            return;
        }
    },
    headers: {
        //自定义标头
        'Author': 'Leamus',
        'Authorization': getUserInfo() != undefined ? "Bearer " + getUserInfo()["token"] : 'Bearer'
    },
    complete: function (xhr) {
        // 设置登陆拦截
        if (xhr.status === 401) {
            let ret = xhr['responseJSON'] !== undefined ? xhr['responseJSON'] : [];
            if (ret['code'] === undefined || ret['code'] != 0) {
                setUserInfo(null);
                location.href = 'index.html'
            }
            setUserInfo({
                isLogin: true, "token": ret.data.token, info: ret.data.info, rules: ret.data.rules,
            });
            //继续完成当前访问
            layui.$.ajax(this)
        }
        if (xhr.status === 403) {
            window.errors('403.html')
        }
        if (xhr.status === 404) {
            window.errors('404.html')
        }
        if (xhr.status === 500) {
            window.errors('500.html')
        }
        if (xhr.responseJSON !== undefined && xhr.responseJSON.status !== undefined && xhr.responseJSON.status === 110003) {
            setUserInfo(null);
            location.href = 'index.html'
        }
    },
});

/**
 * 超时没有操作退出
 * @type {number}
 */
let lastTime = new Date().getTime();
let currentTime = new Date().getTime();
let timeOut = 15 * 60 * 1000; //设置超时时间： 15分

layui.$(function () {
    /* 鼠标移动事件 */
    layui.$(document).mouseover(function () {
        lastTime = new Date().getTime(); //更新操作时间
    });
});

function toLoginPage() {
    currentTime = new Date().getTime(); //更新当前时间
    if (currentTime - lastTime > timeOut) { //判断是否超时
        window.close();//关闭当前页
        location.href = 'login.html';
        // window.parent.location.replace("toLogin.do");//刷新父级页面;
    }
}

/* 定时器
 * 间隔1秒检测是否长时间未操作页面
 */
// window.setInterval(toLoginPage, 1000);
