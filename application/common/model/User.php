<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/2/24
 * Time: 11:26
 */
namespace app\common\model;

use think\Model;
use think\Session;
use think\Config;
use app\common\model\SiteUtil;
use think\db;
class User extends Model
{
    /**
     * 修改后台密码  大后台和结点后台
     * @param $oldpwd
     * @param $newpwd
     * @param $newpwd2
     * @return array
     */
    public function chpwd($oldpwd, $newpwd, $newpwd2)
    {
        if ($newpwd != $newpwd2) {
            return ["status" => "error", "msg" => "两次输入的新密码不一样"];
        }
        //获取session 通用名称
        $sess_arr = (new SiteUtil)->userInfoExchange();
        $user=User::where(["user_name"=>$sess_arr["account"],"pwd"=>md5($oldpwd.$sess_arr["account"])])->find();
        if(is_null($user)){
            return ["status"=>"error","msg"=>"原密码错误","title"=>"修改密码信息"];
        }
        $user->pwd=md5($newpwd.$sess_arr["account"]);
        if(!$user->save()){
            return ["status"=>"error","msg"=>"密码错误失败","title"=>"修改密码信息"];
        }
        return ["status"=>"success","msg"=>"密码修改成功","title"=>"修改密码信息"];
    }

}