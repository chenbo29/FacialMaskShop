<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/31
 * Time: 11:59
 */

namespace app\common\model;


use think\Model;

class Auction extends Model
{
	//自定义初始化
	protected static function init()
	{
		//TODO:自定义初始化
	}

	public function specGoodsPrice()
	{
		return $this->hasOne('specGoodsPrice','goods_id','goods_id');
	}

	public function goods()
	{
		return $this->hasOne('goods','goods_id','goods_id');
	}


	/**
	*是否编辑
	*@param $value
	*@param $data
	*@param $int
	*/
	public function getIsEditAttr($value, $data)
	{
		if($data['is_end'] == 1 || $data['start_time'] < time()){
			return 0;
		}
		return 1;
	}
}