<?php
// +----------------------------------------------------------------------
// | CRMEB [ CRMEB赋能开发者，助力企业发展 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016~2023 https://www.crmeb.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed CRMEB并不是自由软件，未经许可不能去掉CRMEB相关版权
// +----------------------------------------------------------------------
// | Author: CRMEB Team <admin@crmeb.com>
// +----------------------------------------------------------------------

namespace phoenix\basic;

use phoenix\traits\ModelTrait;
use Ramsey\Uuid\Uuid;
use think\Model;

/**
 * Class BaseModel
 *
 * @package crmeb\basic
 * @mixin ModelTrait
 */
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
