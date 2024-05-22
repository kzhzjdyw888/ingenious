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

use app\common\traits\JwtAuthModelTrait;
use app\common\traits\ModelTrait;
use app\model\BaseModel;
use think\db\Query;
use think\Model;

/**
 * 附件管理模型
 *
 * @author Mr.April
 * @since  1.0
 */
class SystemAttachment extends BaseModel
{
    use ModelTrait;

    /**
     * 数据表主键
     *
     * @var string
     */
    protected $pk = 'att_id';

    /**
     * 模型名称
     *
     * @var string
     */
    protected $name = 'system_attachment';

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
     * 图片类型搜索器
     *
     * @param \think\db\Query $query
     * @param                 $value
     */
    public function searchModuleTypeAttr(Query $query, $value)
    {
        $query->where('module_type', $value ?: 1);
    }

    /**
     * pid搜索器
     *
     * @param \think\db\Query $query
     * @param                 $value
     */
    public function searchPidAttr(Query $query, $value)
    {
        if ($value) $query->where('pid', $value);
    }

    /**
     * name模糊搜索
     *
     * @param \think\db\Query $query
     * @param                 $value
     */
    public function searchLikeNameAttr(Query $query, $value)
    {
        if ($value) $query->where('name', 'LIKE', "$value%");
    }

    /**
     * real_name模糊搜索
     *
     * @param \think\db\Query $query
     * @param                 $value
     */
    public function searchRealNameAttr(Query $query, $value)
    {
        if ($value != '') $query->where('real_name', 'LIKE', "%$value%");
    }
}
