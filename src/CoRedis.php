<?php
/**
 * User: sethink
 */

namespace sethink\swooleRedis;

use sethink\swooleRedis\map\CoRedisMap;

/**
 * Class CoRedis
 * @package sethink\swooleRedis
 * @method CoRedis init(string $server) static 初始化，加入server
 * @method CoRedis instance();
 * @method CoRedis put(object $redis)
 * @method CoRedis setDefer(bool $bool=true)
 *
 * //base
 * @method CoRedis ping()
 * @method CoRedis echo(string $string)
 * @method CoRedis randomKey()
 * @method CoRedis select(int $tableId)
 * @method CoRedis move(string $key,int $tableId)
 * @method CoRedis rename(string $srcKey,String $newKey)
 * @method CoRedis renameNx(string $srcKey,string $newKey)
 * @method CoRedis expire(string $key, int $ttl)
 * @method CoRedis pexpire(string $key,int $ttl)
 * @method CoRedis expireAt(string $key,int $timestamp)
 * @method CoRedis pexpireAt(string $key,int $timestamp)
 * @method CoRedis keys(string $key)
 * @method CoRedis dbSize()
 * @method CoRedis object(string $key,string $object)
 * @method CoRedis save()
 * @method CoRedis bgsave()
 * @method CoRedis lastSave()
 * @method CoRedis type(string $type)
 * @method CoRedis flushDB()
 * @method CoRedis flushAll()
 * @method CoRedis sort(string $key,$value)
 * @method CoRedis info()
 * @method CoRedis resetStat()
 * @method CoRedis ttl(string $key)
 * @method CoRedis pttl(string $key)
 * @method CoRedis persist(string $key)
 * @method CoRedis eval(string $luaScript)
 * @method CoRedis evalSha(string $luaScriptSha)
 * @method CoRedis script()
 * @method CoRedis getLastError()
 * @method CoRedis _prefix(string $key)
 * @method CoRedis _unserialize(string $serialized)
 * @method CoRedis dump(string $key)
 * @method CoRedis restore(string $key,int $ttl,string $value)
 * @method CoRedis time()
 *
 *
 * //string
 * @method CoRedis get(string $key)
 * @method CoRedis set(string $key, string $value, int $timeout = 0)
 * @method CoRedis setex(string $key, int $ttl, string $value)
 * @method CoRedis psetex(string $key, int $expire, string $value)
 * @method CoRedis setnx(string $key, string $value)
 * @method CoRedis del(string ... $key)
 * @method CoRedis delete(string ... $key)
 * @method CoRedis getSet(string $key, string $value)
 * @method CoRedis exists(string $key)
 * @method CoRedis incr(string $key)
 * @method CoRedis incrBy(string $key, int $increment)
 * @method CoRedis incrByFloat(string $key, float $increment)
 * @method CoRedis decr(string $key)
 * @method CoRedis decrBy(string $key, int $increment)
 * @method CoRedis mget(array ... $keys)
 * @method CoRedis append(string $key, string $value)
 * @method CoRedis getRange(string $key, int $start, int $end)
 * @method CoRedis setRange(string $key, int $offset, string $value)
 * @method CoRedis strlen(string $key)
 * @method CoRedis getBit(string $key, int $offset)
 * @method CoRedis setBit(string $key, int $offset, bool $bool)
 * @method CoRedis mset(array $keyValue)
 *
 * //list
 * @method CoRedis lPush(string $key, string $value)
 * @method CoRedis rPush(string $key, string $value)
 * @method CoRedis lPushx(string $key, string $value)
 * @method CoRedis rPushx(string $key, string $value)
 * @method CoRedis lPop(string $key)
 * @method CoRedis rPop(string $key)
 * @method CoRedis blpop(array $keys, int $timeout)
 * @method CoRedis brpop(array $keys, int $timeout)
 * @method CoRedis lSize(string $key)
 * @method CoRedis lGet(string $key, int $index)
 * @method CoRedis lSet(string $key, int $index, string $value)
 * @method CoRedis IRange(string $key, int $start, int $end)
 * @method CoRedis lTrim(string $key, int $start, int $end)
 * @method CoRedis lRem(string $key, string $value, int $count)
 * @method CoRedis rpoplpush(string $srcKey, string $dstKey)
 * @method CoRedis brpoplpush(string $srcKey, string $detKey, int $timeout)
 *
 * //set
 * @method CoRedis sAdd(string $key, string $value)
 * @method CoRedis sRem(string $key, string $value)
 * @method CoRedis sMove(string $srcKey, string $dstKey, string $value)
 * @method CoRedis sIsMember(string $key, string $value)
 * @method CoRedis sCard(string $key)
 * @method CoRedis sPop(string $key)
 * @method CoRedis sRandMember(string $key)
 * @method CoRedis sInter(string ... $keys)
 * @method CoRedis sInterStore(string $dstKey, string ... $srcKey)
 * @method CoRedis sUnion(string ... $keys)
 * @method CoRedis sUnionStore(string $dstKey, string ... $srcKey)
 * @method CoRedis sDiff(string ... $keys)
 * @method CoRedis sDiffStore(string $dstKey, string ... $srcKey)
 * @method CoRedis sMembers(string $key)
 *
 * //zset
 * @method CoRedis zAdd(string $key, double $score, string $value)
 * @method CoRedis zRange(string $key, int $start, int $end)
 * @method CoRedis zDelete(string $key, string $value)
 * @method CoRedis zRevRange(string $key, int $start, int $end)
 * @method CoRedis zRangeByScore(string $key, int $start, int $end, array $options = [])
 * @method CoRedis zCount(string $key, int $start, int $end)
 * @method CoRedis zRemRangeByScore(string $key, int $start, int $end)
 * @method CoRedis zRemRangeByRank(string $key, int $start, int $end)
 * @method CoRedis zSize(string $key)
 * @method CoRedis zScore(string $key, string $value)
 * @method CoRedis zRank(string $key, string $value)
 * @method CoRedis zRevRank(string $key, string $value)
 * @method CoRedis zIncrBy(string $key, double $score, string $value)
 *
 * //hash
 * @method CoRedis hSet(string $key, string $hashKey, string $value)
 * @method CoRedis hSetNx(string $key, string $hashKey, string $value)
 * @method CoRedis hGet(string $key, string $hashKey)
 * @method CoRedis hLen(string $key)
 * @method CoRedis hDel(string $key, string $hashKey)
 * @method CoRedis hKeys(string $key)
 * @method CoRedis hVals(string $key)
 * @method CoRedis hGetAll(string $key)
 * @method CoRedis hExists(string $key, string $hashKey)
 * @method CoRedis hIncrBy(string $key, string $hashKey, int $value)
 * @method CoRedis hIncrByFloat(string $key, string $hashKey, float $value)
 * @method CoRedis hMset(string $key, array $keyValue)
 * @method CoRedis hMGet(string $key, array $hashKeys)
 *
 * //stream
 * @method CoRedis xLen(string $key)
 * @method CoRedis xAdd(string $key, string $id, array $keyValue, array $options)
 * @method CoRedis xRead(array $streams, array $options = [])
 * @method CoRedis xDel(string $key, string ... $id)
 * @method CoRedis xRange(string $key, string $start, string $end, int $count)
 * @method CoRedis xRevRange(string $key, string $start, string $end, int $count)
 * @method CoRedis xTrim(string $key, array $options)
 * @method CoRedis xGroupCreate(string $key, string $groupname, string $id, bool $mkstream = false)
 * @method CoRedis xGroupSetId(string $key, string $groupname, string $id)
 * @method CoRedis xGroupDestroy(string $key, string $groupname)
 * @method CoRedis xGroupCreateConsumer(string $key, string $groupname, string $consumername)
 * @method CoRedis xGroupDelConsumer(string $key, string $groupname, string $consumername)
 * @method CoRedis xGroupReadGroup(string $groupname, string $consumername, array $streams, array $options)
 * @method CoRedis xPending(string $key, string $groupname, array $options)
 * @method CoRedis xClaim(string $key, string $groupname, array $options)
 * @method CoRedis xAutoClaim(string $key, string $groupname, string $consumer, int $min_idle_timearray, string $start, array $options)
 * @method CoRedis xInfoConsumers(string $key, string $groupname)
 * @method CoRedis xInfoGroups(string $key)
 * @method CoRedis xInfoStream(string $key)
 */
class CoRedis
{
    public static function __callStatic($method, $args)
    {

        $class = '\\sethink\\swooleRedis\\map\\CoRedisMap';
        return call_user_func_array([new $class, $method], $args);
    }
}