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

namespace phoenix\utils;

use Firebase\JWT\Key;
use phoenix\services\CacheService;
use Firebase\JWT\JWT;
use phoenix\exceptions\AdminException;
use think\facade\Env;


/**
 *
 * JWT类
 * @author Mr.April
 * @since  1.0
 */
class JwtAuth
{

    /**
     * token
     *
     * @var string
     */
    protected string $token;

    /**
     * alg
     *
     * @var string
     */
    protected string $algorithm = 'HS256';

    /**
     * 获取token
     *
     * @param int|string $id
     * @param string     $type
     * @param array      $params
     *
     * @return array
     */
    public function getToken($id, string $type, array $params = []): array
    {
        $keyId         = "keyId"; //这个东西必须要加上，不加上，报错，报错内容：'"kid" empty, unable to lookup correct key'
        $host          = app()->request->host();
        $time          = time();
        $exp_time      = strtotime('+ 30day');
        $params        += [
            'iss' => $host,
            'aud' => $host,
            'iat' => $time,
            'nbf' => $time,
            'exp' => $exp_time,
        ];
        $params['jti'] = compact('id', 'type');
        $token         = JWT::encode($params, Env::get('app.app_key', 'default'), $this->algorithm, $keyId);
        return compact('token', 'params');
    }

    /**
     * 解析token
     *
     * @param string $jwt
     *
     * @return array
     */
    public function parseToken(string $jwt): array
    {
        $this->token = $jwt;
        list($headb64, $bodyb64, $cryptob64) = explode('.', $this->token);
        $payload = JWT::jsonDecode(JWT::urlsafeB64Decode($bodyb64));
        return [$payload->jti->id, $payload->jti->type, $payload->pwd ?? '',$payload->exp];
    }

    /**
     * 验证token
     */
    public function verifyToken(): void
    {
        JWT::$leeway = 60;
        JWT::decode($this->token, new Key(Env::get('app.app_key', 'default'), $this->algorithm));
    }

    /**
     * 获取token并放入令牌桶
     *
     * @param             $id
     * @param string      $type
     * @param array       $params
     *
     * @return array
     */
    public function createToken($id, string $type, array $params = []): array
    {
        $tokenInfo = $this->getToken($id, $type, $params);
        $exp       = $tokenInfo['params']['exp'] - $tokenInfo['params']['iat'] + 60;
        $info      = [
            'uid'   => $id,
            'token' => $tokenInfo['token'],
            'exp'   => $exp,
        ];
        $res       = CacheService::set(md5($tokenInfo['token']), $info, (int)$exp, $type);
        if (!$res) {
            throw new AdminException(10023);
        }
        return $tokenInfo;
    }
}
