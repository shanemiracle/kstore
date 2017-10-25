<?php
/**
 * Created by PhpStorm.
 * User: xiaojie
 * Date: 2017/10/25
 * Time: 23:15
 */

namespace app\index\controller;


use think\controller\Rest;

class Api extends Rest
{
    public function index()
    {
        $ar = ['root'=>'k1','child'=>'c1'];
        return $this->response($ar,'json',200);
    }
}