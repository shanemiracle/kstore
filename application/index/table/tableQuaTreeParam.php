<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 2017/7/13
 * Time: 10:24
 */

namespace app\index\table;

use think\Db;

class tableQuaTreeParam
{
    private $tableName = 'qua_tree_param';

    public static $s_id = 0;

    private $id;
    private $tree_name;
    private $is_init;
    private $sys_one_addr;
    private $sys_two_addr;
    private $sys_three_addr;
    private $record_pre;
    private $icon_1;
    private $icon_1_5;
    private $icon_2;
    private $icon_3;
    private $icon_rec;

    private $all_data;

    /**
     * tableBasicInfo constructor.
     */
    public function __construct()
    {
        if(tableQuaTreeParam::$s_id == 0 ) {
            $sql = "select max(id) as id from ".$this->tableName;
            $data = Db::query($sql);
            if($data){
                tableQuaTreeParam::$s_id = $data[0]['id'];
            }
        }
    }
    public function add()
    {
        return 1;
    }

    public function update()
    {

        return 1;
    }

    public function get($id)
    {
        $sql = "SELECT * FROM ".$this->tableName." WHERE id=:id LIMIT 1;";

        $data = Db::query($sql,['id' =>$id]);
//        if($data){
//            $this->id = $data[0]['id'];
//            $this->tree_name = $data[0]['tree_name'];
//            $this->is_init = $data[0]['is_init'];
//            $this->sys_one_addr = $data[0]['sys_one_addr'];
//            $this->sys_two_addr = $data[0]['sys_two_addr'];
//            $this->sys_three_addr = $data[0]['sys_three_addr'];
//            $this->record_pre = $data[0]['record_pre'];
//            $this->icon_1 = $data[0]['icon_1'];
//            $this->icon_1_5 = $data[0]['icon_1_5'];
//            $this->icon_2 = $data[0]['icon_2'];
//            $this->icon_3 = $data[0]['icon_3'];
//            $this->icon_rec = $data[0]['icon_rec'];
//
//            $this->all_data = $data[0];
//
//            return 0;
//        }
//
//        return -1;
        return $data;
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
    public function getTreeName()
    {
        return $this->tree_name;
    }

    /**
     * @param mixed $tree_name
     */
    public function setTreeName($tree_name)
    {
        $this->tree_name = $tree_name;
    }

    /**
     * @return mixed
     */
    public function getIsInit()
    {
        return $this->is_init;
    }

    /**
     * @param mixed $is_init
     */
    public function setIsInit($is_init)
    {
        $this->is_init = $is_init;
    }

    /**
     * @return mixed
     */
    public function getSysOneAddr()
    {
        return $this->sys_one_addr;
    }

    /**
     * @param mixed $sys_one_addr
     */
    public function setSysOneAddr($sys_one_addr)
    {
        $this->sys_one_addr = $sys_one_addr;
    }

    /**
     * @return mixed
     */
    public function getSysTwoAddr()
    {
        return $this->sys_two_addr;
    }

    /**
     * @param mixed $sys_two_addr
     */
    public function setSysTwoAddr($sys_two_addr)
    {
        $this->sys_two_addr = $sys_two_addr;
    }

    /**
     * @return mixed
     */
    public function getSysThreeAddr()
    {
        return $this->sys_three_addr;
    }

    /**
     * @param mixed $sys_three_addr
     */
    public function setSysThreeAddr($sys_three_addr)
    {
        $this->sys_three_addr = $sys_three_addr;
    }

    /**
     * @return mixed
     */
    public function getRecordPre()
    {
        return $this->record_pre;
    }

    /**
     * @param mixed $record_pre
     */
    public function setRecordPre($record_pre)
    {
        $this->record_pre = $record_pre;
    }

    /**
     * @return mixed
     */
    public function getIcon1()
    {
        return $this->icon_1;
    }

    /**
     * @param mixed $icon_1
     */
    public function setIcon1($icon_1)
    {
        $this->icon_1 = $icon_1;
    }

    /**
     * @return mixed
     */
    public function getIcon15()
    {
        return $this->icon_1_5;
    }

    /**
     * @param mixed $icon_1_5
     */
    public function setIcon15($icon_1_5)
    {
        $this->icon_1_5 = $icon_1_5;
    }

    /**
     * @return mixed
     */
    public function getIcon2()
    {
        return $this->icon_2;
    }

    /**
     * @param mixed $icon_2
     */
    public function setIcon2($icon_2)
    {
        $this->icon_2 = $icon_2;
    }

    /**
     * @return mixed
     */
    public function getIcon3()
    {
        return $this->icon_3;
    }

    /**
     * @param mixed $icon_3
     */
    public function setIcon3($icon_3)
    {
        $this->icon_3 = $icon_3;
    }

    /**
     * @return mixed
     */
    public function getIconRec()
    {
        return $this->icon_rec;
    }

    /**
     * @param mixed $icon_rec
     */
    public function setIconRec($icon_rec)
    {
        $this->icon_rec = $icon_rec;
    }

    /**
     * @return mixed
     */
    public function getAllData()
    {
        return $this->all_data;
    }

    /**
     * @param mixed $all_data
     */
    public function setAllData($all_data)
    {
        $this->all_data = $all_data;
    }



}