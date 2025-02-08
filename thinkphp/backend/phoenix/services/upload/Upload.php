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
namespace phoenix\services\upload;

use phoenix\basic\BaseManager;
use think\facade\Config;


/**
 * Upload
 *
 * @author Mr.April
 * @since  1.0
 * @mixin \phoenix\services\upload\storage\Local
 * @mixin \phoenix\services\upload\storage\OSS
 * @mixin \phoenix\services\upload\storage\COS
 * @mixin \phoenix\services\upload\storage\Qiniu
 */
class Upload extends BaseManager
{
    /**
     * 空间名
     *
     * @var string
     */
    protected $namespace = '\\phoenix\\services\\upload\\storage\\';

    /**
     * 设置默认上传类型
     *
     * @return mixed
     */
    protected function getDefaultDriver(): mixed
    {
        return Config::get('upload.default', 'local');
    }

}
