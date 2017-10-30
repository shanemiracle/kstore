<?php
/**
 * Created by PhpStorm.
 * User: xiaojie
 * Date: 2017/10/28
 * Time: 16:16
 */

namespace app\index\table;


use think\Db;

class tableQuaDepart
{
    private $tableName = 'qua_depart';

    private $err_desc;

    public function getList()
    {
        $sql = "SELECT depart_name FROM ".$this->tableName;

        $data = Db::query($sql);
        return $data;
    }
}