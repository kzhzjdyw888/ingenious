<?php

namespace app\model\system\setting;

use app\common\traits\JwtAuthModelTrait;
use app\common\traits\ModelTrait;
use app\model\BaseModel;

class SystemRolePost extends BaseModel
{

    use ModelTrait;

    protected $connection = 'rbac';

    /**
     * 模型名称
     *
     * @var string
     */
    protected $name = 'role_post';

    public function searchPostIdInAttr(Query $query, $value, $data)
    {
        if ($value) {
            $query->whereIn('post_id',$value);
        }
    }

}
