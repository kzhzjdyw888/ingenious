<?php

/**
 *+------------------
 * Ingenious
 *+------------------
 * Copyright (c) https://gitee.com/ingenstream/ingenious  All rights reserved. 本版权不可删除，侵权必究
 *+------------------
 * Author: Mr. April (405784684@qq.com)
 *+------------------
 * Software Registration Number: 2024SR0694589
 * Official Website: http://www.ingenstream.cn
 */
namespace ingenious\libs\base;

use think\Model;

/**
 * 模型基础类
 *
 * @author Mr.April
 * @since  1.0
 * @method getTableFields()
 */
class BaseModel extends model
{

    /**
     * 获取模型定义的字段列表
     *
     * @return mixed
     */
    public function getFields(): mixed
    {
        return $this->getTableFields();
    }

    /**
     * 获取模型定义的数据库表名【全称】
     */
    public static function getTableName(): string
    {
        $self = (new static());
        return $self->getConfig('prefix') . $self->name;
    }

}
