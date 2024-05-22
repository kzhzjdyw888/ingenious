<?php

namespace app\exception;

use app\common\Json;
use support\exception\BusinessException;
use Webman\Http\Request;
use Webman\Http\Response;

class AuthException extends BusinessException
{

    public function render(Request $request): ?Response
    {
        return new Response(403, ['Content-Type' => 'application/json'], json_encode(['code' => $this->getCode(), 'msg' => $this->getMessage()], JSON_UNESCAPED_UNICODE));
    }
}
