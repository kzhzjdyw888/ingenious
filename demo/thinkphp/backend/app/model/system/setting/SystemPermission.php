<?php

namespace app\model\system\setting;

use phoenix\basic\BaseModel;
use phoenix\traits\ModelTrait;

/**
 * 管理员权限规则
 * Class Permission
 *
 * @package app\model\admin
 */
class SystemPermission extends BaseModel
{
    use ModelTrait;


    /**
     * 数据表主键
     *
     * @var string
     */
    protected $pk = 'permission_id';

    /**
     * 模型名称
     *
     * @var string
     */
    protected $name = 'permission';

    //软删除，查询时会自动加上 xxx IS NULL
    use \think\model\concern\SoftDelete;

    protected string $deleteTime = 'delete_time';

    /**
     * 删除当前菜单第子级菜单
     *
     * @param $menuId
     *
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function deleteSubMenu($menuId)
    {
        $subMenus = $this->where('parent_id', $menuId)->select();
        foreach ($subMenus as $subMenu) {
            $this->deleteSubMenu($subMenu['id']);
            $subMenu->delete();
        }
    }

    /**
     * 参数修改器
     *
     * @param $value
     *
     * @return false|string
     */
    public function setParamsAttr($value)
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
    public function getParamsAttr($_value)
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
    public function getPidStrAttr($value)
    {
        return !$value ? '顶级' : $this->where('parent_id', $value)->value('menu_name');
    }

    /**
     * 默认条件查询器
     *
     * @param Model $query
     * @param       $value
     */
    public function searchDefaultAttr($query)
    {
        $query->where(['is_show' => 1, 'status' => 1]);
    }

    /**
     * 是否显示搜索器
     *
     * @param Model $query
     * @param       $value
     */
    public function searchIsShowAttr($query, $value)
    {
        if ($value != '') {
            $query->where('is_show', $value);
        }
    }

    /**
     * 是否删除搜索器
     *
     * @param Model $query
     * @param       $value
     */
    public function searchIsDelAttr($query, $value)
    {
        $query->where('delete_time', $value);
    }

    /**
     * Pid搜索器
     *
     * @param Model $query
     * @param       $value
     */
    public function searchPidAttr($query, $value)
    {
        $query->where('parent_id', $value ?? '-1');
    }

    /**
     * 规格搜索器
     *
     * @param Model $query
     * @param       $value
     */
    public function searchRuleAttr($query, $value)
    {
        $query->whereIn('permission_id', $value)->where('delete_time', null)->whereOr('parent_id', '-1');
    }

    /**
     * 搜索菜单
     *
     * @param Model $query
     * @param       $value
     */
    public function searchKeywordAttr($query, $value)
    {
        if ($value != '') {
            $query->whereLike('menu_name|permission_id|parent_id', "%$value%");
        }
    }

    /**
     * 方法搜索器
     *
     * @param Model $query
     * @param       $value
     */
    public function searchActionAttr($query, $value)
    {
        $query->where('action', $value);
    }

    /**
     * 控制器搜索器
     *
     * @param Model $query
     * @param       $value
     */
    public function searchControllerAttr($query, $value)
    {
        $query->where('controller', lcfirst($value));
    }

    /**
     * 访问地址搜索器
     *
     * @param Model $query
     * @param       $value
     */
    public function searchUrlAttr($query, $value)
    {
        $query->where('api_path', $value);
    }

    /**
     * 参数搜索器
     *
     * @param Model $query
     * @param       $value
     */
    public function searchParamsAttr($query, $value)
    {
        $query->where(function ($query) use ($value) {
            $query->where('params', $value)->whereOr('params', "'[]'");
        });
    }

    /**
     * 权限标识搜索器
     *
     * @param Model $query
     * @param       $value
     */
    public function searchUniqueAttr($query, $value)
    {
        $query->where('delete_time', null);
        if ($value) {
            $query->whereIn('permission_id', $value);
        }
    }

    /**
     * 菜单规格搜索
     *
     * @param Model $query
     * @param       $value
     */
    public function searchRouteAttr($query, $value)
    {
        $query->where('auth_type', 1);//菜单
        if ($value) {
            $query->whereIn('permission_id', $value);
        }
    }

    /**
     * Id搜索器
     *
     * @param Model $query
     * @param       $value
     */
    public function searchIdAttr($query, $value)
    {
        $query->whereIn('permission_id', $value);
    }

    /**
     * is_show_path
     *
     * @param Model $query
     * @param       $value
     */
    public function searchIsShowPathAttr($query, $value)
    {
        $query->where('is_show_path', $value);
    }

    /**
     * auth_type
     *
     * @param Model $query
     * @param       $value
     */
    public function searchAuthTypeAttr($query, $value)
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
