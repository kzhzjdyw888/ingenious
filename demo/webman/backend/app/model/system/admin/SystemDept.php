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

use app\common\traits\JwtAuthModelTrait;
use app\common\traits\ModelTrait;
use app\model\BaseModel;
use think\db\Query;
use think\Model;

/**
 * 组织管理
 *
 * @author Mr.April
 * @since  1.0
 */
class SystemDept extends BaseModel
{
    use ModelTrait;


    /**
     * 数据表主键
     *
     * @var string
     */
    protected $pk = 'dept_id';

    /**
     * 模型名称
     *
     * @var string
     */
    protected $name = 'system_dept';

    protected array $insert = ['create_time', 'update_time'];

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
     * id搜索器
     *
     * @param \think\db\Query $query
     * @param                 $value
     */
    public function searchIdAttr(Query $query, $value)
    {
        if (is_array($value)) {
            $query->whereIn('role_id', $value);
        } else {
            $query->where('role_id', $value);
        }
    }

    /**
     * 身份管理搜索
     *
     * @param \think\db\Query $query
     * @param                 $value
     */
    public function searchDeptNameAttr(Query $query, $value)
    {
        if ($value) {
            $query->whereLike('dept_name', '%' . $value . '%');
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

    /**
     * Type搜索器
     *
     * @param \think\db\Query $query
     * @param                 $value
     */
    public function searchDeptTypeAttr(Query $query, $value)
    {
        if (!empty($value)) {
            $query->where('dept_type', $value);
        }
    }

}