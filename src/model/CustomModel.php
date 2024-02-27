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

namespace ingenious\model;

use ingenious\ex\LFlowException;
use ingenious\processor\IHandler;

/**
 * @method getDisplayName()
 * @method getNmae()
 */
class CustomModel {
    private string $clazz; // 类路径
    private string $methodName; // 方法名
    private string|object $args; // 入参
    private string $var; // 执行返回值的变量
    private object $invokeObject;

    public function __construct($clazz, $methodName, $args, $var) {
        $this->clazz = $clazz;
        $this->methodName = $methodName;
        $this->args = $args;
        $this->var = $var;
    }

    public function exec(array $execArgs) {
        if ($this->invokeObject == null) {
            $this->invokeObject = new $this->clazz();
        }

        if ($this->invokeObject instanceof IHandler) {
            $handler = $this->invokeObject;
            $handler->handle($execArgs);
        } else {
            $objects = $this->getArgs($execArgs, $this->args);
            $paramTypes = array_map(function($obj) { return get_class($obj); }, $objects);
            $method = self::getMethod($this->invokeObject, $this->methodName, $paramTypes);

            if ($method == null) {
                throw new LFlowException("无法找到方法名称: " . $this->methodName);
            }

            $returnValue = call_user_func_array(array($this->invokeObject, $this->methodName), $objects);

            if ($this->var != null) {
                $execArgs[$this->var] = $returnValue;
            }
        }
        // 这里可以调用其他服务或方法处理历史记录和过渡等逻辑
    }

    private function getArgs(array $execArgs, string $args) {
        if (empty($args)) {
            return [];
        }
        $argArray = explode(",", $args);
        $objects = [];
        foreach ($argArray as $arg) {
            if (isset($execArgs[$arg])) {
                $objects[] = $execArgs[$arg];
            } else {
                throw new Exception("参数缺失: " . $arg);
            }
        }
        return $objects;
    }
}
