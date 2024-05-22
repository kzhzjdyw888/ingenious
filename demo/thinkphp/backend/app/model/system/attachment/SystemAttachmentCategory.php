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

namespace app\model\system\attachment;

use phoenix\basic\BaseModel;
use phoenix\traits\ModelTrait;
use phoenix\utils\Str;
use think\db\Query;
use think\Model;

/**
 *
 * 附件管理分类模型
 * @author Mr.April
 * @since  1.0
 */
class SystemAttachmentCategory extends BaseModel
{
    use ModelTrait;

    /**
     * 数据表主键
     *
     * @var string
     */
    protected $pk = 'id';

    /**
     * 模型名称
     *
     * @var string
     */
    protected $name = 'system_attachment_category';

    /**
     * ID 生成
     *
     * @param $model
     *
     * @return void
     */
    protected static function onBeforeInsert($model): void
    {
        $uuid                = !empty($model->{$model->pk}) ? $model->{$model->pk} : Str::uuid();
        $model->{$model->pk} = $uuid;
    }

    /**
     * 附件分类昵称搜索器
     *
     * @param \think\db\Query $query
     * @param                 $value
     */
    public function searchNameAttr(Query $query, $value)
    {
        if ($value != '') $query->where('name', 'like', '%' . $value . '%');
    }

    /**
     * pid搜索器
     *
     * @param \think\db\Query $query
     * @param                 $value
     */
    public function searchPidAttr(Query $query, $value)
    {
        if ($value !== '') $query->where('pid', $value);
    }

}
