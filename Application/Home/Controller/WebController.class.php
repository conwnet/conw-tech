<?php
namespace Home\Controller;
use Think\Controller;

/**
	网站设置控制器
*/
class WebController extends AutoController {
	/**
		限制权限
	*/
	public function _initialize() {
		parent::_initialize();
		if($_SESSION['power']!=0)
			$this->error("访问出错...");
	}

	/**
		导航管理页面
	*/
	public function nav() {
		$tb_n = M('nav');
		$this->assign('nav_list', $tb_n->select());
		$this->display();
	}

	/**
		导航更新控制
	*/
	public function nav_update() {
		$tb_n = M('nav');
		$nid=$_GET['nid'];
		$tb_n->create();
		$tb_n->where("nid=%d", $nid)->save();
		$this->success('更新成功！');
	}

	/**
		友情链接管理页面
	*/
	public function friend() {
        $tb_n = M('friend');
        if(I('add')) {
            $tb_n->create(['name'=>'']);
            $tb_n->add();
        }
        if(I('delete')) {
            $tb_n->where('fid=%d', I('fid'))->delete();
        }
		$this->assign('friend_list', $tb_n->select());
		$this->display();
	}

	/**
		友情链接更新控制
	*/
	public function friend_update() {
		$tb_n = M('friend');
		$fid = $_GET['fid'];
		$tb_n->create();
        $tb_n->where("fid=%d", $fid)->save();
		$this->success('更新成功！');
	}
}
