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

    public function setDefer(bool $bool = true)
    {
        $this->options['setDefer'] = $bool;
        return $this;
    }

    protected function query($method, $args)
    {
        $chan = new \chan(1);

        go(function () use ($chan, $method, $args) {
            $redis = $this->RedisPool->get();

            $rs = call_user_func_array([$redis, $method], $args);

            $this->RedisPool->put($redis);


            if ($this->options['setDefer']) {
                $chan->push($rs);
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
            return false;
        }
    }
}