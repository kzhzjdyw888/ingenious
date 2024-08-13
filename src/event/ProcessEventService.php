<?php
/**
 * Copyright (C) 2024 Ingenstream
 * This software is licensed under the Apache-2.0 license.
 * A copy of the license can be found at http://www.apache.org/licenses/LICENSE-2.0
 * Official Website: http://www.ingenstream.cn
 * Author: Mr. April <405784684@qq.com>
 * Project: Ingenious
 * Repository: https://gitee.com/ingenstream/ingenious
 */

namespace ingenious\event;

class ProcessEventService {
    private static array $listeners = [];
    private static array $events = [];

    /**
     * 添加事件监听器
     *
     * @param ProcessEventListener $listener 事件监听器实例
     * @return void
     */
    public static function addListener(ProcessEventListener $listener): void {
        self::$listeners[] = $listener;
    }

    /**
     * 移除事件监听器
     *
     * @param ProcessEventListener $listener 事件监听器实例
     * @return void
     */
    public static function removeListener(ProcessEventListener $listener): void {
        $index = array_search($listener, self::$listeners);
        if ($index !== false) {
            unset(self::$listeners[$index]);
        }
    }

    /**
     * 触发事件并通知所有注册的事件监听器
     *
     * @param ProcessEvent $event 事件实例
     * @return void
     */
    public static function triggerEvent(ProcessEvent $event): void {
        self::$events[] = $event; // 记录事件
//        foreach (self::$listeners as $listener) {
//            $listener->onEvent($event);
//        }
        // 调用处理类处理事件
        $eventHandler = new ProcessEventHandler();
        $eventHandler->handle($event);
    }

    /**
     * 发布通知
     *
     * @param string $eventType 事件类型
     * @param mixed $sourceId 事件源
     * @return void
     */
    public static function publishNotification(string $eventType, mixed $sourceId): void {
        $event = new ProcessEvent($eventType, $sourceId);
        self::triggerEvent($event);
    }

    /**
     * 获取所有事件列表
     *
     * @return array
     */
    public static function getAllEvents(): array {
        return self::$events;
    }

    /**
     * 获取指定类型的事件列表
     *
     * @param string $eventType 事件类型
     * @return array
     */
    public static function getEventsByType(string $eventType): array {
        $filteredEvents = [];
        foreach (self::$events as $event) {
            if ($event->getEventType() === $eventType) {
                $filteredEvents[] = $event;
            }
        }
        return $filteredEvents;
    }
}

