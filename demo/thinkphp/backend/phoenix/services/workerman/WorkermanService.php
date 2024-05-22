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
use Workerman\Connection\TcpConnection;
use Workerman\Lib\Timer;
use Workerman\Worker;

class WorkermanService
{
    /**
     * @var Worker
     */
    protected $worker;

    /**
     * @var TcpConnection[]
     */
    protected array $connections = [];

    /**
     * @var TcpConnection[]
     */
    protected $user = [];

    /**
     * @var WorkermanHandle
     */
    protected $handle;

    /**
     * @var Response
     */
    protected $response;

    /**
     * @var int
     */
    protected $timer;

    public function __construct(Worker $worker)
    {
        $this->worker   = $worker;
        $this->handle   = new WorkermanHandle($this);
        $this->response = new Response();
    }

    /**
     * setUser
     *
     * @param \Workerman\Connection\TcpConnection $connection
     */
    public function setUser(TcpConnection $connection): void
    {
        $this->user[$connection->adminInfo['id']] = $connection;
    }

    /**
     * @param \Workerman\Connection\TcpConnection $connection
     */
    public function onConnect(TcpConnection $connection): void
    {
        $this->connections[$connection->id] = $connection;
        $connection->lastMessageTime        = time();
    }

    public function onMessage(TcpConnection $connection, $res)
    {
        $connection->lastMessageTime = time();
        $res                         = json_decode($res, true);
        if (!$res || !isset($res['type']) || !$res['type'] || $res['type'] == 'ping') {
            return $this->response->connection($connection)->success('ping', ['now' => time()]);
        }
        if (!method_exists($this->handle, $res['type'])) return;

        $this->handle->{$res['type']}($connection, $res + ['data' => []], $this->response->connection($connection));
    }

    public function onWorkerStart(Worker $worker)
    {

        ChannelService::connet();
        Client::on('phoenix', function ($eventData) use ($worker) {
            if (!isset($eventData['type']) || !$eventData['type']) return;
            $ids = isset($eventData['ids']) && count($eventData['ids']) ? $eventData['ids'] : array_keys($this->user);
            foreach ($ids as $id) {
                if (isset($this->user[$id]))
                    $this->response->connection($this->user[$id])->success($eventData['type'], $eventData['data'] ?? null);
            }
        });

        //定时检测超时连接
//        $this->timer = Timer::add(15, function () use (&$worker) {
//            $time_now = time();
//            foreach ($worker->connections as $connection) {
//                if ($time_now - $connection->lastMessageTime > 15) {
//                    $this->response->connection($connection)->close('timeout');
//                }
//            }
//        });
    }

    public function onClose(TcpConnection $connection): void
    {
        unset($this->connections[$connection->id]);
    }
}
