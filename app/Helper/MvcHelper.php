<?php

namespace App\Helper;

use Neo\Config;
use Neo\Exception\NeoException;
use Neo\HttpAuth\HttpJWT;
use Neo\Neo;
use Neo\Utility;

/**
 * Class MvcHelper
 */
class MvcHelper extends BaseHelper
{
    /**
     * 构造函数
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 401 unauthorized
     */
    public static function unauthorized(): void
    {
        byebye(401);
    }

    /**
     * 获取httpJWT对象
     *
     * @param array $config
     *
     * @return HttpJWT
     */
    public static function httpJwt(?array $config = null): HttpJWT
    {
        static $jwt = [];

        $config || $config = static::getHttpJwtConfig('neo');

        if (! $jwt[$config['id']]) {
            $jwt[$config['id']] = new HttpJWT($config);
        }

        return $jwt[$config['id']];
    }

    /**
     * 获取缺省JWT配置，即当前系统用配置
     *
     * @param string $key
     *
     * @return array
     */
    public static function getHttpJwtConfig(string $key): array
    {
        $config = Config::get('jwt', $key);

        if (! $config) {
            throw new NeoException(__f('Invalid JWT config, maybe key(%s) is not exist', $key));
        }

        return $config;
    }

    /**
     * 系统相关常量
     */
    public static function initFunctionalityConstants(): void
    {
        /*
         * 时间
         */
        // 一天的秒数
        define('SECONDS_DAY', 86400);
        // 一周的秒数
        define('SECONDS_WEEK', 604800);
        // 两周的秒数
        define('SECONDS_TWO_WEEK', 1209600);
        // 一月的秒数
        define('SECONDS_MONTH', 2592000);
        // 一年的秒数
        define('SECONDS_YEAR', 31536000);
        // 定义Redis常默认缓存时间: 一周
        define('REDIS_DEFAULT_TIMEOUT', SECONDS_WEEK);

        // 格式化时间相关
        // 短类型 2011-8-12
        define('FORMAT_DATE_SHORT', 'Y-m-d');
        // 长类型 2011-8-12 11:34:00
        define('FORMAT_DATE_LONG', 'Y-m-d H:i:s');

        /*
         * 性别
         */
        define('GENDER_MALE', 1);
        define('GENDER_FEMALE', 2);

        /*
         * 接口返回
         */
        // 没有错误
        define('I_SUCCESS', 0);
        // 没有成功
        define('I_FAILURE', 1);
    }

    /**
     * 初始化系统设置缓存
     *
     * @param Neo $neo
     */
    public static function initOptionsContant(Neo $neo): void
    {
        // 自定义时区，比如：Asia/Shanghai
        $datetimezone = Config::get('datetime', 'zone');
        if (! $datetimezone) {
            $datetimezone = getOption('datetimezone', date_default_timezone_get() ?: 'UTC');
            Config::set('datetime', 'zone', $datetimezone);
        }

        Config::set('datetime', 'offset', timezone_offset_get(timezone_open($datetimezone), date_create()));

        // 分页每页记录数
        if (! defined('PERPAGE')) {
            define('PERPAGE', 20);
        }

        // 含域名的URL，用于跳转、邮件等等脱离系统环境的URL
        if (! defined('ABSURL')) {
            define('ABSURL', neo()->getRequest()->getSchemeAndHttpHost() . Config::get('server', 'path'));
        }

        // 系统URL
        if (! defined('SYSTEMURL')) {
            define('SYSTEMURL', getOption('baseurl', '') . Config::get('server', 'path'));
        }

        // 使用redirect方法跳转时，添加一个随机数
        if (! defined('REDIRECT_WITH_RANDOM')) {
            define('REDIRECT_WITH_RANDOM', (bool) getOption('redirectwithrandom'));
        }
    }

    /**
     * 载入系统缺省
     *
     * @param Neo $neo
     */
    public static function loadDefault(Neo $neo): void
    {
        // 自定义参数
        setMiscParams($neo);

        // 缓存Key
        setCacheKeys($neo);

        // 初始化Redis
        $neo->setRedis(Neo::initRedis('neo'));

        // 系统设置
        static::initOptionsContant($neo);

        // 系统运行配置
        static::initFunctionalityConstants();

        // 用户自定义常量
        if (function_exists('initUserInterfaceConstants')) {
            initUserInterfaceConstants();
        }

        // 检查PHP版本
        Utility::checkPHPVersion(NEO_REQUIRED_PHP_VERSION);

        // 是否显示SQL解释信息
        $neo->setExplainSQL(getOption('outputexplain') ? (int) $neo->getRequest()->_request('explain') : 0);

        // 是否记录API日志
        $neo['log_api_response'] = (bool) getOption('log_api_response');
        $neo['log_api_request'] = (bool) getOption('log_api_request');
    }

    /**
     * 加载Web页面处理
     *
     * @param Neo $neo
     */
    public static function dispatch(Neo $neo): void
    {
        // 分页导航第几页
        define('CURRENT_PAGE', max($neo->getRequest()->_request('p'), 1));

        // 路由
        $route = new RouteHelper($neo);
        $route->init(
            customizedRoutes(),
            NEO_ROUTE_CACHE_ENABLE,
            ['cacheFile' => $neo['datastore_dir'] . DIRECTORY_SEPARATOR . 'neo_routecaches.php']
        );
        $route->dispatch();
    }
}
