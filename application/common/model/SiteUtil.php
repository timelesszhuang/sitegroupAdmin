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
    public function get_tree($data, $parent_id = 0, $level = 0) {
        static $arr = array();
        foreach ($data as $d) {
            if ($d['parent_id'] == $parent_id) {
                $d['level'] = $level;
                $d['_parentId']=$d['parent_id'];
                $arr[] = $d;
                $this->get_tree($data, $d['id'], $level + 1);
            }
        }
        return $arr;
    }


}