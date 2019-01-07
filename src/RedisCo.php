<?php
/**
 * User: sethink
 */

namespace sethink\swooleRedis;

use sethink\swooleRedis\map\RedisCoMap;

/**
 * Class RedisCo
 * @package sethink\swooleRedis
 * @method RedisCo init(string $server) static 初始化，加入server
 *
 * //string
 * @method RedisCo get(string $key)
 * @method RedisCo set(string $key, string $value)
 * @method RedisCo setex(string $key, int $expire, string $value)
 * @method RedisCo psetex(string $key, int $expire, string $value)
 * @method RedisCo setnx(string $key, string $value)
 * @method RedisCo delete(string ... $key)
 * @method RedisCo getSet(string $key, string $value)
 * @method RedisCo exists(string $key)
 * @method RedisCo incr(string $key)
 * @method RedisCo incrBy(string $key, int $num)
 * @method RedisCo incrByFloat(string $key, float $num)
 * @method RedisCo decr(string $key)
 * @method RedisCo decrBy(string $key, int $num)
 * @method RedisCo mGet(array ... $keys)
 * @method RedisCo append(string $key, string $value)
 * @method RedisCo getRange(string $key, int $start, int $end)
 * @method RedisCo setRange(string $key, int $offset, string $value)
 * @method RedisCo strlen(string $key)
 * @method RedisCo getBit(string $key, int $offset)
 * @method RedisCo setBit(string $key, int $offset, bool $bool)
 * @method RedisCo mset(array $keyValue)
 *
 * //list
 * @method RedisCo lPush(string $key, string $value)
 * @method RedisCo rPush(string $key, string $value)
 * @method RedisCo lPushx(string $key, string $value)
 * @method RedisCo rPushx(string $key, string $value)
 * @method RedisCo lPop(string $key)
 * @method RedisCo rPop(string $key)
 * @method RedisCo blPop(string $key, int $timeout)
 * @method RedisCo brPop(string $key, int $timeout)
 * @method RedisCo lSize(string $key)
 * @method RedisCo lGet(string $key, int $index)
 * @method RedisCo lSet(string $key, int $index, string $value)
 * @method RedisCo IRange(string $key, int $start, int $end)
 * @method RedisCo lTrim(string $key, int $start, int $end)
 * @method RedisCo lRem(string $key, string $value, int $count)
 * @method RedisCo rpoplpush(string $srcKey, string $dstKey)
 * @method RedisCo brpoplpush(string $srcKey, string $detKey, int $timeout)
 *
 * //set
 * @method RedisCo sAdd(string $key, string $value)
 * @method RedisCo sRem(string $key, string $value)
 * @method RedisCo sMove(string $srcKey, string $dstKey, string $value)
 * @method RedisCo sIsMember(string $key, string $value)
 * @method RedisCo sCard(string $key)
 * @method RedisCo sPop(string $key)
 * @method RedisCo sRandMember(string $key)
 * @method RedisCo sInter(string ... $keys)
 * @method RedisCo sInterStore(string $dstKey, string ... $srcKey)
 * @method RedisCo sUnion(string ... $keys)
 * @method RedisCo sUnionStore(string $dstKey, string ... $srcKey)
 * @method RedisCo sDiff(string ... $keys)
 * @method RedisCo sDiffStore(string $dstKey, string ... $srcKey)
 * @method RedisCo sMembers(string $key)
 *
 * //zset
 * @method RedisCo zAdd(string $key, double $score, string $value)
 * @method RedisCo zRange(string $key, int $start, int $end)
 * @method RedisCo zDelete(string $key, string $value)
 * @method RedisCo zRevRange(string $key, int $start, int $end)
 * @method RedisCo zRangeByScore(string $key, int $start, int $end, array $options = [])
 * @method RedisCo zCount(string $key, int $start, int $end)
 * @method RedisCo zRemRangeByScore(string $key, int $start, int $end)
 * @method RedisCo zRemRangeByRank(string $key, int $start, int $end)
 * @method RedisCo zSize(string $key)
 * @method RedisCo zScore(string $key, string $value)
 * @method RedisCo zRank(string $key, string $value)
 * @method RedisCo zRevRank(string $key, string $value)
 * @method RedisCo zIncrBy(string $key, double $score, string $value)
 *
 * //hash
 * @method RedisCo hSet(string $key, string $hashKey, string $value)
 * @method RedisCo hSetNx(string $key, string $hashKey, string $value)
 * @method RedisCo hGet(string $key, string $hashKey)
 * @method RedisCo hLen(string $key)
 * @method RedisCo hDel(string $key, string $hashKey)
 * @method RedisCo hKeys(string $key)
 * @method RedisCo hVals(string $key)
 * @method RedisCo hGetAll(string $key)
 * @method RedisCo hExists(string $key, string $hashKey)
 * @method RedisCo hIncrBy(string $key, string $hashKey, int $value)
 * @method RedisCo hIncrByFloat(string $key, string $hashKey, float $value)
 * @method RedisCo hMset(string $key, array $keyValue)
 * @method RedisCo hMGet(string $key, array $hashKeys)
 */
class RedisCo
{
    public static function __callStatic($method, $args)
    {
        $class = '\\sethink\\swooleRedis\\map\\RedisCoMap';
        return call_user_func_array([new $class, $method], $args);
    }
}