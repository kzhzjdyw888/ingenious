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

/**
 * 菜单规则模型
 * Class SystemMenus
 *
 * @package app\model\system
 */
class SystemMenus extends BaseModel
{
    use ModelTrait;


    /**
     * 数据表主键
     *
     * @var string
     */
    protected $pk = 'id';

    protected string $pkType = 'string';

    /**
     * 模型名称
     *
     * @var string
     */
    protected $name = 'system_menus';

    protected array $insert = ['create_time', 'update_time'];

    protected $type = [
        'create_time' => 'timestamp:Y-m-d H:i:s',
        'update_time' => 'timestamp:Y-m-d H:i:s',
    ];

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
     * 参数修改器
     *
     * @param $value
     *
     * @return false|string
     */
    public function setParamsAttr($value): bool|string
    {
        $value  = $value ? explode('/', $value) : [];
        $params = array_chunk($value, 2);
        $data   = [];
        foreach ($params as $param) {
            if (isset($param[0]) && isset($param[1])) $data[$param[0]] = $param[1];
        }
        return json_encode($data);
    }

    /**
     * 参数获取器
     *
     * @param $_value
     *
     * @return mixed
     */
    public function getParamsAttr($_value): mixed
    {
        return json_decode($_value, true);
    }

    /**
     * pid获取器
     *
     * @param $value
     *
     * @return mixed|string
     */
    public function getPidStrAttr($value): mixed
    {
        return !$value ? '顶级' : $this->where('pid', $value)->value('menu_name');
    }

    /**
     * 默认条件查询器
     *
     * @param \think\db\Query $query
     */
    public function searchDefaultAttr(Query $query)
    {
        $query->where(['is_show' => 1, 'access' => 1]);
    }

    /**
     * 是否显示搜索器
     *
     * @param \think\db\Query $query
     * @param                 $value
     */
    public function searchIsShowAttr(Query $query, $value)
    {
        if ($value != '') {
            $query->where('is_show', $value);
        }
    }

    /**
     * 是否删除搜索器
     *
     * @param \think\db\Query $query
     * @param                 $value
     */
    public function searchIsDelAttr(Query $query, $value)
    {
        $query->where('delete_time', $value);
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
     * 规格搜索器
     *
     * @param \think\db\Query $query
     * @param                 $value
     */
    public function searchRuleAttr(Query $query, $value)
    {
        $query->whereIn('id', $value)->where('delete_time', null);
    }

    /**
     * 搜索菜单
     *
     * @param \think\db\Query $query
     * @param                 $value
     */
    public function searchKeywordAttr(Query $query, $value)
    {
        if ($value != '') {
            $query->whereLike('menu_name|id|pid', "%$value%");
        }
    }

    /**
     * 方法搜索器
     *
     * @param \think\db\Query $query
     * @param                 $value
     */
    public function searchActionAttr(Query $query, $value)
    {
        $query->where('action', $value);
    }

    /**
     * 控制器搜索器
     *
     * @param \think\db\Query $query
     * @param                 $value
     */
    public function searchControllerAttr(Query $query, $value)
    {
        $query->where('controller', lcfirst($value));
    }

    /**
     * 访问地址搜索器
     *
     * @param \think\db\Query $query
     * @param                 $value
     */
    public function searchUrlAttr(Query $query, $value)
    {
        $query->where('api_url', $value);
    }

    /**
     * 参数搜索器
     *
     * @param \think\db\Query $query
     * @param                 $value
     */
    public function searchParamsAttr(Query $query, $value)
    {
        $query->where(function ($query) use ($value) {
            $query->where('params', $value)->whereOr('params', "'[]'");
        });
    }

    /**
     * 权限标识搜索器
     *
     * @param \think\db\Query $query
     * @param                 $value
     */
    public function searchUniqueAttr(Query $query, $value)
    {
        $query->where('delete_time', null);
        if ($value) {
            $query->whereIn('id', $value);
        }
    }

    /**
     * 菜单规格搜索
     *
     * @param \think\db\Query $query
     * @param                 $value
     */
    public function searchRouteAttr(Query $query, $value)
    {
        $query->where('auth_type', 1)->where('delete_time', null);
        if ($value) {
            $query->whereIn('id', $value);
        }
    }

    /**
     * Id搜索器
     *
     * @param \think\db\Query $query
     * @param                 $value
     */
    public function searchIdAttr(Query $query, $value)
    {
        $query->whereIn('id', $value);
    }

    /**
     * is_show_path
     *
     * @param \think\db\Query $query
     * @param                 $value
     */
    public function searchIsShowPathAttr(Query $query, $value)
    {
        $query->where('is_show_path', $value);
    }

    /**
     * auth_type
     *
     * @param \think\db\Query $query
     * @param                 $value
     */
    public function searchAuthTypeAttr(Query $query, $value)
    {
        if ($value !== '') {
            if ($value == 3) {
                $query->whereIn('auth_type', [1, 3]);
            } else {
                $query->where('auth_type', $value);
            }
        }
    }
}
