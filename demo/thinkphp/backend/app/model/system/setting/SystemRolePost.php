<?php

namespace app\model\system\setting;

use phoenix\basic\BaseModel;
use phoenix\traits\ModelTrait;
use think\db\Query;

class SystemRolePost extends BaseModel
{

    use ModelTrait;


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
