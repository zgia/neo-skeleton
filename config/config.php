<?php

/**
 * Neo Skeleton 基础配置文件。
 *
 * 本文件包含以下配置选项：
 * Redis 设置，MySQL 设置、密钥
 * 语言、编码设定
 */

/*
 * 系统的运行环境
 * 开发时，请设置为: development
 * 上线部署，请设置为: product
 */
define('NEO_ENVIRONMENT', 'development');

/*
 * 语言设置，默认为中文。
 */
define('NEO_LANG', 'zh-CN');

/*
 * 页面编码设置
 */
define('NEO_CHARSET', 'utf-8');

/*
 * PHP 最小版本
 */
define('NEO_REQUIRED_PHP_VERSION', '7.1.0');

/*
 * 加密盐
 */
define('NEO_SECRET_SALT', 'thisisasalt');

/*
 * 时区，和基于时区的时间偏移
 *
 * @link https://www.php.net/manual/en/timezones.php
 */
$NEO_CONFIG['datetime'] = [
    'zone' => 'Asia/Shanghai',
    'offset' => 28800,
];

/*
 * host: 服务域名
 * template_path: 视图模板路径
 * path: 路径设置，注：结尾不含“/”
 *      如果网址地址是：http://xxx.com/path/to/index.php，path填写： /path/to；
 *      如果网址地址是：http://xxx.com/path/index.php，path填写： /path；
 *      如果网址地址是：http://xxx.com/index.php，path什么都不填写。
 * redirect_page: 页面跳转时，显示信息提示
 */
$NEO_CONFIG['server'] = [
    'host' => 'xxx.com',
    'path' => '',
    'redirect_page' => '',
];

/*
 * Redis 配置
 */
/*
 * 是否启用Redis
 */
define('NEO_REDIS', false);

/*
 * Redis
 */
$NEO_CONFIG['redis'] = [
    'neo' => [
        'master' => [
            'host' => '127.0.0.1',
            'port' => 6271,
            'password' => 'thisisapassword',
            'options' => [
                \Redis::OPT_PREFIX => 'neo:',
            ],
        ],
    ],
];

/*
 * MySQL(>=5.0)
 */
$NEO_CONFIG['database'] = [
    'mysql' => [
        'driver' => 'pdo_mysql',
        'dbname' => 'dbname',
        'prefix' => '',
        'port' => 3306,
        'user' => 'dbuser',
        'password' => 'thisisapassword',
        'charset' => 'utf8mb4',
        'master' => ['host' => '127.0.0.1'],
        'logger' => \Neo\Database\Logger::class,
    ], ];

/*
 * 文件日志级别
 */
// 日志级别: level:
// DEBUG = 100;
// INFO = 200;
// NOTICE = 250;
// WARNING = 300;
// ERROR = 400;
// CRITICAL = 500;
// ALERT = 550;
// EMERGENCY = 600;
// 日志种类: types: file, redis
// NeoLog::info($type, $msg, $context)
$NEO_CONFIG['logger'] = [
    'level' => 200,
    'dir' => dirname(__FILE__, 2) . DIRECTORY_SEPARATOR . 'logs',
    'id' => sha1(uniqid('', true) . str_shuffle(str_repeat('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', 16))),
    'types' => ['file'],
    'file' => [
        'pertype' => true, // 是否每个$type一个日志文件
        'typename' => 'neo', // 如果pertype==false，可以指定日志文件名称，默认为neo
        'formatter' => 'json', // 文件内容格式，默认为json，可选：line, json
    ],
];

/*
 * JWT
 */
$NEO_CONFIG['jwt'] = [
    'neo' => [
        'id' => 'neo',
        'appclient' => 'weapp',
        'secret_key' => NEO_SECRET_SALT,
        'expired_time' => 518400,
    ],
];
