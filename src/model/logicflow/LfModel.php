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

namespace ingenious\model\logicflow;

use ingenious\libs\traits\DynamicMethodTrait;
use ingenious\model\BaseModel;

class LfModel extends BaseModel
{
    use DynamicMethodTrait;

    private string $type = ''; // 流程定义分类
    private string $expire_time = '';// 过期时间（常量或变量）
    private string $instance_url = ''; // 启动实例的url,前后端分离后，定义为路由名或或路由地址
    private string $instance_no_class = ''; // 启动流程时，流程实例的流水号生成类
    private string $pre_interceptors = ''; // 节点前置拦截器
    private string $post_interceptors = ''; // 节点后置拦截器
    private array|object $nodes = []; // 节点集合
    private array|object $edges = []; // 边集合
}
