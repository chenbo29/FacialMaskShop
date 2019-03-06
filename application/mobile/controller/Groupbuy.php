<?php
/**
 * 拼团
 */
namespace app\mobile\controller;

use think\Db;
use app\common\model\WxNews;
 
class Groupbuy extends MobileBase
{
    /**
     * 拼团（弹窗）
     * 拼团详情页
     * 选择款式
     */
    public function detail()
    {
       
        return $this->fetch();
    }

    /**
     * 拼团 列表
     */
    public function listdata()
    {
       
        return $this->fetch();
    }


     /**
     * 用户 评价
     */
    public function comment()
    {
       
        return $this->fetch();
    }

  
    
}