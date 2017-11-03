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

    public function apiQuaTree1_5Update()
    {
        $id = Request::instance()->param('id');
        $level_remark = Request::instance()->param('level_remark');
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
                if (2 == $node['level']) {
                    $text = sprintf("%s%d %s", $this->treeLevelAddr($node['level'], $param[0]), $node['parent_seq'] * 10, $node['level_remark']);
                } else if (3 == $node['level'] || 4 == $node['level']) {
                    $text = sprintf("%s%d %s", $this->treeLevelAddr($node['level'], $param[0]), ($node['parent_seq'] + $node['next_seq']), $node['level_remark']);
                } else if (5 == $node['level']) {
                    $text = sprintf("%s-%d %s", $this->treeLevelAddr($node['level'], $param[0]), $node['rec_seq'], $node['level_remark']);
                } else if (6 == $node['level'] || 7 == $node['level']) {
                    $text = sprintf("%s%d-%d %s", $this->treeLevelAddr($node['level'], $param[0]), $node['parent_seq'], $node['rec_seq'], $node['level_remark']);
                } else {
                    $text = sprintf("%s %s", $this->treeLevelAddr($node['level'], $param[0]), $node['level_remark']);
                }

                array_push($r_data, [
                        'id' => sprintf("%d-%d", $node['level'], $node['id']),
                        'text' => $text,
                        'parent' => $node['parent'], 'icon' => $this->treeIconAddr($node['level'], $param[0])

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

    public function apiQuaTreeInfoGet()
    {
        $id = Request::instance()->param('id');
        $data = ['ret_code' => -1, 'ret_desc' => '异常失败'];
        $tableTree = new tableQuaTree();

        try {
            $r_data = $tableTree->get($id);
            if (null == $r_data) {
                $data = ['ret_code' => 1, 'ret_desc' => '获取失败'];
            } else {
                $data = ['ret_code' => 0, 'ret_desc' => '成功', 'data' => $r_data];
            }

        } catch (Exception $e) {
            $data = ['ret_code' => -1, 'ret_desc' => $e->getMessage()];
        }

        Finish:
        return json($data);

    }

    public function apiQuaTreeFileListGet()
    {
        $id = Request::instance()->param('id');

        $data = ['ret_code' => -1, 'ret_desc' => '异常失败'];


        try {
            $tableParam = new tableQuaTreeParam();
            $tableTreeFile = new tableQuaTreeFile();
            $tableTree = new tableQuaTree();

            $param = $tableParam->get(1);
            if (null == $param) {
                $data = ['ret_code' => 1, 'ret_desc' => '获取配置参数错误'];
                goto Finish;
            }

            $id_ar = explode('-', $id);
            if (2 != count($id_ar)) {
                $data = ['ret_code' => -1, 'ret_desc' => 'id格式错误' . $id];
                goto Finish;
            }

            $file_ar = null;

            if (1 == $id_ar[0] || 3 == $id_ar[0] || 4 == $id_ar[0]) {
                $file_ar = $tableTreeFile->getByParent($id_ar[1], 1);
            } else if (5 == $id_ar[0] || 6 == $id_ar[0] || 7 == $id_ar[0]) {
                $file_ar = $tableTreeFile->getByParent($id_ar[1], 2);
            }

            if (null == $file_ar) {
                $data = ['ret_code' => 0, 'ret_desc' => '无法获取文件', 'data' => ''];
                goto Finish;
            }

            $tree = $tableTree->get($id_ar[1]);
            if (null == $tree) {
                $data = ['ret_code' => 0, 'ret_desc' => '无法获取文件', 'data' => ''];
                goto Finish;
            }

            $treeNode = $tree[0];

            $r_data = [];

            for ($i = 0; $i < count($file_ar); $i++) {
                $fileNode = $file_ar[$i];
                if ($id_ar[0] == 1) {
                    $file_name = sprintf("%s_%s%s_%03d%s", $this->treeLevelAddr($id_ar[0], $param[0]),
                        $treeNode['cn_name'], $treeNode['en_name'] == null ? '' : '_' . $treeNode['en_name'], $fileNode['self_ver'], $treeNode['suffix']);

                } else if ($id_ar[0] == 5 || $id_ar[0] == 6 || $id_ar[0] == 7) {
                    $file_name = sprintf("%s_%d_%s%s_%03d_%03d%s", $this->treeLevelAddr($id_ar[0], $param[0]), ($treeNode['parent_seq'] + $treeNode['next_seq']),
                        $treeNode['cn_name'], $treeNode['en_name'] == null ? '' : '_' . $treeNode['en_name'], $fileNode['self_ver'], $fileNode['refresh_ver'], $treeNode['suffix']);

                } else {
                    $file_name = sprintf("%s_%d_%s%s_%03d%s", $this->treeLevelAddr($id_ar[0], $param[0]), ($treeNode['parent_seq'] + $treeNode['next_seq']),
                        $treeNode['cn_name'], $treeNode['en_name'] == null ? '' : '_' . $treeNode['en_name'], $fileNode['self_ver'], $treeNode['suffix']);

                }

                array_push($r_data, ['file_name' => $file_name, 'address' => $fileNode['address'], 'remark' => $fileNode['remark'],
                    'depart' => $fileNode['depart'], 'create_user' => $fileNode['create_user'], 'size' => $fileNode['size'], 'create_time' => $fileNode['create_time']]);
            }

            $data = ['ret_code' => 0, 'ret_desc' => '成功', 'data' => $r_data];


        } catch (Exception $e) {
            $data = ['ret_code' => -1, 'ret_desc' => $e->getMessage()];
        }

        Finish:
        return json($data);
    }

    public function apiQuaTree0Add()
    {
        $parent = Request::instance()->param('parent');
        $remark = Request::instance()->param('remark');

        $data = ['ret_code' => -1, 'ret_desc' => '异常失败'];

        $tableQuaTree = new tableQuaTree();

        table::startTrans();

        try {

            $tableQuaTree->setLevel(0);
            $tableQuaTree->setParentSeq(0);
            $tableQuaTree->setNextSeq(0);
            $tableQuaTree->setRecSeq(0);
            $tableQuaTree->setLevelRemark($remark);
            $tableQuaTree->setParent($parent);
            $tableQuaTree->setSelfVer(0);
            $tableQuaTree->setRefreshVer(0);
            $tableQuaTree->setChildCreateNum(0);
            $tableQuaTree->setChildRecordCreateNum(0);
            $tableQuaTree->setCnName($remark);
            $tableQuaTree->setEnName('');
            $tableQuaTree->setSuffix('');

            if (0 != $tableQuaTree->add()) {
                $data = ['ret_code' => 1, 'ret_desc' => '添加 qua_tree 失败'];
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

        $tableQuaTreeF = new tableQuaTree();
        $tableQuaTreeC = new tableQuaTree();
        $tableQuaTreeFile = new tableQuaTreeFile();

        table::startTrans();

        try {
            $parent_id_ar = explode('-', $parent_id);
            if (2 != count($parent_id_ar) || $parent_id_ar[0] != '0') {
                $data = ['ret_code' => 1, 'ret_desc' => '父节点不属于第0层'];
                goto Finish;
            }

            $suffixAr = explode('.', $address);
            $num = count($suffixAr);
            if ($num <= 1) {
                $suffix = '';
            } else {
                $suffix = $suffixAr[$num - 1];
            }


            if (null == $tableQuaTreeF->get($parent_id_ar[1])) {
                $data = ['ret_code' => 3, 'ret_desc' => '父节点获取失败'];
                table::rollback();
                goto Finish;
            }

            $tableQuaTreeC->setLevel(1);
            $tableQuaTreeC->setParentSeq(0);
            $tableQuaTreeC->setNextSeq($tableQuaTreeF->getNextSeq() * 10 + $tableQuaTreeF->getChildCreateNum());
            $tableQuaTreeC->setRecSeq($tableQuaTreeF->getChildRecordCreateNum());
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

            $tableQuaTreeF->setChildCreateNum($tableQuaTreeF->getChildCreateNum() + 1);
            if (0 != $tableQuaTreeF->update($tableQuaTreeF->getId())) {
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
            $parent_id_ar = explode('-', $parent_id);
            if (2 != count($parent_id_ar) || $parent_id_ar[0] != '1') {
                $data = ['ret_code' => 1, 'ret_desc' => '父节点不属于第一层'];
                goto Finish;
            }

            if (null == $tableQuaTreeF->get($parent_id_ar[1])) {
                $data = ['ret_code' => 3, 'ret_desc' => '父节点获取失败'];
                table::rollback();
                goto Finish;
            }

            $tableQuaTreeC->setLevel(2);
            $tableQuaTreeC->setParentSeq(intval($start_num) / 10);
            $tableQuaTreeC->setNextSeq($tableQuaTreeF->getChildCreateNum());
            $tableQuaTreeC->setRecSeq(0);
            $tableQuaTreeC->setLevelRemark($level_remark);
            $tableQuaTreeC->setParent($parent_id);
            $tableQuaTreeC->setSelfVer(0);
            $tableQuaTreeC->setRefreshVer(0);
            $tableQuaTreeC->setChildCreateNum(0);
            $tableQuaTreeC->setChildRecordCreateNum(0);
            $tableQuaTreeC->setCnName($level_remark);
            $tableQuaTreeC->setEnName('');
            $tableQuaTreeC->setSuffix('');

            if (0 != $tableQuaTreeC->add()) {
                $data = ['ret_code' => 2, 'ret_desc' => '添加子节点失败'];
                table::rollback();
                goto Finish;
            }


            $tableQuaTreeF->setChildCreateNum($tableQuaTreeF->getChildCreateNum() + 1);
            if (0 != $tableQuaTreeF->update($tableQuaTreeF->getId())) {
                $data = ['ret_code' => 4, 'ret_desc' => '父节点修改child_num失败'];
                table::rollback();
                goto Finish;
            }

            table::commit();
            $data = ['ret_code' => 0, 'ret_desc' => '成功'];

        } catch (Exception $e) {
            $data = ['ret_code' => -1, 'ret_desc' => $e->getMessage()];
        }

        Finish:
        return json($data);
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
            $parent_id_ar = explode('-', $parent_id);
            if (2 != count($parent_id_ar) || $parent_id_ar[0] != '2') {
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


            if (null == $tableQuaTreeF->get($parent_id_ar[1])) {
                $data = ['ret_code' => 3, 'ret_desc' => '父节点获取失败'];
                table::rollback();
                goto Finish;
            }

            $tableQuaTreeC->setLevel(3);
            $tableQuaTreeC->setParentSeq($tableQuaTreeF->getParentSeq() * 10);
            $tableQuaTreeC->setNextSeq($tableQuaTreeF->getChildCreateNum());
            $tableQuaTreeC->setRecSeq(0);
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

            $tableQuaTreeF->setChildCreateNum($tableQuaTreeF->getChildCreateNum() + 1);
            if (0 != $tableQuaTreeF->update($tableQuaTreeF->getId())) {
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
            $parent_id_ar = explode('-', $parent_id);
            if (2 != count($parent_id_ar) || $parent_id_ar[0] != '3') {
                $data = ['ret_code' => 1, 'ret_desc' => '父节点不属于第2层'];
                goto Finish;
            }

            $suffixAr = explode('.', $address);
            $num = count($suffixAr);
            if ($num <= 1) {
                $suffix = '';
            } else {
                $suffix = $suffixAr[$num - 1];
            }


            if (null == $tableQuaTreeF->get($parent_id_ar[1])) {
                $data = ['ret_code' => 3, 'ret_desc' => '父节点获取失败'];
                table::rollback();
                goto Finish;
            }

            $tableQuaTreeC->setLevel(4);
            $tableQuaTreeC->setParentSeq(($tableQuaTreeF->getParentSeq() + $tableQuaTreeF->getNextSeq()) * 10);
            $tableQuaTreeC->setNextSeq($tableQuaTreeF->getChildCreateNum());
            $tableQuaTreeC->setRecSeq($tableQuaTreeC->getChildRecordCreateNum());
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

            $tableQuaTreeF->setChildCreateNum($tableQuaTreeF->getChildCreateNum() + 1);
            if (0 != $tableQuaTreeF->update($tableQuaTreeF->getId())) {
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

    public function apiQuaTree1RecAdd()
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

        $tableQuaTreeC = new tableQuaTree();
        $tableQuaTreeF = new tableQuaTree();
        $tableQuaTreeFile = new tableQuaTreeFile();

        table::startTrans();

        try {
            $parent_id_ar = explode('-', $parent_id);
            if (2 != count($parent_id_ar) || $parent_id_ar[0] != '1') {
                $data = ['ret_code' => 1, 'ret_desc' => '父节点不属于第1层'];
                goto Finish;
            }

            $suffixAr = explode('.', $address);
            $num = count($suffixAr);
            if ($num <= 1) {
                $suffix = '';
            } else {
                $suffix = '.' . $suffixAr[$num - 1];
            }


            if (null == $tableQuaTreeF->get($parent_id_ar[1])) {
                $data = ['ret_code' => 3, 'ret_desc' => '父节点获取失败'];
                table::rollback();
                goto Finish;
            }

            $tableQuaTreeC->setLevel(5);
//            $tableQuaTreeC->setParentSeq(($tableQuaTreeF->getParentSeq()+$tableQuaTreeF->getNextSeq())*10);
            $tableQuaTreeC->setParentSeq(($tableQuaTreeF->getParentSeq() + $tableQuaTreeF->getNextSeq()) * 10);
            $tableQuaTreeC->setNextSeq(0);
            $tableQuaTreeC->setRecSeq($tableQuaTreeF->getChildRecordCreateNum());
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


            $tableQuaTreeFile->setParentId($tableQuaTreeC->getId());
            $tableQuaTreeFile->setType(2);
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

            $tableQuaTreeF->setChildRecordCreateNum($tableQuaTreeF->getChildRecordCreateNum() + 1);
            if (0 != $tableQuaTreeF->update($tableQuaTreeF->getId())) {
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

    public function apiQuaTree2RecAdd()
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

        $tableQuaTreeC = new tableQuaTree();
        $tableQuaTreeF = new tableQuaTree();
        $tableQuaTreeFile = new tableQuaTreeFile();

        table::startTrans();

        try {
            $parent_id_ar = explode('-', $parent_id);
            if (2 != count($parent_id_ar) || $parent_id_ar[0] != '3') {
                $data = ['ret_code' => 1, 'ret_desc' => '父节点不属于第2层'];
                goto Finish;
            }

            $suffixAr = explode('.', $address);
            $num = count($suffixAr);
            if ($num <= 1) {
                $suffix = '';
            } else {
                $suffix = '.' . $suffixAr[$num - 1];
            }


            if (null == $tableQuaTreeF->get($parent_id_ar[1])) {
                $data = ['ret_code' => 3, 'ret_desc' => '父节点获取失败'];
                table::rollback();
                goto Finish;
            }

            $tableQuaTreeC->setLevel(6);
            $tableQuaTreeC->setParentSeq($tableQuaTreeF->getParentSeq() + $tableQuaTreeF->getNextSeq());
            $tableQuaTreeC->setNextSeq(0);
            $tableQuaTreeC->setRecSeq($tableQuaTreeF->getChildRecordCreateNum());
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


            $tableQuaTreeFile->setParentId($tableQuaTreeC->getId());
            $tableQuaTreeFile->setType(2);
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

            $tableQuaTreeF->setChildRecordCreateNum($tableQuaTreeF->getChildRecordCreateNum() + 1);
            if (0 != $tableQuaTreeF->update($tableQuaTreeF->getId())) {
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

    public function apiQuaTree3RecAdd()
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

        $tableQuaTreeC = new tableQuaTree();
        $tableQuaTreeF = new tableQuaTree();
        $tableQuaTreeFile = new tableQuaTreeFile();

        table::startTrans();

        try {
            $parent_id_ar = explode('-', $parent_id);
            if (2 != count($parent_id_ar) || $parent_id_ar[0] != '4') {
                $data = ['ret_code' => 1, 'ret_desc' => '父节点不属于第3层'];
                goto Finish;
            }

            $suffixAr = explode('.', $address);
            $num = count($suffixAr);
            if ($num <= 1) {
                $suffix = '';
            } else {
                $suffix = '.' . $suffixAr[$num - 1];
            }


            if (null == $tableQuaTreeF->get($parent_id_ar[1])) {
                $data = ['ret_code' => 3, 'ret_desc' => '父节点获取失败'];
                table::rollback();
                goto Finish;
            }

            $tableQuaTreeC->setLevel(7);
            $tableQuaTreeC->setParentSeq($tableQuaTreeF->getParentSeq() + $tableQuaTreeF->getNextSeq());
            $tableQuaTreeC->setNextSeq(0);
            $tableQuaTreeC->setRecSeq($tableQuaTreeF->getChildRecordCreateNum());
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


            $tableQuaTreeFile->setParentId($tableQuaTreeC->getId());
            $tableQuaTreeFile->setType(2);
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

            $tableQuaTreeF->setChildRecordCreateNum($tableQuaTreeF->getChildRecordCreateNum() + 1);
            if (0 != $tableQuaTreeF->update($tableQuaTreeF->getId())) {
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

    public function apiQuaTree1Update()
    {
        $id = Request::instance()->param('id');
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
            $id_ar = explode('-', $id);
            if (2 != count($id_ar) || $id_ar[0] != '1') {
                $data = ['ret_code' => 1, 'ret_desc' => '节点不属于第1层'];
                goto Finish;
            }

            $suffixAr = explode('.', $address);
            $num = count($suffixAr);
            if ($num <= 1) {
                $suffix = '';
            } else {
                $suffix = $suffixAr[$num - 1];
            }


            if (null == $tableQuaTree->get($id_ar[1])) {
                $data = ['ret_code' => 3, 'ret_desc' => '节点获取失败'];
                table::rollback();
                goto Finish;
            }

            $tableQuaTreeFile->setParentId(intval($tableQuaTree->getId()));
            $tableQuaTreeFile->setType(1);
            $tableQuaTreeFile->setSelfVer($tableQuaTree->getSelfVer() + 1);
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

            $tableQuaTree->setLevelRemark($cn_name);
            $tableQuaTree->setSelfVer($tableQuaTree->getSelfVer() + 1);
            $tableQuaTree->setRefreshVer(0);
            $tableQuaTree->setCnName($cn_name);
            $tableQuaTree->setEnName($en_name);
            $tableQuaTree->setSuffix($suffix);

            if (0 != $tableQuaTree->update($tableQuaTree->getId())) {
                $data = ['ret_code' => 1, 'ret_desc' => '更新节点失败'];
                table::rollback();
                goto Finish;
            }


            $data = ['ret_code' => 0, 'ret_desc' => '成功'];

            table::commit();
        } catch (Exception $e) {

            table::rollback();
            $data = ['ret_code' => -1, 'ret_desc' => $e->getMessage()];
        }

        Finish:
        return json($data);
    }

    public function apiQuaTree2Update()
    {
        $id = Request::instance()->param('id');
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
            $id_ar = explode('-', $id);
            if (2 != count($id_ar) || $id_ar[0] != '3') {
                $data = ['ret_code' => 1, 'ret_desc' => '节点不属于第2层'];
                goto Finish;
            }

            $suffixAr = explode('.', $address);
            $num = count($suffixAr);
            if ($num <= 1) {
                $suffix = '';
            } else {
                $suffix = $suffixAr[$num - 1];
            }


            if (null == $tableQuaTree->get($id_ar[1])) {
                $data = ['ret_code' => 3, 'ret_desc' => '节点获取失败'];
                table::rollback();
                goto Finish;
            }

            $tableQuaTreeFile->setParentId(intval($tableQuaTree->getId()));
            $tableQuaTreeFile->setType(1);
            $tableQuaTreeFile->setSelfVer($tableQuaTree->getSelfVer() + 1);
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

            $tableQuaTree->setLevelRemark($cn_name);
            $tableQuaTree->setSelfVer($tableQuaTree->getSelfVer() + 1);
            $tableQuaTree->setRefreshVer(0);
            $tableQuaTree->setCnName($cn_name);
            $tableQuaTree->setEnName($en_name);
            $tableQuaTree->setSuffix($suffix);

            if (0 != $tableQuaTree->update($tableQuaTree->getId())) {
                $data = ['ret_code' => 1, 'ret_desc' => '更新节点失败'];
                table::rollback();
                goto Finish;
            }


            $data = ['ret_code' => 0, 'ret_desc' => '成功'];

            table::commit();
        } catch (Exception $e) {

            table::rollback();
            $data = ['ret_code' => -1, 'ret_desc' => $e->getMessage()];
        }

        Finish:
        return json($data);
    }

    public function apiQuaTree3Update()
    {
        $id = Request::instance()->param('id');
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
            $id_ar = explode('-', $id);
            if (2 != count($id_ar) || $id_ar[0] != '4') {
                $data = ['ret_code' => 1, 'ret_desc' => '节点不属于第3层'];
                goto Finish;
            }

            $suffixAr = explode('.', $address);
            $num = count($suffixAr);
            if ($num <= 1) {
                $suffix = '';
            } else {
                $suffix = $suffixAr[$num - 1];
            }


            if (null == $tableQuaTree->get($id_ar[1])) {
                $data = ['ret_code' => 3, 'ret_desc' => '节点获取失败'];
                table::rollback();
                goto Finish;
            }

            $tableQuaTreeFile->setParentId(intval($tableQuaTree->getId()));
            $tableQuaTreeFile->setType(1);
            $tableQuaTreeFile->setSelfVer($tableQuaTree->getSelfVer() + 1);
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

            $tableQuaTree->setLevelRemark($cn_name);
            $tableQuaTree->setSelfVer($tableQuaTree->getSelfVer() + 1);
            $tableQuaTree->setRefreshVer(0);
            $tableQuaTree->setCnName($cn_name);
            $tableQuaTree->setEnName($en_name);
            $tableQuaTree->setSuffix($suffix);

            if (0 != $tableQuaTree->update($tableQuaTree->getId())) {
                $data = ['ret_code' => 1, 'ret_desc' => '更新节点失败'];
                table::rollback();
                goto Finish;
            }


            $data = ['ret_code' => 0, 'ret_desc' => '成功'];

            table::commit();
        } catch (Exception $e) {

            table::rollback();
            $data = ['ret_code' => -1, 'ret_desc' => $e->getMessage()];
        }

        Finish:
        return json($data);
    }

    public function apiQuaTree1RecUpdate()
    {
        $id = Request::instance()->param('id');
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
            $id_ar = explode('-', $id);
            if (2 != count($id_ar) || $id_ar[0] != '5') {
                $data = ['ret_code' => 1, 'ret_desc' => '节点不属于第1层记录文件'];
                goto Finish;
            }

            $suffixAr = explode('.', $address);
            $num = count($suffixAr);
            if ($num <= 1) {
                $suffix = '';
            } else {
                $suffix = $suffixAr[$num - 1];
            }


            if (null == $tableQuaTree->get($id_ar[1])) {
                $data = ['ret_code' => 3, 'ret_desc' => '节点获取失败'];
                table::rollback();
                goto Finish;
            }

            $tableQuaTreeFile->setParentId(intval($tableQuaTree->getId()));
            $tableQuaTreeFile->setType(2);
            $tableQuaTreeFile->setSelfVer($tableQuaTree->getSelfVer() + 1);
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

            $tableQuaTree->setLevelRemark($cn_name);
            $tableQuaTree->setSelfVer($tableQuaTree->getSelfVer() + 1);
            $tableQuaTree->setRefreshVer(0);
            $tableQuaTree->setCnName($cn_name);
            $tableQuaTree->setEnName($en_name);
            $tableQuaTree->setSuffix($suffix);

            if (0 != $tableQuaTree->update($tableQuaTree->getId())) {
                $data = ['ret_code' => 1, 'ret_desc' => '更新节点失败'];
                table::rollback();
                goto Finish;
            }


            $data = ['ret_code' => 0, 'ret_desc' => '成功'];

            table::commit();
        } catch (Exception $e) {

            table::rollback();
            $data = ['ret_code' => -1, 'ret_desc' => $e->getMessage()];
        }

        Finish:
        return json($data);
    }

    public function apiQuaTree2RecUpdate()
    {
        $id = Request::instance()->param('id');
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
            $id_ar = explode('-', $id);
            if (2 != count($id_ar) || $id_ar[0] != '6') {
                $data = ['ret_code' => 1, 'ret_desc' => '节点不属于第2层记录文件'];
                goto Finish;
            }

            $suffixAr = explode('.', $address);
            $num = count($suffixAr);
            if ($num <= 1) {
                $suffix = '';
            } else {
                $suffix = $suffixAr[$num - 1];
            }


            if (null == $tableQuaTree->get($id_ar[1])) {
                $data = ['ret_code' => 3, 'ret_desc' => '节点获取失败'];
                table::rollback();
                goto Finish;
            }

            $tableQuaTreeFile->setParentId(intval($tableQuaTree->getId()));
            $tableQuaTreeFile->setType(2);
            $tableQuaTreeFile->setSelfVer($tableQuaTree->getSelfVer() + 1);
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

            $tableQuaTree->setLevelRemark($cn_name);
            $tableQuaTree->setSelfVer($tableQuaTree->getSelfVer() + 1);
            $tableQuaTree->setRefreshVer(0);
            $tableQuaTree->setCnName($cn_name);
            $tableQuaTree->setEnName($en_name);
            $tableQuaTree->setSuffix($suffix);

            if (0 != $tableQuaTree->update($tableQuaTree->getId())) {
                $data = ['ret_code' => 1, 'ret_desc' => '更新节点失败'];
                table::rollback();
                goto Finish;
            }


            $data = ['ret_code' => 0, 'ret_desc' => '成功'];

            table::commit();
        } catch (Exception $e) {

            table::rollback();
            $data = ['ret_code' => -1, 'ret_desc' => $e->getMessage()];
        }

        Finish:
        return json($data);
    }

    public function apiQuaTree3RecUpdate()
    {
        $id = Request::instance()->param('id');
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
            $id_ar = explode('-', $id);
            if (2 != count($id_ar) || $id_ar[0] != '7') {
                $data = ['ret_code' => 1, 'ret_desc' => '节点不属于第1层记录文件'];
                goto Finish;
            }

            $suffixAr = explode('.', $address);
            $num = count($suffixAr);
            if ($num <= 1) {
                $suffix = '';
            } else {
                $suffix = $suffixAr[$num - 1];
            }


            if (null == $tableQuaTree->get($id_ar[1])) {
                $data = ['ret_code' => 3, 'ret_desc' => '节点获取失败'];
                table::rollback();
                goto Finish;
            }

            $tableQuaTreeFile->setParentId(intval($tableQuaTree->getId()));
            $tableQuaTreeFile->setType(2);
            $tableQuaTreeFile->setSelfVer($tableQuaTree->getSelfVer() + 1);
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

            $tableQuaTree->setLevelRemark($cn_name);
            $tableQuaTree->setSelfVer($tableQuaTree->getSelfVer() + 1);
            $tableQuaTree->setRefreshVer(0);
            $tableQuaTree->setCnName($cn_name);
            $tableQuaTree->setEnName($en_name);
            $tableQuaTree->setSuffix($suffix);

            if (0 != $tableQuaTree->update($tableQuaTree->getId())) {
                $data = ['ret_code' => 1, 'ret_desc' => '更新节点失败'];
                table::rollback();
                goto Finish;
            }


            $data = ['ret_code' => 0, 'ret_desc' => '成功'];

            table::commit();
        } catch (Exception $e) {

            table::rollback();
            $data = ['ret_code' => -1, 'ret_desc' => $e->getMessage()];
        }

        Finish:
        return json($data);
    }

    public function apiQuaTreeRecRefresh()
    {

        $id = Request::instance()->param('id');
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
            $id_ar = explode('-', $id);
            if (2 != count($id_ar) || ($id_ar[0] != '5' && $id_ar[0] != '6' && $id_ar[0] != '7')) {
                $data = ['ret_code' => 1, 'ret_desc' => '节点不属于记录文件'];
                goto Finish;
            }

            $suffixAr = explode('.', $address);
            $num = count($suffixAr);
            if ($num <= 1) {
                $suffix = '';
            } else {
                $suffix = $suffixAr[$num - 1];
            }


            if (null == $tableQuaTree->get($id_ar[1])) {
                $data = ['ret_code' => 3, 'ret_desc' => '节点获取失败'];
                table::rollback();
                goto Finish;
            }

            $tableQuaTreeFile->setParentId(intval($tableQuaTree->getId()));
            $tableQuaTreeFile->setType(2);
            $tableQuaTreeFile->setSelfVer($tableQuaTree->getRefreshVer() + 1);
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

            $tableQuaTree->setLevelRemark($cn_name);
            $tableQuaTree->setRefreshVer($tableQuaTree->getRefreshVer() + 1);
            $tableQuaTree->setCnName($cn_name);
            $tableQuaTree->setEnName($en_name);
            $tableQuaTree->setSuffix($suffix);

            if (0 != $tableQuaTree->update($tableQuaTree->getId())) {
                $data = ['ret_code' => 1, 'ret_desc' => '更新节点失败'];
                table::rollback();
                goto Finish;
            }


            $data = ['ret_code' => 0, 'ret_desc' => '成功'];

            table::commit();
        } catch (Exception $e) {

            table::rollback();
            $data = ['ret_code' => -1, 'ret_desc' => $e->getMessage()];
        }

        Finish:
        return json($data);

    }

    private function revTreeDelete($parent_id){
        
    }

    public function apiQuaTreeDelete()
    {
        $id = Request::instance()->param('id');
        $data = ['ret_code' => -1, 'ret_desc' => '异常失败'];

        $tableQuaTree = new tableQuaTree();
        $tableQuaTreeFile = new tableQuaTreeFile();

        table::startTrans();

        try {

            $data = ['ret_code' => 0, 'ret_desc' => '成功'];

            table::commit();
        } catch (Exception $e) {

            table::rollback();
            $data = ['ret_code' => -1, 'ret_desc' => $e->getMessage()];
        }

        Finish:
        return json($data);
    }

}


