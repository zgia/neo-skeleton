<?php

/**
 * 自定义参数
 *
 * @param \Neo\Neo $neo
 */
function setMiscParams($neo)
{
    $mps = [];

    //性别
    $mps['gender'] = [
        '1' => '男',
        '2' => '女',
    ];

    $neo->miscparam = $mps;
}

/**
 * 系统使用的缓存 Keys
 *
 * 分类与参数使用冒号分隔
 *
 * @param \Neo\Neo $neo
 */
function setCacheKeys($neo)
{
    $neo->cacheKeys = [];
}

/**
 * 导航菜单的路由设置
 *
 * @return array
 */
function setNaviMap()
{
    // key: URL
    // value: 想对应显示的菜单项
    return [];
}

/**
 * 自定义常量
 */
function initUserInterfaceConstants()
{
}
