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

use DateTime;
use ingenious\core\ServiceContext;
use ingenious\enums\ProcessConst;
use ingenious\interface\ProcessUserInterface;
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
        $map = ServiceContext::findFirst(ProcessUserInterface::class);
        AssertHelper::notNull($map, '引擎未适配用户获取接口');
        $api = $map->findUser($operator);
        AssertHelper::notNull($api, '未知用户请核对后重试');
        $args->put(ProcessConst::USER_USER_ID, $operator);
        $args->put(ProcessConst::USER_USER_NAME, $api->user_name ?? '');
        $args->put(ProcessConst::USER_REAL_NAME, $api->real_name ?? '');
        $args->put(ProcessConst::USER_DEPT_ID, $api->dept_id ?? '');
        $args->put(ProcessConst::USER_DEPT_NAME, $api->dept_name ?? '');
        $args->put(ProcessConst::USER_POST_ID, $api->post_id ?? '');
        $args->put(ProcessConst::USER_POST_NAME, $api->post_name ?? '');
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

    /**
     * 解析日期
     *
     * @param string|int                 $expireTime
     * @param \ingenious\libs\utils\Dict $args
     *
     * @return \DateTime|null
     * @throws \Exception
     */
    public static function processTime(string|int $expireTime, Dict $args): ?DateTime
    {

        // 如果变量中存在，则使用变量中的时间
        if ($args->containsKey($expireTime) !== false) {
            $obj = $args->get($expireTime);
            if ($obj instanceof DateTime) {
                return $obj;
            } else if (is_int($obj)) {
                return new DateTime("@$obj");
            } else if (is_string($obj)) {
                return new DateTime($obj);
            }
        }

        if (!empty($expireTime)) {
            if (str_contains($expireTime, 's')) {
                return (new DateTime())->modify('+' . (int)substr($expireTime, 0, -1) . ' second');
            } else if (str_contains($expireTime, 'm')) {
                return (new DateTime())->modify('+' . (int)substr($expireTime, 0, -1) . ' minute');
            } else if (str_contains($expireTime, 'h')) {
                return (new DateTime())->modify('+' . (int)substr($expireTime, 0, -1) . ' hour');
            } else if (str_contains($expireTime, 'd')) {
                return (new DateTime())->modify('+' . (int)substr($expireTime, 0, -1) . ' day');
            }
            return new DateTime($expireTime);
        }
        return null;
    }
}
