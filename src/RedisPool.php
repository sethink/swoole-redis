<?php
/**
 * User: sethink
 */

namespace sethink\swooleRedis;

use Swoole;

class RedisPool
{
    //池
    protected $pool;
    //池状态
    protected $available = true;
    //新建时间
    protected $addPoolTime = '';
    //入池时间
    protected $pushTime = 0;
    //配置
    public $config = [
        //服务器地址
        'host'            => '127.0.0.1',
        //端口
        'port'            => 6379,
        //密码
        'auth'            => '',
        //空闲时，保存的最大链接，默认为5
        'poolMin'         => 5,
        //地址池最大连接数，默认1000
        'poolMax'         => 1000,
        //清除空闲链接的定时器，默认60s
        'clearTime'       => 60000,
        //空闲多久清空所有连接,默认300s
        'clearAll'        => 300,
        //设置是否返回结果
        'setDefer'        => true,

        //options配置
        'connect_timeout' => 1, //连接超时时间，默认为1s
        'timeout'         => 1, //超时时间，默认为1s
        'serialize'       => false, //自动序列化，默认false
        'reconnect'       => 1  //自动连接尝试次数，默认为1次
    ];

    public function __construct($config)
    {
        if (isset($config['clearAll'])) {
            if ($config['clearAll'] < $config['clearTime']) {
                $config['clearAll'] = (int)($config['clearTime'] / 1000);
            } else {
                $config['clearAll'] = (int)($config['clearAll'] / 1000);
            }
        }

        $this->config = array_merge($this->config, $config);
        $this->pool   = new Swoole\Coroutine\Channel($this->config['poolMax']);
    }

    /**
     * @入池
     *
     * @param $redis
     */
    public function put($redis)
    {
        //未超出池最大值时
        if ($this->pool->length() < $this->config['poolMax']) {
            $this->pool->push($redis);
        }
        $this->pushTime = time();
    }

    /**
     * @出池
     */
    public function get()
    {
        if (!$this->available) {
            throw new \Exception('Redis连接池正在销毁');
        }

        //有空闲连接且连接池处于可用状态
        if ($this->pool->length() > 0) {
            return $this->pool->pop();
        }
        //无空闲连接，创建新连接
        $redis = new Swoole\Coroutine\Redis([
            'connect_timeout' => $this->config['connect_timeout'],
            'timeout'         => $this->config['timeout'],
            'serialize'       => $this->config['serialize'],
            'reconnect'       => $this->config['reconnect']
        ]);

        $redis->connect($this->config['host'], $this->config['port']);

        if (!empty($this->config['auth'])) {
            $redis->auth($this->config['auth']);
        }

        $this->addPoolTime = time();

        if ($redis) {
            return $redis;
        } else {
            throw new \Exception('Redis连接失败');
        }
    }

    /**
     * @定时器
     *
     * @param $server
     */
    public function clearTimer($server)
    {
        $server->tick($this->config['clearTime'], function () use ($server) {
            if ($this->pool->length() > $this->config['poolMin'] && time() - 5 > $this->addPoolTime) {
                $this->pool->pop();
            }
            if ($this->pool->length() > 0 && time() - $this->config['clearAll'] > $this->pushTime) {
                while (!$this->pool->isEmpty()) {
                    $this->pool->pop();
                }
            }
        });
    }

    public function destruct()
    {
        // 连接池销毁, 置不可用状态, 防止新的客户端进入常驻连接池, 导致服务器无法平滑退出
        $this->available = false;
        while (!$this->pool->isEmpty()) {
            $this->pool->pop();
        }
    }
}