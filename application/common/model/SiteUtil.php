<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/2/25
 * Time: 10:17
 */
namespace app\common\model;

use think\Session;

class SiteUtil
{
    /**
     * 检测session中的数据  并合并成统一的下标名称返回
     * @return array
     */
    public function userInfoExchange()
    {
        //判断type
        $siteType = Session::get("type");
        //大后台
        $account = "admin_user";
        $account_id = "admin_id";
        $name = "admin_name";
        //小后台
        if ($siteType == 2) {
            $account = "index_user";
            $account_id = "index_id";
            $name = "index_name";
        }
        $sess_info=[
            "account"=>Session::get($account),
            "account_id"=>Session::get($account_id),
            "name"=>Session::get($name),
        ];
        return $sess_info;
    }


}