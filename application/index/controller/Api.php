<?php
/**
 * Created by PhpStorm.
 * User: xiaojie
 * Date: 2017/10/25
 * Time: 23:15
 */

namespace app\index\controller;


use app\index\table\table;
use app\index\table\tableQuaDepart;
use app\index\table\tableQuaTreeParam;
use app\index\table\tableUser;
use think\controller\Rest;
use think\Request;

class Api extends Rest
{
    public function index()
    {
    //     $ar = [
    //     	{'id':"0-0",'text'=>"质量体系文件"},
    //     	{'id':'1-0','text'=>"质量管理文件",''
    // ];

    }


    public function apiUserAdd()
    {
        table::startTrans();
        try {
            $user_name = Request::instance()->param('user_name');
            $pwd = Request::instance()->param('pwd');
            $logo = Request::instance()->param('logo');


            $tableUser = new tableUser();
            $tableUser->setUserName($user_name);
            $tableUser->setPwd($pwd);
            $tableUser->setLogo($logo);

            if (0 != $tableUser->add()) {
                $data = ['ret_code' => -1, 'ret_desc' => "add failed ".$tableUser->getErrDesc()];
            } else {
                $data = ['ret_code' => 0, 'ret_desc' => "add ok"];
            }

            table::commit();
        }catch (\think\Exception $e){
            $data = ['ret_code' => -2, 'ret_desc' => $e->getMessage()];
            table::rollback();
        }

        return json($data);
    }

    public function apiUserUpdatePwd()
    {
        table::startTrans();
        try {
            $user_name = Request::instance()->param('user_name');
            $old_pwd = Request::instance()->param('old_pwd');
            $new_pwd = Request::instance()->param('new_pwd');


            $tableUser = new tableUser();

            if( 0 != $tableUser->get($user_name) )
            {
                $data = ['ret_code' => -1, 'ret_desc' => "查找不到用户"];
            }
            else{
                if($old_pwd != $tableUser->getPwd()){
                    $data = ['ret_code' => 1, 'ret_desc' => "旧密码不匹配"];
                }
                else{
                    if ( 0 != $tableUser->update($user_name,$new_pwd,null) )
                    {
                        $data = ['ret_code' => 2, 'ret_desc' => "数据库错误"];
                    }
                    else{
                        $data = ['ret_code' => 0, 'ret_desc' => "修改密码成功"];
                    }
                }
            }

            table::commit();
        }catch (\think\Exception $e){
            $data = ['ret_code' => -2, 'ret_desc' => $e->getMessage()];
            table::rollback();
        }

        return json($data);
    }

    public function apiUserLogin()
    {
        $user_name = Request::instance()->param('user_name');
        $pwd = Request::instance()->param('pwd');

        $tableUser = new tableUser();

        if ( 0 != $tableUser->get($user_name) ) {
            $data = ['ret_code' => 1, 'ret_desc' => "用户名或密码错误"];
        }
        else{
            if( $pwd != $tableUser->getPwd() ) {
                $data = ['ret_code' => 1, 'ret_desc' => "用户名或密码错误"];
            }
            else{
                $data = ['ret_code' => 0, 'ret_desc' => "登录成功"];
            }
        }

        return json($data);
    }


    public function apiQuaParamGet()
    {
        $table = new tableQuaTreeParam();

        if( 0 != $table->get(1) )
        {
            $data = ['ret_code' => 1, 'ret_desc' => "数据库获取不到数据"];
        }
        else{
            $data = ['ret_code' => 0, 'ret_desc' =>'获取成功','data' => $table->getAllData()];
        }

        return json($data);
    }

    public function apiQuaDepartGet()
    {
        $table = new tableQuaDepart();

        $r_data = $table->getList();

        if( $r_data != null ) {
            $data = ['ret_code' => 0, 'ret_desc' => '获取成功', 'data'=>$r_data];
        }
        else{
            $data = ['ret_code' => 1, 'ret_desc' => '获取失败'];
        }

        return json($data);
    }

}