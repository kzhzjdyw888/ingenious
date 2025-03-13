<?php

namespace app\common\enums;


/**
 * 任务调度类型枚举
 *
 * @author Mr.April
 * @since  1.0
 */
enum ScheduleTypeEnum: int
{
    case PROCESS_EXPIRE_TIME = 1; // 流程期望时间
    case PROCESS_TASK_EXPIRE_TIME = 2; // 任务期望时间
    case PROCESS_TASK_REMINDER_TIME = 3; // 消息提醒
    case PROCESS_TASK_REPEAT_TIME = 4; // 间隔时间
    case PROCESS_TASK_AUTO_EXECUTE = 5; // 自动运行
    case PROCESS_TASK_CALLBACK = 6; // 回调处理
    case PROCESS_INSTANCE_START_REMINDER = 7; // 启动流程消息提醒
    case PROCESS_INSTANCE_END_REMINDER = 8; // 结束流程消息提醒
    case PROCESS_TASK_START_REMINDER = 9; // 启动任务消息提醒
    case PROCESS_TASK_END_REMINDER = 10; // 结束任务消息提醒

    public function label(): string
    {
        return match ($this) {
            self::PROCESS_EXPIRE_TIME => '流程期望时间',
            self::PROCESS_TASK_EXPIRE_TIME => '任务期望时间',
            self::PROCESS_TASK_REMINDER_TIME => '消息提醒',
            self::PROCESS_TASK_REPEAT_TIME => '间隔时间',
            self::PROCESS_TASK_AUTO_EXECUTE => '自动运行',
            self::PROCESS_TASK_CALLBACK => '回调处理',
            self::PROCESS_INSTANCE_START_REMINDER => '启动流程消息提醒',
            self::PROCESS_INSTANCE_END_REMINDER => '结束流程消息提醒',
            self::PROCESS_TASK_START_REMINDER => '启动任务消息提醒',
            self::PROCESS_TASK_END_REMINDER => '结束任务消息提醒',
        };
    }
}
