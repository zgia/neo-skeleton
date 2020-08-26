<?php

namespace App\Service;

use Neo\Base\Model as NeoModel;
use Neo\Base\NeoBase;
use Neo\Traiter\GuzzleHttpClientTraiter;

/**
 * Class BaseService
 */
abstract class BaseService extends NeoBase
{
    use GuzzleHttpClientTraiter;

    // ***********************************************
    // 所有的模型初始化
    // ***********************************************

    /**
     * 获取某个数据库表对应的标准模型
     *
     * 注：如果不想传入主键，则需要将第二个参数设置为NULL，即：setTable($tbl, NULL);
     *
     * @param string $table   表名
     * @param string $tableid 表的主键
     *
     * @return NeoModel
     */
    public static function neoModel(string $table, ?string $tableid = null)
    {
        $model = new NeoModel();

        $model->setTable($table);
        $model->setTableid($tableid ?: $table . 'id');

        return $model;
    }
}
