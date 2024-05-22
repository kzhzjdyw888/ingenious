<?php
namespace app\model\system\setting;

use app\common\traits\JwtAuthModelTrait;
use app\common\traits\ModelTrait;
use app\model\BaseModel;

class SystemUserOrganization extends BaseModel
{

    use ModelTrait;

    protected $connection = 'rbac';

    /**
     * 模型名称
     *
     * @var string
     */
    protected $name = 'user_organization';


    // 定义与Organization模型的多对一关联关系0808
    public function organization(): \think\model\relation\BelongsTo
    {
        return $this->belongsTo(SystemOrganization::class, 'org_id');
    }

}
