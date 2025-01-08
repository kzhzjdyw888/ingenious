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

namespace madong\ingenious\model\logicflow;


use madong\ingenious\libs\traits\DynamicPropsTrait;
use madong\ingenious\model\BaseModel;

class LfModel extends BaseModel
{
    use DynamicPropsTrait;

    private string $type = ''; // 流程定义分类
    private string $expire_time = '';// 过期时间（常量或变量）
    private string $instance_url = ''; // 启动实例的url,前后端分离后，定义为路由名或或路由地址
    private string $instance_no_class = ''; // 启动流程时，流程实例的流水号生成类
    private string $pre_interceptors = ''; // 节点前置拦截器
    private string $post_interceptors = ''; // 节点后置拦截器
    private array|object $nodes = []; // 节点集合
    private array|object $edges = []; // 边集合
}
