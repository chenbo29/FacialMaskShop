<?php

namespace app\admin\controller;

use app\admin\logic\GoodsLogic;
use app\common\model\Order;
use think\Db;
use think\Page;

class SellerManagement extends Base
{
	public function _initialize(){
        parent::_initialize();
    }
    
    public function seller_list(){


        return $this->fetch();
    }

}