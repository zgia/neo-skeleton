<?php

namespace App\Service\System;

use App\Service\BaseService;

/**
 * 系统的一些基础服务
 *
 * Class SystemService
 */
class SystemService extends BaseService
{
    /**
     * 系统设置项会添加一个config前缀
     *
     * @param $key
     *
     * @return string
     */
    protected static function getOptionCacheKey(string $key)
    {
        return 'config:' . $key;
    }

    /**
     * 返回某个系统设置项
     *
     * @param string     $key     系统设置的某个项
     * @param null|mixed $default 没有获取到值时，可以返回一个默认值
     *
     * @return mixed 如果这个项目不存在，则返回NULL
     */
    public static function getOption(string $key, $default = null)
    {
        $opt = neo()->getRedis()->get(static::getOptionCacheKey($key));

        if (is_null($opt)) {
            $opt = $default;
        }

        return $opt;
    }
}
