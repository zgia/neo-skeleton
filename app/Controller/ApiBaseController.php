<?php

namespace App\Controller;

use stdClass;

/**
 * 接口抽象基类
 * Class base
 */
class ApiBaseController extends BaseController
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->request->setAjax(true);
        $this->response->sendAccessControlHeaders();
    }

    /**
     * 重写父类beforeRender方法，防止影响其它引用NEO的项目
     */
    public function beforeRender()
    {
    }

    /**
     * 输出JSON格式的数据
     *
     * @param null|string $errMsg
     * @param int         $errCode
     * @param null|array  $data
     * @param int         $responseCode
     */
    protected function resp(?string $errMsg = null, int $errCode = I_SUCCESS, ?array $data = null, int $responseCode = 200)
    {
        $arr = [
            'code' => $errCode,
            'msg' => $errMsg ?: '',
            'data' => $data ?: new stdClass(),
        ];

        printOutJSON($arr, $responseCode);
    }

    /**
     * Not exist?
     *
     * @param string $name
     * @param array  $arguments
     */
    public function __call($name, $arguments)
    {
        printOutJSON(['message' => 'Not Found'], 404);
    }
}
