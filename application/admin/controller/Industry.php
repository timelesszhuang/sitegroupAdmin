<?php
/**
 * 友商分类控制器
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/2/27
 * Time: 9:14
 */
namespace app\admin\controller;
use think\Validate;
use think\Request;
class Industry extends AdminBase
{
    /**
     * 友商分类首页展示
     * @return mixed
     */
    public function index()
    {
        $this->assignFlag();
        return $this->fetch();
    }

    /**
     * 获取所有分类信息
     * @return array
     */
    public function getAll()
    {
        $page=$this->Rinstance->get("start");
        $rows=$this->Rinstance->get("length");
        $draw = $this->Rinstance->get("draw");
        return (new \app\admin\model\Industry())->getAll($draw,$rows,$page);
    }

    /**
     * 分类添加页面展示
     * @return mixed
     */
    public function addHtml()
    {
        return view();
    }

    /**
     * 添加分类
     * @return array
     */
    public function add()
    {
        $rule=[
            "name|名称"=>"require|min:2",
            "name|名称"=>"unique:industry",
            "detail|描述"=>"require"
        ];
        $validate=new Validate($rule);
        $getData=$this->Rinstance->post();
        if(!$validate->check($getData)){
            return ["status"=>"error","title"=>"添加分类","msg"=>$validate->getError()];
        }
        $industry=new \app\admin\model\Industry();
        $industry->name=$getData["name"];
        $industry->detail=$getData["detail"];
        if(!$industry->save()){
            return ["status"=>"error","title"=>"添加分类","msg"=>"添加失败"];
        }
        return ["status"=>"success","title"=>"添加分类","msg"=>"添加成功"];
    }

    /**
     * 修改页面展示
     * @return mixed
     */
    public function editHtml()
    {
        $id=$this->Rinstance->post("id");
        $instance=\app\admin\model\Industry::get(["id"=>$id]);
        $this->assign([
            "data"=>$instance->toArray()
        ]);
        return view();
    }

    /**
     * 修改操作
     * @return array
     */
    public function edit()
    {
        $rule=[
            "name|名称"=>"require",
            "name|名称"=>"unique:industry",
            "detail|描述"=>"require"
        ];
        $validate=new Validate($rule);
        $getData=$this->Rinstance->post();
        if(!$validate->check($getData)){
            return ["status"=>"error","title"=>"修改分类","msg"=>$validate->getError()];
        }
        $instance=\app\admin\model\Industry::get($getData["id"]);
        $instance->name=$getData["name"];
        $instance->detail=$getData["detail"];
        if(!$instance->save()){
            return ["status"=>"error","title"=>"修改分类","msg"=>"修改失败"];
        }
        return ["status"=>"success","title"=>"修改分类","msg"=>"修改成功"];
    }

    /**
     * 删除操作
     * @return array
     */
    public function del()
    {
        $id=$this->Rinstance->post("id");
        $instance=\app\admin\model\Industry::get($id);
        if(!$instance->delete()){
            return ["status"=>"error","title"=>"删除分类","msg"=>"删除失败"];
        }
        return ["status"=>"success","title"=>"删除分类","msg"=>"删除成功"];
    }

}