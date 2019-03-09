<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/3/8 0008
 * Time: 14:24
 */

namespace app\common\model;

use think\Db;
use think\Model;

class Category extends Model
{
    /**
     * @param $categoryId  分类id
     * @return  不是返回数据 就是空数组
     * 获取 获取该分类的下一级子分类
     */
    public function get_children_category($categoryId){
        //判断如果分类id不存在默认为为你推荐的id
        if($categoryId==0 || $categoryId==''){
            return array();
        }
        return Db::name('goods_category')->where(['id'=>$categoryId])->select();
    }
    /**
     * @return  不是返回数据 就是空数组
     * 获取 获取所有的一级分类
     */
    public function get_first_level_category(){
        return Db::name('goods_category')->where('level', 1)->where('is_show',1)->order('sort_order','asc')->column('id,name,mobile_name,parent_id,parent_id_path,level,image');
    }
}