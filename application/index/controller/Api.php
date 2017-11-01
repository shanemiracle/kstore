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
use think\Log;
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

            $address = '/file/' . $fatherPath . '/' . $filename;

            $data = ['ret_code' => 0, 'ret_desc' => '上传成功', 'address' => $address, 'size' => $size];
        } else {
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
        $address = Request::instance()->param('address');
        $remark = Request::instance()->param('remark');
        $depart = Request::instance()->param('depart');
        $size = Request::instance()->param('size');
        $create_user = Request::instance()->param('create_user');

        $data = ['ret_code' => -1, 'ret_desc' => '异常失败'];

        $tableQuaTree = new tableQuaTree();
        $tableQuaTreeFile = new tableQuaTreeFile();

        table::startTrans();

        try {

            $suffixAr = explode('.', $address);
            $num = count($suffixAr);
            if ($num <= 1) {
                $suffix = '';
            } else {
                $suffix = $suffixAr[$num - 1];
            }

            $tableQuaTree->setLevel(1);
            $tableQuaTree->setSeq(0);
            $tableQuaTree->setLevelRemark($cn_name);
            $tableQuaTree->setParent($parent_id);
            $tableQuaTree->setSelfVer(0);
            $tableQuaTree->setRefreshVer(0);
            $tableQuaTree->setChildCreateNum(0);
            $tableQuaTree->setChildRecordCreateNum(0);
            $tableQuaTree->setCnName($cn_name);
            $tableQuaTree->setEnName($en_name);
            $tableQuaTree->setSuffix($suffix);

            if (0 != $tableQuaTree->add()) {
                $data = ['ret_code' => 1, 'ret_desc' => '添加 qua_tree 失败'];
                table::rollback();
                goto Finish;
            }

            $tableQuaTreeFile->setParentId($tableQuaTree->getId());
            $tableQuaTreeFile->setType(1);
            $tableQuaTreeFile->setSelfVer(0);
            $tableQuaTreeFile->setRefreshVer(0);
            $tableQuaTreeFile->setRemark($remark);
            $tableQuaTreeFile->setAddress($address);
            $tableQuaTreeFile->setDepart($depart);
            $tableQuaTreeFile->setCreateUser($create_user);
            $tableQuaTreeFile->setSize($size);

            if (0 != $tableQuaTreeFile->add()) {
                $data = ['ret_code' => 2, 'ret_desc' => '添加 qua_tree_file 失败'];
                table::rollback();
                goto Finish;
            }
            $data = ['ret_code' => 0, 'ret_desc' => '添加成功'];

            table::commit();
        } catch (Exception $e) {

            table::rollback();
            $data = ['ret_code' => -1, 'ret_desc' => $e->getMessage()];
        }

        Finish:
        return json($data);
    }

    private function treeLevelAddr($level, $tableData)
    {

        if (1 == $level) {
            return $tableData['sys_one_addr'];
        } else if (2 == $level || 3 == $level) {
            return $tableData['sys_two_addr'];
        } else if (4 == $level) {
            return $tableData['sys_three_addr'];
        } else if (5 == $level) {
            return sprintf("%s-%s", $tableData['record_pre'], $tableData['sys_one_addr']);
        } else if (6 == $level) {
            return sprintf("%s-%s", $tableData['record_pre'], $tableData['sys_two_addr']);
        } else if (7 == $level) {
            return sprintf("%s-%s", $tableData['record_pre'], $tableData['sys_three_addr']);
        }

        return '';
    }

    private function treeIconAddr($level, $tableData)
    {

        if (1 == $level) {
            return $tableData['icon_1'];
        } else if (2 == $level) {
            return $tableData['icon_1_5'];
        } else if (3 == $level) {
            return $tableData['icon_2'];
        } else if (4 == $level) {
            return $tableData['icon_3'];
        } else if (5 == $level || 6 == $level || 7 == $level) {
            return $tableData['icon_rec'];
        }

        return '';
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

//            $r_data = [['id' => '0-0', 'text' => '质量体系管理系统', 'parent' => '#']];
            $r_data = [];

            for ($i = 0; $i < count($treeData); $i++) {
                $node = $treeData[$i];
                if(2 == $node['level']){
                    $text = sprintf("%s %s %s", $this->treeLevelAddr($node['level'], $param[0]),($node['seq']+1)*100,$node['level_remark']);
                }
                else if(3 == $node['level'] || 4 == $node['level'] || 5 == $node['level'] || 6 == $node['level'] || 7 == $node['level']){
                    $text = sprintf("%s %d %s", $this->treeLevelAddr($node['level'], $param[0]),$node['seq'],$node['level_remark']);
                }
                else{
                    $text = sprintf("%s %s", $this->treeLevelAddr($node['level'], $param[0]), $node['level_remark']);
                }

                array_push($r_data, [
                    'id' => sprintf("%d-%d", $node['level'], $node['seq']),
                    'text' => $text,
                    'parent' => $node['parent'], 'icon'=>$this->treeIconAddr($node['level'], $param[0])

                    ]
                );

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

        $table = new tableQuaTree();

        try {
            $r_data = $table->nameCheck($level, $cn_name);
            if ($r_data) {
                return 'false';
            }
        } catch (Exception $e) {
            Log::alert('apiQuaTreeFileNameCheck ' . $e->getMessage());
        }

        Finish:
        return 'true';

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
        $parent_id = Request::instance()->param('parent_id');
        $start_num = Request::instance()->param('start_num');
        $level_remark = Request::instance()->param('level_remark');

        $data = ['ret_code' => -1, 'ret_desc' => '异常失败'];

        $tableQuaTreeF = new tableQuaTree();
        $tableQuaTreeC = new tableQuaTree();

        table::startTrans();

        try {
            $parent_id_ar = explode('-',$parent_id);
            if(2 != count($parent_id_ar)||$parent_id_ar[0]!= '1') {
                $data = ['ret_code' => 1, 'ret_desc' => '父节点不属于第一层'];
                goto Finish;
            }

            if( 0 !=$tableQuaTreeF->getByLvel($parent_id_ar[0],$parent_id_ar[1]) ){
                $data = ['ret_code' => 3, 'ret_desc' => '父节点获取失败'];
                table::rollback();
                goto Finish;
            }

            $tableQuaTreeC->setLevel(2);
            $tableQuaTreeC->setSeq($tableQuaTreeF->getChildCreateNum());
            $tableQuaTreeC->setLevelRemark($level_remark);
            $tableQuaTreeC->setParent($parent_id);
            $tableQuaTreeC->setSelfVer(0);
            $tableQuaTreeC->setRefreshVer(0);
            $tableQuaTreeC->setChildCreateNum($start_num);
            $tableQuaTreeC->setChildRecordCreateNum(0);
            $tableQuaTreeC->setCnName($level_remark);
            $tableQuaTreeC->setEnName('');
            $tableQuaTreeC->setSuffix('');

            if( 0 != $tableQuaTreeC->add() ){
                $data = ['ret_code' => 2, 'ret_desc' => '添加子节点失败'];
                table::rollback();
                goto Finish;
            }



            $tableQuaTreeF->setChildCreateNum($tableQuaTreeF->getChildCreateNum()+1);
            if( 0 != $tableQuaTreeF->update($tableQuaTreeF->getId()) ){
                $data = ['ret_code' => 4, 'ret_desc' => '父节点修改child_num失败'];
                table::rollback();
                goto Finish;
            }

            table::commit();
            $data = ['ret_code' => 0, 'ret_desc' => '成功'];

        }catch (Exception $e) {
            $data = ['ret_code' => -1, 'ret_desc' => $e->getMessage()];
        }

        Finish:
        return json($data);
    }

    public function apiQuaTree1_5Update()
    {
        $id = Request::instance()->param('id');
        $level_remark = Request::instance()->param('level_remark');
    }

    public function apiQuaTree2Add()
    {
        $parent_id = Request::instance()->param('parent_id');
        $cn_name = Request::instance()->param('cn_name');
        $en_name = Request::instance()->param('en_name');
        $address = Request::instance()->param('address');
        $remark = Request::instance()->param('remark');
        $depart = Request::instance()->param('depart');
        $size = Request::instance()->param('size');
        $create_user = Request::instance()->param('create_user');

        $data = ['ret_code' => -1, 'ret_desc' => '异常失败'];

        $tableQuaTreeF = new tableQuaTree();
        $tableQuaTreeC = new tableQuaTree();
        $tableQuaTreeFile = new tableQuaTreeFile();

        table::startTrans();

        try {
            $parent_id_ar = explode('-',$parent_id);
            if(2 != count($parent_id_ar)||$parent_id_ar[0]!= '2') {
                $data = ['ret_code' => 1, 'ret_desc' => '父节点不属于第1.5层'];
                goto Finish;
            }

            $suffixAr = explode('.', $address);
            $num = count($suffixAr);
            if ($num <= 1) {
                $suffix = '';
            } else {
                $suffix = $suffixAr[$num - 1];
            }


            if( 0 !=$tableQuaTreeF->getByLvel($parent_id_ar[0],$parent_id_ar[1]) ){
                $data = ['ret_code' => 3, 'ret_desc' => '父节点获取失败'];
                table::rollback();
                goto Finish;
            }

            $tableQuaTreeC->setLevel(3);
            $tableQuaTreeC->setSeq(($tableQuaTreeF->getSeq()+1)*100+$tableQuaTreeF->getChildCreateNum());
            $tableQuaTreeC->setLevelRemark($cn_name);
            $tableQuaTreeC->setParent($parent_id);
            $tableQuaTreeC->setSelfVer(0);
            $tableQuaTreeC->setRefreshVer(0);
            $tableQuaTreeC->setChildCreateNum(0);
            $tableQuaTreeC->setChildRecordCreateNum(0);
            $tableQuaTreeC->setCnName($cn_name);
            $tableQuaTreeC->setEnName($en_name);
            $tableQuaTreeC->setSuffix($suffix);

            if (0 != $tableQuaTreeC->add()) {
                $data = ['ret_code' => 1, 'ret_desc' => '添加 qua_tree 失败'];
                table::rollback();
                goto Finish;
            }

            $tableQuaTreeFile->setParentId(intval($tableQuaTreeC->getId()));
            $tableQuaTreeFile->setType(1);
            $tableQuaTreeFile->setSelfVer(0);
            $tableQuaTreeFile->setRefreshVer(0);
            $tableQuaTreeFile->setRemark($remark);
            $tableQuaTreeFile->setAddress($address);
            $tableQuaTreeFile->setDepart($depart);
            $tableQuaTreeFile->setCreateUser($create_user);
            $tableQuaTreeFile->setSize($size);

            if (0 != $tableQuaTreeFile->add()) {
                $data = ['ret_code' => 2, 'ret_desc' => '添加 qua_tree_file 失败'];
                table::rollback();
                goto Finish;
            }

            $tableQuaTreeF->setChildCreateNum($tableQuaTreeF->getChildCreateNum()+1);
            if( 0 != $tableQuaTreeF->update($tableQuaTreeF->getId()) ){
                $data = ['ret_code' => 4, 'ret_desc' => '父节点修改child_num失败'];
                table::rollback();
                goto Finish;
            }

            $data = ['ret_code' => 0, 'ret_desc' => '添加成功'];

            table::commit();
        } catch (Exception $e) {

            table::rollback();
            $data = ['ret_code' => -1, 'ret_desc' => $e->getMessage()];
        }

        Finish:
        return json($data);
    }

    public function apiQuaTree3Add()
    {
        $parent_id = Request::instance()->param('parent_id');
        $cn_name = Request::instance()->param('cn_name');
        $en_name = Request::instance()->param('en_name');
        $address = Request::instance()->param('address');
        $remark = Request::instance()->param('remark');
        $depart = Request::instance()->param('depart');
        $size = Request::instance()->param('size');
        $create_user = Request::instance()->param('create_user');

        $data = ['ret_code' => -1, 'ret_desc' => '异常失败'];

        $tableQuaTreeF = new tableQuaTree();
        $tableQuaTreeC = new tableQuaTree();
        $tableQuaTreeFile = new tableQuaTreeFile();

        table::startTrans();

        try {
            $parent_id_ar = explode('-',$parent_id);
            if(2 != count($parent_id_ar)||$parent_id_ar[0]!= '3') {
                $data = ['ret_code' => 1, 'ret_desc' => '父节点不属于第1.5层'];
                goto Finish;
            }

            $suffixAr = explode('.', $address);
            $num = count($suffixAr);
            if ($num <= 1) {
                $suffix = '';
            } else {
                $suffix = $suffixAr[$num - 1];
            }


            if( 0 !=$tableQuaTreeF->getByLvel($parent_id_ar[0],$parent_id_ar[1]) ){
                $data = ['ret_code' => 3, 'ret_desc' => '父节点获取失败'];
                table::rollback();
                goto Finish;
            }

            $tableQuaTreeC->setLevel(4);
            $tableQuaTreeC->setSeq($tableQuaTreeF->getSeq()*10+$tableQuaTreeF->getChildCreateNum());
            $tableQuaTreeC->setLevelRemark($cn_name);
            $tableQuaTreeC->setParent($parent_id);
            $tableQuaTreeC->setSelfVer(0);
            $tableQuaTreeC->setRefreshVer(0);
            $tableQuaTreeC->setChildCreateNum(0);
            $tableQuaTreeC->setChildRecordCreateNum(0);
            $tableQuaTreeC->setCnName($cn_name);
            $tableQuaTreeC->setEnName($en_name);
            $tableQuaTreeC->setSuffix($suffix);

            if (0 != $tableQuaTreeC->add()) {
                $data = ['ret_code' => 1, 'ret_desc' => '添加 qua_tree 失败'];
                table::rollback();
                goto Finish;
            }

            $tableQuaTreeFile->setParentId(intval($tableQuaTreeC->getId()));
            $tableQuaTreeFile->setType(1);
            $tableQuaTreeFile->setSelfVer(0);
            $tableQuaTreeFile->setRefreshVer(0);
            $tableQuaTreeFile->setRemark($remark);
            $tableQuaTreeFile->setAddress($address);
            $tableQuaTreeFile->setDepart($depart);
            $tableQuaTreeFile->setCreateUser($create_user);
            $tableQuaTreeFile->setSize($size);

            if (0 != $tableQuaTreeFile->add()) {
                $data = ['ret_code' => 2, 'ret_desc' => '添加 qua_tree_file 失败'];
                table::rollback();
                goto Finish;
            }

            $tableQuaTreeF->setChildCreateNum($tableQuaTreeF->getChildCreateNum()+1);
            if( 0 != $tableQuaTreeF->update($tableQuaTreeF->getId()) ){
                $data = ['ret_code' => 4, 'ret_desc' => '父节点修改child_num失败'];
                table::rollback();
                goto Finish;
            }

            $data = ['ret_code' => 0, 'ret_desc' => '添加成功'];

            table::commit();
        } catch (Exception $e) {

            table::rollback();
            $data = ['ret_code' => -1, 'ret_desc' => $e->getMessage()];
        }

        Finish:
        return json($data);
    }


}