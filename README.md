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

# setDefer($bool)
```
部分操作，如果不需要返回结果，则可以设置为false。

相对于$bool为true，执行后，由于主进程和协程间不需要再通信，可以立即往下执行程序
```

```php
<?php
//此操作不会返回结果
CoRedis::init($this->RedisPool)
    ->setDefer(false)
    ->set('sethink', 'sethink');
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
            'poolMin'   => 5,   //空闲时，保存的最大链接，默认为5
            'poolMax'   => 1000,    //地址池最大连接数，默认1000
            'clearTime' => 60000,   //清除空闲链接的定时器，默认60s
            'clearAll'  => 300000,  //空闲多久清空所有连接,默认300s
            'setDefer'  => true //设置是否返回结果
        ];
        $this->RedisPool = new RedisPool($config);
        unset($config);
        
        //定时器，清除空闲连接
        $this->RedisPool->clearTimer($this->server);
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