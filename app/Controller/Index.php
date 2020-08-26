<?php

namespace App\Controller;

/**
 * 首页控制器
 */
class Index extends ApiBaseController
{
    // 不要登录验证
    protected $needSignin = false;

    /**
     * 构造函数
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 首页
     */
    public function index()
    {
        printOutJSON(['message' => '世界真奇妙。']);
    }
}
