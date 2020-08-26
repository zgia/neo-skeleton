<?php

// 路由是否缓存？
define('NEO_ROUTE_CACHE_ENABLE', false);

/**
 * 设置自定义路由
 *
 * @return Closure
 */
function customizedRoutes()
{
    return function (FastRoute\RouteCollector $r) {
        // 首页
        $r->addRoute('GET', '/index', 'App\Controller\Index@index');
    };
}
