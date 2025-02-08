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

namespace app\model\system\config;

use app\common\traits\JwtAuthModelTrait;
use app\common\traits\ModelTrait;
use app\model\BaseModel;
use think\db\Query;
use think\Model;


/**
 *
 * 配置分类模型
 * @author Mr.April
 * @since  1.0
 */
class SystemConfigTab extends BaseModel
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
    protected $name = 'system_config_tab';

    /**
     * 新增自动创建字符串id
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
     * 状态搜索器
     *
     * @param \think\db\Query $query
     * @param                 $value
     */
    public function searchStatusAttr(Query $query, $value)
    {
        if ($value != '') {
            $query->where('status', $value);
        }
    }

    /**
     * pid搜索器
     *
     * @param \think\db\Query $query
     * @param                 $value
     */
    public function searchPidAttr(Query $query, $value)
    {
        if (is_array($value)) {
            $query->whereIn('pid', $value);
        } else {
            $value && $query->where('pid', $value);
        }
    }

    /**
     * 类型搜索器
     *
     * @param \think\db\Query $query
     * @param                 $value
     */
    public function searchTypeAttr(Query $query, $value)
    {
        $query->where('status', 1);
        if ($value > -1) {
            $query->where(['type' => $value, 'pid' => 0]);
        }
    }

    /**
     * 分类名称搜索器
     *
     * @param \think\db\Query $query
     * @param                 $value
     */
    public function searchTitleAttr(Query $query, $value)
    {
        $query->whereLike('title', '%' . $value . '%');
    }

}
