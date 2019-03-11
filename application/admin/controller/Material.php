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
use app\admin\logic\MaterialLogic;

class Material extends Base {
	public function materialList(){//素材列表
	$Article =  M('material'); 
	$res = $list = array();
	$p = empty($_REQUEST['p']) ? 1 : $_REQUEST['p'];
	$size = empty($_REQUEST['size']) ? 20 : $_REQUEST['size'];
	
	$where = " 1 = 1 ";
	$keywords = trim(I('keywords'));
	$keywords && $where.=" and title like '%$keywords%' ";
	$cat_id = I('cat_id',0);
	$cat_id && $where.=" and cat_id = $cat_id ";
	$res = $Article->where($where)->order('material_id desc')->page("$p,$size")->select();
	$count = $Article->where($where)->count();// 查询满足要求的总记录数
	$pager = new Page($count,$size);// 实例化分页类 传入总记录数和每页显示的记录数
	//$page = $pager->show();//分页显示输出
	
	$ArticleCat = new MaterialLogic();
	$cats = $ArticleCat->article_cat_list(0,0,false);
	if($res){
		foreach ($res as $val){
			$val['category'] = $cats[$val['cat_id']]['cat_name'];
			$val['add_time'] = date('Y-m-d H:i:s',$val['add_time']);        		
			$list[] = $val;
		}
	}
	
	$material_tag=config('NEWS_TAG');
	foreach ($list as $k => $v) {
	    if($v['tags']){
	        $str='';
	        $tmp=explode(',', $v[tags]);
	        foreach ($tmp as $k2 => $v2) {
	            $str.='['.$material_tag[$v2].']'.' ';
	        }
	        $list[$k]['tags']=$str;
	    }
	}
	
	$admin_info=getAdminInfo(session('admin_id'));
	$this->assign('cats',$cats);
	$this->assign('cat_id',$cat_id);
	$this->assign('list',$list);// 赋值数据集
	$this->assign('pager',$pager);// 赋值分页输出
	return $this->fetch();
	}
	public function materialClass(){//素材分类列表
	$ArticleCat = new MaterialLogic(); 
	$cat_list = $ArticleCat->article_cat_list(0, 0, false);
	$this->assign('cat_list',$cat_list);
		return $this->fetch();
	}
	
	public function mOperate(){//素材操作列表
		 $ArticleCat = new MaterialLogic();
		$act = I('GET.act','add');
		$info = array();
		$info['add_time'] = time()+3600*24;
		if(I('GET.material_id')){
		   $material_id = I('GET.material_id');
		   $info = M('material')->where('material_id='.$material_id)->find();
		}
		if($info['tags']){
		    $info['tags_arr']=explode(',', $info['tags']);
		}
		//dump($info);exit();
		
		
		$tag=config('NEWS_TAG');
		$admin_info=getAdminInfo(session('admin_id'));
		
		$cats = $ArticleCat->article_cat_list(0,$info['cat_id']);
		$this->assign('cat_select',$cats);
		$this->assign('act',$act);
		$this->assign('info',$info);
		$this->assign('tags',$tag);
		$this->assign('role_id',$admin_info['role_id']);
		return $this->fetch();
	}
	
	public function mClassadd(){//素材分类操作表
	$ArticleCat = new MaterialLogic();
	$act = I('get.act', 'add');
	$cat_id = I('get.cat_id/d');
	
	$parent_id = I('get.parent_id/d');
	if ($cat_id) {
	    $cat_info = M('material_cat')->where('cat_id=' . $cat_id)->find();
	    $parent_id = $cat_info['parent_id'];
	    $this->assign('cat_info', $cat_info);
	}
	$cats = $ArticleCat->article_cat_list(0, $parent_id, true);
	$this->assign('act', $act);
	$this->assign('cat_select', $cats);
		return $this->fetch();
	}
	public function aticleHandle()
	{
	    $data = I('post.');
	    $data['add_time'] = strtotime($data['add_time']);
	    
	    $result = $this->validate($data, 'News.'.$data['act'], [], true);
	    if ($result !== true) {
	        $this->ajaxReturn(['status' => 0, 'msg' => '参数错误', 'result' => $result]);
	    }
	    
	
	    if($data['tags']){
	        $data['tags']=implode(',', $data['tags']);
	    }else{
	        $data['tags']='';
	    }
	    
	    if ($data['act'] == 'add') {
	        $data['click'] = mt_rand(1000,1300);
	    	$data['add_time'] = time();
	        $r = M('material')->add($data);
	    } elseif ($data['act'] == 'edit') {
	        $r = M('material')->where('material_id='.$data['material_id'])->save($data);
	    } elseif ($data['act'] == 'del') {
	    	$r = M('material')->where('material_id='.$data['material_id'])->delete(); 	
	    }
	    
	    if (!$r) {
	        $this->ajaxReturn(['status' => -1, 'msg' => '操作失败']);
	    }
	
	    $this->ajaxReturn(['status' => 1, 'msg' => '操作成功']);
	}
	public function categoryHandle()
	    {
	    	$data = I('post.');
	        
	        $result = $this->validate($data, 'NewsCategory.'.$data['act'], [], true);
	        if ($result !== true) {
	            $this->ajaxReturn(['status' => 0, 'msg' => '参数错误', 'result' => $result]);
	        }
	
	        if ($data['act'] == 'add') {
	            $r = M('material_cat')->add($data);
	        } elseif ($data['act'] == 'edit') {
	        	$cat_info = M('material_cat')->where("cat_id",$data['cat_id'])->find();
	        	if($cat_info['cat_type'] == 1 && $data['parent_id'] > 1){
	        		$this->ajaxReturn(['status' => -1, 'msg' => '可更改系统预定义分类的上级分类']);
	        	}
	        	$r = M('material_cat')->where("cat_id",$data['cat_id'])->save($data);
	        } elseif ($data['act'] == 'del') {
	/*        	if($data['cat_id']<9){
	        		$this->ajaxReturn(['status' => -1, 'msg' => '系统默认分类不得删除']);
	        	}*/
	        	if (M('material_cat')->where('parent_id', $data['cat_id'])->count()>0)
	        	{
	        		$this->ajaxReturn(['status' => -1, 'msg' => '还有子分类，不能删除']);
	        	}
	        	if (M('material')->where('cat_id', $data['cat_id'])->count()>0)
	        	{
	        		$this->ajaxReturn(['status' => -1, 'msg' => '该分类下有文章，不允许删除，请先删除该分类下的文章']);
	        	}
	        	$r = M('material_cat')->where('cat_id', $data['cat_id'])->delete();
	        }
	        
	        if (!$r) { 
	            $this->ajaxReturn(['status' => -1, 'msg' => '操作失败']);
	        } 
	        $this->ajaxReturn(['status' => 1, 'msg' => '操作成功']);
	    }
}