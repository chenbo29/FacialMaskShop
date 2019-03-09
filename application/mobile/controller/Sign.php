<?php
/**
 * 签到
 */
namespace app\mobile\controller;

use think\Db;
use think\Page;
use app\common\logic\ActivityLogic;

class Sign extends MobileBase {


    /*
    * 初始化操作
    */
    public function _initialize()
    {
        parent::_initialize();
        if (!session('user')) {
            header("location:" . U('Mobile/User/login'));
            exit;
        }
    }


    public function index(){     
        
        $user_id = session('user.user_id');
        $this->assign('user_id',$user_id);

        return $this->fetch();
    }

    public function integral(){     
        
        

        return $this->fetch();
    }
  

    /**
     * 判断今天是否签到
     */
    // public function check_sign(){
    //     $user_id = I('user_id');
    //     if(!$user_id){
    //         return $this->ajaxReturn(['status'=>-1,'msg'=>'user_id不能为空']);
    //     }
        
    //     $con['sign_day'] = array('like',date('Y-m-d',time()).'%');
    //     $cunzai = M('sign_log')->where(['user_id'=>$user_id])->where($con)->find();

    //     if($cunzai){
    //         return $this->ajaxReturn(['status'=>1,'msg'=>'今日已签到','data' => true]);
    //     }else{
    //         return $this->ajaxReturn(['status'=>-1,'msg'=>'今日未签到','data' => false]);

    //     }

    // }

    /**
     * 签到
     */
    public function sign(){
        $user_id = I('user_id');
        if(!$user_id){
            return $this->ajaxReturn(['status'=>-1,'msg'=>'签到user_id不能为空']);
        }

        $con['sign_day'] = array('like',date('Y-m-d',time()).'%');
        $cunzai = M('sign_log')->where(['user_id'=>$user_id])->where($con)->find();

        if($cunzai){
            return $this->ajaxReturn(['status'=>1,'msg'=>'今日已签到']);
        }else{
            $r = M('sign_log')->save(['user_id'=>$user_id,'sign_day'=>date('Y-m-d H:i:s')]);
            if($r){
                if($r){
                    return $this->ajaxReturn(['status'=>1,'msg'=>'签到成功']);
                }else{
                    return $this->ajaxReturn(['status'=>-1,'msg'=>'签到失败']);
                }
            }
        }
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


        $con['sign_day'] = array('like',date('Y-m-d',time()).'%');
        $cunzai = M('sign_log')->where(['user_id'=>$user_id])->where($con)->find();

        if($cunzai){
            $today_sign = true;
        }else{
            $today_sign = false;
        }

        //当前积分
        $points = M('users')->where(['user_id'=>$user_id])->value('pay_points');

        $add_point = 10;

        $continue_sign = $this->continue_sign($user_id);

        //连续签到几天
        $accumulate_day = count($data);

        return $this->ajaxReturn(
            ['status'=>1,
            'msg'=>'获取成功',
            'data'=>$data,
            'today_sign'=>$today_sign,
            'points'=>$points,
            'add_point'=>$add_point,
            'continue_sign'=> $continue_sign,
            'accumulate_day'=>$accumulate_day]);
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

    /**
     * 连续签到次数
     */
    private function continue_sign($user_id){

        $first_sign_day = M('sign_log')->where(['user_id'=>$user_id])->order('id desc')->value('sign_day');
        //判断一下这个 日期连续签到次数
        if(!$first_sign_day){
            return 0;
        }

        $continue_sign = 1;

        $sign_day_2 = date('Y-m-d',strtotime("$first_sign_day -1 day")) ;
        




        return $continue_sign;
    }
}