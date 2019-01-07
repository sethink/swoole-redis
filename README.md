# swoole-redis
```
基于swoole的redis协程连接池，简单封装。  
实现多个协程间共用同一个协程客户端。

tip：
实现redis函数的基本提示（部分函数未实现）
```

# 引入
```
>composer require sethink/swoole-redis
```

# 入门例子
```php
<?php
namespace Demo;

include_once "./vendor/autoload.php";

use swoole;
use sethink\swooleRedis\CoRedis;
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
        $rs1 = CoRedis::init($this->RedisPool)->set('sethink', 'sethink');
        var_dump($rs1);
        $rs2 = CoRedis::init($this->RedisPool)->get('sethink');
        var_dump($rs2);
    }
}

new Demo();

```