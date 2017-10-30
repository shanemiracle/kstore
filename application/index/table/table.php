<?php
/**
 * Created by PhpStorm.
 * User: xiaojie
 * Date: 2017/7/10
 * Time: 下午7:56
 */

namespace app\index\table;


use think\Db;

class table
{
    public static function startTrans() {
        Db::startTrans();
    }

    public static function commit() {
        Db::commit();
    }

    public static function rollback() {
        Db::rollback();
    }

}