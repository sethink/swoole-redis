<?php
/**
 * User: sethink
 */

namespace sethink\swooleRedis\map;

class CoRedisMap
{
    protected $RedisPool = false;

    public function init($RedisPool)
    {
        $this->RedisPool = $RedisPool;
        return $this;
    }

    protected function query($method, $args)
    {
        $redis = $this->RedisPool->get();

        $rs = call_user_func_array([$redis, $method], $args);

        $this->RedisPool->put($redis);

        return $rs;
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