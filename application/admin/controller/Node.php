<?php
/**
 * 节点控制器
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/2/28
 * Time: 11:34
 */
namespace app\admin\controller;

use app\admin\model\Company;
use app\common\model\User;
use think\Validate;

class Node extends AdminBase
{
    /**
     *节点首页
     * @return mixed
     */
    public function index()
    {
        return $this->fetch();
    }

    /**
     * 获取所有节点数据
     */
    public function getAll()
    {
        $draw = $this->Rinstance->get("draw");
        $rows = $this->Rinstance->get("length");
        $page = $this->Rinstance->get("start");
        return (new \app\admin\model\Node)->getAll($draw, $rows, $page);
    }

    /**
     * 添加页面
     * @return mixed
     */
    public function addHtml()
    {
        return view();
    }

    /**
     * 添加节点
     * @return array
     */
    public function add()
    {
        $rule = [
            "name|节点名称" => "require",
            "com_id|公司" => "require",
            "user_id|账号" => "require"
        ];
        $validate = new Validate($rule);
        $getData = $this->Rinstance->post();
        if (!$validate->check($getData)) {
            return [
                "status" => "error",
                "title" => "添加节点",
                "msg" => $validate->getError()
            ];
        }
        $node = (new \app\admin\model\Node($_POST));
        if (!$node->allowField(true)->save()) {
            return ["status" => "error", "title" => "添加节点", "msg" => "添加失败"];
        }
        return ["status" => "success", "title" => "添加节点", "msg" => "添加成功"];
    }

    /**
     * 显示节点编辑页面
     * @return mixed
     */
    public function editHtml()
    {
        $id = $this->Rinstance->post("id");
        $node = \app\admin\model\Node::get($id);
        $this->assign([
            "data" => $node->toArray()
        ]);
        return view();
    }

    /**
     * 添加节点 修改操作
     * @return array
     */
    public function edit()
    {
        $id = $this->Rinstance->post("id");
        $node = (new \app\admin\model\Node());
        if (!$node->allowField(true)->save($_POST, ["id" => $id])) {
            return ["status" => "error", "title" => "编辑节点", "msg" => "编辑失败"];
        }
        return ["status" => "success", "title" => "编辑节点", "msg" => "编辑成功"];
    }

    /**
     * 获取company json数据
     * @return mixed
     */
    public function getCompanyJson()
    {
        return (new \app\admin\model\Company)->getJson();
    }

    /**
     * 获取账号json
     * @return mixed
     */
    public function getAccountJson()
    {
        return (new \app\common\model\User)->getJson();
    }

    /**
     * 删除节点
     * @return array
     */
    public function del()
    {
        $id = $this->Rinstance->post("id");
        $node = \app\admin\model\Node::get($id);
        if (!$node->delete()) {
            return ["status" => "error", "title" => "编辑节点", "msg" => "编辑失败"];
        }
        return ["status" => "success", "title" => "编辑节点", "msg" => "编辑成功"];
    }

    /**
     * 跳转到其他节点后台
     * @return string
     */
    public function jump()
    {
        $id = $this->Rinstance->post("id");
        $node_instance = \app\admin\model\Node::get($id);
        $user_id=$node_instance->toArray()["user_id"];
        $user_instance = \app\common\model\User::get($user_id);
        return (new \app\common\model\CheckLogin())->checktype($user_instance->toArray());
    }
}