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
  


    /**
     * 获取签到的日期列表
     */
    public function get_sign_day(){
        $user_id = I('user_id');
        if(!$user_id){
            return $this->ajaxReturn(['status'=>-1,'msg'=>'user_id不能为空','data'=>'']);
        }
        $list = M('sign_log')->where(['user_id'=>$user_id])->field('sign_day')->select();
        foreach($list as $k => $v){
            $data[$k] = $this->deal_time($v['sign_day']);
        }
        return $this->ajaxReturn(['status'=>1,'msg'=>'获取成功','data'=>$data]);
    }

    /**
     * 处理时间
     */
    private function deal_time($time){
       $time = strtotime("$time -1 month") ;
        //前端要求  减去 1个月
        $time = date('Y-m-d', $time);
        return str_replace('-','/',$time);
    }

}