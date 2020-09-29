<?php

namespace App\Controller;

use Neo\Base\Controller;
use Neo\Exception\NeoException;

/**
 * Class BaseController
 */
class BaseController extends Controller
{
    /**
     * @var array
     */
    protected $helpers = [];

    /**
     * @var array
     */
    protected $services = [];

    /**
     * 当前控制器是否需要登录验证，默认需要登录
     *
     * @var bool
     */
    protected $needSignin = true;

    /**
     * 允许未登录访问方法
     * @var array
     */
    protected $allowNoLoginMethods = [];

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        switch ($this->getRequestMethod()) {
            case 'GET':
            case 'DELETE':
            case 'PUT':
            case 'POST':
                break;
            case 'OPTIONS':
                $this->options();
                break;
            default:
                $this->forbidden(405);
                break;
        }

        foreach ($this->helpers as $helper) {
            $class = $this->getClassName($helper);

            $this->{$class} = loadHelper($helper);
        }

        foreach ($this->services as $service) {
            $class = $this->getClassName($service);

            $this->{$class} = loadService($service);
        }

        $this->request->setAjax(true);
        $this->response->sendAccessControlHeaders();
    }

    /**
     * Request Method(大写字符串)
     *
     * @return string The request method
     */
    public function getRequestMethod()
    {
        return $this->request->getMethod();
    }

    /**
     * 获取类的变量的名称
     *
     * User\Address AS Address   => Address
     * User\Address              => Address
     * Address                   => Address
     *
     * @param string $class
     *
     * @return string
     */
    protected function getClassName(string &$class)
    {
        if (stripos($class, ' AS ') !== false) {
            [$class, $clazz] = explode(' AS ', $class);
        } elseif (stripos($class, '\\') !== false) {
            $pieces = explode('\\', $class);
            $clazz = end($pieces);
        } else {
            $clazz = $class;
        }

        return $clazz;
    }

    /**
     * 添加助手类
     *
     * @param mixed ...$helpers
     */
    protected function addHelpers(...$helpers)
    {
        $this->_merge($this->helpers, $helpers);
    }

    /**
     * 添加业务类
     *
     * @param mixed ...$services
     */
    protected function addServices(...$services)
    {
        $this->_merge($this->services, $services);
    }

    /**
     * 合并2个数组,并去重
     *
     * @param array $classes
     * @param array $args
     */
    private function _merge(array &$classes, array $args)
    {
        $classes = array_unique(array_merge($classes, $args));
    }

    /**
     * 403 Forbidden
     *
     * @param int $code
     */
    protected function forbidden(int $code = 403)
    {
        byebye($code);
    }

    /**
     * HTTP Header: OPTIONS
     */
    protected function options()
    {
        byebye(204);
    }

    /**
     * 输出JSON格式的数据
     *
     * @param string     $errMsg
     * @param int        $errCode
     * @param null|array $data
     * @param int        $responseCode
     */
    protected function resp(string $errMsg, int $errCode = I_SUCCESS, ?array $data = null, int $responseCode = 200)
    {
        $arr = [
            'code' => $errCode,
            'msg' => $errMsg ?: '',
            'data' => $data ?: new \stdClass(),
        ];

        printOutJSON($arr, $responseCode);
    }

    /**
     * 重载
     *
     * @param $name
     * @param $arguments
     */
    public function __call($name, $arguments)
    {
        throw new NeoException("方法 {$name} 不存在。");
    }
}
