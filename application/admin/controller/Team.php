<?php
/**
 * 智丰网络
 * ============================================================================
 * 版权所有 2015-2027 深圳搜豹网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.tp-shop.cn
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用 .
 * 不允许对程序代码以任何形式任何目的的再发布。
 * 如果商业用途务必到官方购买正版授权, 以免引起不必要的法律纠纷.
 * ============================================================================
 * Author: 当燃
 * 拼团控制器
 * Date: 2016-06-09
 */

namespace app\admin\controller;

use app\admin\logic\OrderLogic;
use app\common\model\Order;
use app\common\model\TeamActivity;
use app\common\model\TeamFollow;
use app\common\model\TeamFound;
use app\common\logic\MessageFactory;
use think\Loader;
use think\Db;
use think\Page;
use app\common\model\GoodsActivity;
use app\common\model\GroupBuy;
use app\common\logic\MessageTemplateLogic;


class Team extends Base
{
	public function index(){
		
		return $this->fetch();
	}
	public function info(){
        $act = I('GET.act', 'add');
        $groupbuy_id = I('get.id/d');
        $group_info = array();
        $group_info['start_time'] = date('Y-m-d H:i:s');
        $group_info['end_time'] = date('Y-m-d H:i:s', time() + 3600 * 365);
        $group_info['is_edit'] = 1;
        if ($groupbuy_id) {
            $GroupBy = new GroupBuy();
            $group_info = $GroupBy->with('specGoodsPrice,goods')->find($groupbuy_id);
            $group_info['start_time'] = date('Y-m-d H:i:s', $group_info['start_time']);
            $group_info['end_time'] = date('Y-m-d H:i:s', $group_info['end_time']);
            $act = 'edit';
        }
        $this->assign('min_date', date('Y-m-d H:i:s'));
        $this->assign('info', $group_info);
        $this->assign('act', $act);

		return $this->fetch();
	}
}
