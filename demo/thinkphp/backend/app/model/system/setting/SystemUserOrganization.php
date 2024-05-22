<?php
namespace app\model\system\setting;

use phoenix\basic\BaseModel;
use phoenix\traits\ModelTrait;

class SystemUserOrganization extends BaseModel
{

    use ModelTrait;

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
