<?php
/**
 * Created by PhpStorm.
 * User: xiaojie
 * Date: 2017/10/28
 * Time: 16:26
 */

namespace app\index\table;


use think\Db;

class tableTree
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

    /**
     * tableBasicInfo constructor.
     */
    public function __construct()
    {
        if(tableTree::$s_id == 0 ) {
            $sql = "select max(id) as id from ".$this->tableName;
            $data = Db::query($sql);
            if($data){
                tableTree::$s_id = $data[0]['id'];
            }
        }
    }

    public function get($id)
    {
        $sql = "SELECT * FROM ".$this->tableName." WHERE id=:id LIMIT 1;";

        $data = Db::query($sql,['id' =>$id]);
        if($data){
            $this->id = $data[0]['id'];
            $this->level = $data[0]['level'];
            $this->seq = $data[0]['seq'];
            $this->level_addr = $data[0]['level_addr'];
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

            $this->all_data = $data;

            return 0;
        }

        return -1;
    }

    public function add(){
        

        return !($this->id != 0);
    }




}