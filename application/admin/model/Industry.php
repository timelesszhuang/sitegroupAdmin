<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/2/27
 * Time: 11:26
 */
namespace app\admin\model;

use think\Model;
use think\Request;

class Industry extends Model
{
    /**
     * 获取所有分类信息
     * @param $draw 原样返回给前台 这样前台才可以知道
     * @param $row
     * @param $page
     * @return array
     */
    public function getAll($draw, $row, $page)
    {
        $search=Request::instance()->get("search.value");
        $where=[];
        if(!empty($search)){
            $where["name"]=["like","%$search%"];
        }
        $limit = $page * $row;
        $count = $this->count();
        $instance = $this->limit($limit, $row)->where($where)->order("id", "desc")->select();
        array_walk($instance, [$this, 'formatter_getAll']);
        return [
            "draw" => intval($draw),
            "recordsTotal" => $count,
            "recordsFiltered" => $count,
            "data" => $instance
        ];
    }

    /**
     * 格式化时间
     * @param $v
     * @param $k
     */
    public function formatter_getAll(&$v, $k)
    {
        if ($v["create_time"]) {
            $v["create_time"] = date("Y-m-d", $v["create_time"]);
        }
    }

    /**
     * 获取分类列表
     * @return mixed
     */
    public function getJson()
    {
        $data = $this->field("id,name as text")->order("id", "desc")->select();
        return $data;
    }


}