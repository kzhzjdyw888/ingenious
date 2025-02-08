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

/**
 * 系统配置模型
 *
 * @author Mr.April
 * @since  1.0
 */
class SystemConfig extends BaseModel
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
    protected $name = 'system_config';

    /**
     * 模型ID触发器
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
     * 菜单名搜索器
     *
     * @param \think\db\Query $query
     * @param                 $value
     */
    public function searchMenuNameAttr(Query $query, $value)
    {
        if (is_array($value)) {
            $query->whereIn('menu_name', $value);
        } else {
            if (!empty($value)) {
                $query->where('menu_name', $value);
            }
        }
    }

    /**
     * Info搜索
     * @param \think\db\Query $query
     * @param                 $value
     */
    public function searchInfoAttr(Query $query, $value)
    {
        if (!empty($value)) {
            $query->whereLike('info', $value . '%');
        }
    }

    /**
     * tab id 搜索
     *
     * @param \think\db\Query $query
     * @param                 $value
     */
    public function searchTabIdAttr(Query $query, $value)
    {
        if (!empty($value)) {
            $query->where('config_tab_id', $value);
        }
    }

    /**
     * 状态搜索器
     *
     * @param \think\db\Query $query
     * @param                 $value
     */
    public function searchStatusAttr(Query $query, $value)
    {
        $query->where('status', $value ?: 1);
    }

    /**
     * value搜索器
     *
     * @param \think\db\Query $query
     * @param                 $value
     */
    public function searchValueAttr(Query $query, $value)
    {
        $query->where('value', $value);
    }
}
