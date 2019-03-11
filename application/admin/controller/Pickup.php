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
    	if ($_POST) {
    		$order_sn = input('order_sn/s');
            $where = "and  order_sn like '%$order_sn%' ";
            $cwhere['order_sn'] = "like '%$order_sn%' ";
    	}
        $count = Db::table("tp_order")->join('tp_pick_up',' tp_order.user_id=tp_pick_up.pickup_id')->where($cwhere)->count();
        $page = new Page($count,10);
        $Pickup =  Db::query("select * from tp_order as a,tp_pick_up as b where a.user_id = b.pickup_id $where order by a.order_id desc limit $page->firstRow,$page->listRows");
        // var_dump($Pickup);exit;
        // $res = $Pickup->order('order_id desc')->select();
        $this->assign('page',$page);
        $this->assign('res',$Pickup);


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

