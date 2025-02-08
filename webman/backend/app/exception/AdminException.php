<?php
/**
 *+------------------
 * Lflow
 *+------------------
 * Copyright (c) 2023~2030 gitee.com/liu_guan_qing All rights reserved.本版权不可删除，侵权必究
 *+------------------
 * Author: Mr.April(405784684@qq.com)
 *+------------------
 */

namespace app\exception;

use support\exception\BusinessException;
use Webman\Http\Request;
use Webman\Http\Response;

class AdminException extends BusinessException
{

    public function render(Request $request): ?Response
    {
        return new Response(400, ['Content-Type' => 'application/json'], json_encode(['code' => $this->getCode(), 'msg' => $this->getMessage()], JSON_UNESCAPED_UNICODE));
    }
}
