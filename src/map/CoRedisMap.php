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

        switch (count($args)) {
            case '1':
                $rs = $redis->$method($args[0]);
                break;
            case '2':
                $rs = $redis->$method($args[0], $args[1]);
                break;
            case '3':
                $rs = $redis->$method($args[0], $args[1], $args[2]);
                break;
            case '4':
                $rs = $redis->$method($args[0], $args[1], $args[2], $args[3]);
                break;
            case '5':
                $rs = $redis->$method($args[0], $args[1], $args[2], $args[3], $args[4]);
                break;
            case '6':
                $rs = $redis->$method($args[0], $args[1], $args[2], $args[3], $args[4], $args[5]);
                break;
            case '7':
                $rs = $redis->$method($args[0], $args[1], $args[2], $args[3], $args[4], $args[5], $args[6]);
                break;
            case '8':
                $rs = $redis->$method($args[0], $args[1], $args[2], $args[3], $args[4], $args[5], $args[6], $args[7]);
                break;
            case '9':
                $rs = $redis->$method($args[0], $args[1], $args[2], $args[3], $args[4], $args[5], $args[6], $args[7], $args[8]);
                break;
        }

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