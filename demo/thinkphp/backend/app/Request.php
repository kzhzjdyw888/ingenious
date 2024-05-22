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

namespace app;

use Spatie\Macroable\Macroable;

/**
 * Class Request
 *
 * @package app
 * @method tokenData() 获取token信息
 * @method user(string $key = null) 获取用户信息
 * @method uid() 获取用户uid
 * @method isAdminLogin() 后台登陆状态
 * @method adminId() 后台管理员id
 * @method adminInfo() 后台管理信息
 * @method kefuId() 客服id
 * @method kefuInfo() 客服信息
 */
class Request extends \think\Request
{
    use Macroable;

    /**
     * 不过滤变量名
     *
     * @var array
     */
    protected array $except = ['menu_path', 'api_url', 'unique_auth', 'description', 'custom_form', 'content'];

    /**
     * 获取请求的数据
     *
     * @param array $params
     * @param bool  $suffix
     * @param bool  $filter
     *
     * @return array
     */
    public function more(array $params, bool $suffix = false, bool $filter = true): array
    {
        $p = [];
        $i = 0;
        foreach ($params as $param) {
            if (!is_array($param)) {
                $p[$suffix == true ? $i++ : $param] = $this->filterWord(is_string($this->param($param)) ? trim($this->param($param)) : $this->param($param), $filter && !in_array($param, $this->except));
            } else {
                if (!isset($param[1])) $param[1] = null;
                if (!isset($param[2])) $param[2] = '';
                if (is_array($param[0])) {
                    $name    = is_array($param[1]) ? $param[0][0] . '/a' : $param[0][0] . '/' . $param[0][1];
                    $keyName = $param[0][0];
                } else {
                    $name    = is_array($param[1]) ? $param[0] . '/a' : $param[0];
                    $keyName = $param[0];
                }
                $p[$suffix == true ? $i++ : ($param[3] ?? $keyName)] = $this->filterWord(is_string($this->param($name, $param[1], $param[2])) ? trim($this->param($name, $param[1], $param[2])) : $this->param($name, $param[1], $param[2]), $filter && !in_array($keyName, $this->except));
            }
        }
        return $p;
    }

    /**
     * 过滤接受的参数
     *
     * @param      $str
     * @param bool $filter
     *
     * @return array|mixed|string|string[]
     */
    public function filterWord($str, bool $filter = true)
    {
        if (!$str || !$filter) return $str;
        // 把数据过滤
        $farr = [
            "/<(\\/?)(script|i?frame|style|html|body|title|link|meta|object|\\?|\\%)([^>]*?)>/isU",
            "/(<[^>]*)on[a-zA-Z]+\s*=([^>]*>)/isU",
            '/phar/is',
            "/select|join|where|drop|like|modify|rename|insert|update|table|database|alter|truncate|\'|\/\*|\.\.\/|\.\/|union|into|load_file|outfile/is",
        ];
        if (is_array($str)) {
            foreach ($str as &$v) {
                if (is_array($v)) {
                    foreach ($v as &$vv) {
                        if (!is_array($vv)) $vv = preg_replace($farr, '', $vv);
                    }
                } else {
                    $v = preg_replace($farr, '', $v);
                }
            }
        } else {
            $str = preg_replace($farr, '', $str);
        }
        return $str;
    }

    /**
     * 获取get参数
     *
     * @param array $params
     * @param bool  $suffix
     * @param bool  $filter
     *
     * @return array
     */
    public function getMore(array $params, bool $suffix = false, bool $filter = true): array
    {
        return $this->more($params, $suffix, $filter);
    }

    /**
     * 获取post参数
     *
     * @param array $params
     * @param bool  $suffix
     * @param bool  $filter
     *
     * @return array
     */
    public function postMore(array $params, bool $suffix = false, bool $filter = true): array
    {
        return $this->more($params, $suffix, $filter);
    }

    /**
     * 获取请求的数据
     * 获取额外参数和值；
     * 　　type为 1、2 表示 索引数组（1为 Value 的一维数组，2为key、Value的二维数组），0表示 关联数组
     *     每条数据组成：
     *       数组/字符串
     *     如果是数组：
     *       $param[0]：要获取的Key
     *         可以为数组：0为key，1为变量修饰符
     *         $param[1]：默认值；!!!注意，如果默认值为数组，则这个项的值也会转为数组（索引）!!!
     *         $param[2]：过滤函数
     *         $param[3]：替换的新key
     *     已修改为：如果没有 $param 且没有缺省值，则跳过这个；
     *     感觉还可以修改 变量修饰符 那部分（去掉 增加变量修饰）
     *     返回：
     *       索引：二维数组：[[key, 值], [key, 值]。。。]，适用于where
     *        一维数组[值, 值, 。。。]
     *       关联：一维关联数组
     *
     * @param array $params
     * @param int   $type
     *
     * @return array
     */
    public function getParams(array $params, int $type = 0): array
    {
        $p = [];
        $i = 0;
        foreach ($params as $param) {
            if (!is_array($param))    //如果不是数组
            {
                //原来的索引数组没有key
                //$p[$suffix == true ? $i++ : $param] = $this->param($param);
                if ($type == 0)
                    $p[$param] = $this->param($param);
                else if ($type == 1)
                    $p[$i++] = $this->param($param);
                else {
                    //修改为 [key, 值]
                    $p[$i++] = [$param, $this->param($param)];
                }
            } else {
                //if (!isset($param[1])) $param[1] = null;
                if (!isset($param[2])) $param[2] = '';
                if (is_array($param[0]))    //如果是数组
                {
                    if (!array_key_exists('1', $param))    //如果没有默认值
                    {
                        if (!$this->has($param[0][0]))    //如果request中没有
                            continue;

                        $param[1] = null;
                    }

                    //如果默认值为数组，则给param加上变量修饰符（/a）；否则手动连接修饰符
                    $name    = is_array($param[1]) ? $param[0][0] . '/a' : $param[0][0] . '/' . $param[0][1];
                    $keyName = $param[0][0];
                } else {
                    if (!array_key_exists('1', $param))    //如果没有默认值
                    {
                        if (!$this->has($param[0]))    //如果request中没有
                            continue;

                        $param[1] = null;
                    }

                    //如果默认值为数组，则给param加上变量修饰符（/a）
                    $name    = is_array($param[1]) ? $param[0] . '/a' : $param[0];
                    $keyName = $param[0];
                }

                //原来的索引数组没有key
                //$p[$suffix == true ? $i++ : (isset($param[3]) ? $param[3] : $keyName)] = $this->param($name, $param[1], $param[2]);
                if ($type == 0)
                    $p[(isset($param[3]) ? $param[3] : $keyName)] = $this->param($name, $param[1], $param[2]);
                else if ($type == 1)
                    $p[$i++] = $this->param($name, $param[1], $param[2]);
                else {
                    //修改为 [key, 值]
                    $p[$i++] = [isset($param[3]) ? $param[3] : $keyName, $this->param($name, $param[1], $param[2])];
                }
            }
        }
        return $p;
    }

    /**
     * 获取用户访问端
     *
     * @return array|string|null
     */
    public function getFromType()
    {
        return $this->header('Form-type', '');
    }

    /**
     * 当前访问端
     *
     * @param string $terminal
     *
     * @return bool
     */
    public function isTerminal(string $terminal)
    {
        return strtolower($this->getFromType()) === $terminal;
    }

    /**
     * 是否是H5端
     *
     * @return bool
     */
    public function isH5()
    {
        return $this->isTerminal('h5');
    }

    /**
     * 是否是微信端
     *
     * @return bool
     */
    public function isWechat()
    {
        return $this->isTerminal('wechat');
    }

    /**
     * 是否是小程序端
     *
     * @return bool
     */
    public function isRoutine()
    {
        return $this->isTerminal('routine');
    }

    /**
     * 是否是app端
     *
     * @return bool
     */
    public function isApp()
    {
        return $this->isTerminal('app');
    }

    /**
     * 是否是app端
     *
     * @return bool
     */
    public function isPc()
    {
        return $this->isTerminal('pc');
    }
}
