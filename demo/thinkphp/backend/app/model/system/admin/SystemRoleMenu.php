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
use think\db\Query;


/**
 * 管理员权限规则
 *
 * @author Mr.April
 * @since  1.0
 */
class SystemRoleMenu extends BaseModel
{
    use ModelTrait;


    /**
     * 模型名称
     *
     * @var string
     */
    protected $name = 'system_role_menu';


    /**
     * roleId搜索器
     *
     * @param \think\db\Query $query
     * @param                 $value
     */
    public function searchRoleIdAttr(Query $query, $value)
    {
        if (is_array($value)) {
            $query->whereIn('role_id', $value);
        } else {
            $query->where('role_id', $value);
        }
    }

    /**
     * menuId搜索器
     *
     * @param \think\db\Query $query
     * @param                 $value
     */
    public function searchMenuIdAttr(Query $query, $value)
    {
        if ($value) {
            if (is_array($value)) {
                $query->whereIn('menu_id', $value);
            } else {
                $query->where('menu_id', $value);
            }
        }
    }
}