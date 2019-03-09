<?php

namespace app\admin\controller;

use think\Page;
use think\Db;


class Distribut extends Base {

    public function goods_list(){
       
     
        return $this->fetch();
    }

    /**
     * 分销商列表
     */
    public function distributor_list()
    {
        $count = M('users')->count();
        $pager = new Page($count, 10);
        $distributor = M('users')
                    ->where('is_lock', 0)
                    ->where('is_distribut', 1)
                    ->limit($pager->firstRow, $pager->listRows)
                    ->field('user_id, nickname, level, first_leader, province, mobile, email')
                    ->select();
        $this->assign('pager', $pager);
        $this->assign('distributor', $distributor);
        return $this->fetch();
    }

    /**
     * 分销商删除
     */
    public function distributor_del()
    {
        $id = I('del_id/d');
        if ($id) {
            $result = M('users')->where(['user_id' => $id])->update(['is_distribut' => 0]);
            if($result){
                exit(json_encode(1));
            }else{
                exit(json_encode(0));
            }
        } else {
            exit(json_encode(0));
        }
    }

    /**
     * 代理列表
     */
    public function agent_list()
    {
        $count = M('users')->count();
        $pager = new Page($count, 10);
        $distributor = M('users')
                    ->where('is_lock', 0)
                    ->where('is_distribut', 1)
                    ->limit($pager->firstRow, $pager->listRows)
                    ->field('user_id, nickname, level, first_leader, province, mobile, email')
                    ->select();
        $this->assign('pager', $pager);
        $this->assign('distributor', $distributor);
        return $this->fetch();
    }

    /**
     * 代理删除
     */
    public function agent_del()
    {
        $id = I('del_id/d');
        if ($id) {
            $result = M('users')->where(['user_id' => $id])->update(['is_distribut' => 0]);
            if($result){
                exit(json_encode(1));
            }else{
                exit(json_encode(0));
            }
        } else {
            exit(json_encode(0));
        }
    }
    
    public function tree()
    {
      
        return $this->fetch();
    }
    

    public function grade_list()
    {
      
        return $this->fetch();
    }
    
    /**
     * 分成日志列表
     */
    public function rebate_log()
    {
        $count = Db::name('agent_log')->alias('log')
                ->join('users', 'users.user_id = log.user_id')
                ->join('goods', 'goods.goods_id = log.goods_id')
                ->field('log.*')
                ->count();
        $page = new Page($count, 10);
        $log = Db::name('agent_log')->alias('log')
                ->join('users', 'users.user_id = log.user_id')
                ->join('goods', 'goods.goods_id = log.goods_id')
                ->field('log.*, users.nickname, goods.goods_name')
                ->limit($page->firstRow, $page->listRows)
                ->order('log.log_id desc')
                ->select();
        $this->assign('pager', $page);
        $this->assign('log',$log);
        return $this->fetch();
    }
    
    /**
     * 分成日志删除
     */
    public function log_del()
    {
        $id = I('del_id/d');
        if ($id) {
            $result = M('agent_log')->where(['log_id' => $id])->delete();
            if($result){
                exit(json_encode(1));
            }else{
                exit(json_encode(0));
            }
        } else {
            exit(json_encode(0));
        }
    }
    
    /**
     * 分成日志详情
     */
    public function log_detail()
    {
        $logId = I('id');
        $detail = Db::name('agent_log')->alias('log')
                ->join('users', 'users.user_id = log.user_id')
                ->join('goods', 'goods.goods_id = log.goods_id')
                ->field('log.*, users.nickname, goods.goods_name')
                ->where('log.log_id', $logId)
                ->find();
        $this->assign('detail', $detail);
        return $this->fetch();
    }
   
    
}