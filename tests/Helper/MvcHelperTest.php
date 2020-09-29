<?php

use App\Helper\Route\RouteHelper;
use Neo\Http\Request;

/**
 * @backupGlobals disabled
 *
 * @internal
 * @coversNothing
 */
class MvcHelperTest extends BaseTester
{
    protected function setUp(): void
    {
        parent::setUp();

        require ABSPATH . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'routes.php';
    }

    public function testDispatch()
    {
        $neo = neo();

        $server = ['REQUEST_URI' => '/api/index', 'REQUEST_METHOD' => 'GET'];
        $path = Request::stripQueryString(parse_url($server['REQUEST_URI'], PHP_URL_PATH) ?: '');

        // 路由
        $route = new RH();
        $route->init(
            customizedRoutes(),
            NEO_ROUTE_CACHE_ENABLE,
            ['cacheFile' => $neo['datastore_dir'] . DIRECTORY_SEPARATOR . 'neo_routecaches.php']
        );
        $route->dispatch($server['REQUEST_METHOD'],$path);
    }
}

class RH extends RouteHelper
{
    public function callFunc(array $routeInfo)
    {
        if (is_array($routeInfo[1])) {
            $route = is_array($routeInfo[1]) ? implode('@', $routeInfo[1]) : $routeInfo[1];
        }

        \PHPUnit\Framework\assertEquals('App\Controller\Index@index', $route);
    }
}
