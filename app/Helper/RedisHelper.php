<?php

namespace App\Helper;

use Neo\Cache\Redis\Redis;
use Neo\Cache\Redis\RedisNull;
use Neo\Exception\NeoException;

/**
 * 不支持第一个非Key的方法，比如zUnion，zInter，rawCommand等等
 * 如果要使用这种方法，请使用原生方法(getPhpRedis::xxx())
 *
 * Class RedisHelper
 */
class RedisHelper
{
    /**
     * 构造函数
     */
    private function __construct()
    {
    }

    /**
     * 获取原生的Redis类
     *
     * @return bool|\Redis
     */
    public static function getPhpRedis()
    {
        return static::redis()->getPhpRedis();
    }

    /**
     * 设置默认的Key的前缀
     *
     * @param string $prefix 空字符串表示去掉前缀
     */
    public static function setPrefix(string $prefix = '')
    {
        static::getPhpRedis()->setOption(\Redis::OPT_PREFIX, $prefix);
    }

    /**
     * 获取 Redis
     *
     * @return bool|Redis|RedisNull
     */
    public static function redis()
    {
        return neo()->getRedis();
    }

    /**
     * @param string $key
     *
     * @return mixed
     */
    public static function get($key)
    {
        return static::redis()->get($key);
    }

    /**
     * 写入值到Redis，默认有效期是一周
     *
     * @param string $key
     * @param mixed  $value
     * @param int    $expired 有效期，秒
     *
     * @return bool
     */
    public static function set($key, $value, $expired = REDIS_DEFAULT_TIMEOUT)
    {
        return static::redis()->set($key, $value, $expired);
    }

    /**
     * 移除
     *
     * @param array ...$keys
     */
    public static function delete(...$keys)
    {
        static::getPhpRedis()->del($keys);
    }

    /**
     * 批量设置
     *
     * @param $data
     */
    public static function mset($data)
    {
        static::redis()->mset($data);
    }

    /**
     * 队列中pop数据
     *
     * @param string $key
     *
     * @return null|int|mixed|string
     */
    public static function pop(string $key)
    {
        return static::redis()->pop($key);
    }

    /**
     * Push数据到队列
     * $batch=true，表示批量push多个值，$data可以为一个数组，数组每个元素都是待push的值
     * $batch=fasle，表示push一个值，$data为一个整体push
     *
     * @param string $key
     * @param mixed  $data
     * @param bool   $batch
     */
    public static function push(string $key, $data, $batch = false)
    {
        static::redis()->push($key, $data, $batch);
    }

    /**
     * 调用redis 方法
     *
     * @param string $method
     * @param array  $args
     *
     * @throws \Exception
     * @return mixed
     */
    public static function __callStatic($method, $args)
    {
        if (! method_exists(static::getPhpRedis(), $method)) {
            throw new NeoException("Method ({$method}) is not in RedisHelper.");
        }

        return call_user_func_array([static::getPhpRedis(), $method], $args);
    }
}
