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

namespace crmeb\services\oauth;

/**
 * Class OAuthException
 * @package crmeb\services\oauth
 */
class OAuthException extends \RuntimeException
{
    /**
     * OAuthException constructor.
     * @param $message
     * @param array $replace
     * @param int $code
     * @param \Throwable|null $previous
     */
    public function __construct($message, $replace = [], $code = 0, \Throwable $previous = null)
    {
        if (is_array($message)) {
            $errInfo = $message;
            $message = $errInfo[1] ?? '未知错误';
            if ($code === 0) {
                $code = $errInfo[0] ?? 400;
            }
        }

        if (is_numeric($message)) {
            $code = $message;
            $message = getLang($message, $replace);
        }

        parent::__construct($message, $code, $previous);
    }
}
