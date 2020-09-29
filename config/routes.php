<?php

use App\Helper\Route\RouteCollector;

// 路由是否缓存？
define('NEO_ROUTE_CACHE_ENABLE', false);

/**
 * 设置自定义路由
 *
 * @return Closure
 */
function customizedRoutes()
{
    return function (RouteCollector $r) {
        // 首页
        $r->addRoute('GET', '/index', 'App\Controller\Index@index');

        /*
        $r->addGroup(
            '/api',
            function (RouteCollector $r) {
                $r->addRoute('GET', '/index', ['App\Controller\Api\Index', 'index']);
            }
        );
        //*/
    };
}
