<?php
/**
 * 竞拍 的 PHP 后端
 */
namespace app\mobile\controller;

use app\common\logic\AuctionLogic;
use think\Db;
use app\common\model\WxNews;

class Auction extends MobileBase
{

    public $user_id = 3;
    public $user = array();
    /**
     * 析构流函数
     */
    public function  __construct() {
        parent::__construct();
        if (session('?user')) {
            $user = session('user');
            $user = M('users')->where("user_id", $user['user_id'])->find();
            session('user', $user);  //覆盖session 中的 user
            $this->user = $user;
            $this->user_id = $user['user_id'];
            $this->assign('user', $user); //存储用户信息

        }
    }

    public function detail()
    {
       
        return $this->fetch();
    }

     /**
     * 竞拍
     */
    public function index()
    {
       
        return $this->fetch();
    }

//    public function index()
//    {
//        $commodity = M('Auction')->order('preview_time asc')->select();
//        $this->assign('commodity', $commodity);
//        return $this->fetch();
//    }


    /**
     * 竞拍成功 付款页面
     */
    public function pay()
    {
       
        return $this->fetch();
    }

    /**
     * 竞拍详情
     */
    public function detail()
    {

        return $this->fetch();
    }

    /**
     * 竞拍详情
     */
    public function auction_detail()
    {

        $goods_id = I("get.id/d");
        $goodsModel = new \app\common\model\Auction();
        $goods = $goodsModel::get($goods_id);
        //dump($goods);exit;
        if (empty($goods) || ($goods['auction_status'] == 0)) {
            $this->error('此商品不存在或者已下架');
        }
        $auctionLogic = new AuctionLogic();
        $isBond = $auctionLogic->getUserIsBond($this->user_id, $goods_id);
        $bondUser = $auctionLogic->getHighPrice($goods_id);
        //是否有交保证金
        if (empty($isBond)){
            $goods['isBond'] = 0;
        }else{
            $goods['isBond'] = 1;
        }

        $this->assign('bondUser', $bondUser);
        $this->assign('bondCount', count($bondUser));
        $this->assign('goods', $goods);
        return $this->fetch();
    }

    /*
     * 出价
     */
    public function offerPrice()
    {
        $auction_id = 64;//input("goods_id/d"); // 竞拍商品id
        $item = 275;//input("goods_id/d"); // 竞拍商品id
        $price = 30;//input("goods_price/d");// 竞拍价格
//        if ($this->user_id == 0){
//            $this->error('请先登录', U('Mobile/User/login'));
//        }
        $auction = \app\common\model\Auction::get($auction_id);
        $auctionLogic = new AuctionLogic();
        $isBond = $auctionLogic->getUserIsBond($this->user_id, $auction_id);
        if(empty($isBond)){
            $this->ajaxReturn(['status' => 0, 'msg' => '未交保证金', 'jump' => U('Mobile/Payment/payBond',array('goods_id'=>$auction_id))]);
        }
        $high = $auctionLogic->getHighPrice($auction_id);
        // 活动是否已开始
        if ( time() < $auction['start_time']){
            $this->ajaxReturn(['status' => 0, 'msg' => '本轮活动还没开始', 'result' => '']);
        }
        // 当前时间是否已结束
        if ( time() > $auction['end_time']+($auction['delay_num']*$auction['delay_time'])){
            $this->ajaxReturn(['status' => 0, 'msg' => '本轮活动已结束', 'result' => '']);
        }

        if (empty($high)){
            $auctionLogic->addAuctionOffer($this->user_id, $auction_id, $price);
        } else {
            if($this->user_id == $high[0]['user_id']){
                $this->ajaxReturn(['status' => 0, 'msg' => '您已经是目前最高出价者了', 'result' => '']);
            }
            if($price <= $high[0]['offer_price']){
                $this->ajaxReturn(['status' => 0, 'msg' => '您的出价低于别人', 'result' => '']);
            }
            if ($price < ($high[0]['offer_price']+$auction['increase_price'])){
                $this->ajaxReturn(['status' => 0, 'msg' => '加价幅度'.$auction['increase_price'], 'result' => '']);
            }
            // 结束时间小于延时时间的话就添加延时次数
            if($auction['end_time']-time() < $auction['delay_time']*60){
                $auctionLogic->addDelayTime($auction_id, $auction['delay_time']);
            }
            $auctionLogic->addAuctionOffer($this->user_id, $auction_id, $price);
        }

        return $this->ajaxReturn(['status' => 1, 'msg' => '出价成功', 'result' => '']);
//        return $this->fetch('auction_detail', ['id'=>$auction_id,'item_id'=>$item]);
//        return $this->success('出价成功',U('auction_detail',array('id'=>$auction_id, 'item_id'=>$item))); //分跳转 和不 跳转

    }


	/**
	 * 条数具体化
	 */
	public function tiaoshu()
    {

        $goods_id = I("get.id/d");
        $auctionLogic = new AuctionLogic();
        $bondUser = $auctionLogic->getHighPrice($goods_id);

        $this->assign('bondUser', $bondUser);
        return $this->fetch();
    }

}