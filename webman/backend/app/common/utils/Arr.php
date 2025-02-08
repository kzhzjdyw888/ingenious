<?php

namespace app\common\utils;

/**
 * 操作数组帮助类
 * Class Arr
 *
 * @package crmeb\utils
 */
class Arr
{
    /**
     * 对数组增加默认值
     *
     * @param array $keys
     * @param array $configList
     *
     * @return array
     */
    public static function getDefaultValue(array $keys, array $configList = []): array
    {
        $value = [];
        foreach ($keys as $val) {
            if (is_array($val)) {
                $k = $val[0] ?? '';
                $v = $val[1] ?? '';
            } else {
                $k = $val;
                $v = '';
            }
            $value[$k] = $configList[$k] ?? $v;
        }
        return $value;
    }

    /**
     * 获取layui菜单列表
     *
     * @param array|object $data
     *
     * @return array
     */
    public static function getMenuLayuiViewList(array|object $data): array
    {
        $arr = new Arr();
        return $arr->toLayuiUi($data);
    }

    /**
     * 转化layuiUi需要的key值
     *
     * @param array|object $rules
     * @param int          $type
     * @param null         $setKeysById
     *
     * @return array
     */
    public function toLayuiUi(array|object $rules, int $type = 1, $setKeysById = null): array
    {
        $retMenu = []; //返回值（也是顶层menu）
        static $tmpMenu = []; // 按id为key的menu 解决作用域问题静态处理
        foreach ($rules as $r) {
            if ($type === 1 && $r['auth_type'] !== 1) {
                //api
                continue;
            }
            if ($r['is_show'] !== 1) { //是否显示
                continue;
            }

            //制作菜单：
            if (!isset($tmpMenu[$r['id']])) { //如果还不存在，则创建
                $tmpMenu[$r['id']] = ['type' => 1];
            }
            $tmpMenu[$r['id']]['id']    = $r['id'];
            $tmpMenu[$r['id']]['title'] = $r['menu_name'];
            $tmpMenu[$r['id']]['icon']  = 'layui-icon ' . $r['icon'];
//            $tmpMenu[$r['id']]['openType'] = "_iframe";
            $tmpMenu[$r['id']]['openType'] = "_component";//1._component  2._iframe 3._blank 4._layer
            $tmpMenu[$r['id']]['href']     = $r['menu_path'];

            //加入额外的keys
            if ($setKeysById) {
                //如果这个id在给定的Id列表内
                if (in_array($r['id'], $setKeysById[0])) {
                    $tmpMenu[$r['id']][$setKeysById[1]] = $setKeysById[2]; //给特定key赋值
                }
            }

            if ($r['pid'] === '-1') { //顶层
                $retMenu[] = &$tmpMenu[$r['id']];
                continue;
            }
            if (!isset($tmpMenu[$r['pid']])) { //子菜单
                $tmpMenu[$r['pid']] = ['children' => []]; //创建父菜单
            } elseif (!isset($tmpMenu[$r['pid']]['children'])) {
                $tmpMenu[$r['pid']]['children'] = [];
            }
            $tmpMenu[$r['pid']]['type']       = 0; //菜单
            $tmpMenu[$r['pid']]['children'][] = &$tmpMenu[$r['id']]; //加入父菜单 children
        }
        $tmpMenu = [];
        return $retMenu;
    }

    /**
     * 获取树型菜单
     *
     * @param     $data
     * @param int $pid
     * @param int $level
     *
     * @return array
     */
    public static function getTree($data, $pid = 0, $level = 1)
    {
        $childs   = self::getChild($data, $pid, $level);
        $dataSort = array_column($childs, 'sort');
        array_multisort($dataSort, SORT_DESC, $childs);
        foreach ($childs as $key => $navItem) {
            $resChild = self::getTree($data, $navItem['id']);
            if (null != $resChild) {
                $childs[$key]['children'] = $resChild;
            }
        }
        return $childs;
    }

    /**
     * 递归数据
     *
     * @param array      $data
     * @param int|string $pid
     * @param string     $field1
     * @param string     $field2
     * @param string     $field3
     *
     * @return array
     */
    public static function tree(array $data, int|string $pid = 0, string $field1 = 'id', string $field2 = 'pid', string $field3 = 'children'): array
    {
        $arr = [];
        foreach ($data as $k => $v) {
            if ($v[$field2] == $pid) {
                $v[$field3] = self::tree($data, $v[$field1], $field1, $field2, $field3);
                $arr[]      = $v;
            }
        }
        return $arr;
    }

    /**
     * 获取子菜单
     *
     * @param $arr
     * @param $id
     * @param $lev
     *
     * @return array
     */
    private static function getChild(&$arr, $id, $lev)
    {
        $child = [];
        foreach ($arr as $value) {
            if ($value['pid'] == $id) {
                $value['level'] = $lev;
                $child[]        = $value;
            }
        }
        return $child;
    }

    /**
     * 格式化数据
     *
     * @param array $array
     * @param       $value
     * @param int   $default
     *
     * @return mixed
     */
    public static function setValeTime(array $array, $value, $default = 0)
    {
        foreach ($array as $item) {
            if (!isset($value[$item]))
                $value[$item] = $default;
            else if (is_string($value[$item]))
                $value[$item] = (float)$value[$item];
        }
        return $value;
    }

    /**
     * 获取二维数组中某个值的集合重新组成数组,并判断数组中的每一项是否为真
     *
     * @param array  $data
     * @param string $filed
     *
     * @return array
     */
    public static function getArrayFilterValeu(array $data, string $filed)
    {
        return array_filter(array_unique(array_column($data, $filed)), function ($item) {
            if ($item) {
                return $item;
            }
        });
    }

    /**
     * 数组转字符串去重复
     *
     * @param array $data
     *
     * @return false|string[]
     */
    public static function unique(array $data)
    {
        return array_unique(explode(',', implode(',', $data)));
    }

    /**
     * 获取数组中去重复过后的指定key值
     *
     * @param array  $list
     * @param string $key
     *
     * @return array
     */
    public static function getUniqueKey(array $list, string $key)
    {
        return array_unique(array_column($list, $key));
    }

    /**
     * 获取数组中随机值
     *
     * @param array $data
     *
     * @return bool|mixed
     */
    public static function getArrayRandKey(array $data)
    {
        if (!$data) {
            return false;
        }
        $mun = rand(0, count($data));
        if (!isset($data[$mun])) {
            return self::getArrayRandKey($data);
        }
        return $data[$mun];
    }

    /**
     * 选出列字符串
     *
     * @param array  $array
     * @param string $key
     * @param string $index
     *
     * @return string
     */
    public static function extractStr(array $array, string $key, string $index): string
    {
        $names = [];
        foreach ($array as $item) {
            if (isset($item[$key][$index])) {
                $names[] = $item[$key][$index];
            }
        }
        return implode(',', $names);
    }

    /**
     * 多个一维索引型数组合并并去重
     *
     * @param ...$arrays
     *
     * @return array
     */
    public static function mergeAndUniqueArrays(...$arrays): array
    {
        $mergedArray = array_merge(...$arrays);
        return array_values(array_unique($mergedArray));
    }

    /**
     * 多维数组去重并重置KEY
     *
     * @param $array
     *
     * @return array
     */
    public static function uniqueArrayValues(&$array): array
    {
        $result = array();
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $value = self::uniqueArrayValues($value);
            }
            if (is_array($value) && count($value) > 0) {
                $value = array_values($value);
            }
            if (!in_array($value, $result)) {
                $result[$key] = $value;
            }
        }
        return $result;
    }
}
