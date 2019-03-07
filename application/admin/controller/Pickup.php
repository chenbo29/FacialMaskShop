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

	public function pickup_list(){

		$Pickup =  M('offline_pickup'); 
		$res = $Pickup->order('id desc')->select();
  		$this->assign('res',$res);


		return $this->fetch();
	}

	public function pickupHandle()
    {
        $data = I('post.');
        $data['pickup_time'] = strtotime($data['pickup_time']);
        $result = $this->validate($data, 'Pickup.'.$data['act'], [], true);
        if ($result !== true) {
            $this->ajaxReturn(['status' => 0, 'msg' => '参数错误', 'result' => $result]);
        }
        $where['id']=$data['id'];
        // $where['title']=$data['title'];
        if($data['act']=='edit'){
            $where['user_id']=array('neq',$data['user_id']);
        }
        $find = db('pickup')->where($where)->select();
        if($find){
            $this->ajaxReturn(['status' => 0, 'msg' => '此分类下已存在该标题']);
        }
        if ($data['act'] == 'add') {
            $data['click'] = mt_rand(1000,1300);
        	$data['add_time'] = time(); 
            $r = M('pickup')->add($data);
        } elseif ($data['act'] == 'edit') {
            $r = M('pickup')->where('user_id='.$data['user_id'])->save($data);
        } elseif ($data['act'] == 'del') {
        	$r = M('pickup')->where('user_id='.$data['user_id'])->delete(); 	
        }
        
        if (!$r) {
            $this->ajaxReturn(['status' => -1, 'msg' => '操作失败']);
        }
            
        $this->ajaxReturn(['status' => 1, 'msg' => '操作成功']);
    }

	public function verification(){
		$Pickup =  M('offline_pickup'); 
		$res = $Pickup->order('id desc')->select();
  		$this->assign('res',$res);

		
		
		return $this->fetch();
	}
}

