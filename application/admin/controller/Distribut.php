<?php

namespace app\admin\controller;

use think\Page;
use think\Db;
use think\Loader;

class Distribut extends Base {

    public function goods_list(){
       
     
        return $this->fetch();
    }

    public function distributor_list()
    {
      
        return $this->fetch();
    }
    
    public function tree()
    {
      
        return $this->fetch();
    }
    
    /**
    * 分销商设置
    **/
    public function grade_list()
    {
        $data = input('post.');

        $distribut = M('distribut')->find();

        if ($data) {
            if ($distribut) {
                M('distribut')->where('distribut_id',$distribut['distribut_id'])->update(['rate'=>$data['rate'],'time'=>$data['date'],'update_time'=>time()]);
            } else {
                M('distribut')->insert(['rate'=>$data['rate'],'time'=>$data['date'],'create_time'=>time(),'update_time'=>time()]);
            }
        }

        $this->assign('rate', $distribut['rate']);
        
        return $this->fetch();
    }

    /**
    * 代理商设置
    **/
    public function agent_grade_list()
    {
         $Ad = M('user_level');
        $p = $this->request->param('p');
        $res = $Ad->order('level_id')->page($p . ',10')->select();
        if ($res) {
            foreach ($res as $val) {
                $list[] = $val;
            }
        }
        $this->assign('list', $list);
        $count = $Ad->count();
        $Page = new Page($count, 10);
        $show = $Page->show();
        $this->assign('page', $show);

        return $this->fetch();
    }

    /**
     * 代理商等级编辑
     */
    public function level()
    {
        $act = I('get.act', 'add');
        $this->assign('act', $act);
        $level_id = I('get.level_id');
        if ($level_id) {
            $level_info = D('user_level')->where('level_id=' . $level_id)->find();
            $this->assign('info', $level_info);
        }
        return $this->fetch();
    }

    /**
     * 代理商等级添加编辑删除
     */
    public function levelHandle()
    {
        $data = I('post.');
        $userLevelValidate = Loader::validate('UserLevel');
        $return = ['status' => 0, 'msg' => '参数错误', 'result' => ''];//初始化返回信息
        if ($data['act'] == 'add') {
            if (!$userLevelValidate->batch()->check($data)) {
                $return = ['status' => 0, 'msg' => '添加失败', 'result' => $userLevelValidate->getError()];
            } else {
                $rateCount = M('user_level')->sum('rate');
                if (($rateCount+$data['rate']) > 100) {
                    $return = ['status' => 0, 'msg' => '编辑失败，所有等级佣金比率总和在100内', 'result' => ''];
                } else {
                    $r = D('user_level')->add($data);
                    if ($r !== false) {
                        $return = ['status' => 1, 'msg' => '添加成功', 'result' => $userLevelValidate->getError()];
                    } else {
                        $return = ['status' => 0, 'msg' => '添加失败，数据库未响应', 'result' => ''];
                    }
                }
            }
        }
        if ($data['act'] == 'edit') {
            if (!$userLevelValidate->scene('edit')->batch()->check($data)) {
                $return = ['status' => 0, 'msg' => '编辑失败', 'result' => $userLevelValidate->getError()];
            } else {
                $rateCount = M('user_level')->where('level_id','neq',$data['level_id'])->sum('rate');
                if (($rateCount+$data['rate']) > 100) {
                    $return = ['status' => 0, 'msg' => '编辑失败，所有等级佣金比率总和在100内', 'result' => ''];
                } else {
                    $r = D('user_level')->where('level_id=' . $data['level_id'])->save($data);
                    if ($r !== false) {
                        $data['rate'] = $data['rate'] / 100;
                        D('users')->where(['level' => $data['level_id']])->save($data);
                        $return = ['status' => 1, 'msg' => '编辑成功', 'result' => $userLevelValidate->getError()];
                    } else {
                        $return = ['status' => 0, 'msg' => '编辑失败，数据库未响应', 'result' => ''];
                    }
                }
            }
        }
        if ($data['act'] == 'del') {
            $r = D('user_level')->where('level_id=' . $data['level_id'])->delete();
            if ($r !== false) {
                $return = ['status' => 1, 'msg' => '删除成功', 'result' => ''];
            } else {
                $return = ['status' => 0, 'msg' => '删除失败，数据库未响应', 'result' => ''];
            }
        }
        $this->ajaxReturn($return);
    }

    public function rebate_log()
    {
        
        return $this->fetch();
    }
}