<?php

namespace app\admin\controller;

use think\AjaxPage;
use think\Page;
use think\Db;
use app\common\model\Order as OrderModel;

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
		if ($_POST) {
			$order_sn = input('order_sn/s');
    		$where = "and  order_sn like '%$order_sn%' ";
		}

        $Pickup =  Db::query('select * from tp_order as a,tp_pick_up as b where a.user_id = b.pickup_id order by a.order_id desc');
        // $res = $Pickup->order('order_id desc')->select();
        $this->assign('res',$Pickup);


        return $this->fetch();
    }
	

	
    public function off(){



        return $this->fetch();

    }

    public function statistic(){
    	
    	$Pickup =  M('shop'); 
        $res = $Pickup->order('shop_id desc')->select();
        $this->assign('res',$res);
    	
        return $this->fetch();

    }

    public function index(){
    	if ($_POST) {
    		/*$add_time = date("Y-m-d H:i:s",I('add_time'));
    		$add_time_end = date("Y-m-d H:i:s",I('add_time_end'));add_time between 'add_time' and 'add_time_end' and*/
    		$order_sn = input('order_sn/s');
    		$where = "and  order_sn like '%$order_sn%' ";
    		/*$Pickup =  Db::query("select * from tp_order as a,tp_pick_up as b where a.user_id = b.pickup_id $where order by a.order_id desc");*/
    		// print_r($Pickup);exit;
    	}

        $Pickup =  Db::query("select * from tp_order as a,tp_pick_up as b where a.user_id = b.pickup_id $where order by a.order_id desc");

        // $res = $Pickup->order('order_id desc')->select();
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

	public function detail(){
        $order_id = input('order_id', 0);
        $orderModel = new OrderModel();
        $order = $orderModel::get(['order_id'=>$order_id]);
        if(empty($order)){
            $this->error('订单不存在或已被删除');
        }
        $this->assign('order', $order);
        return $this->fetch();
    }

    /**
     * 拆分订单
     */
    public function split_order(){
    	$order_id = I('order_id');
        $orderModel = new OrderModel();
        $orderObj = $orderModel::get(['order_id'=>$order_id]);
        $order =$orderObj->append(['full_address','orderGoods'])->toArray();
    	if($order['pay_status'] == 0){
    		$this->error('未支付订单不允许拆分');
    		exit;
    	}
        if($order['shipping_status'] != 0){
            $this->error('已发货订单不允许编辑');
            exit;
        }
    	$orderGoods = $order['orderGoods'];
        if($orderGoods){
            $orderGoods = collection($orderGoods)->toArray();
        }
    	if(IS_POST){
    		//################################先处理原单剩余商品和原订单信息
    		$old_goods = I('old_goods/a');
    		foreach ($orderGoods as $val){
    			$all_goods[$val['rec_id']] = $val;//所有商品信息
    		}


    		//################################新单处理
    		for($i=1;$i<20;$i++){
                $temp = $this->request->param($i.'_old_goods/a');
    			if(!empty($temp)){
    				$split_goods[] = $temp;
    			}
    		}

    		foreach ($split_goods as $key=>$vrr){
    			foreach ($vrr as $k=>$v){
    				$all_goods[$k]['goods_num'] = $v;
    				$brr[$key][] = $all_goods[$k];
    			}
    		}

    		$user_money = $order['user_money'] / $order['total_amount'];
    		$integral = $order['integral'] / $order['total_amount'];
    		$order_amount = $order['order_amount'] / $order['total_amount'];
    		$split_user_money = 0;// 累计
    		$split_integral = 0;
    		$split_order_amount = 0;

            foreach($brr as $k=>$goods){
                $newPay = new Pay();
                try{
                    $newPay->setUserId($order['user_id']);
                    $newPay->payGoodsList($goods);
                    $newPay->delivery($order['district']);
                    $newPay->orderPromotion();
                } catch (TpshopException $t) {
                    $error = $t->getErrorArr();
                    $this->error($error['msg']);
                }
    			$new_order = $order;
    			$new_order['order_sn'] = date('YmdHis').mt_rand(1000,9999);
    			$new_order['parent_sn'] = $order['order_sn'];
    			//修改订单费用
    			$new_order['goods_price']    = $newPay->getGoodsPrice(); // 商品总价
    			$new_order['total_amount']   = $newPay->getTotalAmount(); // 订单总价
//                if($order['pay_name'] == '余额支付'){
                    //修改拆分订单余额展示
//                    $new_order['user_money'] = $newPay->getTotalAmount();
                    $new_order['user_money'] = floor(($user_money * $newPay->getTotalAmount())*100)/100;//向下取整保留2位小数点
//                }else{
//                    $new_order['order_amount']   = $newPay->getOrderAmount(); // 应付金额
                    $new_order['order_amount']   = floor(($order_amount * $newPay->getTotalAmount())*100)/100;//向下取整保留2位小数点
//                }
                $new_order['integral'] = floor(($integral * $newPay->getTotalAmount())*100)/100;//向下取整保留2位小数点
                //前面按订单总比例拆分，剩余全部给最后一个订单
                if($k == count($brr)-1){
                    $new_order['user_money'] = $order['user_money']-$split_user_money;
                    $new_order['integral'] = $order['integral']-$split_integral;
                    $new_order['order_amount'] = $order['order_amount']-$split_order_amount;
                }else{
                    $split_user_money += $new_order['user_money'];
                    $split_integral += $new_order['integral'];
                    $split_order_amount += $new_order['order_amount'];
                }
                if($order['integral'] > 0 ){
                    $new_order['integral_money'] = $new_order['integral']/($order['integral']/$order['integral_money']);
                }
                $new_order['add_time'] = time();
    			unset($new_order['order_id']);
    			$new_order_id = DB::name('order')->insertGetId($new_order);//插入订单表
    			foreach ($goods as $vv){
    				$vv['order_id'] = $new_order_id;
    				unset($vv['rec_id']);
    				$nid = M('order_goods')->add($vv);//插入订单商品表
    			}
    		}
            //拆分订单后删除原父订单信息
            $orderObj->delete();
            DB::name('order_goods')->where(['order_id'=>$order_id])->delete();

    		//################################新单处理结束
    		$this->success('操作成功',U('Admin/Order/index'));
            exit;
    	}

    	foreach ($orderGoods as $val){
    		$brr[$val['rec_id']] = array('goods_num'=>$val['goods_num'],'goods_name'=>getSubstr($val['goods_name'], 0, 35).$val['spec_key_name']);
    	}
    	$this->assign('order',$order);
    	$this->assign('goods_num_arr',json_encode($brr));
    	$this->assign('orderGoods',$orderGoods);
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

