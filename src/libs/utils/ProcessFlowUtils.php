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

use DateTime;
use Exception;
use madong\ingenious\core\ServiceContext;
use madong\ingenious\ex\LFlowException;
use madong\ingenious\interface\IDict;
use madong\ingenious\interface\IProcessUser;
use madong\ingenious\model\ProcessModel;
use madong\plugin\wf\enums\ProcessConstEnum;

class ProcessFlowUtils
{
    private function __construct()
    {
    }

    /**
     * 将用户信息添加到参数中
     *
     * @param string                                   $operator
     * @param \madong\ingenious\libs\utils\Dict $args
     */
    public static function addUserInfoToArgs(string $operator, IDict &$args): void
    {
        $map = ServiceContext::find(IProcessUser::class);
        AssertHelper::notNull($map, '引擎未适配用户获取接口');
        $api = $map->findUser($operator);
        AssertHelper::notNull($api, '未知用户请核对后重试');
        $args->put(ProcessConstEnum::USER_USER_ID->value, $operator);
        $args->put(ProcessConstEnum::USER_USER_NAME->value, $api->user_name ?? '');
        $args->put(ProcessConstEnum::USER_REAL_NAME->value, $api->real_name ?? '');
        $args->put(ProcessConstEnum::USER_DEPT_ID->value, $api->dept_id ?? '');
        $args->put(ProcessConstEnum::USER_DEPT_NAME->value, $api->dept_name ?? '');
        $args->put(ProcessConstEnum::USER_POST_ID->value, $api->post_id ?? '');
        $args->put(ProcessConstEnum::USER_POST_NAME->value, $api->post_name ?? '');
    }

    /**
     * 添加自动生成标题
     *
     * @param string                                   $processDefineDisplayName
     * @param \madong\ingenious\libs\utils\Dict $args
     */
    public static function addAutoGenTitle(string $processDefineDisplayName, IDict &$args): void
    {
        // 直接将 f_title 存入 args
        if ($args->containsKey('f_title')) {
            $args->put(ProcessConstEnum::AUTO_GEN_TITLE->value, $args->get('f_title'));
        } else {
            // 申请人的xx流程-日期
            $format = "%s的%s-%s";
            $args->put(ProcessConstEnum::AUTO_GEN_TITLE->value, sprintf($format,
                $args->get(ProcessConstEnum::USER_REAL_NAME->value),
                $processDefineDisplayName,
                date("Y-m-d H:i")
            ));
        }
    }

    /**
     * 将参数转换为字典
     * @param object|array|string $variable
     *
     * @return \madong\ingenious\interface\IDict|null
     */
    public static function variableToDict(object|array|string $variable): ?IDict
    {
        $dict = new Dict();
        if (is_string($variable)) {
            $decoded = json_decode($variable, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $variable = $decoded; // 如果解析成功，将其赋值为数组
            } else {
                throw new LFlowException('不是有效json，解析失败');
            }
        }
        if (is_array($variable)) {
            $dict->putAll($variable);
        } elseif (is_object($variable)) {
            $dict->putAll((array)$variable); // 将对象转换为数组
        }
        return $dict;
    }

    /**
     * 判断是否为第一个任务节点
     *
     * @param madong\ingenious\model\ProcessModel $processModel
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
     * 解析时间
     *
     * @param string|int                               $expireTime
     * @param \madong\ingenious\interface\IDict $args
     *
     * @return \DateTime|null
     * @throws \Exception
     */
    public static function processTime(string|int $expireTime, IDict $args): ?DateTime
    {
        // 如果变量中存在，则使用变量中的时间
        if ($args->containsKey($expireTime)) {
            $obj = $args->get($expireTime);
            if ($obj instanceof DateTime) {
                return $obj;
            } elseif (is_int($obj)) {
                return new DateTime("@$obj");
            } elseif (is_string($obj)) {
                return new DateTime($obj);
            }
        }

        // 处理 expireTime
        if (!empty($expireTime)) {
            // 处理带单位的时间字符串
            if (preg_match('/^(\d+)([smhd])$/', $expireTime, $matches)) {
                $value = (int)$matches[1];
                $unit  = $matches[2];

                switch ($unit) {
                    case 's':
                        return (new DateTime())->modify("+{$value} second");
                    case 'm':
                        return (new DateTime())->modify("+{$value} minute");
                    case 'h':
                        return (new DateTime())->modify("+{$value} hour");
                    case 'd':
                        return (new DateTime())->modify("+{$value} day");
                }
            }

            // 如果没有单位，尝试直接解析为 DateTime
            if (is_string($expireTime)) {
                try {
                    return new DateTime($expireTime);
                } catch (Exception $e) {
                    return null;
                }
            }
        }

        return null; // 如果没有有效的时间，返回 null
    }

}
