<?php
/**
 *+------------------
 * Lflow
 *+------------------
 * Copyright (c) 2023~2030 gitee.com/liu_guan_qing All rights reserved.本版权不可删除，侵权必究
 *+------------------
 * Author: Mr.April(405784684@qq.com)
 *+------------------
 */

namespace ingenious\libs\utils;

use ingenious\enums\ProcessConst;
use ingenious\model\ProcessModel;

class ProcessFlowUtils
{
    private function __construct()
    {
    }

    /**
     * 将用户信息添加到参数中
     *
     * @param string                     $operator
     * @param \ingenious\libs\utils\Dict $args
     */
    public static function addUserInfoToArgs(string $operator, Dict &$args): void
    {
//        $data     = getUserInfo($operator);//未知方法
        $data     = [];
        $userInfo = (object)[
            ProcessConst::USER_USER_ID   => $data[ProcessConst::USER_USER_ID] ?? $operator,//用户id
            ProcessConst::USER_REAL_NAME => $data[ProcessConst::USER_REAL_NAME] ?? $operator,//用户名称
            ProcessConst::USER_DEPT_ID   => $data[ProcessConst::USER_DEPT_ID] ?? $operator,//用户部门ID
            ProcessConst::USER_DEPT_NAME => $data[ProcessConst::USER_DEPT_NAME] ?? $operator,//用户部门ID
            ProcessConst::USER_POST_ID   => $data[ProcessConst::USER_POST_ID] ?? $operator,//追加用户岗位ID
            ProcessConst::USER_POST_NAME => $data[ProcessConst::USER_POST_NAME] ?? $operator,//追加用户部门名称
        ];
        $args->putAll($userInfo);
    }

    /**
     * 添加自动生成标题
     *
     * @param string                     $processDefineDisplayName
     * @param \ingenious\libs\utils\Dict $args
     */
    public static function addAutoGenTitle(string $processDefineDisplayName, Dict &$args): void
    {
        // 申请人的xx流程-日期
        $format = "%s的%s-%s";
        $args->put(ProcessConst::AUTO_GEN_TITLE, sprintf($format, $args->get(ProcessConst::USER_REAL_NAME), $processDefineDisplayName, date("Y-m-d H:i")));
    }

    /**
     * 将参数转换为字典
     *
     * @param object|array $variable
     *
     * @return \ingenious\libs\utils\Dict|null
     */
    public static function variableToDict(object|array $variable): ?Dict
    {
        $dict = new Dict();
        $dict->putAll($variable);
        return $dict;
    }

    /**
     * 判断是否为第一个任务节点
     *
     * @param \ingenious\model\ProcessModel $processModel
     * @param string                        $taskName
     *
     * @return bool
     */
    public static function isFistTaskName(ProcessModel $processModel, string $taskName): bool
    {
        $isFirst = false;
        foreach ($processModel->getStart()->getOutputs() as $nodeModel) {
            if (strcasecmp($nodeModel->getTo(), $taskName) === 0) {
                $isFirst = true;
                break;
            }
        }
        return $isFirst;
    }

    /**
     * 筛选数组指定前缀list
     *
     * @param array  $data
     * @param string $prefix
     *
     * @return array
     */
    public static function filterByPrefix(array $data, string $prefix): array
    {
        return array_filter($data, function ($value, $key) use ($prefix) {
            return str_starts_with($key, $prefix);
        }, ARRAY_FILTER_USE_BOTH);
    }

    /**
     * @param object $data
     * @param string $prefix
     *
     * @return object
     */
    public static function filterObjectByPrefix(object $data, string $prefix): object
    {
        $result = [];
        foreach (get_object_vars($data) as $key => $value) {
            if (str_starts_with($key, $prefix)) {
                $result[$key] = $value;
            }
        }
        return (object)$result;
    }
}
