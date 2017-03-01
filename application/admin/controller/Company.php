<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/2/27
 * Time: 16:14
 */
namespace app\admin\controller;

use app\admin\model\Industry;
use think\Validate;

class Company extends AdminBase
{
    /**
     * 首页html展示
     * @return mixed
     */
    public function index()
    {
        return $this->fetch();
    }

    /**
     * 获取所有数据
     */
    public function getAll()
    {
        $page=$this->Rinstance->get("start");
        $rows=$this->Rinstance->get("length");
        $draw = $this->Rinstance->get("draw");
        return (new \app\admin\model\Company())->getAll($draw,$rows,$page);
    }

    /**
     * 添加页面展示
     * @return mixed
     */
    public function addHtml()
    {
        return view();
    }

    /**
     * 获取分类列表
     * @return mixed
     */
    public function getCompanylist()
    {
        return (new \app\admin\model\Industry())->getJson();
    }

    /**
     * 添加公司
     * @return array
     */
    public function add()
    {
        $rule = [
            "name|公司名称" => "require",
            "name|公司名称"=>"unique:company",
            "industry_id|请选择行业" => "require",
            "industry_name|请选择行业" => "require",
            "artificialperson|法人" => "require"
        ];
        $validate = new Validate($rule);
        $getData = $this->Rinstance->post();
        if (!$validate->check($getData)) {
            return ["status" => "error", "title" => "添加公司", "msg" => $validate->getError()];
        }
        $comp = (new \app\admin\model\Company());
        $comp->data($getData);
        if (!$comp->save()) {
            return ["status" => "error", "title" => "添加公司", "msg" => "添加失败"];
        }
        return ["status" => "success", "title" => "添加公司", "msg" => "添加成功"];
    }

    /**
     * 修改页面展示
     * @return mixed
     */
    public function editHtml()
    {
        $id = $this->Rinstance->post("id");
        $instance = \app\admin\model\Company::get($id);
        $this->assign([
            "data" => $instance->toArray()
        ]);
        return view();
    }

    /**
     * 修改数据
     * @return array
     */
    public function edit()
    {
        $rule = [
            "name|公司名称" => "require",
            "name|公司名称"=>"unique:company",
            "industry_id|请选择行业" => "require",
            "industry_name|请选择行业" => "require",
            "artificialperson|法人" => "require"
        ];
        $validate = new Validate($rule);
        $getData = $this->Rinstance->post();
        if (!$validate->check($getData)) {
            return ["status" => "error", "title" => "添加公司", "msg" => $validate->getError()];
        }
        $instance = (new \app\admin\model\Company);
        if (!$instance->save($getData, ["id" => $getData["id"]])) {
            return ["status" => "error", "title" => "修改公司信息", "msg" => "修改失败"];
        }
        return ["status" => "success", "title" => "修改公司信息", "msg" => "修改成功"];
    }

    /**
     * 删除操作
     * @return array
     */
    public function del()
    {
        $id = $this->Rinstance->post("id");
        $instance = \app\admin\model\Company::get($id);
        if (!$instance->delete()) {
            return ["status" => "error", "title" => "删除公司信息", "msg" => "删除失败"];
        }
        return ["status" => "success", "title" => "删除公司信息", "msg" => "删除成功"];
    }
}