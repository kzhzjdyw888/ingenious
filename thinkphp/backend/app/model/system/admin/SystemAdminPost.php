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
use think\Model;

/**
 * 管理员关联-职位
 *
 * @author Mr.April
 * @since  1.0
 */
class SystemAdminPost extends BaseModel
{
    use ModelTrait;

    /**
     * 模型名称
     *
     * @var string
     */
    protected $name = 'system_admin_post';

    /**
     * adminId搜索器
     *
     * @param \think\db\Query $query
     * @param                 $value
     */
    public function searchAdminIdAttr(Query $query, $value)
    {
        if (is_array($value)) {
            $query->whereIn('admin_id', $value);
        } else {
            $query->where('admin_id', $value);
        }
    }

    /**
     * menuId搜索器
     *
     * @param \think\db\Query $query
     * @param                 $value
     */
    public function searchPostIdAttr(Query $query, $value)
    {
        if ($value) {
            if (is_array($value)) {
                $query->whereIn('post_id', $value);
            } else {
                $query->where('post_id', $value);
            }
        }
    }

}
