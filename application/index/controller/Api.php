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
use app\index\table\tableQuaTree;
use app\index\table\tableQuaTreeFile;
use app\index\table\tableQuaTreeParam;
use app\index\table\tableUser;
use think\controller\Rest;
use think\Exception;
use think\Request;

class Api extends Rest
{
    public function index()
    {
        //     $ar = [
        //     	{'id':"0-0",'text'=>"质量体系文件"},
        //     	{'id':'1-0','text'=>"质量管理文件",''
        // ];

        $table = new tableQuaTree();
        $data = $table->getList();
        return json($data);
    }

    public function apiFileUp()
    {
        $data = ['ret_code' => -1, 'ret_desc' => '异常错误'];
        $file = Request::instance()->file('file');
        if (null == $file) {
            $data = ['ret_code' => 1, 'ret_desc' => '缺少file关键字'];

            goto Finish;
        }

        $info = $file->rule('md5')->move(ROOT_PATH . 'public' . DS . 'file');
        if ($info) {
            $size = $info->getSize();

            $filename = $info->getFilename();
            $fatherPath = $info->getPathInfo()->getBasename();

            $address = '/file/'.$fatherPath.'/'.$filename;

            $data = ['ret_code' => 0, 'ret_desc' => '上传成功', 'address'=>$address];
        }
        else{
            $data = ['ret_code' => 2, 'ret_desc' => '上传失败'];
        }

        Finish:

        return json($data);

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
                $data = ['ret_code' => -1, 'ret_desc' => "add failed " . $tableUser->getErrDesc()];
            } else {
                $data = ['ret_code' => 0, 'ret_desc' => "add ok"];
            }

            table::commit();
        } catch (\think\Exception $e) {
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

            if (0 != $tableUser->get($user_name)) {
                $data = ['ret_code' => -1, 'ret_desc' => "查找不到用户"];
            } else {
                if ($old_pwd != $tableUser->getPwd()) {
                    $data = ['ret_code' => 1, 'ret_desc' => "旧密码不匹配"];
                } else {
                    if (0 != $tableUser->update($user_name, $new_pwd, null)) {
                        $data = ['ret_code' => 2, 'ret_desc' => "数据库错误"];
                    } else {
                        $data = ['ret_code' => 0, 'ret_desc' => "修改密码成功"];
                    }
                }
            }

            table::commit();
        } catch (\think\Exception $e) {
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

        if (0 != $tableUser->get($user_name)) {
            $data = ['ret_code' => 1, 'ret_desc' => "用户名或密码错误"];
        } else {
            if ($pwd != $tableUser->getPwd()) {
                $data = ['ret_code' => 1, 'ret_desc' => "用户名或密码错误"];
            } else {
                $data = ['ret_code' => 0, 'ret_desc' => "登录成功"];
            }
        }

        return json($data);
    }


    public function apiQuaParamGet()
    {
        $table = new tableQuaTreeParam();

        try {
            $param = $table->get(1);
            if (null == $param) {
                $data = ['ret_code' => 1, 'ret_desc' => "数据库获取不到数据"];
            } else {
                $data = ['ret_code' => 0, 'ret_desc' => '获取成功', 'data' => $param[0]];
            }
        } catch (Exception $e) {
            $data = ['ret_code' => -1, 'ret_desc' => $e->getMessage()];
        }

        return json($data);
    }

    public function apiQuaDepartGet()
    {
        $table = new tableQuaDepart();

        $r_data = $table->getList();

        if ($r_data != null) {
            $data = ['ret_code' => 0, 'ret_desc' => '获取成功', 'data' => $r_data];
        } else {
            $data = ['ret_code' => 1, 'ret_desc' => '获取失败'];
        }

        return json($data);
    }

    public function apiQuaTree1Add()
    {
        $parent_id = Request::instance()->param('parent_id');
        $cn_name = Request::instance()->param('cn_name');
        $en_name = Request::instance()->param('en_name');
        $suffix = Request::instance()->param('suffix');
        $remark = Request::instance()->param('remark');
        $create_user = Request::instance()->param('create_user');

        $tableParam = new tableQuaTreeParam();
        $tableQuaTree = new tableQuaTree();
        $tableQuaTreeFile = new tableQuaTreeFile();

        try {
            $param = $tableParam->get(1);
//            if()

        } catch (Exception $e) {
            $data = ['ret_code' => -1, 'ret_desc' => $e->getMessage()];
        }

        Finish:
        return json($data);
    }

    public function apiQuaTreeListGet()
    {
        $tableParam = new tableQuaTreeParam();
        $tableQuaTree = new tableQuaTree();
        $data = ['ret_code' => -1, 'ret_desc' => '异常错误'];

        try {
            $param = $tableParam->get(1);
            if (null == $param) {
                $data = ['ret_code' => 1, 'ret_desc' => '获取配置参数错误'];
                goto Finish;
            }

            $treeData = $tableQuaTree->getList();
            if ($treeData == null) {
                $data = ['ret_code' => 2, 'ret_desc' => '获取树结构错误'];
                goto Finish;
            }

            $r_data = [['id' => '0-0', 'text' => '质量体系管理系统', 'parent' => '#', 'icon' => 'icon']];

            for ($i = 0; $i < count($treeData); $i++) {
                $node = $treeData[$i];

                //#####
            }

            $data = ['ret_code' => 0, 'ret_desc' => '获取成功', 'data' => $r_data];
        } catch (Exception $e) {
            $data = ['ret_code' => -1, 'ret_desc' => $e->getMessage()];
        }

        Finish:
        return json($data);
    }

    public function apiQuaTreeFileNameCheck()
    {
        $level = Request::instance()->param('level');
        $cn_name = Request::instance()->param('cn_name');
    }

    public function apiQuaTree1Update()
    {
        $id = Request::instance()->param('id');
        $cn_name = Request::instance()->param('cn_name');
        $en_name = Request::instance()->param('en_name');
        $suffix = Request::instance()->param('suffix');
        $remark = Request::instance()->param('remark');

    }

    public function apiQuaTree1_5Add()
    {
        $start_num = Request::instance()->param('start_num');
        $level_remark = Request::instance()->param('level_remark');
    }

    public function apiQuaTree1_5Update()
    {
        $id = Request::instance()->param('id');
        $level_remark = Request::instance()->param('level_remark');
    }


}