<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/2/23
 * Time: 16:30
 */
namespace app\admin\behavior;

use think\Controller;
use think\Cookie;
use think\Crypt\driver\Crypt;
use think\Session;
use think\Config;

class AuthenticateBehavior extends Controller
{
    /**
     * 统一命令执行入口  里面调用其他方法
     * @param $params
     */
    public function run(&$params)
    {
        $this->$params();
    }

    /**
     * 检查是否登陆
     */
    public function adminCheckAuth()
    {
        if (empty(Session::has("admin_user"))) {
            $this->redirect("admin/login/login");
        }
    }

    /**
     * 检查cookie免登陆跳转跳转
     */
    public function checkCookie()
    {
        $CuserId = Cookie::get("rebUserId");
        $Csalt = Cookie::get("rebSalt");
        if (!empty($CuserId) && !empty($Csalt)) {
            //获取私钥
            $private = Config::get("crypt.cookiePrivate");
            $userId = Crypt::decrypt($CuserId, $private);
            $userSalt = Crypt::decrypt($Csalt, $private);
            $instance = \app\common\model\User::get($userId);
            $user_info = $instance->toArray();
            if($userSalt!=$user_info["salt"]){
                return;
            }
            if (!empty($user_info)) {
                $info_arr = (new \app\common\model\CheckLogin())->checktype($user_info, 0);
                $this->redirect($info_arr["url"]);
            }
        }
    }


}