<?php

use app\services\system\config\SystemConfigServices;
use app\services\system\config\SystemGroupDataServices;

//if (!function_exists('sys_config')) {
//    /**
//     * 获取系统单个配置
//     *
//     * @param string     $name
//     * @param string|int $default
//     *
//     * @return string
//     */
//    function sys_config(string $name, string|int $default = '')
//    {
//        if (empty($name))
//            return $default;
//        $services  = \support\Container::make(SystemConfigServices::class);
//        $sysConfig = $services->get($name);
//        if (is_array($sysConfig)) {
//            foreach ($sysConfig as &$item) {
//                if (strpos($item, '/uploads/system/') !== false || strpos($item, '/statics/system_images/') !== false) $item = set_file_url($item);
//            }
//        } else {
//            if (strpos($sysConfig, '/uploads/system/') !== false || strpos($sysConfig, '/statics/system_images/') !== false) $sysConfig = set_file_url($sysConfig);
//        }
//        $config = is_array($sysConfig) ? $sysConfig : trim($sysConfig);
//        if ($config === '' || $config === false) {
//            return $default;
//        } else {
//            return $config;
//        }
//    }
//}
//
//if (!function_exists('sys_data')) {
//    /**
//     * 获取系统单个配置
//     *
//     * @param string $name
//     * @param int    $limit
//     *
//     * @return string
//     */
//    function sys_data(string $name, int $limit = 0): string
//    {
//        $services = \support\Container::make(SystemGroupDataServices::class);
//        return $services->getData($name, $limit);
//    }
//}
//
//if (!function_exists('get_phoenix_version')) {
//    /**
//     * 获取系统版本号
//     *
//     * @param string $default
//     *
//     * @return string
//     */
//    function get_phoenix_version(string $default = 'v1.0.0'): string
//    {
//        try {
//            $version = parse_ini_file(app()->getRootPath() . '.version');
//            return $version['version'] ?? $default;
//        } catch (\Throwable $e) {
//            return $default;
//        }
//    }
//}
//
//if (!function_exists('get_file_link')) {
//    /**
//     * 获取文件带域名的完整路径
//     *
//     * @param string $link
//     *
//     * @return string
//     */
//    function get_file_link(string $link): string
//    {
//        if (!$link) {
//            return '';
//        }
//        if (strstr('http', $link) === false) {
////            return request()->domain() . $link;
//            return '';
//        } else {
//            return $link;
//        }
//    }
//}