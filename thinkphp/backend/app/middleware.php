<?php
// 全局中间件定义文件
return [
    // 全局请求缓存
    // \think\middleware\CheckRequestCache::class,
    // 多语言加载
    // \think\middleware\LoadLangPack::class,
    // Session初始化
    // \think\middleware\SessionInit::class
    //多语言初始化
    \think\middleware\LoadLangPack::class,
    // 页面Trace调试
    // \think\middleware\TraceDebug::class,
    //初始化基础中间件
    \phoenix\middleware\BaseMiddleware::class,
    // 多语言支持
    \think\middleware\LoadLangPack::class,
];
