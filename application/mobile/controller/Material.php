<?php
/**
 * 智丰网络
 * ============================================================================
 * * 版权所有 2015-2027 深圳搜豹网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.tp-shop.cn
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用 .
 * 不允许对程序代码以任何形式任何目的的再发布。
 * 采用最新Thinkphp5助手函数特性实现单字母函数M D U等简写方式
 * ============================================================================
 * $Author: C   2019-03-09
 */ 
namespace app\mobile\controller;
// use app\common\logic\GoodsLogic;
// use app\common\model\FlashSale;
// use app\common\model\GroupBuy;
// use app\common\model\PreSell;
use think\Db;
use think\Page;
// use app\common\logic\ActivityLogic;

class Material extends MobileBase {

    public function index(){  
        // 获取分类渲染到页面
        $where = " show_in_nav=1";
        $category = M('material_cat')->field('cat_id, cat_name')->where($where)->order('sort_order')->select();   
        $this->assign('category', $category);
        return $this->fetch();
    }

    /**
     * 默认获取分享区数据列表
     * @author C
     * @time2019-3
     */
    public function share_list(){
        // $now_time = time();
        // $where = " start_time <= $now_time and end_time >= $now_time and is_end = 0";
        // $count = M('prom_goods')->where($where)->count();  // 查询满足要求的总记录数
        // $pagesize = C('PAGESIZE');  //每页显示数
        // $Page  = new Page($count,$pagesize); //分页类
        // $promote = M('prom_goods')->field('id,title,start_time,end_time,prom_img')->where($where)->limit($Page->firstRow.','.$Page->listRows)->select();    //查询活动列表
        // $this->assign('promote',$promote);
        // if(I('is_ajax')){
        //     return $this->fetch('ajax_promote_goods');
        // }
        // return $this->fetch();

        $where = " is_open = 1";
        $count = M('material')->where($where)->count(); // 查询满足需求的总记录数
        $pagesize = C('PAGESIZE'); // 每页显示数
        $page = new Page($count, $pagesize); // 分页类
        $material = M('material')->where($where)->limit($page->firstRow.','.$page->listRows)->select(); // 查询已发布的列表
        $this->assign('material', $material);
        // if(I('is_ajax')){
        //     return $this->fetch('ajax_share_list');
        // }
        return $this->fetch('Material/index');



    }


    
}