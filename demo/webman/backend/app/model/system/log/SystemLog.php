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

namespace app\model\system\log;

use app\common\traits\JwtAuthModelTrait;
use app\common\traits\ModelTrait;
use app\model\BaseModel;
use think\Model;

/**
 * 日志模型
 * Class SystemLog
 *
 * @package app\model\system\log
 */
class SystemLog extends BaseModel
{
    use ModelTrait;


    /**
     * 模型名称
     *
     * @var string
     */
    protected $name = 'system_log';
    /**
     * 数据表主键
     *
     * @var string
     */
    protected $pk = 'id';

    protected array $insert = ['create_time'];

    protected $type = [
        'create_time' => 'timestamp:Y-m-d H:i:s',
    ];

    protected function setCreateTimeAttr(): int
    {
        return time();
    }

    /**
     * 访问方式搜索器
     *
     * @param Model $query
     * @param       $value
     */
    public function searchPagesAttr($query, $value)
    {
        if ($value !== '') {
            $query->whereLike('page', '%' . $value . '%');
        }
    }

    /**
     * 访问路径搜索器
     *
     * @param Model $query
     * @param       $value
     */
    public function searchPathAttr($query, $value)
    {
        if ($value !== '') {
            $query->whereLike('path', '%' . $value . '%');
        }
    }

    /**
     * ip搜索器
     *
     * @param Model $query
     * @param       $value
     */
    public function searchIpAttr($query, $value)
    {
        if ($value !== '') {
            $query->where('ip', 'LIKE', "%$value%");
        }
    }

    /**
     * 管理员id搜索器
     *
     * @param Model $query
     * @param       $value
     */
    public function searchAdminIdAttr($query, $value)
    {
        if (!empty($value)) {
            $query->whereIn('admin_id', $value);
        }
    }
}
