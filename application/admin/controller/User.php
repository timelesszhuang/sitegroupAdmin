<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/2/28
 * Time: 9:08
 */
namespace app\admin\controller;

use think\Request;
use think\Validate;

class User extends AdminBase
{
    /**
     * 主页面展示
     * @return mixed
     */
    public function index()
    {
        $this->assignFlag();
        return $this->fetch();
    }

    /**
     * 获取所有用户信息
     */
    public function getAll()
    {
        $draw = $this->Rinstance->get("draw");
        $rows = $this->Rinstance->get("length");
        $page = $this->Rinstance->get("start");
        return (new \app\common\model\User)->getAll($draw, $rows, $page);
    }

    /**
     * 用户添加页面
     * @return mixed
     */
    public function addHtml()
    {
        return view();
    }

    /**
     * 添加用户信息
     * @return array
     */
    public function add()
    {
        $rule = [
            "user_name|账号" => "require|min:5",
            "user_name|账号" => "unique:user",
            "pwd|密码" => "require|min:6",
            "pwd2|再次密码" => "require"
        ];
        $validate = new Validate($rule);
        $getData = $this->Rinstance->post();
        if (!$validate->check($getData)) {
            return ["status" => "error", "title" => "添加用户信息", "msg" => $validate->getError()];
        }
        if ($getData["pwd"] != $getData["pwd2"]) {
            return ["status" => "error", "title" => "添加用户信息", "msg" => "两次密码输入不一致"];
        }
        $user = (new \app\common\model\User());
        $user->pwd = md5($getData["pwd"] . $getData["user_name"]);
        $user->type = 2;
        $user->salt = chr(rand(97, 122)) . chr(rand(65, 90)) . chr(rand(97, 122)) . chr(rand(65, 90));
        $user->user_name = $getData["user_name"];
        if (!$user->save()) {
            return ["status" => "error", "title" => "添加用户信息", "msg" => "添加失败"];
        }
        return ["status" => "success", "title" => "添加用户信息", "msg" => "添加成功"];
    }

    /**
     * 用户编辑页面
     * @return mixed
     */
    public function editHtml()
    {
        $id = $this->Rinstance->post("id");
        $user = \app\common\model\User::get($id);
        $this->assign([
            "data" => $user->toArray()
        ]);
        return view();
    }

    /**
     * 修改用户信息
     * @return array
     */
    public function edit()
    {
        $getData = $this->Rinstance->post();
        $instance = (new \app\common\model\User);
        if (!$instance->save($getData, ["id" => $getData["id"]])) {
            return ["status" => "error", "title" => "修改用户信息", "msg" => "修改失败"];
        }
        return ["status" => "success", "title" => "修改用户信息", "msg" => "修改成功"];
    }

    /**
     * 删除用户
     * @return array
     */
    public function del()
    {
        $id = $this->Rinstance->post("id");
        if ($id == 1) {
            return ["status" => "error", "title" => "删除用户信息", "msg" => "管理员不能删除"];
        }
        $user = \app\common\model\User::get($id);
        if (!$user->delete()) {
            return ["status" => "error", "title" => "删除用户信息", "msg" => "删除失败"];
        }
        return ["status" => "success", "title" => "删除用户信息", "msg" => "删除成功"];
    }

    /**
     * 重置密码页面
     * @return mixed
     */
    public function repwdHtml()
    {
        $id = $this->Rinstance->post("id");
        $this->assign([
            "id" => $id
        ]);
        return view();
    }

    /**
     * 重置节点密码
     * @return array
     */
    public function repwd()
    {
        $rule = [
            "pwd|密码" => "require|min:6",
            "pwd2|再次密码" => "require"
        ];
        $validate = new Validate($rule);
        $getData = $this->Rinstance->post();
        if (!$validate->check($getData)) {
            return ["status" => "error", "title" => "重置密码信息", "msg" => $validate->getError()];
        }
        if ($getData["pwd"] != $getData["pwd2"]) {
            return ["status" => "error", "title" => "重置密码信息", "msg" => "两次密码输入不一致"];
        }
        $user = \app\common\model\User::get($getData["id"]);
        $user->pwd = md5($getData["pwd"] . $user["user_name"]);
        $user->salt = chr(rand(97, 122)) . chr(rand(65, 90)) . chr(rand(97, 122)) . chr(rand(65, 90));
        if (!$user->save()) {
            return ["status" => "error", "title" => "重置密码信息", "msg" => "重置失败"];
        }
        return ["status" => "success", "title" => "重置密码信息", "msg" => "重置成功"];
    }

    /**
     * 重置salt
     * @return array
     */
    public function resalt()
    {
        $id = $this->Rinstance->post("id");
        $user = \app\common\model\User::get($id);
        $user->salt = chr(rand(97, 122)) . chr(rand(65, 90)) . chr(rand(97, 122)) . chr(rand(65, 90));
        if (!$user->save()) {
            return ["status" => "error", "title" => "重置salt", "msg" => "重置失败"];
        }
        return ["status" => "success", "title" => "重置salt", "msg" => "重置成功"];
    }
}