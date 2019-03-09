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
 * 采用最新Thinkphp5助手函数特性实现单字母函数M D U等简写方式
 * ============================================================================
 * Author: 聂晓克      
 * Date: 2018-05-03
 */
namespace app\admin\controller;

use think\Page;
use think\Db;
// use app\admin\logic\MaterialLogic;

class Material extends Base {
	public function materialList(){//素材列表
	if($_POST){
		$act = input('cat_id/s');
		$title = input('title/s');
		if($act==1){
			$where = "and title like '%$title%'";
		}else{
			$where = "and cat_name like '%$title%'";
		}
	}
	$count = Db::name('material')->count();
	$page = new Page($count,10); 
	// print_r($page->firstRow.','.$page->listRows);exit;
	$list = Db::query("select * from tp_material,tp_material_cat where tp_material.cat_id=tp_material_cat.cat_id $where order by tp_material.material_id desc limit $page->firstRow,$page->listRows");
	$this->assign('page',$page);
	$this->assign('list',$list);
	return $this->fetch();
	}
	
	public function materialClass(){//素材分类列表
	$count = Db::name('material_cat')->count();
	$page = new Page($count,10); 
	$list = Db::query("select * from tp_material_cat limit $page->firstRow,$page->listRows");
	// print_r($list);exit;
	$this->assign('page',$page);
	$this->assign('list',$list);
	return $this->fetch();
	}
	
	public function mOperate($material_id=""){//素材操作列表
		$class = M('material_cat')->select();
		$this->assign('class',$class);
		if($material_id>0){
			$material = M('material')->where('material_id',$material_id)->select();
			$this->assign('material',$material[0]);
		}
		
		if(IS_POST){
			$material_id = I('material_id');
			
			$title = I('title');
			$cat_id = I('cat_id');
			$add_time = time();
			$is_open = I('is_open');
			$describe = I('describe');
			$content = I('content');
			$thumb = I('thumb');
			$video = I('video');
			$video_type = I('video_type');
			$data = array(
				'title' => $title,
				'cat_id' => $cat_id,
				'is_open' => $is_open,
				'describe' => $describe,
				'content' => $content,
				'thumb' => $thumb,
				'video' => $video,
				'video_type' => $video_type
			);
			if($title==""){
				$this->ajaxReturn(['status' => 0, 'msg' => '请填写标题！']);
			}elseif($cat_id==0){
				$this->ajaxReturn(['status' => 0, 'msg' => '请选择分类，没有请先添加！']);
			}
			elseif($video!="" && $video_type==0){
				$this->ajaxReturn(['status' => 0, 'msg' => '添加视频,请选择视频类别！']);
			}else{
				if($material_id>0){
					$res = M('material')->data($data)->where('material_id='.$material_id)->save();
					$msg = '您没有修改信息！';
				}else{
					$data['add_time'] = $add_time;
					$res = Db::name('material')->insert($data);
					$msg = '操作失败，请联系官方!';
				}
				if($res){
					$this->ajaxReturn(['status' => 1, 'msg' => '操作成功！']);
				}else{
					$this->ajaxReturn(['status' => 0, 'msg' => $msg]);
				}
			}
		}
		return $this->fetch();
	}
	public function mdel(){//素材删除
		$material_id = I('post.material_id');
		if($material_id>0){
			$del = Db::name('material')->where('material_id',$material_id)->delete();
			$this->ajaxReturn(['status' => 1]);
		}else{
			$this->ajaxReturn(['status' => 0, 'msg' => '操作失败，请联系官方!']);
		}
	}
	
	public function mClassadd($cat_id=""){//素材分类操作表
	if($cat_id>0){
		$red = Db::name('material_cat')->where('cat_id',$cat_id)->select();
		$this->assign('red',$red);
	}
	
	if(IS_POST){
		$cat_id = I('cat_id');
		// $this->ajaxReturn(['status' => 0, 'msg' => $cat_id]);
		$cat_name =I('cat_name');
		$show_in_nav = I('show_in_nav');
		$sort_order =I('sort_order');
		$cat_desc = I('cat_desc');
		if($sort_order==''){
			$sort_order =50;
		}
		$data = array(
			'cat_name' => $cat_name, 
			'show_in_nav' => $show_in_nav,
			'sort_order' => $sort_order,
			'cat_desc' => $cat_desc
		);
		if($cat_name ==""){
			$this->ajaxReturn(['status' => 0, 'msg' => '请填写分类名称！']);
		}else{
			if($cat_id>0){
				$add = M('material_cat')->data($data)->where('cat_id='.$cat_id)->save();
				$msg = '您没有修改信息！';
			}else{
				$add = Db::name('material_cat')->insert($data);
				// print_r($data);exit;
				$msg = '操作失败，请联系官方!';
			}
			if($add){
				$this->ajaxReturn(['status' => 1, 'msg' => '操作成功!']);
			}else{
				$this->ajaxReturn(['status' => 0, 'msg' => $msg]);
			}
			
		}
		
	}
	return $this->fetch();
	}
	
	public function delclass(){//分类删除
		$act = I('post.cat_id');
		if($act>0){
			$res = Db::query("SELECT count(*) as num from tp_material as a,tp_material_cat as b where a.cat_id=b.cat_id and b.cat_id=$act");
			if($res['num']>0){
				$this->ajaxReturn(['status' => -1, 'msg' => '该分类下有文章，不允许删除，请先删除该分类下的文章']);
			}else{
				$del = Db::name('material_cat')->where('cat_id',$act)->delete();
				$this->ajaxReturn(['status' => 1, 'msg' => '操作成功!']);
			}
		}else{
			$this->ajaxReturn(['status' => 0, 'msg' => '操作失败，请联系官方!']);
		}
	}
	
}