<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/2/24
 * Time: 14:49
 */

namespace app\admin\controller;
use app\common\model\User;
use think\Validate;
use think\Session;
use think\Url;
use think\Cookie;
class Index extends AdminBase
{
    /**
     * 大后台首页html展示
     * @return mixed
     */
    public function index()
    {
        $this->assign([
            "flag"=>"index"
        ]);
        return $this->fetch();
    }

    /**
     * 修改密码页面展示
     * @return mixed
     */
    public function chpwdHtml()
    {
        return view();
    }

    /**
     * 修改后台密码
     * @return array
     */
    public function chpwd()
    {
        $rule=[
            "oldpwd|原密码"=>"require",
            "newpwd|新密码"=>"require|min:7",
            "newpwd2|再次输入密码"=>"require|min:7",
        ];
        $pwd_arr=$this->Rinstance->post();
        $validate=new Validate($rule);
        if(!$validate->check($pwd_arr)){
                return ["status"=>"error","title"=>"验证错误","msg"=>$validate->getError()];
        }
        return (new User())->chAdminpwd($pwd_arr["oldpwd"],$pwd_arr["newpwd"],$pwd_arr["newpwd2"]);
    }
    /**
     * 退出登陆
     */
    public function loginout()
    {
        cookie::delete('rebUserId');
        cookie::delete('rebSalt');
        Session::clear();
        return ["url"=>Url::build("admin/login/login")];
    }

}