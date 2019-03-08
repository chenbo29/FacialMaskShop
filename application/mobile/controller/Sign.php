<?php
/**
 * 签到
 */
namespace app\mobile\controller;

use think\Db;
use think\Page;
use app\common\logic\ActivityLogic;

class Sign extends MobileBase {


    public function index(){     
        
        //$user_id = session('user.user_id');
        
        $user_id = 1;
        
        $this->assign('user_id',$user_id);

        return $this->fetch();
    }

    public function integral(){     
        
        

        return $this->fetch();
    }
  

}