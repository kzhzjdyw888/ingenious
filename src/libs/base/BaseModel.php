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
