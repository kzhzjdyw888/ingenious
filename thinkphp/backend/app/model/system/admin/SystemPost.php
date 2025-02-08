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

namespace app\model\system\admin;

use phoenix\basic\BaseModel;
use phoenix\traits\ModelTrait;
use phoenix\utils\Str;
use think\db\Query;
use think\Model;

/**
 * 职位管理
 *
 * @author Mr.April
 * @since  1.0
 */
class SystemPost extends BaseModel
{
    use ModelTrait;

    /**
     * 数据表主键
     *
     * @var string
     */
    protected $pk = 'post_id';

    /**
     * 模型名称
     *
     * @var string
     */
    protected $name = 'system_post';

    protected  $insert = ['create_time', 'update_time'];

    protected $type = [
        'create_time' => 'timestamp:Y-m-d H:i:s',
        'update_time' => 'timestamp:Y-m-d H:i:s',
        'delete_time' => 'timestamp:Y-m-d H:i:s',
    ];

    /**
     * 生成字符串id
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
     * 权限规格状态搜索器
     *
     * @param \think\db\Query $query
     * @param                 $value
     */
    public function searchStatusAttr(Query $query, $value)
    {
        if ($value != '') {
            $query->where('status', $value ?: 1);
        }
    }

    /**
     * post_code搜索器
     *
     * @param \think\db\Query $query
     * @param                 $value
     */
    public function searchPostCodeAttr(Query $query, $value)
    {
        if (!empty($value)) {
            $query->whereLike('post_code', '%' . $value . '%');
        }
    }

    /**
     * post_name搜索器
     *
     * @param \think\db\Query $query
     * @param                 $value
     */
    public function searchPostNameAttr(Query $query, $value)
    {
        if (!empty($value)) {
            $query->whereLike('post_name', '%' . $value . '%');
        }
    }

    /**
     * 关联部门搜索器
     *
     * @param \think\db\Query $query
     * @param                 $value
     */
    public function searchDeptIdAttr(Query $query, $value)
    {
        if (!empty($value)) {
            $query->where('dept_id', $value);
        }
    }

    /**
     * Pid搜索器
     *
     * @param \think\db\Query $query
     * @param                 $value
     */
    public function searchPidAttr(Query $query, $value)
    {
        $query->where('pid', $value ?? '-1');
    }
}
