<?php
// +----------------------------------------------------------------------
// | Cookie设置
// +----------------------------------------------------------------------
return [
    // cookie 保存时间
    'expire'     => 0,
    // cookie 保存路径
    'path'       => '/',
    // cookie 有效域名
    'domain'     => 'http://127.0.0.5',
    //  cookie 启用安全传输
    'secure'     => false,
    // httponly设置
    'httponly'   => false,
    // 是否使用 setcookie
    'setcookie'  => true,
    // samesite 设置，支持 'strict' 'lax'
    'samesite'   => '',

    // 跨域header
    'header'     => [
        'Access-Control-Allow-Origin'      => '*',
        'Access-Control-Allow-Headers'     => 'Authori-zation,Authorization, Content-Type, If-Match, If-Modified-Since, If-None-Match, If-Unmodified-Since, X-Requested-With, Form-type, Cb-lang, Invalid-zation',
        'Access-Control-Allow-Methods'     => 'GET,POST,PATCH,PUT,DELETE,OPTIONS,DELETE',
        'Access-Control-Max-Age'           => '1728000',
        'Access-Control-Allow-Credentials' => 'true',
    ],
    // token名称
    'token_name' => 'Authori-zation',
];
