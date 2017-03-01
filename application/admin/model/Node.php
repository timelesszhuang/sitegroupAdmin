<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/2/28
 * Time: 13:42
 */
namespace app\admin\model;
use think\Model;
use think\Request;

class Node extends Model
{
    /**
     * 获取所有数据
     * @param $draw
     * @param $rows
     * @param $page
     * @return array
     */
    public function getAll($draw,$rows,$page)
    {
        $search=Request::instance()->get("search.value");
        $where=[];
        if(!empty($search)){
            $where["name"]=["like","%$search%"];
        }
        $limit=$rows*$page;
        $count=$this->count();
        $instance=$this->limit($limit,$rows)->where($where)->order("id","desc")->select();
        array_walk($instance,[$this,"formatter_data"]);
        return [
            "draw"=>$draw,
            "recordsTotal"=>$count,
            "recordsFiltered"=>$count,
            "data"=>$instance
        ];
    }

    /**
     * 格式化数据
     * @param $v
     * @param $k
     */
    public function formatter_data(&$v,$k)
    {
        if(!empty($v["create_time"])){
            $v["create_time"]=date("Y-m-d",$v["create_time"]);
        }
    }


}