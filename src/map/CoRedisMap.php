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

    protected $sethink_array = [
        'init',
        'instance',
        'put',
        'setDefer'
    ];
    protected $base_array = [
        'ping',
        'echo',
        'randomKey',
        'select',
        'move',
        'rename',
        'renameNx',
        'expire',
        'pexpire',
        'expireAt',
        'pexpireAt',
        'keys',
        'dbSize',
        'object',
        'save',
        'bgsave',
        'lastSave',
        'type',
        'flushDB',
        'flushAll',
        'sort',
        'info',
        'resetStat',
        'ttl',
        'pttl',
        'persist',
        'eval',
        'evalSha',
        'script',
        'getLastError',
        '_prefix',
        '_unseriablize',
        'dump',
        'restore',
        'time'
    ];
    protected $string_array = [
        'get',
        'set',
        'setex',
        'psetex',
        'setnx',
        'del',
        'delete',
        'getSet',
        'exists',
        'incr',
        'incrBy',
        'incrByFloat',
        'decr',
        'decrBy',
        'mget',
        'append',
        'getRange',
        'setRange',
        'strlen',
        'getBit',
        'setBit',
        'mset'
    ];
    protected $list_array = [
        'lPush',
        'rPush',
        'lPushx',
        'rPushx',
        'lPop',
        'rPop',
        'blpop',
        'brpop',
        'lSize',
        'lGet',
        'lSet',
        'IRange',
        'lTrim',
        'lRem',
        'rpoplpush',
        'brpoplush'
    ];
    protected $set_array = [
        'sAdd',
        'sRem',
        'sMove',
        'sInMember',
        'sCard',
        'sPop',
        'sRandMember',
        'sInter',
        'sInterStore',
        'sUnion',
        'sUnionStore',
        'sDiff',
        'sDiffStore',
        'sMembers'
    ];
    protected $zset_array = [
        'zAdd',
        'zRange',
        'zDelete',
        'zRevRange',
        'zRangeByScore',
        'zCount',
        'zRemRangeByScore',
        'zRemRangeByRank',
        'zSize',
        'zScore',
        'zRank',
        'zRevRank',
        'zIncrBy'
    ];
    protected $hash_array = [
        'hSet',
        'hSetNx',
        'hGet',
        'hLen',
        'hDel',
        'hKeys',
        'hVals',
        'hGetAll',
        'hExists',
        'hIncrBy',
        'hIncrByFloat',
        'hMset',
        'hMGet'
    ];

    protected $stream_array = [
        'xAdd',
        'xTrim',
        'xDel',
        'xLen',
        'xRange',
        'xRevRange',
        'xRead',
        'xGroupCreate',
        'xGroupSetId',
        'xGroupDestroy',
        'xGroupCreateConsumer',
        'xGroupDelConsumer',
        'xReadGroup',
        'xPending',
        'xAck',
        'xClaim',
        'xAutoClaim',
        'xPeding',
        'xInfoConsumers',
        'xInfoGroups',
        'xInfoStream',
    ];

    protected $key_array = [];

    public function __construct()
    {
        $this->key_array = array_merge($this->sethink_array,
            $this->base_array,
            $this->string_array,
            $this->list_array,
            $this->set_array,
            $this->zset_array,
            $this->hash_array,
            $this->stream_array
        );
    }


    /**
     * @文件信息
     *
     * @return string
     */
    protected function class_info (){
        return json_encode(debug_backtrace());
    }


    /**
     * @错误信息格式
     *
     * @param $class_info
     */
    protected function dumpError($class_info){
        echo PHP_EOL.PHP_EOL.PHP_EOL;
        echo "=================================================================".PHP_EOL;
        echo "时间：".date('Y-m-d H:m:i',time()).PHP_EOL.PHP_EOL;
        echo "报错信息：(格式为json，请格式化后分析)".PHP_EOL;
        echo $class_info.PHP_EOL;
    }


    public function init($RedisPool)
    {
        $this->RedisPool           = $RedisPool;
        $this->options['setDefer'] = $RedisPool->config['setDefer'];
        return $this;
    }

    public function instance()
    {
        return $this->RedisPool->get();
    }

    public function put($redis)
    {
        if ($redis instanceof \Swoole\Coroutine\Redis) {
            $this->RedisPool->put($redis);
        } else {
            $class_info = $this->class_info();
            $this->dumpError($class_info);
            throw new \RuntimeException('传入的$redis不属于该连接池');
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

        $class_info = $this->class_info();

        go(function () use ($chan, $method, $args,$class_info) {
            $redis = $this->RedisPool->get();

            $rs = call_user_func_array([$redis, $method], $args);

            $this->put($redis);

            if ($this->options['setDefer']) {
                $chan->push($rs);
            }
        });

        if ($this->options['setDefer']) {
            return $chan->pop();
        }
        return;
    }

    public function __call($method, $args)
    {
        if ($this->RedisPool) {
            if(in_array($method,$this->key_array)){
                return $this->query($method, $args);
            }else{
                $class_info = $this->class_info();
                $this->dumpError($class_info);
                echo PHP_EOL."错误信息：{{{$method}}}方法不存在".PHP_EOL;
            }
        } else {
            $class_info = $this->class_info();
            $this->dumpError($class_info);
            echo PHP_EOL."错误信息：请先执行init()函数".PHP_EOL;
        }
    }
}