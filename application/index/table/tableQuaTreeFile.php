<?php
/**
 * Created by PhpStorm.
 * User: xiaojie
 * Date: 2017/10/31
 * Time: 10:51
 */

namespace app\index\table;


use think\Db;

class tableQuaTreeFile
{
    private $tableName = 'qua_tree_file';
    public static $s_id = 0;

    private $id;
    private $parent_id;
    private $type;
    private $self_ver;
    private $refresh_ver;
    private $remark;
    private $address;
    private $depart;
    private $create_user;
    private $create_time;

    private $all_data;

    public function __construct()
    {
        if(tableQuaTree::$s_id == 0 ) {
            $sql = "select max(id) as id from ".$this->tableName;
            $data = Db::query($sql);
            if($data){
                tableQuaTreeFile::$s_id = $data[0]['id'];
            }
        }
    }

    public function get($id)
    {
        $sql = "SELECT * FROM ".$this->tableName." WHERE id=:id LIMIT 1;";

        $data = Db::query($sql,['id' =>$id]);
//        if($data){
//            $this->id = $data[0]['id'];
//            $this->parent_id = $data[0]['parent_id'];
//            $this->type = $data[0]['type'];
//            $this->self_ver = $data[0]['self_ver'];
//            $this->refresh_ver = $data[0]['refresh_ver'];
//            $this->remark = $data[0]['remark'];
//            $this->address = $data[0]['address'];
//            $this->depart = $data[0]['depart'];
//            $this->create_user = $data[0]['create_user'];
//            $this->create_time = $data[0]['create_time'];
//
//            $this->all_data = $data[0];
//
//            return 0;
//        }
//
//        return -1;
        return $data;
    }

    public function add(){
        $sql = "INSERT INTO ".$this->tableName." (`id`, `parent_id`, `type`, `self_ver`, `refresh_ver`, `remark`, `address`, `depart`, `create_user`) VALUES (:id, :parent_id, :type, :self_ver, :refresh_ver, :remark, :address, :depart, :create_user);";

        $data=[];

        if($this->parent_id==null){
            $data['parent_id'] = '';
        }
        else{
            $data['parent_id'] = $this->parent_id;
        }

        if($this->type==null){
            $data['type'] = '';
        }
        else{
            $data['type'] = $this->type;
        }

        if($this->self_ver==null){
            $data['self_ver'] = '';
        }
        else{
            $data['self_ver'] = $this->self_ver;
        }

        if($this->refresh_ver==null){
            $data['refresh_ver'] = '';
        }
        else{
            $data['refresh_ver'] = $this->refresh_ver;
        }

        if($this->remark==null){
            $data['remark'] = '';
        }
        else{
            $data['remark'] = $this->remark;
        }

        if($this->address==null){
            $data['address'] = '';
        }
        else{
            $data['address'] = $this->address;
        }

        if($this->depart==null){
            $data['depart'] = '';
        }
        else{
            $data['depart'] = $this->depart;
        }

        if($this->create_user==null){
            $data['create_user'] = '';
        }
        else{
            $data['create_user'] = $this->create_user;
        }

        $data['id'] =(tableQuaTreeFile::$s_id+1);
        $ret = Db::execute($sql,$data);
        if($ret == 1 )
        {
            tableQuaTreeFile::$s_id += 1;
            $this->id = tableQuaTreeFile::$s_id;
        }

        return !($this->id != 0);
    }

    public function update($id)
    {
        $updateSql = '';

        $updateData=[];


        if($this->parent_id!=null){
            $updateSql .= ',`parent_id`=:parent_id';
            $updateData['parent_id'] = $this->parent_id;
        }

        if($this->type!=null){
            $updateSql .= ',`type`=:type';
            $updateData['type'] = $this->type;
        }

        if($this->self_ver!=null){
            $updateSql .= ',`self_ver`=:self_ver';
            $updateData['self_ver'] = $this->self_ver;
        }

        if($this->refresh_ver!=null){
            $updateSql .= ',`refresh_ver`=:refresh_ver';
            $updateData['refresh_ver'] = $this->refresh_ver;
        }

        if($this->remark!=null){
            $updateSql .= ',`remark`=:remark';
            $updateData['remark'] = $this->remark;
        }

        if($this->address!=null){
            $updateSql .= ',`address`=:address';
            $updateData['address'] = $this->address;
        }

        if($this->depart!=null){
            $updateSql .= ',`depart`=:depart';
            $updateData['depart'] = $this->depart;
        }

        if($this->create_user!=null){
            $updateSql .= ',`create_user`=:create_user';
            $updateData['create_user'] = $this->create_user;
        }

        if( $updateSql == '' || $id == null) {

            return -1;
        }

        $updateData['id'] = $id;

        $sql = 'UPDATE '.$this->tableName.' SET '.substr($updateSql,1,strlen($updateSql)).' WHERE `id`=:id LIMIT 1;';

        $ret = Db::execute($sql,$updateData);
//        Log::info("ret".$ret);

        return $ret;
    }


    public function del($id)
    {
        return !(1==Db::table($this->tableName)->where('id',$id)->delete());

    }

}