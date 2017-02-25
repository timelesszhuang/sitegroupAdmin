<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/2/23
 * Time: 16:30
 */
namespace app\admin\behavior;

use think\Controller;

class AuthenticateBehavior
{
    /**
     * 统一命令执行入口  里面调用其他方法
     * @param $params
     */
    public function run(&$params)
    {
        $this->$params();
    }

    public function check_auth()
    {
         
    }


}