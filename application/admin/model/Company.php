<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/2/27
 * Time: 16:17
 */
namespace app\admin\model;

use think\Model;
use think\Request;

class Company extends Model
{
    /**
     * 获取所有数据
     * @param $draw
     * @param $rows
     * @param $page
     * @return array
     */
    public function getAll($draw, $rows, $page)
    {
        $search = Request::instance()->get("search.value");
        $where = [];
        if (!empty($search)) {
            $where["name"] = ["like", "%$search%"];
        }
        $count = $this->count();
        $limit = $page * $rows;
        $instance = $this->limit($limit, $rows)->where($where)->order("id", "desc")->select();
        array_walk($instance, [$this, "formatter_data"]);
        return [
            "draw" => intval($draw),
            "recordsTotal" => $count,
            "recordsFiltered" => $count,
            "data" => $instance
        ];
    }

    /**
     * 格式化数据
     * @param $v
     * @param $k
     */
    public function formatter_data(&$v, $k)
    {
        if ($v["create_time"]) {
            $v["create_time"] = date("Y-m-d", $v["create_time"]);
        }
    }

    /**
     * 获取json数据
     * @return mixed
     */
    public function getJson()
    {
        $instance=$this->field("id,name as text")->order("id","desc")->select();
        return $instance;
    }
}