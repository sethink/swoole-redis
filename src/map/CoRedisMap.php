<?php
/**
 * User: sethink
 */

namespace sethink\swooleRedis\map;

class CoRedisMap
{
    protected $RedisPool = false;
    protected $options = [
        'setDefer' => true
    ];

    public function init($RedisPool)
    {
        $this->RedisPool           = $RedisPool;
        $this->options['setDefer'] = $RedisPool->config['setDefer'];
        return $this;
    }

    public function instance()
    {
        $re_i = -1;

        back:
        $re_i++;

        $redis = $this->RedisPool->get();

        if ($redis->connected) {
            return $redis;
        } else {
            if ($re_i <= $this->RedisPool->config['poolMin']) {
                $redis->close();
                unset($redis);
                goto back;
            }
        }

        throw new \Exception('Redis连接获取失败');
    }

    public function put($redis)
    {
        if ($redis instanceof \Swoole\Coroutine\Redis) {
            $this->RedisPool->put($redis);
        }else{
            throw new \Exception('传入的$redis不属于该连接池');
        }
    }

    public function setDefer($bool = true)
    {
        $this->options['setDefer'] = $bool;
        return $this;
    }

    protected function query($method, $args)
    {
        $chan = new \chan(1);

        go(function () use ($chan, $method, $args) {
            $re_i = -1;

            back:
            $re_i++;

            $redis = $this->RedisPool->get();

            if ($redis->connected) {
                $rs = call_user_func_array([$redis, $method], $args);
                $this->put($redis);

                if ($this->options['setDefer']) {
                    $chan->push($rs);
                }
            } else {
                if ($re_i <= $this->RedisPool->config['poolMin']) {
                    $redis->close();
                    unset($redis);
                    goto back;
                }

                throw new \Exception('Redis连接获取失败');
            }
        });

        if ($this->options['setDefer']) {
            return $chan->pop();
        }
    }

    public function __call($method, $args)
    {
        if ($this->RedisPool) {
            return $this->query($method, $args);
        } else {
            throw new \Exception('请先执行init()函数');
        }
    }
}