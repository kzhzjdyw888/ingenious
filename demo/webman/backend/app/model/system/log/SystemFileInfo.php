<?php

namespace app\model\system\log;

use app\common\traits\JwtAuthModelTrait;
use app\common\traits\ModelTrait;
use app\model\BaseModel;

/**
 * @author 吴汐
 * @email 442384644@qq.com
 * @date 2023/04/07
 */
class SystemFileInfo extends BaseModel
{
    use ModelTrait;

    /**
     * 数据表主键
     * @var string
     */
    protected $pk = 'id';

    /**
     * 模型名称
     * @var string
     */
    protected $name = 'system_file_info';
}