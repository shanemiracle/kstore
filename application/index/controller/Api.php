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
    //     $ar = [
    //     	{'id':"0-0",'text'=>"质量体系文件"},
    //     	{'id':'1-0','text'=>"质量管理文件",''
    // ];
    	$ar = [
    		['id'=>'0-0','text'=>"质量体系管理系统"],
    		['id'=>'1-0','text'=>"质量体系文件",'parent'=>'0-0'],
    		['id'=>'2-0','text'=>"SOP100 控制文件",'parent'=>'1-0'],
    		['id'=>'2-1','text'=>"SOP200 管理文件",'parent'=>'1-0'],
    	];
        
        return $this->response($ar,'json',200);
    }
}