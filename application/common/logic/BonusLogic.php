<?php
/**
 * 智丰网络
 * ============================================================================
 * 版权所有 2015-2027 深圳搜豹网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.tp-shop.cn
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用 .
 * 不允许对程序代码以任何形式任何目的的再发布。
 * 采用TP5助手函数可实现单字母函数M D U等,也可db::name方式,可双向兼容
 * ============================================================================
 * Author: lhb
 * Date: 2017-05-15
 */

namespace app\common\logic;

use app\common\logic\LevelLogic;
use think\Model;
use think\Db;

/**
 * 活动逻辑类
 */
class BonusLogic extends Model
{
	private $userId;//用户id
	private $goodId;//商品id
	private $goodNum;//商品数量
	private $orderSn;//订单编号
	private $orderId;//订单id

	public function __construct($userId,  $goodId, $goodNum, $orderSn, $orderId)
	{	
		$this->userId = $userId;
		$this->goodId = $goodId;
		$this->goodNum = $goodNum;
		$this->orderSn = $orderSn;
		$this->orderId = $orderId;
	}

	public function bonusModel()
	{
		//判断商品是否是分销商品或者代理商品
		$good = M('goods')
				->where('goods_id', $this->goodId)
				->field('is_distribut,is_agent')
                ->find();
		
		if(($good['is_distribut'] == 1) && ($good['is_agent'] == 1)){
			$dist = $this->distribution();
			$agent = $this->theAgent($this->userId);
			return true;
		}else if($good['is_distribut'] == 1){
			$dist = $this->distribution();
			return true;
		}else if($good['is_agent'] == 1){
			$agent = $this->theAgent($this->userId);
			return true;
		}else{
            return false;
		}	
	}

	/**
	* 分销模式
	**/
	public function distribution()
	{
        $distributor = $this->users($this->userId);

        if ($distributor['is_distribut'] != 1) {
        	M('users')->where('user_id',$this->userId)->update(['is_distribut'=>1]);
        }
        
		//判断上级用户是否为分销商
        if (!$distributor['first_leader']){
        	return false;
        }

        $goods = $this->goods();

        $distribut = M('distribut')->find();
        $commission = $goods['shop_price'] * ($distribut['rate'] / 100) * $this->goodNum; //计算佣金

        $bool = M('users')->where('user_id',$distributor['first_leader'])->setInc('user_money',$commission);

        if ($bool !== false) {
        	$desc = "分销所得佣金";
        	$log = $this->writeLog($distributor['first_leader'],$commission,$desc); //写入日志

        	return true;
        } else {
        	return false;
        }

	}

	//记录日志
	public function writeLog($userId,$money,$desc)
	{
		$data = array(
			'user_id'=>$userId,
			'user_money'=>$money,
			'change_time'=>time(),
			'desc'=>$desc,
			'order_sn'=>$this->orderSn,
			'order_id'=>$this->orderId
		);

		$bool = M('account_log')->insert($data);

		return $bool;
	}

	//商品信息
	public function goods(){
		$goods = M('goods')->field("shop_price,cat_id")->where(['goods_id'=>$this->goodId])->find();
		return $goods;
	}

	//查询用户信息
	public function users($user_id){

		$users = M('users')->where(['user_id'=>$user_id])->find();
		return $users;
	}

	//查询用户上级信息
	public function first_leader($user_id){

		$users = M('users')->where(['user_id'=>$user_id])->find();
		return $users;
	}

	/**
	* 代理模式
	**/
	public function theAgent($uid)
	{

		$agentLevel = M('users')->where('user_id', $uid)->value('first_leader');
		//上级升级
		$top_level = new LevelLogic();
		$result = $top_level->user_in($agentLevel);
		
	}

	public function sel($agentId,$price)
	{
		$allHead = getAllUp($agentId);
		$allId = array_column($allHead, 'first_leader');
		$allId = array_filter($allId);  //去空值
		array_unshift($allId,$agentId);

		for ($i=0; $i < (count($allId)-1); $i++) { 
			$bool = $this->poorAgent($allId[$i],$allId[$i+1],$price);

			if (!$bool) {
				break;
			}
		}
	}

	//极差
	public function poorAgent($agentId,$headId,$price)
	{
		//代理信息
		$agent = $this->agent($agentId);
		$headAgent = $this->headAgent($headId);
		// $next  = $this->nextAgent($nextId);

		if (!$headAgent) {
			return false;
		}

		$money = 0;
		$bool = true;

		if ($agent) {
			//判断等级
			if ($agent['level'] > $headAgent['level']) {
				return false;
			}
			
			//是否同等级
			if ($headAgent['level'] == $agent['level']) {
				// //获取最大等级
				// $maxLevel = M('user_level')->max('level');

				if ($headAgent['level'] == 6) {
					$next = $this->agent($agentId);

					if ($next['level'] && $next['level'] == 6) {
						return false;
					}

					$money = $price * ($headAgent['rate'] - $agent['rate']) / 100 * 0.1;
				}
			} else {
				$money = $price * ($headAgent['rate'] - $agent['rate']) / 100;
			}
		} elseif ($agentId == $this->userId) {
			 return false;
		} else {
			$money = $price * $headAgent['rate'] / 100;
			$bool = false;
		}
		
		$is_true = M('users')->where('user_id',$headAgentt['uid'])->setInc('user_money',$money);

		if ($is_true !== false) {
			$desc = "代理所得佣金";
			$log = $this->writeLog($headAgent['agent_id'],$money,$desc);  //写入日志		
		}

		return $bool;
	}

	//上级代理信息
	public function headAgent($agentId)
	{
		$agent = M('agent_info')->alias('info')
				 ->join('user_level level','level.level = info.level_id')
				 ->where('info.head_id',$agentId)
				 ->where('level.level','neq',0)
				 ->field('info.agent_id,level.level,level.rate')
				 ->find();

		return $agent;
	}

	//代理
	public function agent($agentId)
	{
		$agent = M('agent_info')->alias('info')
				 ->join('user_level level','level.level = info.level_id')
				 ->where('info.head_id',$agentId)
				 ->field('info.agent_id,level.level,level.rate')
				 ->find();

		return $agent;
	}
}