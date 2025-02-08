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

namespace app\model;

use think\Model;

class BaseModel extends Model
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

    public static function getTableNameNoPrefix(): string
    {
        $self = (new static());
        return $self->name;
    }

}
