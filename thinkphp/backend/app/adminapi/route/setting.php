<?php

use think\facade\Route;

/**
 * 系统设置维护 系统权限管理、系统菜单管理 系统配置 相关路由
 */
Route::group('setting', function () {

    /** 管理员 */
    Route::group(function () {
        //扮演用户
         Route::post('admin/act_user', 'v1.setting.SystemAdmin/actUser')->name('SystemAdminActAdmin')->option(['real_name' => '扮演用户']);

        //批量删除
        Route::put('admin/batch_remove', 'v1.setting.SystemAdmin/batchUpdate')->name('SystemAdminDeleteAdmin')->option(['real_name' => '批量删除管理员']);
        //管理员资源路由
        Route::resource('admin', 'v1.setting.SystemAdmin')->except(['edit', 'create'])->option([
            'real_name' => [
                'index'  => '获取管理员列表',
                'save'   => '保存管理员',
                'read'   => '获取详情',
                'update' => '修改管理员',
                'delete' => '删除管理员',
            ],
        ]);

        //退出登陆
        Route::get('admin/logout', 'v1.setting.SystemAdmin/logout')->name('SystemAdminLogout')->option(['real_name' => '退出登陆']);
        //修改状态
        Route::put('set_status/:id/:status', 'v1.setting.SystemAdmin/set_status')->name('SystemAdminSetStatus')->option(['real_name' => '修改管理员状态']);
        //获取当前管理员信息
        Route::get('info', 'v1.setting.SystemAdmin/info')->name('SystemAdminInfo')->option(['real_name' => '获取当前管理员信息']);
        //修改当前管理员信息
        Route::put('update_admin', 'v1.setting.SystemAdmin/update_admin')->name('SystemAdminUpdateAdmin')->option(['real_name' => '修改当前管理员信息']);
        //设置文件管理密码
        Route::put('set_file_password', 'v1.setting.SystemAdmin/set_file_password')->name('SystemAdminSetFilePassword')->option(['real_name' => '设置当前文件管理密码']);
        //管理员设置部门职位
        Route::put('set_admin_position/:id', 'v1.setting.SystemAdmin/setPosition')->name('SystemAdminSetPosition')->option(['real_name' => '设置管理员职位']);
    })->option(['parent' => 'setting', 'cate_name' => '管理员']);

    /** 权限菜单 */
    Route::group(function () {
        //获取菜单
         Route::get('permission', 'v1.setting.SystemMenus/permission')->name('SystemMenusUnique')->option(['real_name' => '获取菜单']);
        //获取菜单权限和权限标识
        Route::get('menus/unique', 'v1.setting.SystemMenus/unique')->name('SystemMenusUnique')->option(['real_name' => '获取菜单权限和权限标识']);
        //批量保存权限
        Route::post('menus/batch', 'v1.setting.SystemMenus/batchSave')->name('SystemMenusBatchSave')->option(['real_name' => '批量保存权限']);
        //权限菜单资源路由
        Route::resource('menus', 'v1.setting.SystemMenus')->option([
            'real_name' => [
                'index'  => '获取权限菜单列表',
                'create' => '获取权限菜单表单',
                'save'   => '保存权限菜单',
                'edit'   => '获取修改权限菜单表单',
                'read'   => '查看权限菜单信息',
                'update' => '修改权限菜单',
                'delete' => '删除权限菜单',
            ],
        ]);
        //未添加的权限规则列表
        Route::get('ruleList', 'v1.setting.SystemMenus/ruleList')->option(['real_name' => '权限规则列表']);
        //权限规则分类
        Route::get('rule_cate', 'v1.setting.SystemMenus/ruleCate')->option(['real_name' => '权限规则分类']);
        //修改显示
        Route::put('menus/show/:id', 'v1.setting.SystemMenus/show')->name('SystemMenusShow')->option(['real_name' => '修改权限规格显示状态']);
    })->option(['parent' => 'setting', 'cate_name' => '权限菜单']);

    /** 角色管理 */
    Route::group(function () {
        //身份列表
//        Route::get('role', 'v1.setting.SystemRole/index')->option(['real_name' => '管理员身份列表']);
//        //身份权限列表
//        Route::get('role/create', 'v1.setting.SystemRole/create')->option(['real_name' => '管理员身份权限列表']);
//        //编辑详情
//        Route::get('role/:id/edit', 'v1.setting.SystemRole/edit')->option(['real_name' => '编辑管理员详情']);
//        //保存新建或编辑
//        Route::post('role/:id', 'v1.setting.SystemRole/save')->option(['real_name' => '新建或编辑管理员']);
//        //修改身份状态
//        Route::put('role/set_status/:id/:status', 'v1.setting.SystemRole/set_status')->option(['real_name' => '修改管理员身份状态']);
//        //删除身份
//        Route::delete('role/:id', 'v1.setting.SystemRole/delete')->option(['real_name' => '删除管理员身份']);

        //修改状态
        Route::put('role/set_status/:id/:status', 'v1.setting.SystemRole/set_status')->name('SystemAdminSetStatus')->option(['real_name' => '修改角色状态']);
        //权限菜单资源路由
        Route::resource('role', 'v1.setting.SystemRole')->except(['edit', 'create'])->option([
            'real_name' => [
                'index'  => '获取角色列表',
                'save'   => '保存角色',
                'read'   => '查看角色信息',
                'update' => '修改角色',
                'delete' => '删除角色',
            ],
        ]);
        //角色分配权限
        Route::post('role_menu', 'v1.setting.SystemRoleMenu/save')->name('SystemRoleMenuSave')->option(['real_name' => '角色分配权限']);
        //角色分配组织
        Route::post('role_dept', 'v1.setting.SystemRoleDept/save')->name('SystemRoleDeptSave')->option(['real_name' => '角色分配组织']);
        //角色分配职位
        Route::post('role_post', 'v1.setting.SystemRolePost/save')->name('SystemRolePostSave')->option(['real_name' => '角色分配职位']);
        //获取菜单详情
        Route::get('menu_list', 'v1.setting.SystemRole/menuList')->name('SystemRoleMenuList')->option(['real_name' => '角色分配职位']);

    })->option(['parent' => 'setting', 'cate_name' => '管理员身份']);

    /** 组织管理 */
    Route::group(function () {
        //修改状态
        Route::put('dept/set_status/:id/:status', 'v1.setting.SystemDept/set_status')->name('SystemDeptSetStatus')->option(['real_name' => '修改角色状态']);
        //组织资源路由
        Route::resource('dept', 'v1.setting.SystemDept')->except(['edit', 'create'])->option([
            'real_name' => [
                'index'  => '获取列表',
                'save'   => '保存组织',
                'read'   => '查看详情',
                'update' => '修改组织',
                'delete' => '删除组织',
            ],
        ]);
    })->option(['parent' => 'setting', 'cate_name' => '组织管理']);

    /** 职位管理 */
    Route::group(function () {
        //组织资源路由
        Route::resource('post', 'v1.setting.SystemPost')->except(['edit', 'create'])->option([
            'real_name' => [
                'index'  => '获取列表',
                'save'   => '保存职位',
                'read'   => '查看详情',
                'update' => '修改职位',
                'delete' => '删除职位',
            ],
        ]);
    })->option(['parent' => 'setting', 'cate_name' => '职位管理']);

//
//    /** 系统配置 */
//    Route::group(function () {
//        //配置分类资源路由
//        Route::resource('config_class', 'v1.setting.SystemConfigTab')->except(['read'])->option([
//            'real_name' => [
//                'index'  => '获取系统配置分类列表',
//                'create' => '获取系统配置分类表单',
//                'save'   => '保存系统配置分类',
//                'edit'   => '获取修改系统配置分类表单',
//                'update' => '修改系统配置分类',
//                'delete' => '删除系统配置分类',
//            ],
//        ]);
//        //修改配置分类状态
//        Route::put('config_class/set_status/:id/:status', 'v1.setting.SystemConfigTab/set_status')->option(['real_name' => '修改配置分类状态']);
//        //配置资源路由
//        Route::resource('config', 'v1.setting.SystemConfig')->except(['read'])->option([
//            'real_name' => [
//                'index'  => '获取系统配置列表',
//                'create' => '获取系统配置表单',
//                'save'   => '保存系统配置',
//                'edit'   => '获取修改系统配置表单',
//                'update' => '修改系统配置',
//                'delete' => '删除系统配置',
//            ],
//        ]);
//        //修改配置状态
//        Route::put('config/set_status/:id/:status', 'v1.setting.SystemConfig/set_status')->option(['real_name' => '修改配置状态']);
//        //基本配置编辑表单
//        Route::get('config/header_basics', 'v1.setting.SystemConfig/header_basics')->option(['real_name' => '基本配置编辑头部数据']);
//        //基本配置编辑表单
//        Route::get('config/edit_basics', 'v1.setting.SystemConfig/edit_basics')->option(['real_name' => '基本配置编辑表单']);
//        //基本配置保存数据
//        Route::post('config/save_basics', 'v1.setting.SystemConfig/save_basics')->option(['real_name' => '基本配置保存数据']);
//        //基本配置上传文件
//        Route::post('config/upload', 'v1.setting.SystemConfig/file_upload')->option(['real_name' => '基本配置上传文件']);
//        //获取单个配置值
//        Route::get('config/get_system/:name', 'v1.setting.SystemConfig/get_system')->option(['real_name' => '基本配置编辑表单']);
//        //获取某个分类下的所有配置信息
//        Route::get('config_list/:tabId', 'v1.setting.SystemConfig/get_config_list')->option(['real_name' => '获取某个分类下的所有配置信息']);
//    })->option(['parent' => 'setting', 'cate_name' => '系统配置']);
//
//    /** 组合数据 */
//    Route::group(function () {
//        //组合数据资源路由
//        Route::resource('group', 'v1.setting.SystemGroup')->option([
//            'real_name' => [
//                'index'  => '获取组合数据列表',
//                'create' => '获取组合数据表单',
//                'save'   => '保存组合数据',
//                'edit'   => '获取修改组合数据表单',
//                'update' => '修改组合数据',
//                'delete' => '删除组合数据',
//            ],
//        ]);
//        //组合数据全部
//        Route::get('group_all', 'v1.setting.SystemGroup/getGroup')->option(['real_name' => '组合数据全部']);
//        //组合数据子数据资源路由
//        Route::resource('group_data', 'v1.setting.SystemGroupData')->except(['read'])->option([
//            'real_name' => [
//                'index'  => '获取组合数据子数据列表',
//                'create' => '获取组合数据子数据表单',
//                'save'   => '保存组合数据子数据',
//                'edit'   => '获取修改组合数据子数据表单',
//                'update' => '修改组合数据子数据',
//                'delete' => '删除组合数据子数据',
//            ],
//        ]);
//        //修改数据状态
//        Route::get('group_data/header', 'v1.setting.SystemGroupData/header')->option(['real_name' => '组合数据头部']);
//        //修改数据状态
//        Route::put('group_data/set_status/:id/:status', 'v1.setting.SystemGroupData/set_status')->option(['real_name' => '修改组合数据状态']);
//        //数据配置保存
//        Route::post('group_data/save_all', 'v1.setting.SystemGroupData/saveAll')->option(['real_name' => '提交数据配置']);
//        //获取客服广告
//        Route::get('get_kf_adv', 'v1.setting.SystemGroupData/getKfAdv')->option(['real_name' => '获取客服广告']);
//        //设置客服广告
//        Route::post('set_kf_adv', 'v1.setting.SystemGroupData/setKfAdv')->option(['real_name' => '设置客服广告']);
//        //签到天数配置资源
//        Route::resource('sign_data', 'v1.setting.SystemGroupData')->except(['read'])->option([
//            'real_name' => [
//                'index'  => '获取签到天数配置列表',
//                'create' => '获取签到天数配置表单',
//                'save'   => '保存签到天数配置',
//                'edit'   => '获取修改签到天数配置表单',
//                'update' => '修改签到天数配置',
//                'delete' => '删除签到天数配置',
//            ],
//        ]);
//        //签到数据字段
//        Route::get('sign_data/header', 'v1.setting.SystemGroupData/header')->option(['real_name' => '签到数据头部']);
//        //修改签到数据状态
//        Route::put('sign_data/set_status/:id/:status', 'v1.setting.SystemGroupData/set_status')->option(['real_name' => '修改签到数据状态']);
//        //订单详情动态图配置资源
//        Route::resource('order_data', 'v1.setting.SystemGroupData')->except(['read'])->option([
//            'real_name' => [
//                'index'  => '获取订单详情动态图列表',
//                'create' => '获取订单详情动态图表单',
//                'save'   => '保存订单详情动态图',
//                'edit'   => '获取修改订单详情动态图表单',
//                'update' => '修改订单详情动态图',
//                'delete' => '删除订单详情动态图',
//            ],
//        ]);
//        //订单数据字段
//        Route::get('order_data/header', 'v1.setting.SystemGroupData/header')->option(['real_name' => '订单数据字段']);
//        //订单数据状态
//        Route::put('order_data/set_status/:id/:status', 'v1.setting.SystemGroupData/set_status')->option(['real_name' => '订单数据状态']);
//        //个人中心菜单配置资源
//        Route::resource('usermenu_data', 'v1.setting.SystemGroupData')->except(['read'])->option([
//            'real_name' => [
//                'index'  => '获取个人中心菜单列表',
//                'create' => '获取个人中心菜单表单',
//                'save'   => '保存个人中心菜单',
//                'edit'   => '获取修改个人中心菜单表单',
//                'update' => '修改个人中心菜单',
//                'delete' => '删除个人中心菜单',
//            ],
//        ]);
//        //个人中心菜单数据字段
//        Route::get('usermenu_data/header', 'v1.setting.SystemGroupData/header')->option(['real_name' => '个人中心菜单数据字段']);
//        //个人中心菜单数据状态
//        Route::put('usermenu_data/set_status/:id/:status', 'v1.setting.SystemGroupData/set_status')->option(['real_name' => '个人中心菜单数据状态']);
//        //分享海报配置资源
//        Route::resource('poster_data', 'v1.setting.SystemGroupData')->except(['read'])->option([
//            'real_name' => [
//                'index'  => '获取分享海报列表',
//                'create' => '获取分享海报表单',
//                'save'   => '保存分享海报',
//                'edit'   => '获取修改分享海报表单',
//                'update' => '修改分享海报',
//                'delete' => '删除分享海报',
//            ],
//        ]);
//        //分享海报数据字段
//        Route::get('poster_data/header', 'v1.setting.SystemGroupData/header')->option(['real_name' => '分享海报数据字段']);
//        //分享海报数据状态
//        Route::put('poster_data/set_status/:id/:status', 'v1.setting.SystemGroupData/set_status')->option(['real_name' => '分享海报数据状态']);
//        //秒杀配置资源
//        Route::resource('seckill_data', 'v1.setting.SystemGroupData')->except(['read'])->option([
//            'real_name' => [
//                'index'  => '获取分秒杀配置列表',
//                'create' => '获取秒杀配置表单',
//                'save'   => '保存秒杀配置',
//                'edit'   => '获取修改秒杀配置表单',
//                'update' => '修改秒杀配置',
//                'delete' => '删除秒杀配置',
//            ],
//        ]);
//        //秒杀数据字段
//        Route::get('seckill_data/header', 'v1.setting.SystemGroupData/header')->option(['real_name' => '秒杀数据字段']);
//        //秒杀数据状态
//        Route::put('seckill_data/set_status/:id/:status', 'v1.setting.SystemGroupData/set_status')->option(['real_name' => '秒杀数据状态']);
//        //获取隐私协议
//        Route::get('get_user_agreement', 'v1.setting.SystemGroupData/getUserAgreement')->option(['real_name' => '获取隐私协议']);
//        //设置隐私协议
//        Route::post('set_user_agreement', 'v1.setting.SystemGroupData/setUserAgreement')->option(['real_name' => '设置隐私协议']);
//    })->option(['parent' => 'setting', 'cate_name' => '组合数据']);
//
//    /** 城市数据 */
//    Route::group(function () {
//        //获取城市数据完整列表
//        Route::get('city/full_list', 'v1.setting.SystemCity/fullList')->option(['real_name' => '获取城市数据完整列表']);
//        //获取城市数据列表
//        Route::get('city/list/:parent_id', 'v1.setting.SystemCity/index')->option(['real_name' => '获取城市数据列表']);
//        //添加城市数据表单
//        Route::get('city/add/:parent_id', 'v1.setting.SystemCity/add')->option(['real_name' => '添加城市数据表单']);
//        //修改城市数据表单
//        Route::get('city/:id/edit', 'v1.setting.SystemCity/edit')->option(['real_name' => '修改城市数据表单']);
//        //新增/修改城市数据
//        Route::post('city/save', 'v1.setting.SystemCity/save')->option(['real_name' => '新增/修改城市数据']);
//        //修改城市数据表单
//        Route::delete('city/del/:city_id', 'v1.setting.SystemCity/delete')->option(['real_name' => '删除城市数据']);
//        //清除城市数据缓存
//        Route::get('city/clean_cache', 'v1.setting.SystemCity/clean_cache')->option(['real_name' => '清除城市数据缓存']);
//    })->option(['parent' => 'setting', 'cate_name' => '城市数据']);
//
//    /** 运费模版 */
//    Route::group(function () {
//        //运费模板列表
//        Route::get('shipping_templates/list', 'v1.setting.ShippingTemplates/temp_list')->option(['real_name' => '运费模板列表']);
//        //修改运费模板数据
//        Route::get('shipping_templates/:id/edit', 'v1.setting.ShippingTemplates/edit')->option(['real_name' => '修改运费模板数据']);
//        //保存新增修改
//        Route::post('shipping_templates/save/:id', 'v1.setting.ShippingTemplates/save')->option(['real_name' => '新增或修改运费模版']);
//        //删除运费模板
//        Route::delete('shipping_templates/del/:id', 'v1.setting.ShippingTemplates/delete')->option(['real_name' => '删除运费模板']);
//        //城市数据接口
//        Route::get('shipping_templates/city_list', 'v1.setting.ShippingTemplates/city_list')->option(['real_name' => '城市数据接口']);
//    })->option(['parent' => 'setting', 'cate_name' => '运费模版']);
//
//    /** 系统通知 */
//    Route::group(function () {
//        //系统通知列表
//        Route::get('notification/index', 'v1.setting.SystemNotification/index')->option(['real_name' => '系统通知列表']);
//        //获取单条数据
//        Route::get('notification/info', 'v1.setting.SystemNotification/info')->option(['real_name' => '获取单条通知数据']);
//        //保存通知设置
//        Route::post('notification/save', 'v1.setting.SystemNotification/save')->option(['real_name' => '保存通知设置']);
//        //修改消息状态
//        Route::put('notification/set_status/:type/:status/:id', 'v1.setting.SystemNotification/set_status')->option(['real_name' => '修改消息状态']);
//    })->option(['parent' => 'setting', 'cate_name' => '系统通知']);
//
//    /** 协议版权 */
//    Route::group(function () {
//        //协议设置
//        Route::get('get_agreement/:type', 'v1.setting.SystemAgreement/getAgreement')->option(['real_name' => '获取协议内容']);
//        Route::post('save_agreement', 'v1.setting.SystemAgreement/saveAgreement')->option(['real_name' => '设置协议内容']);
//        //获取版权信息
//        Route::get('get_version', 'v1.setting.SystemConfig/getVersion')->option(['real_name' => '获取版权信息']);
//    })->option(['parent' => 'setting', 'cate_name' => '协议版权']);
//
//    /** 对外接口 */
//    Route::group(function () {
//        //对外接口账号信息
//        Route::get('system_out_account/index', 'v1.setting.SystemOutAccount/index')->option(['real_name' => '对外接口账号信息']);
//        //对外接口账号添加
//        Route::post('system_out_account/save', 'v1.setting.SystemOutAccount/save')->option(['real_name' => '对外接口账号添加']);
//        //对外接口账号修改
//        Route::post('system_out_account/update/:id', 'v1.setting.SystemOutAccount/update')->option(['real_name' => '对外接口账号修改']);
//        //设置账号是否禁用
//        Route::put('system_out_account/set_status/:id/:status', 'v1.setting.SystemOutAccount/set_status')->option(['real_name' => '设置账号是否禁用']);
//        //设置账号推送接口
//        Route::put('system_out_account/set_up/:id', 'v1.setting.SystemOutAccount/outSetUpSave')->option(['real_name' => '设置账号推送接口']);
//        //删除账号
//        Route::delete('system_out_account/:id', 'v1.setting.SystemOutAccount/delete')->option(['real_name' => '删除账号']);
//        //测试获取token接口
//        Route::post('system_out_account/text_out_url', 'v1.setting.SystemOutAccount/textOutUrl')->option(['real_name' => '测试获取token接口']);
//
//        //对外接口列表
//        Route::get('system_out_interface/list', 'v1.setting.SystemOutAccount/outInterfaceList')->option(['real_name' => '对外接口列表']);
//        //新增修改对外接口
//        Route::post('system_out_interface/save/:id', 'v1.setting.SystemOutAccount/saveInterface')->option(['real_name' => '新增修改对外接口']);
//        //对外接口信息
//        Route::get('system_out_interface/info/:id', 'v1.setting.SystemOutAccount/interfaceInfo')->option(['real_name' => '对外接口信息']);
//        //修改接口名称
//        Route::put('system_out_interface/edit_name', 'v1.setting.SystemOutAccount/editInterfaceName')->option(['real_name' => '修改接口名称']);
//        //删除接口
//        Route::delete('system_out_interface/del/:id', 'v1.setting.SystemOutAccount/delInterface')->option(['real_name' => '删除接口']);
//    })->option(['parent' => 'setting', 'cate_name' => '对外接口']);
//
    /** 多语言 */
    Route::group(function () {
        //语言国家列表
        Route::get('lang_country/list', 'v1.setting.LangCountry/langCountryList')->option(['real_name' => '语言国家列表']);
        //语言地区详情
        Route::get('lang_country/:id', 'v1.setting.LangCountry/langCountryDetail')->option(['real_name' => '语言地区详情']);
        //保存语言地区
        Route::post('lang_country/save/:id', 'v1.setting.LangCountry/langCountrySave')->option(['real_name' => '保存语言地区']);
        //删除语言地区
        Route::delete('lang_country/del/:id', 'v1.setting.LangCountry/langCountryDel')->option(['real_name' => '删除语言地区']);

        //语言类型列表
        Route::get('lang_type/list', 'v1.setting.LangType/langTypeList')->option(['real_name' => '语言类型列表']);
        //语言详情
        Route::get('lang_type/get', 'v1.setting.LangType/langTypeDetail')->option(['real_name' => '类型详情']);
        //新增语言
        Route::post('lang_type/save/:id', 'v1.setting.LangType/langTypeSave')->option(['real_name' => '新增语言']);
        //删除语言
        Route::delete('lang_type/del/:id', 'v1.setting.LangType/langTypeDel')->option(['real_name' => '删除语言']);
        //修改语言类型状态
        Route::put('lang_type/status/:id/:status', 'v1.setting.LangType/langTypeStatus')->option(['real_name' => '修改语言类型状态']);

        //获取语言分类
        Route::get('lang_code/type', 'v1.setting.LangCode/langCodeType')->option(['real_name' => '语言类型']);
        //获取语言列表
        Route::get('lang_code/list', 'v1.setting.LangCode/langCodeList')->option(['real_name' => '语言列表']);
        //获取语言信息
        Route::get('lang_code/info', 'v1.setting.LangCode/langCodeInfo')->option(['real_name' => '语言详情']);
        //保存语言
        Route::post('lang_code/save/:id', 'v1.setting.LangCode/langCodeSave')->option(['real_name' => '保存语言']);
        //删除语言
        Route::delete('lang_code/del/:id', 'v1.setting.LangCode/langCodeDel')->option(['real_name' => '删除语言']);
        //机器翻译
        Route::post('lang_code/translate', 'v1.setting.LangCode/langCodeTranslate')->option(['real_name' => '机器翻译']);
    })->option(['parent' => 'setting', 'cate_name' => '语言设置']);

    /** 系统配置 */
    Route::group(function () {
        //配置分类资源路由
        Route::resource('config_class', 'v1.setting.SystemConfigTab')->except(['create', 'edit'])->option([
            'real_name' => [
                'index'  => '获取系统配置分类列表',
                'save'   => '保存系统配置分类',
                'update' => '修改系统配置分类',
                'delete' => '删除系统配置分类',
                'read'   => '获取系统配置分类详情',
            ],
        ]);
        //修改配置分类状态
        Route::put('config_class/set_status/:id/:status', 'v1.setting.SystemConfigTab/set_status')->option(['real_name' => '修改配置分类状态']);

        //配置资源路由
        Route::resource('config', 'v1.setting.SystemConfig')->except(['create', 'edit'])->option([
            'real_name' => [
                'index'  => '获取系统配置列表',
                'read'   => '获取系统配置详情',
                'save'   => '保存系统配置',
                'update' => '修改系统配置',
                'delete' => '删除系统配置',
            ],
        ]);
        //批量删除
        Route::put('config/batch/delete', 'v1.setting.SystemConfig/batch_delete')->option(['real_name' => '批量删除系统配置']);
        //修改配置状态
        Route::put('config/set_status/:id/:status', 'v1.setting.SystemConfig/set_status')->option(['real_name' => '修改配置状态']);
        //基本配置编辑表单
        Route::get('config/header_basics', 'v1.setting.SystemConfig/header_basics')->option(['real_name' => '基本配置编辑头部数据']);
        //基本配置编辑表单
        Route::get('config/edit_basics', 'v1.setting.SystemConfig/edit_basics')->option(['real_name' => '基本配置编辑表单']);
        //基本配置保存数据
        Route::post('config/save_basics', 'v1.setting.SystemConfig/save_basics')->option(['real_name' => '基本配置保存数据']);
        //基本配置上传文件
        Route::post('config/upload', 'v1.setting.SystemConfig/file_upload')->option(['real_name' => '基本配置上传文件']);
        //获取单个配置值
        Route::get('config/get_system/:name', 'v1.setting.SystemConfig/get_system')->option(['real_name' => '基本配置编辑表单']);
        //获取某个分类下的所有配置信息
        Route::get('config_list/:tabId', 'v1.setting.SystemConfig/get_config_list')->option(['real_name' => '获取某个分类下的所有配置信息']);
    })->option(['parent' => 'setting', 'cate_name' => '系统配置']);

})->middleware([
    \phoenix\middleware\AllowCrossOriginMiddleware::class,
//    \app\adminapi\middleware\AdminAuthTokenMiddleware::class,
//    \app\adminapi\middleware\AdminAuthPermissionMiddleware::class,
//    \app\adminapi\middleware\AdminLogMiddleware::class,
])->option(['mark' => 'setting', 'mark_name' => '系统设置']);
