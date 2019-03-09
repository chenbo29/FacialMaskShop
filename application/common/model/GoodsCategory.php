<?php
/**
 * 智丰网络
 * ============================================================================
 * 版权所有 2015-2027 深圳搜豹网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.tp-shop.cn
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用 .
 * 不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * Author: IT宇宙人
 * Date: 2015-09-09
 */
namespace app\common\model;
use think\Db;
use think\Model;
class GoodsCategory extends Model {

    public function getParentListAttr($value, $data)
    {
        $parent_ids = explode('_', $data['parent_id_path']);
        array_pop($parent_ids);
        array_shift($parent_ids);
        if(count($parent_ids) > 0){
            $parent_list = Db::name('goods_category')->where('id', 'in', $parent_ids)->select();
            if($parent_list){
                return $parent_list;
            }
        }
        return [];
    }
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
        return Db::name('goods_category')->where(['parent_id'=>$categoryId])->select();
    }
    /**
     * @return  不是返回数据 就是空数组
     * 获取 获取所有的一级分类
     */
    public function get_first_level_category(){
        return Db::name('goods_category')->where('level', 1)->where('is_show',1)->order('sort_order','asc')->column('id,name,mobile_name,parent_id,parent_id_path,level,image');
    }
}
