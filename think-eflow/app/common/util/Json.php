<?php

/**
 *+------------------
 * madong
 *+------------------
 * Copyright (c) https://gitee.com/motion-code  All rights reserved.
 *+------------------
 * Author: Mr. April (405784684@qq.com)
 *+------------------
 * Official Website: http://www.madong.tech
 */

namespace app\common\util;

use think\exception\HttpResponseException;
use think\Response;

class Json
{
    private static int $code = 200;

    public static function make(int $code, string $msg, ?array $data = null, ?array $replace = [])
    {
        $res = compact('code', 'msg');

        if (!is_null($data))
            $res['data'] = $data;

        if (is_numeric($res['msg'])) {
            $res['code'] = $res['msg'];
            $res['msg']  = $res['msg'];
        }

        $defaultHttpCode = self::$code;
        // 如果 code 不是 -1 或 400，设置 HTTP 状态码
        if (!in_array($code, [-1, 0, 200, 400]) && $code >= 100 && $code < 600) {
            $defaultHttpCode = $code;
        }

        return json($res, $defaultHttpCode);
    }

    public static function success($msg = 'success', ?array $data = [], ?array $replace = []): Response
    {
        if (is_array($msg)) {
            $data = $msg;
            $msg  = 'success';
        }

        if (is_array($data)) {
            $data = self::convertLongNumbersToString($data);
        }

        return self::make(0, $msg, $data, $replace);
    }

    public static function fail($msg = 'fail', ?array $data = null, int|string $code = -1, ?array $replace = []): Response
    {
        if (is_array($msg)) {
            $data = $msg;
            $msg  = 'fail';
        }
        return self::make($code, $msg, $data, $replace);
    }

    public static function status($status, $msg, $result = []): Response
    {
        $status = strtoupper($status);
        if (is_array($msg)) {
            $result = $msg;
            $msg    = 'success';
        }
        return self::success($msg, compact('status', 'result'));
    }

    /**
     *  数据输出雪花id 转字符串
     *
     * @param $array
     *
     * @return mixed
     */
    public static function convertLongNumbersToString($array): mixed
    {
        foreach ($array as &$value) {
            if (is_array($value)) {
                // 如果值是数组，递归调用
                $value = self::convertLongNumbersToString($value);
            } elseif (is_object($value)) {
                // 如果值是对象，检查是否是模型对象
                if (method_exists($value, 'toArray')) {
                    // 将模型对象转换为数组并递归处理
                    $value = self::convertLongNumbersToString($value->toArray());
                }
            } elseif (is_numeric($value) && strlen((string)$value) > 15) {
                // 如果是数字且长度大于 15，转换为字符串
                $value = (string)$value;
            }
        }
        return $array;
    }
}

