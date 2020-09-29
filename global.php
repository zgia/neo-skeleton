<?php

use App\Helper\MvcHelper;
use Neo\Config;
use Neo\Neo;

// 必须使用其他文件加载global.php
if (! defined('NEO_PHP_SCRIPT')) {
    die('NEO_PHP_SCRIPT must be defined to continue');
}

// 错误报告
ini_set('display_errors', 'on');
error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED);

// 缺省时区
date_default_timezone_set('UTC');

// ABSPATH
if (! defined('ABSPATH')) {
    define('ABSPATH', dirname(__FILE__));
}

// APP_PATH, M-V-C is here
if (! defined('APP_PATH')) {
    define('APP_PATH', ABSPATH . DIRECTORY_SEPARATOR . 'app');
}

// 系统配置
$NEO_CONFIG = [];
require ABSPATH . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'config.php';

// 一些变量
require ABSPATH . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'params.php';

// 自动加载
require ABSPATH . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

(function () use ($NEO_CONFIG) {
    // 初始化NeoFrame
    Config::load($NEO_CONFIG);
    $neo = new Neo(ABSPATH);
    MvcHelper::loadDefault($neo);

    // HTTP方式访问
    if (defined('NEO_LOAD_WEBPAGE') && NEO_LOAD_WEBPAGE) {
        // 路由
        require ABSPATH . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'routes.php';

        MvcHelper::loadWebPage($neo);
    }
})();
