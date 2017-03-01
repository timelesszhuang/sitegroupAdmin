<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/2/24
 * Time: 11:26
 */
namespace app\common\model;

use think\Model;
use think\Request;
use think\Session;
use think\Config;
use app\common\model\SiteUtil;
use think\db;
use traits\model\SoftDelete;
class User extends Model
{
    use SoftDelete;
    protected $deleteTime = 'delete_time';
    /**
     * 修改后台密码  大后台和结点后台
     * @param $oldpwd
     * @param $newpwd
     * @param $newpwd2
     * @return array
     */
    public function chpwd($oldpwd, $newpwd, $newpwd2)
    {
        if ($newpwd != $newpwd2) {
            return ["status" => "error", "msg" => "两次输入的新密码不一样"];
        }
        //获取session 通用名称
        $sess_arr = (new SiteUtil)->userInfoExchange();
        $user=User::where(["user_name"=>$sess_arr["account"],"pwd"=>md5($oldpwd.$sess_arr["account"])])->find();
        if(is_null($user)){
            return ["status"=>"error","msg"=>"原密码错误","title"=>"修改密码信息"];
        }
        $user->pwd=md5($newpwd.$sess_arr["account"]);
        if(!$user->save()){
            return ["status"=>"error","msg"=>"密码错误失败","title"=>"修改密码信息"];
        }
        return ["status"=>"success","msg"=>"密码修改成功","title"=>"修改密码信息"];
    }

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
            $where['user_name|name'] =["like","%$search%"];
        }
        $limit=$page*$rows;
        $count=$this->count();
        $instance=$this->where($where)->limit($limit,$rows)->order("id","desc")->field("id,user_name,type,name,tel,mobile,qq,wechat,email,create_time")->select();
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
        if($v["type"]==1){
            $v["type"]="大后台";
        }else if($v["type"]==2){
            $v["type"]="节点后台";
        }
    }

    /**
     * 获取所有的节点后台账号
     * @return mixed
     */
    public function getJson()
    {
        $user_arr=\app\admin\model\Node::get()->column("user_id");
        if(!empty($user_arr)){
            $usr_str=implode(",",$user_arr);
            $where["id"]=["not in",$usr_str];
        }
        $where["type"]=2;
        return $this->field("id,user_name as text")->where($where)->select();
    }

}