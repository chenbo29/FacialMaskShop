<?php
/**
 * 智丰网络
 * ============================================================================
 * 版权所有 2015-2027 深圳搜豹网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.tp-shop.cn
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用 .
 * 不允许对程序代码以任何形式任何目的的再发布。
 * 如果商业用途务必到官方购买正版授权, 以免引起不必要的法律纠纷.
 * 采用最新Thinkphp5助手函数特性实现单字母函数M D U等简写方式
 * ============================================================================
 * Author: 当燃      
 * Date: 2015-09-09
 */
namespace app\admin\controller;

use app\seller\logic\GoodsLogic;
use app\seller\logic\SearchWordLogic;
use app\common\model\GoodsAttr;
use app\common\model\GoodsAttribute;
use app\common\model\GoodsType;
use app\common\model\Spec;
use app\common\model\SpecGoodsPrice;
use app\common\model\SpecItem;
use app\common\model\GoodsCategory;
use app\common\util\TpshopException;
use think\AjaxPage;
use think\Loader;
use think\Page;
use think\Db;

class Sign extends Base {

    public function goods(){

        return $this->fetch();
    }

    //签到商品列表
    public function ajaxSignGoodsList(){

//        var_dump($_POST);die;
        //先查询签到商品表中商品
        $sign_goods=M('sign_goods')->select();
//        var_dump($sign_goods);die;
        $sign_goods_ids=array_column($sign_goods,'id');
        $where = " goods_id in (".implode(',',$sign_goods_ids).")  "; // 搜索条件固定在签到的商品中查
        I('intro')    && $where = "$where and ".I('intro')." = 1" ;
        I('brand_id') && $where = "$where and brand_id = ".I('brand_id') ;
        (I('is_on_sale') !== '') && $where = "$where and is_on_sale = ".I('is_on_sale') ;
        $cat_id = I('cat_id');
        // 关键词搜索
        $key_word = I('key_word') ? trim(I('key_word')) : '';
        if($key_word)
        {
            $where = "$where and (goods_name like '%$key_word%' or goods_sn like '%$key_word%')" ;
        }

        if($cat_id > 0)
        {
            $grandson_ids = getCatGrandson($cat_id);
            $where .= " and cat_id in(".  implode(',', $grandson_ids).") "; // 初始化搜索条件
        }
//        var_dump($where);die;
        $count = M('Goods')->where($where)->count();
        $Page  = new AjaxPage($count,20);
        /**  搜索条件下 分页赋值
        foreach($condition as $key=>$val) {
        $Page->parameter[$key]   =   urlencode($val);
        }
         */
        $show = $Page->show();
        $order_str = "`{$_POST['orderby1']}` {$_POST['orderby2']}";
//        var_dump($show);die;
        $goodsList = M('Goods')->where($where)->order($order_str)->limit($Page->firstRow.','.$Page->listRows)->select();
        foreach($goodsList as $key=>$value){
            foreach($sign_goods as $k=>$v){
                if($value['goods_id'==$v['gid']]){
                    $goodsList[$key]['sign_user']=$v['sign_user'];
                }
            }
        }
//        var_dump($goodsList);die;
        $catList = D('goods_category')->select();
        $catList = convert_arr_key($catList, 'id');
        $this->assign('catList',$catList);
        $this->assign('goodsList',$goodsList);
        $this->assign('page',$show);// 赋值分页输出
//        var_dump($catList);die;

        ini_set("display_errors","On");
        error_reporting(E_ALL);
        return $this->fetch();
    }

    //将添加的商品入库
    public function ajaxSignGoods(){
        $sign_user=I('sign_user');
        $gid=I('gid');
        $sign_user=$sign_user==1?'fenxiao':'daili';
        $sign_goods_data=array('sign_user'=>$sign_user,'gid'=>$gid);
        $result=db('sign_goods')->save($sign_goods_data);
        if($result){
            return $this->ajaxReturn(['status'=>1,'msg'=>'添加成功']);
        }else{
            return $this->ajaxReturn(['status'=>2,'msg'=>'添加失败']);
        }
    }


    
}