<?php
/**
 * 节点后台控制器
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/2/24
 * Time: 14:53
 */
namespace app\index\controller;

class Index extends IndexBase
{
    /**
     * 节点后台首页html
     * @return mixed
     */
    public function index()
    {
        $this->assign([
            "flag"=>"index"
        ]);
        return $this->fetch();
    }
    public function table()
    {
        return $this->fetch();
    }
    public function getdata()
    {

    }
    public function check()
    {

    }
}
