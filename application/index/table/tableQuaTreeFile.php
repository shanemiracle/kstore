<?php
/**
 * Created by PhpStorm.
 * User: xiaojie
 * Date: 2017/10/31
 * Time: 10:51
 */

namespace app\index\table;


use think\Db;
use think\Log;

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
    private $size;
    private $create_time;

    private $all_data;

    public function __construct()
    {
        if(tableQuaTreeFile::$s_id == 0 ) {
            $sql = "select max(id) as id from ".$this->tableName;
            $data = Db::query($sql);
            if($data){
                tableQuaTreeFile::$s_id = $data[0]['id'];
            }
        }
    }

    public function getByParent($parent_id,$type){
        $sql = "SELECT * FROM ".$this->tableName." WHERE parent_id=:parent_id and type=:type LIMIT 1;";

        $data = Db::query($sql,['parent_id' =>$parent_id,'type'=>$type]);

        return $data;
    }

    public function get($id)
    {
        $sql = "SELECT * FROM ".$this->tableName." WHERE id=:id LIMIT 1;";

        $data = Db::query($sql,['id' =>$id]);
        if($data){
            $this->id = $data[0]['id'];
            $this->parent_id = $data[0]['parent_id'];
            $this->type = $data[0]['type'];
            $this->self_ver = $data[0]['self_ver'];
            $this->refresh_ver = $data[0]['refresh_ver'];
            $this->remark = $data[0]['remark'];
            $this->address = $data[0]['address'];
            $this->depart = $data[0]['depart'];
            $this->create_user = $data[0]['create_user'];
            $this->create_time = $data[0]['create_time'];

            $this->all_data = $data[0];

        }
        return $data;
    }

    public function add(){
        $sql = "INSERT INTO ".$this->tableName." (`id`, `parent_id`, `type`, `self_ver`, `refresh_ver`, `remark`, `address`, `depart`, `create_user`,`size`) VALUES (:id, :parent_id, :type, :self_ver, :refresh_ver, :remark, :address, :depart, :create_user, :size);";

        $data=[];

        if($this->parent_id==null){
            $data['parent_id'] = 0;
        }
        else{
            $data['parent_id'] = $this->parent_id;
        }

        if($this->type==null){
            $data['type'] = 0;
        }
        else{
            $data['type'] = $this->type;
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

        if($this->size==null){
            $data['size'] = 0;
        }
        else{
            $data['size'] = $this->size;
        }

        $data['id'] =(tableQuaTreeFile::$s_id+1);

        Log::info($sql);
        Log::info($data);
        Log::info(tableQuaTreeFile::$s_id);
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

        if($this->size!=null){
            $updateSql .= ',`size`=:size';
            $updateData['size'] = $this->size;
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
    public function getParentId()
    {
        return $this->parent_id;
    }

    /**
     * @param mixed $parent_id
     */
    public function setParentId($parent_id)
    {
        $this->parent_id = $parent_id;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
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
    public function getRemark()
    {
        return $this->remark;
    }

    /**
     * @param mixed $remark
     */
    public function setRemark($remark)
    {
        $this->remark = $remark;
    }

    /**
     * @return mixed
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param mixed $address
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }

    /**
     * @return mixed
     */
    public function getDepart()
    {
        return $this->depart;
    }

    /**
     * @param mixed $depart
     */
    public function setDepart($depart)
    {
        $this->depart = $depart;
    }

    /**
     * @return mixed
     */
    public function getCreateUser()
    {
        return $this->create_user;
    }

    /**
     * @param mixed $create_user
     */
    public function setCreateUser($create_user)
    {
        $this->create_user = $create_user;
    }

    /**
     * @return mixed
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * @param mixed $size
     */
    public function setSize($size)
    {
        $this->size = $size;
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



}