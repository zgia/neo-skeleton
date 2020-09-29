<?php

namespace App\Helper\Route;

use FastRoute\DataGenerator;
use FastRoute\RouteCollector as FastRouteCollector;
use FastRoute\RouteParser;

class RouteCollector extends FastRouteCollector
{
    /**
     * @var RouteHelper
     */
    private $routeHelper;

    /**
     * Constructs a route collector.
     *
     * @param RouteParser   $routeParser
     * @param DataGenerator $dataGenerator
     */
    public function __construct(RouteParser $routeParser, DataGenerator $dataGenerator)
    {
        parent::__construct($routeParser, $dataGenerator);

        $this->routeHelper = neo()->routeHelper;
    }

    /**
     * Adds a route to the collection.
     *
     * The syntax used in the $route string depends on the used route parser.
     *
     * @param string|string[] $httpMethod
     * @param string          $route
     * @param mixed           $handler
     * @param array           $options
     */
    public function addRoute($httpMethod, $route, $handler, array $options = [])
    {
        parent::addRoute($httpMethod, $route, $handler);

        // 扩展属性
        if ($options) {
            $hd = is_array($handler) ? implode('@', $handler) : $handler;
            $this->routeHelper->setOptions($hd, $options);
        }
    }
}
