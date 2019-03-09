<?php

namespace app\admin\controller;

use think\AjaxPage;
use think\Page;
use think\Db;

class Pickup extends Base {

	public function pickup_add($id)
	{
		return $this->fetch('pickup');
	}

	// public function pickup_list(){

	// 	$Pickup =  M('offline_pickup'); 
	// 	$res = $Pickup->order('id desc')->select();
 //  		$this->assign('res',$res);


	// 	return $this->fetch();
	// }
	public function stay(){

        $Pickup =  M('order'); 
        $res = $Pickup->order('order_id desc')->select();
        $this->assign('res',$res);


        return $this->fetch();
    }
	

	
    public function off(){



        return $this->fetch();

    }

    public function statistic(){


    	
        return $this->fetch();

    }

    public function index(){

        $Pickup =  M('order');
        $res = $Pickup->order('order_id desc')->select();
        $this->assign('res',$res);


        return $this->fetch();
    }

    public function store(){

        $Pickup =  M('shop'); 
        $res = $Pickup->order('shop_id desc')->select();
        $this->assign('res',$res);


        return $this->fetch();
    }

	

	public function verification(){
		$Pickup =  M('offline_pickup'); 
		$res = $Pickup->order('id desc')->select();
  		$this->assign('res',$res);

		
		
		return $this->fetch();
	}
}

// 	public function place(){
// 		$Pickup =  M(''); 
// 		$res = $Pickup->order('id desc')->select();
//   		$this->assign('res',$res);

		
		
// 		return $this->fetch();
// 	}
// }

