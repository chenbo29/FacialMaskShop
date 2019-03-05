<?php

namespace app\mobile\controller;

use think\Db;
use app\common\model\WxNews;
 
class Category extends MobileBase
{
    /**
     * 分类页
     */
    public function index()
    {
        
        return $this->fetch();
    }

    
}