<?php
/**
 * Created by PhpStorm.
 * User: xiaojie
 * Date: 2017/10/28
 * Time: 16:26
 */

namespace app\index\table;


use think\Db;

class tableQuaTree
{
    private $tableName = 'qua_tree';
    public static $s_id = 0;

    private $id;
    private $level;
    private $seq;
    private $level_addr;
    private $level_remark;
    private $parent;
    private $self_ver;
    private $refresh_ver;
    private $child_create_num;
    private $child_record_create_num;
    private $cn_name;
    private $en_name;
    private $suffix;
    private $create_time;

    private $all_data;


    public function __construct()
    {
        if(tableQuaTree::$s_id == 0 ) {
            $sql = "select max(id) as id from ".$this->tableName;
            $data = Db::query($sql);
            if($data){
                tableQuaTree::$s_id = $data[0]['id'];
            }
        }
    }

    public function get($id)
    {
        $sql = "select * from ".$this->tableName." where id=:id LIMIT 1;";

        $data = Db::query($sql,['id' =>$id]);

        return $data;
//        if($data){
//            $this->id = $data[0]['id'];
//            $this->level = $data[0]['level'];
//            $this->seq = $data[0]['seq'];
//            $this->level_addr = $data[0]['level_addr'];
//            $this->level_remark = $data[0]['level_remark'];
//            $this->parent = $data[0]['parent'];
//            $this->self_ver = $data[0]['self_ver'];
//            $this->refresh_ver = $data[0]['refresh_ver'];
//            $this->child_create_num = $data[0]['child_create_num'];
//            $this->child_record_create_num = $data[0]['child_record_create_num'];
//            $this->cn_name = $data[0]['cn_name'];
//            $this->en_name = $data[0]['en_name'];
//            $this->suffix = $data[0]['suffix'];
//            $this->create_time = $data[0]['create_time'];
//
//            $this->all_data = $data[0];
//
//            return 0;
//        }
//
//        return -1;
    }

    public function add(){
        $sql = "INSERT INTO ".$this->tableName." (`id`, `level`, `seq`, `level_addr`, `level_remark`, `parent`, `self_ver`, `refresh_ver`, `child_create_num`, `child_record_create_num`, `cn_name`, `en_name`, `suffix`) VALUES (:id, :level, :seq, :level_addr, :level_remark, :parent, :self_ver, :refresh_ver, :child_create_num, :child_record_create_num, :cn_name, :en_name, :suffix);";

        $data=[];

        if($this->level==null){
            $data['level'] = '';
        }
        else{
            $data['level'] = $this->level;
        }

        if($this->seq==null){
            $data['seq'] = '';
        }
        else{
            $data['seq'] = $this->seq;
        }

        if($this->level_addr==null){
            $data['level_addr'] = '';
        }
        else{
            $data['level_addr'] = $this->level_addr;
        }

        if($this->level_remark==null){
            $data['level_remark'] = '';
        }
        else{
            $data['level_remark'] = $this->level_remark;
        }

        if($this->parent==null){
            $data['parent'] = '';
        }
        else{
            $data['parent'] = $this->parent;
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

        if($this->child_create_num==null){
            $data['child_create_num'] = '';
        }
        else{
            $data['child_create_num'] = $this->child_create_num;
        }

        if($this->child_record_create_num==null){
            $data['child_record_create_num'] = '';
        }
        else{
            $data['child_record_create_num'] = $this->child_record_create_num;
        }

        if($this->cn_name==null){
            $data['cn_name'] = '';
        }
        else{
            $data['cn_name'] = $this->cn_name;
        }

        if($this->en_name==null){
            $data['en_name'] = '';
        }
        else{
            $data['en_name'] = $this->en_name;
        }

        if($this->suffix==null){
            $data['suffix'] = '';
        }
        else{
            $data['suffix'] = $this->suffix;
        }

        $data['id'] =(tableQuaTree::$s_id+1);
        $ret = Db::execute($sql,$data);
        if($ret == 1 )
        {
            tableQuaTree::$s_id += 1;
            $this->id = tableQuaTree::$s_id;
        }

        return !($this->id != 0);
    }

    public function update($id)
    {
        $updateSql = '';

        $updateData=[];


        if($this->level!=null){
            $updateSql .= ',`level`=:level';
            $updateData['level'] = $this->level;
        }

        if($this->seq!=null){
            $updateSql .= ',`seq`=:seq';
            $updateData['seq'] = $this->seq;
        }

        if($this->level_addr!=null){
            $updateSql .= ',`level_addr`=:level_addr';
            $updateData['level_addr'] = $this->level_addr;
        }

        if($this->level_remark!=null){
            $updateSql .= ',`level_remark`=:level_remark';
            $updateData['level_remark'] = $this->level_remark;
        }

        if($this->parent!=null){
            $updateSql .= ',`parent`=:parent';
            $updateData['parent'] = $this->parent;
        }

        if($this->self_ver!=null){
            $updateSql .= ',`self_ver`=:self_ver';
            $updateData['self_ver'] = $this->self_ver;
        }

        if($this->refresh_ver!=null){
            $updateSql .= ',`refresh_ver`=:refresh_ver';
            $updateData['refresh_ver'] = $this->refresh_ver;
        }

        if($this->child_create_num!=null){
            $updateSql .= ',`child_create_num`=:child_create_num';
            $updateData['child_create_num'] = $this->child_create_num;
        }

        if($this->child_record_create_num!=null){
            $updateSql .= ',`child_record_create_num`=:child_record_create_num';
            $updateData['child_record_create_num'] = $this->child_record_create_num;
        }

        if($this->cn_name!=null){
            $updateSql .= ',`cn_name`=:cn_name';
            $updateData['cn_name'] = $this->cn_name;
        }

        if($this->en_name!=null){
            $updateSql .= ',`en_name`=:en_name';
            $updateData['en_name'] = $this->en_name;
        }

        if($this->suffix!=null){
            $updateSql .= ',`suffix`=:suffix';
            $updateData['suffix'] = $this->suffix;
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

    public function getList()
    {
        $sql = "select * from ".$this->tableName;

        $data = Db::query($sql);
        return $data;
    }



}