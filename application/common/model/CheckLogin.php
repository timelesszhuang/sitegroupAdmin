<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/2/24
 * Time: 10:37
 */
namespace app\common\model;

use think\Config;
use think\Cookie;
use think\Crypt\driver\Crypt;
use think\Request;
use think\Session;
use think\Url;
use think\Validate;
use think\Model;
use app\common\model\User;

class CheckLogin extends Model
{
    /**
     * 验证登陆
     * @return array|bool
     */
    public function checklogin()
    {
        $request = Request::instance()->post();
//        //检查验证码
//        if(!captcha_check($request['captcha'])){
//            //验证失败
//            return ["msg"=>"验证码错误","status"=>"error"];
//        };
//        //调用规则 验证用户名和密码不为空
//        $rule=$this->loginRule($request);
//        if($rule){
//            return $rule;
//        }
        $userinfo = $this->checkUser($request["user_name"], $request["pwd"]);
        if ($userinfo["status"] == "error") {
            return $userinfo;
        }
        $remember_me = 0;
        if (!empty($request["remember_me"])) {
            $remember_me = 1;
        }
        return $this->checktype($userinfo["model"], $remember_me);
    }

    /**
     * 验证规则 用户名和密码
     * @param $data
     * @return array|bool
     */
    public function loginRule($data)
    {
        $validate = new Validate([
            'user_name|用户名' => 'require|max:25',
            'pwd|密码' => 'require'
        ]);
        if (!$validate->check($data)) {
            return ["status" => "error", "msg" => $validate->getError()];
        }
        return false;
    }

    /**
     * 检查用户名和密码
     * @param $user
     * @param $pwd
     * @return array|bool
     */
    public function checkUser($user, $pwd)
    {
        $user_instance = User::get(["user_name" => $user]);
        //用户名错误
        if (is_null($user_instance)) {
            return ["status" => "error", "msg" => "用户名或密码错误"];
        }
        if (md5($pwd . $user) != $user_instance->pwd) {
            return ["status" => "error", "msg" => "密码错误"];
        }
        $instance_arr = $user_instance->toArray();
        return ["status" => "success", "model" => $instance_arr];
    }

    /**
     * 检查类型 并返回要跳转的url
     * @param $userinfo  用户信息数组
     * @param $remember_me  是否记住用户  1表示记住
     * @return string
     */
    public function checktype($userinfo, $remember_me)
    {
        $jump_url = Url::build("admin/index/index");
        switch ($userinfo["type"]) {
            //大后台
            case 1:
                Session::set("admin_user", $userinfo["user_name"]);
                Session::set("admin_id", $userinfo["id"]);
                Session::set("admin_name", $userinfo["name"]);
                Session::set("admin_type", $userinfo["type"]);
                break;
            //节点后台
            case 2:
                Session::set("index_user", $userinfo["user_name"]);
                Session::set("index_id", $userinfo["id"]);
                Session::set("index_name", $userinfo["name"]);
                Session::set("index_type", $userinfo["type"]);
                Session::set("index_node_id",$userinfo["node_id"]);
                //这里还需要获取用户的公司信息等等
                $jump_url = Url::build("index/index/index");
                break;
        }
        //说明需要写入cookie中去
        if ($remember_me == 1) {
            //获取私钥
            $private = Config::get("crypt.cookiePrivate");
            //对用户id进行加密
            $puserid = Crypt::encrypt($userinfo["id"], $private);
            $psalt = Crypt::encrypt($userinfo["salt"], $private);
            $time = 7 * 86400;
            Cookie::set("rebUserId", $puserid, $time);
            Cookie::set("rebSalt", $psalt, $time);
        }
        return ["status" => "success", "msg" => "登陆成功,即将进行跳转...", "url" => $jump_url];
    }


}