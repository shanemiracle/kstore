<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 2017/7/13
 * Time: 10:24
 */

namespace app\index\table;

use think\Db;
use think\Log;

class tableUser
{
    private $tableName = 'user';

    private $user_name;
    private $pwd;
    private $logo;
    private $create_time;
    private $err_desc;


    public function add()
    {
        $sql = "INSERT INTO ".$this->tableName." (`user_name`, `pwd`, `logo`) VALUES (:user_name,:pwd,:logo);";

        $data=[];

        if(null == $this->user_name){
            Log::alert($this->tableName.' add : null index');
            $this->err_desc = $this->tableName.' add : null index';
            return -1;
        }
        else{
            $data['user_name'] = $this->user_name;
        }

        if(null == $this->pwd){
            Log::alert($this->tableName.' add : pwd is null');
            $this->err_desc = $this->tableName.' add : pwd is null';

            return -1;
        }
        else{
            $data['pwd'] = $this->pwd;
        }

        if(null == $this->logo){
            $data['logo'] = '';
        }
        else{
            $data['logo'] = $this->logo;
        }

        $ret = Db::execute($sql,$data);
        if($ret == 1 )
        {
            return 0;
        }

        return 1;
    }

    public function update($user_name,$pwd,$logo)
    {
        $updateSql = '';

        $updateData=[];


        if(null != $this->pwd){
            $updateSql .= ',`pwd`=:pwd';
            $updateData['pwd'] = $pwd;
        }

        if(null != $this->logo){
            $updateSql .= ',`logo`=:logo';
            $updateData['logo'] = $logo;
        }

        if( $updateSql == '' || $user_name == null) {
            Log::alert($this->tableName." set user_name==null||no data change");
            $this->err_desc = $this->tableName." set user_name==null||no data change";
            return -1;
        }

        $updateData['user_name'] = $user_name;

        $sql = 'UPDATE '.$this->tableName.' SET '.substr($updateSql,1,strlen($updateSql)).' WHERE `user_name`=:user_name LIMIT 1;';

        $ret = Db::execute($sql,$updateData);

        return !($ret == 1);
    }

    public function get($user_name)
    {
        $sql = "SELECT * FROM ".$this->tableName." WHERE user_name=:user_name LIMIT 1;";

        $data = Db::query($sql,['user_name' =>$user_name]);
        if($data){
            $this->user_name = $user_name;
            $this->pwd = $data[0]['pwd'];
            $this->logo = $data[0]['logo'];
            $this->create_time = $data[0]['create_time'];

            return 0;
        }

        return -1;
    }

    /**
     * @return mixed
     */
    public function getUserName()
    {
        return $this->user_name;
    }

    /**
     * @param mixed $user_name
     */
    public function setUserName($user_name)
    {
        $this->user_name = $user_name;
    }

    /**
     * @return mixed
     */
    public function getPwd()
    {
        return $this->pwd;
    }

    /**
     * @param mixed $pwd
     */
    public function setPwd($pwd)
    {
        $this->pwd = $pwd;
    }

    /**
     * @return mixed
     */
    public function getLogo()
    {
        return $this->logo;
    }

    /**
     * @param mixed $logo
     */
    public function setLogo($logo)
    {
        $this->logo = $logo;
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
    public function getErrDesc()
    {
        return $this->err_desc;
    }

    /**
     * @param mixed $err_desc
     */
    public function setErrDesc($err_desc)
    {
        $this->err_desc = $err_desc;
    }




}