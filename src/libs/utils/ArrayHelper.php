<?php
/**
 *+------------------
 * ingenious
 *+------------------
 * Copyright (c) https://gitcode.com/motion-code  All rights reserved.
 *+------------------
 * Author: Mr. April (405784684@qq.com)
 *+------------------
 * Software Registration Number: 2024SR0694589
 * Official Website: https://madong.tech
 */

namespace madong\ingenious\libs\utils;

use InvalidArgumentException;
use madong\ingenious\ex\LFlowException;
use ReflectionClass;
use stdClass;

/**
 * 数组帮助类
 *
 * @author Mr.April
 * @since  1.0
 */
class ArrayHelper
{
    /**
     * convertHumpToLine
     *
     * @param array $data
     *
     * @return array
     */
    public static function convertHumpToLine(array $data): array
    {
        $result = [];
        foreach ($data as $key => $item) {
            if (is_array($item) || is_object($item)) {
                $result[self::humpToLine($key)] = self::convertHumpToLine((array)$item);
            } else {
                $result[self::humpToLine($key)] = trim($item);
            }
        }
        return $result;
    }

    /**
     * toLineHumpConvert
     *
     * @param array $data
     *
     * @return array
     */
    public static function toLineHumpConvert(array $data): array
    {
        $result = [];
        foreach ($data as $key => $item) {
            if (is_array($item) || is_object($item)) {
                $result[self::humpCamelize($key)] = self::toLineHumpConvert((array)$item);
            } else {
                $result[self::humpCamelize($key)] = trim($item);
            }
        }
        return $result;
    }

    /**
     * humpToLine  字符串大写转下划线
     *
     * @param $str
     *
     * @return string|null
     */
    public static function humpToLine($str): string|null
    {
        $str = preg_replace_callback('/([A-Z]{1})/', function ($matches) {
            return '_' . strtolower($matches[0]);
        }, $str);
        return $str;
    }

    /**
     * humpToLine  字符串大写转下划线
     *
     * @param string $str
     * @param string $separator
     *
     * @return string|null
     */
    public static function humpCamelize(string $str, string $separator = '_'): string|null
    {
        $str = $separator . str_replace($separator, " ", strtolower($str));
        return ltrim(str_replace(" ", "", ucwords($str)), $separator);
    }

    /**
     * 数组转换实体对象
     *
     * @param array  $arr
     * @param string $className
     *
     * @return object
     */
    public static function arrayToSimpleObj(array $arr, string $className): object
    {
        try {
            //传递类名或对象进来
            $reflectionClass = new ReflectionClass($className);
            $obj             = $reflectionClass->newInstance();
            foreach ($arr as $key => $value) {
                //过滤验证class 是否包含key
                if ($reflectionClass->hasProperty($key)) {
                    $property = $reflectionClass->getProperty($key);
                    $property->setAccessible(true);//私有属性需要设置允许访问
                    $property->setValue($obj, $value);
                }
            }
            return $obj;
        } catch (\ReflectionException $e) {
            throw new LFlowException($e->getMessage());
        }
    }

    /**
     * 多维数组转对象
     *
     * @param object|array $array
     *
     * @return \stdClass
     */
    public static function arrayToObject(object|array $array): stdClass
    {
        $object = new stdClass();
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                if (isset($value[0])) { // 处理下标数组
                    $object->$key = self::arrayToObject($value); // 递归处理子数组
                } else {
                    $object->$key = self::arrayToObject($value); // 处理关联数组
                }
            } else {
                if (is_numeric($value)) {
                    $object->$key = $value + 0; // 将字符串转换为数字类型
                } else {
                    $object->$key = $value;
                }

            }
        }
        return $object;
    }

    /**
     * json转array
     *
     * @param string|array $input
     *
     * @return array
     */
    public static function jsonToArray(string|array|object $input): array
    {
        // 如果输入是数组，直接返回
        if (is_array($input)) {
            return $input;
        }

        // 如果输入是对象，转换为 JSON 字符串
        if (is_object($input)) {
            $input = json_encode($input);
        }

        // 解码 JSON 字符串
        $decoded = json_decode($input, true);

        // 检查 JSON 解码是否出错
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new InvalidArgumentException('Invalid JSON string provided: ' . json_last_error_msg());
        }

        return $decoded;
}

    /**
     * 参数过滤处理
     *
     * @param array|object $params
     * @param array        $rules 【'输入key','默认值','过滤值','重命名key'】
     *
     * @return array
     */
    public static function paramsFilter(array|object $params, array $rules): array
    {
        $params = is_object($params) ? (array)$params : $params;//兼容数组对象参数
        /** @var TYPE_NAME $filteredParams */
        $filteredParams = [];
        foreach ($rules as $rule) {
            $inputKey     = $rule[0] ?? null;
            $defaultValue = $rule[1] ?? null;
            $filterValue  = $rule[2] ?? null;
            $replaceKey   = $rule[3] ?? null;
            if (empty($inputKey)) {
                continue;
            }
            //优先传入参数
            if (array_key_exists($inputKey, $params)) {
                $paramValue = $params[$inputKey];
            } else {
                $paramValue = $defaultValue;
            }

            // 参数值过滤
            if (isset($filterValue) && !empty($filterValue)) {
                switch ($filterValue) {
                    case 'string':
                        $paramValue = (string)$paramValue;
                        break;
                    case 'int':
                        $paramValue = (int)$paramValue;
                        break;
                    case 'float':
                        $paramValue = (float)$paramValue;
                        break;
                    case 'bool':
                        $paramValue = filter_var($paramValue, FILTER_VALIDATE_BOOLEAN);
                        break;
                    default:
                        // 自定义过滤器函数
                        if (is_callable($filterValue)) {
                            $paramValue = call_user_func($filterValue, $paramValue);
                        } else {
                            throw new InvalidArgumentException("Invalid filter specified for param {$inputKey}.");
                        }
                }
            }

            // 替换参数键名（可选）
            $outputKey                  = $replaceKey ?? $inputKey;
            $filteredParams[$outputKey] = $paramValue;
        }

        return $filteredParams;
    }

    public static function sortItems(array $items, string $key, bool $reverse = false): array
    {
        usort($items, function ($a, $b) use ($key, $reverse) {
            $valueA = is_object($a) ? $a->$key : $a[$key] ?? null;
            $valueB = is_object($b) ? $b->$key : $b[$key] ?? null;

            if ($valueA === $valueB) {
                return 0;
            }

            return ($valueA < $valueB) ? ($reverse ? 1 : -1) : ($reverse ? -1 : 1);
        });

        return $items;

    }

    public static function normalize($data, $separator = ','): array
    {
        if (is_array($data)) {
            return $data;
        } elseif (is_int($data) || is_string($data)) {
            return explode($separator, $data);
        } else {
            throw new InvalidArgumentException('Data must be a string or an array.');
        }
    }

    public static function filterArray($array): array
    {
        return array_filter($array, function ($value) {
            return $value !== null && $value !== '';
        });
    }

}
