<?php
/**
 * 业绩
 */
namespace app\mobile\controller;

use think\Db;
use think\Page;
use app\common\logic\ActivityLogic;

class Performance extends MobileBase {


    public function index(){     
        
        

        return $this->fetch();
    }


  

}