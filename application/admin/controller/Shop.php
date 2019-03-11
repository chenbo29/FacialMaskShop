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

use app\common\model\Shopper;
use think\Loader;
use think\Db;
use think\AjaxPage;
use think\Page;

class Shop extends Base
{
    /**
     * 门店 - 门店管理 - 门店列表
     */
    public function index()
    {
        $list = array();
        $keywords = I('keywords/s');
        $count = D('shop')->count();
        $Page  = new AjaxPage($count,20);
        $show = $Page->show();
        if (empty($keywords)) {
            $res = DB::name('shop')->limit($Page->firstRow.','.$Page->listRows)->select();
        } else {
            $res = DB::name('shop')->where(['store_name|webid|phone|address|city' => ['like', '%' . $keywords . '%']])->order('store_id')->limit($Page->firstRow.','.$Page->listRows)->select();
        }

        $region = DB::name('region')->getField('id,name');
        if ($region && $res) {
            foreach ($res as $val) {
                $val['province_city_district'] = $region[$val['province']].' '.$region[$val['city']].' '.$region[$val['district']];
                $val['add_time'] = date('Y-m-d H:i:s', $val['add_time']);
                $list[] = $val;
            }
        }
        //$show = $Page->show();
        $this->assign('list', $list);
        $this->assign('page',$show);// 赋值分页输出
        $province_list = Db::name('region')->where(['parent_id'=>0,'level'=> 1])->cache(true)->select();
        $this->assign('province_list', $province_list);
        return $this->fetch();
    }

    /**
     * 门店自提点
     */
    public function info()
    {
        $shop_id = input('shop_id/d');
        if ($shop_id) {
            $Shop = new \app\common\model\Shop();
            $shop = $Shop->where(['shop_id' => $shop_id,'deleted' => 0])->find();
            if (empty($shop)) {
                $this->error('非法操作');
            }
            $city_list = Db::name('region')->where(['parent_id'=>$shop['province_id'],'level'=> 2])->select();
            $district_list = Db::name('region')->where(['parent_id'=>$shop['city_id']])->select();
            $shop_image_list = Db::name('shop_images')->where(['shop_id'=>$shop['shop_id']])->select();
            $this->assign('city_list', $city_list);
            $this->assign('district_list', $district_list);
            $this->assign('shop_image_list', $shop_image_list);
            $this->assign('shop', $shop);
        }
        $province_list = Db::name('region')->where(['parent_id'=>0,'level'=> 1])->cache(true)->select();
        $suppliers_list = Db::name("suppliers")->where(['is_check'=>1])->select();
        $this->assign('suppliers_list', $suppliers_list);
        $this->assign('province_list', $province_list);
        return $this->fetch();
    }

    public function add()
    {
        $data = I('post.');
        if (empty($data['longitude']) && empty($data['latitude'])) {
            $this->ajaxReturn(['status' => 0, 'msg' => '请选择地图定位', 'result' => '']);
        }
        if (empty($data['shopper_name'])) {
            $this->ajaxReturn(['status' => 0, 'msg' => '请填写门店自提点后台账号', 'result' => '']);
        }
        if (empty($data['user_name'])) {
            $this->ajaxReturn(['status' => 0, 'msg' => '请填写会员账号', 'result' => '']);
        }
        if (empty($data['password'])) {
            $this->ajaxReturn(['status' => 0, 'msg' => '请填写登录密码', 'result' => '']);
        }
        if (empty($data['shop_name'])) {
            $this->ajaxReturn(['status' => 0, 'msg' => '请填写自提点名称', 'result' => '']);
        }
        if (empty($data['shop_phone'])) {
            $this->ajaxReturn(['status' => 0, 'msg' => '请填写联系电话', 'result' => '']);
        }
        if (empty($data['work_start_time']) || empty($data['work_end_time'])) {
            $this->ajaxReturn(['status' => 0, 'msg' => '请填写营业时间', 'result' => '']);
        }
        if (empty($data['province_id'])) {
            $this->ajaxReturn(['status' => 0, 'msg' => '请选择省份/直辖市', 'result' => '']);
        }
        if (empty($data['city_id'])) {
            $this->ajaxReturn(['status' => 0, 'msg' => '请选择城市', 'result' => '']);
        }
        if (empty($data['district_id'])) {
            $this->ajaxReturn(['status' => 0, 'msg' => '请选择区/县', 'result' => '']);
        }
        if (empty($data['shop_address'])) {
            $this->ajaxReturn(['status' => 0, 'msg' => '请填写详细地址', 'result' => '']);
        }
        if (empty($data['shop_id'])) {
            unset($data['shop_id']);
        }
        if (empty($data['monday'])) {
            unset($data['monday']);
        }
        if (empty($data['tuesday'])) {
            unset($data['tuesday']);
        }
        if (empty($data['wednesday'])) {
            unset($data['wednesday']);
        }
        if (empty($data['thursday'])) {
            unset($data['thursday']);
        }
        if (empty($data['friday'])) {
            unset($data['friday']);
        }
        if (empty($data['saturday'])) {
            unset($data['saturday']);
        }
        if (empty($data['sunday'])) {
            unset($data['sunday']);
        }
        if (empty($data['suppliers_id'])) {
            unset($data['suppliers_id']);
        }
        if (empty($data['shop_images'])) {
            unset($data['shop_images']);
        }else{
            $data['shop_images'] = implode(',',$data['shop_images']);
        }
        $data['add_time'] = time();
        $r = D('shop')->add($data);
        if ($r) {
            $this->ajaxReturn(['status' => 1, 'msg' => '操作成功', 'url' => U('Admin/Shop/index')]);
        } else {
            $this->ajaxReturn(['status' => -1, 'msg' => '操作失败']);
        }
    }

    public function save(){
        $data = I('post.');
        if (empty($data['longitude']) && empty($data['latitude'])) {
            $this->ajaxReturn(['status' => 0, 'msg' => '请选择地图定位', 'result' => '']);
        }
        /*if (empty($data['shopper_name'])) {
            $this->ajaxReturn(['status' => 0, 'msg' => '请填写门店自提点后台账号', 'result' => '']);
        }*/
        /*if (empty($data['user_name'])) {
            $this->ajaxReturn(['status' => 0, 'msg' => '请填写会员账号', 'result' => '']);
        }
        if (empty($data['password'])) {
            $this->ajaxReturn(['status' => 0, 'msg' => '请填写登录密码', 'result' => '']);
        }*/
        if (empty($data['shop_name'])) {
            $this->ajaxReturn(['status' => 0, 'msg' => '请填写自提点名称', 'result' => '']);
        }
        if (empty($data['shop_phone'])) {
            $this->ajaxReturn(['status' => 0, 'msg' => '请填写联系电话', 'result' => '']);
        }
        if (empty($data['work_start_time']) || empty($data['work_end_time'])) {
            $this->ajaxReturn(['status' => 0, 'msg' => '请填写营业时间', 'result' => '']);
        }
        if (empty($data['province_id'])) {
            $this->ajaxReturn(['status' => 0, 'msg' => '请选择省份/直辖市', 'result' => '']);
        }
        if (empty($data['city_id'])) {
            $this->ajaxReturn(['status' => 0, 'msg' => '请选择城市', 'result' => '']);
        }
        if (empty($data['district_id'])) {
            $this->ajaxReturn(['status' => 0, 'msg' => '请选择区/县', 'result' => '']);
        }
        if (empty($data['shop_address'])) {
            $this->ajaxReturn(['status' => 0, 'msg' => '请填写详细地址', 'result' => '']);
        }
        if (empty($data['monday'])) {
            unset($data['monday']);
        }
        if (empty($data['tuesday'])) {
            unset($data['tuesday']);
        }
        if (empty($data['wednesday'])) {
            unset($data['wednesday']);
        }
        if (empty($data['thursday'])) {
            unset($data['thursday']);
        }
        if (empty($data['friday'])) {
            unset($data['friday']);
        }
        if (empty($data['saturday'])) {
            unset($data['saturday']);
        }
        if (empty($data['sunday'])) {
            unset($data['sunday']);
        }
        if (empty($data['suppliers_id'])) {
            unset($data['suppliers_id']);
        }
        if (empty($data['shop_images'])) {
            unset($data['shop_images']);
        }else{
            $data['shop_images'] = implode(',',$data['shop_images']);
        }
        if($data['shop_id']){
            $shop_id = $data['shop_id'];
            unset($data['shopper_name']);
            unset($data['user_name']);
            unset($data['password']);
            unset($data['shop_id']);
            $r = D('shop')->where(['shop_id'=> $shop_id])->save($data);
            if ($r) {
                $this->ajaxReturn(['status' => 1, 'msg' => '操作成功', 'url' => U('Admin/Shop/index')]);
            } else {
                $this->ajaxReturn(['status' => 0, 'msg' => '操作失败', 'result' => '']);
            }
        }
    }

    /**
     * 删除
     */
    public function delete()
    {
        $shop_id = input('shop_id/d');
        if(empty($shop_id)){
            $this->ajaxReturn(['status' => 0, 'msg' => '参数错误']);
        }
        $Shop = new \app\common\model\Shop();
        $shop = $Shop->where(['shop_id'=>$shop_id])->find();
        if(empty($shop)){
            $this->ajaxReturn(['status' => 0, 'msg' => '非法操作', 'result' => '']);
        }
        $row = $shop->save(['deleted'=>1]);
        if($row !== false){
            $this->ajaxReturn(['status' => 1, 'msg' => '删除成功', 'result' => '']);
        }else{
            $this->ajaxReturn(['status' => 0, 'msg' => '删除失败', 'result' => '']);
        }
    }

    public function shopImageDel()
    {
        $path = input('filename','');
        Db::name('goods_images')->where("image_url",$path)->delete();
    }

    /**
     * 商城 - 门店 - 门店管理
     */
    public function store_list()
    {
    }

    /**
     * 商城 - 门店 - 添加商铺门店
     */
    public function store_info()
    {
        $store_id = I('get.store_id/d', 0);
        if ($store_id) {
            $info = Db::name('kf_store')->where("store_id", $store_id)->find();
            $info['password'] = "";
            $this->assign('info', $info);
            $city =  M('region')->where(array('parent_id'=>$info['province']))->select();
            $area =  M('region')->where(array('parent_id'=>$info['city']))->select();
            $this->assign('city',$city);
            $this->assign('area',$area);
        }
        $act = empty($store_id) ? 'add' : 'edit';
        $province = M('region')->where(array('parent_id'=>0))->select();
        $this->assign('province',$province);
        $this->assign('act', $act);
        return $this->fetch();
    }

    /**
     * 商城 - 门店 - 添加商铺门店信息处理(商家审核)
     */
    public function sellerHandle()
    {
        $data = I('post.');
        if (empty($data['province'])) {
            unset($data['province']);
        }
        if (empty($data['city'])) {
            unset($data['city']);
        }
        if (empty($data['district'])) {
            unset($data['district']);
        }
        if ($data['act'] == 'add') {
            unset($data['store_id']);
            $data['avatar'] = "";
            $data['auditing'] = 1;
            $data['is_delete'] = 1;
            $data['add_time'] = time();
            $r = D('kf_store')->add($data);
        }
        if ($data['act'] == 'edit') {
            $r = D('kf_store')->where('store_id', $data['store_id'])->save($data);
        }
        if ($data['act'] == 'del' && $data['store_id'] > 1) {
            $r = D('kf_store')->where('store_id', $data['store_id'])->delete();
        }
        if ($r) {
            $this->ajaxReturn(['status' => 1, 'msg' => '操作成功', 'url' => U('Admin/Goods/store_list')]);
        } else {
            $this->ajaxReturn(['status' => -1, 'msg' => '操作失败']);
        }
    }
}
