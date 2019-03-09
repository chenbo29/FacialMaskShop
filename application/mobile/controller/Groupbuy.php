<?php
/**
 * 拼团
 */
namespace app\mobile\controller;

use think\Db;
use app\common\model\WxNews;
use app\common\model\TeamActivity;
 
class Groupbuy extends MobileBase
{
    /**
     * 拼团（弹窗）
     * 拼团详情页
     * 选择款式
     */
    public function detail()
    {
       
        return $this->fetch();
    }

    /**
     * 拼团 列表
     */
    public function groupList()
    {
        $teamAct = new TeamActivity();
        $time = time();
        $count = $teamAct->where('deleted',0)->where('end_time','<',$time)->count();
        $Page = new Page($count, 10);
        $list = $teamAct->where('deleted',0)>where('end_time','<',$time)->order('team_id desc')
                ->Join('test_paper_user u',"u.paper_id=p.id and u.deleted=0 and u.is_finish=0 and u.user_id='".$Session['user_id']."'")
                ->field('p.id,p.title,p.description,p.img')
                ->limit($Page->firstRow . ',' . $Page->listRows)->select();
        $this->assign('list', $list);
        $this->assign('page', $Page);
        return $this->fetch();
    }


     /**
     * 用户 评价
     */
    public function comment()
    {
       
        return $this->fetch();
    }

  
    
}