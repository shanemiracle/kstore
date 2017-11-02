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
    private $parent_seq;
    private $next_seq;
    private $rec_seq;
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

        if($data){
            $this->id = $data[0]['id'];
            $this->level = $data[0]['level'];
            $this->parent_seq = $data[0]['parent_seq'];
            $this->next_seq = $data[0]['next_seq'];
            $this->rec_seq = $data[0]['rec_seq'];
            $this->level_remark = $data[0]['level_remark'];
            $this->parent = $data[0]['parent'];
            $this->self_ver = $data[0]['self_ver'];
            $this->refresh_ver = $data[0]['refresh_ver'];
            $this->child_create_num = $data[0]['child_create_num'];
            $this->child_record_create_num = $data[0]['child_record_create_num'];
            $this->cn_name = $data[0]['cn_name'];
            $this->en_name = $data[0]['en_name'];
            $this->suffix = $data[0]['suffix'];
            $this->create_time = $data[0]['create_time'];

            $this->all_data = $data[0];

        }

        return $data;
}


    public function add(){
        $sql = "INSERT INTO ".$this->tableName." (`id`, `level`, `parent_seq` ,`next_seq`, `rec_seq`, `level_remark`, `parent`, `self_ver`, `refresh_ver`, `child_create_num`, `child_record_create_num`, `cn_name`, `en_name`, `suffix`) VALUES (:id, :level, :parent_seq, :next_seq, :rec_seq, :level_remark, :parent, :self_ver, :refresh_ver, :child_create_num, :child_record_create_num, :cn_name, :en_name, :suffix);";

        $data=[];

        if($this->level==null){
            $data['level'] = 0;
        }
        else{
            $data['level'] = $this->level;
        }

        if($this->parent_seq ==null){
            $data['parent_seq'] = 0;
        }
        else{
            $data['parent_seq'] = $this->parent_seq;
        }

        if($this->next_seq==null){
            $data['next_seq'] = 0;
        }
        else{
            $data['next_seq'] = $this->next_seq;
        }

        if($this->rec_seq==null){
            $data['rec_seq'] = 0;
        }
        else{
            $data['rec_seq'] = $this->rec_seq;
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
            $data['self_ver'] = 0;
        }
        else{
            $data['self_ver'] = $this->self_ver;
        }

        if($this->refresh_ver==null){
            $data['refresh_ver'] = 0;
        }
        else{
            $data['refresh_ver'] = $this->refresh_ver;
        }

        if($this->child_create_num==null){
            $data['child_create_num'] = 0;
        }
        else{
            $data['child_create_num'] = $this->child_create_num;
        }

        if($this->child_record_create_num==null){
            $data['child_record_create_num'] = 0;
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

        if($this->parent_seq!=null){
            $updateSql .= ',`parent_seq`=:parent_seq';
            $updateData['parent_seq'] = $this->parent_seq;
        }

        if($this->next_seq!=null){
            $updateSql .= ',`next_seq`=:next_seq';
            $updateData['next_seq'] = $this->next_seq;
        }

        if($this->rec_seq!=null){
            $updateSql .= ',`rec_seq`=:rec_seq';
            $updateData['rec_seq'] = $this->rec_seq;
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

        return !($ret == 1);
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

    public function nameCheck($level,$cn_name)
    {
        $sql = "select id from ".$this->tableName.' where level=:level and cn_name=:cn_name limit 1';
        $data = Db::query($sql,['level'=>$level,'cn_name'=>$cn_name]);
        return $data;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * @param mixed $level
     */
    public function setLevel($level)
    {
        $this->level = $level;
    }

    /**
     * @return mixed
     */
    public function getNextSeq()
    {
        return $this->next_seq;
    }

    /**
     * @param mixed $next_seq
     */
    public function setNextSeq($next_seq)
    {
        $this->next_seq = $next_seq;
    }

    /**
     * @return mixed
     */
    public function getLevelAddr()
    {
        return $this->level_addr;
    }

    /**
     * @param mixed $level_addr
     */
    public function setLevelAddr($level_addr)
    {
        $this->level_addr = $level_addr;
    }

    /**
     * @return mixed
     */
    public function getLevelRemark()
    {
        return $this->level_remark;
    }

    /**
     * @param mixed $level_remark
     */
    public function setLevelRemark($level_remark)
    {
        $this->level_remark = $level_remark;
    }

    /**
     * @return mixed
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param mixed $parent
     */
    public function setParent($parent)
    {
        $this->parent = $parent;
    }

    /**
     * @return mixed
     */
    public function getSelfVer()
    {
        return $this->self_ver;
    }

    /**
     * @param mixed $self_ver
     */
    public function setSelfVer($self_ver)
    {
        $this->self_ver = $self_ver;
    }

    /**
     * @return mixed
     */
    public function getRefreshVer()
    {
        return $this->refresh_ver;
    }

    /**
     * @param mixed $refresh_ver
     */
    public function setRefreshVer($refresh_ver)
    {
        $this->refresh_ver = $refresh_ver;
    }

    /**
     * @return mixed
     */
    public function getChildCreateNum()
    {
        return $this->child_create_num;
    }

    /**
     * @param mixed $child_create_num
     */
    public function setChildCreateNum($child_create_num)
    {
        $this->child_create_num = $child_create_num;
    }

    /**
     * @return mixed
     */
    public function getChildRecordCreateNum()
    {
        return $this->child_record_create_num;
    }

    /**
     * @param mixed $child_record_create_num
     */
    public function setChildRecordCreateNum($child_record_create_num)
    {
        $this->child_record_create_num = $child_record_create_num;
    }

    /**
     * @return mixed
     */
    public function getCnName()
    {
        return $this->cn_name;
    }

    /**
     * @param mixed $cn_name
     */
    public function setCnName($cn_name)
    {
        $this->cn_name = $cn_name;
    }

    /**
     * @return mixed
     */
    public function getEnName()
    {
        return $this->en_name;
    }

    /**
     * @param mixed $en_name
     */
    public function setEnName($en_name)
    {
        $this->en_name = $en_name;
    }

    /**
     * @return mixed
     */
    public function getSuffix()
    {
        return $this->suffix;
    }

    /**
     * @param mixed $suffix
     */
    public function setSuffix($suffix)
    {
        $this->suffix = $suffix;
    }

    /**
     * @return mixed
     */
    public function getCreateTime()
    {
        return $this->create_time;
    }

    /**
     * @param mixed $create_time
     */
    public function setCreateTime($create_time)
    {
        $this->create_time = $create_time;
    }

    /**
     * @return mixed
     */
    public function getRecSeq()
    {
        return $this->rec_seq;
    }

    /**
     * @param mixed $rec_seq
     */
    public function setRecSeq($rec_seq)
    {
        $this->rec_seq = $rec_seq;
    }

    /**
     * @return mixed
     */
    public function getParentSeq()
    {
        return $this->parent_seq;
    }

    /**
     * @param mixed $parent_seq
     */
    public function setParentSeq($parent_seq)
    {
        $this->parent_seq = $parent_seq;
    }





}