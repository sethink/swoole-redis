<?php

namespace Demo;

include_once "./src/RedisCo.php";
include_once "./src/RedisPool.php";
include_once "./src/map/RedisCoMap.php";

use swoole;
use sethink\swooleRedis\RedisCo;
use sethink\swooleRedis\RedisPool;

class Demo
{
    protected $server;
    protected $RedisPool;

    public function __construct()
    {

        $this->server = new Swoole\Http\Server("0.0.0.0", 9501);
        $this->server->set(array(
            'worker_num'    => 4,
            'max_request'   => 50000,
            'reload_async'  => true,
            'max_wait_time' => 30,
        ));
        $this->server->on('Start', function ($server) {
        });
        $this->server->on('ManagerStart', function ($server) {
        });
        $this->server->on('WorkerStart', array($this, 'onWorkerStart'));
        $this->server->on('WorkerStop', function ($server, $worker_id) {
        });
        $this->server->on('open', function ($server, $request) {
        });
        $this->server->on('Request', array($this, 'onRequest'));
        $this->server->start();
    }

    public function onWorkerStart($server, $worker_id)
    {
        $config          = [
            'host'      => '127.0.0.1',
            'port'      => 6379,
            'auth'      => 'sethink',
            'poolMin'   => 5,
            'clearTime' => 60000,
            'clearAll'  => 300000,
        ];
        $this->RedisPool = new RedisPool($config);
        unset($config);
    }

    public function onRequest($request, $response)
    {
        $rs1 = RedisCo::init($this->RedisPool)->set('sethink', 'sethink');
        var_dump($rs1);
        $rs2 = RedisCo::init($this->RedisPool)->get('sethink');
        var_dump($rs2);

    }
}

new Demo();