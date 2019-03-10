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

use think\Model;
use think\Db;

/**
 * 活动逻辑类
 */
class BonusLogic extends Model
{
	private $userId;//用户id
	private $leaderId;	//上级id
	private $goodId;//商品id
	private $goodNum;//商品数量

	public function __construct($userId, $leaderId, $goodId, $goodNum)
	{	
		$this->userId = $userId;
		$this->leaderId = $leaderId;
		$this->goodId = $goodId;
		$this->goodNum = $goodNum;
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
			$agent = $this->theAgent();
			return [$dist,$agent];
		}else if($good['is_distribut'] == 1){
			$dist = $this->distribution();
			return $dist;
		}else if($good['is_agent'] == 1){
			$agent = $this->theAgent();
			return $agent;
		}else{
            // return ['bool'=>false,'msg'=>"该商品不是指定的分销或者代理商品"];
            return false;
		}	
	}

	/**
	* 分销模式
	**/
	public function distribution()
	{
		//判断用户是否已经是分销商
        $distributor = $this->users($this->userId);
        
		//判断上级用户是否为分销商
        if (!$distributor['is_distribut'])
        	return false;

        $goods = $this->goods();

        $distribut = M('distribut')->find();
        $commission = $goods['shop_price'] * ($distribut['rate'] / 100) * $this->goodNum;

        $is_true =  M('users')->where('user_id',$distributor['first_leader'])->setInc('distribut_money',$commission);

        if ($is_true !== false) {
        	return true;
        } else {
        	return false;
        }

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
	public function theAgent()
	{
		//判断用户是否已经是代理
		$agent = M('agent_info')->where('uid', $userId)->find();
		if($agent){
            //是代理
            
		}else{
            //不是代理
            
		}
	}
}