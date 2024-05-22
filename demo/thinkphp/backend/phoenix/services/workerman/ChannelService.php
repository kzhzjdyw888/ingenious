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

namespace phoenix\services\workerman;


use Channel\Client;

/**
 *
 *
 * @author GQL
 * @since  1.0
 */
class ChannelService
{
    /**
     * @var Client
     */
    protected $channel;

    /**
     * @var
     */
    protected string $trigger = 'phoenix';

    /**
     * @var ChannelService
     */
    protected static $instance;

    public function __construct()
    {
        self::connet();
    }

    public static function instance()
    {
        if (is_null(self::$instance))
            self::$instance = new self();

        return self::$instance;
    }

    public static function connet(): void
    {
        $config = config('workerman.channel');
        Client::connect($config['ip'], $config['port']);
    }

    /**
     * @param string $name
     *
     * @return \phoenix\services\workerman\ChannelService
     */
    public function setTrigger(string $name): static
    {
        $this->trigger = $name;
        return $this;
    }

    /**
     * 发送消息
     * @param string $type 类型
     * @param array|null $data 数据
     * @param array|null $ids 用户 id,不传为全部用户
     */
    public function send(string $type, ?array $data = null, ?array $ids = null): void
    {
        $res = compact('type');

        if (!is_null($data))
            $res['data'] = $data;

        if (!is_null($ids) && count($ids))
            $res['ids'] = $ids;

        $this->trigger($this->trigger, $res);
        $this->trigger = 'phoenix';
    }

    public function trigger(string $type, ?array $data = null): void
    {
        Client::publish($type, $data);
    }
}
