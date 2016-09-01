<?php
namespace Home\Controller;
use Think\Controller;

/**
	用户管理控制器
*/
class UserController extends AutoController {
	/**
		限制权限
	*/
	public function _initialize() {
		parent::_initialize();
		if(false && $_SESSION['power']!=0 && $_SESSION['power']!=1)
			$this->error("访问出错...");
	}
	/**
		基本信息页面
	*/
	public function base() {
		$tb_u = M('user');
		$uid = $_SESSION['uid'];
		$user = $tb_u->where("uid=%d", $uid)->find();
		$this->assign('user', $user);
		$this->display();
	}
	/**
		信息更新控制
	*/
	public function base_update() {
		$tb_u = M('user');
		$uid = $_SESSION['uid'];
		$user = $tb_u->where("uid=%d", $uid)->find();
		if($user['pass'] == md5(I('pass'))) {
			if(I('new_pass')!='')
				$user['pass'] = md5(I('new_pass'));
			$user['nick'] = I('nick');
			$tb_u->where("uid=%d", $uid)->save($user);
			$this->success("修改成功！");
		} else {
			$this->error('密码不正确...');
		}
	}
	/**
		信息审核页面
	*/
	public function review() {
		$rev_power = user_can_mgr($_SESSION['power']);
		$tb_u = M('user');
		$school = $_SESSION['school'];
		$user_list = $tb_u->where("status='0' AND school='%s' AND power IN %s", $school, $rev_power)->order('time DESC')->select();
		$this->assign('user_list', $user_list);
		$this->display();
	}
	/**
		信息审核控制
	*/
	public function review_ok() {
		$rev_power = user_can_mgr($_SESSION['power']);
		$school = $_SESSION['school'];
		
		$tb_u = M('user');
		$uid = I('uid');
		if($tb_u->where("uid=%d AND school='%s' AND power IN %s", $uid, $school, $rev_power)->save(array('status'=>1))==1)
			$this->success('审核成功！');
		else $this->error('访问错误！');
	}
	/**
		用户管理页面
	*/
	public function manage() {
		$rev_power = user_can_mgr($_SESSION['power']);
		$school = $_SESSION['school'];

		$tb_u = M('user');
		$user_list = $tb_u->where("status='1' AND school='%s' AND power IN %s", $school, $rev_power)->order('time DESC')->select();
		$this->assign('user_list', $user_list);
		$this->display();
	}
	/**
		用户停用
	*/
	public function manage_stop() {
		$rev_power = user_can_mgr($_SESSION['power']);
		$school = $_SESSION['school'];

		$tb_u = M('user');
		$uid = I('uid');
		if($tb_u->where("uid=%d AND school='%s' AND power IN %s", $uid, $school, $rev_power)->save(array('status'=>0)))
			$this->success('停用成功！');
		else $this->error('访问错误！');
	}
	/**
		用户删除
	*/
	public function manage_delete() {
		$rev_power = user_can_mgr($_SESSION['power']);
		$school = $_SESSION['school'];

		$tb_u = M('user');
		$uid = I('uid');
		if($tb_u->where("uid=%d AND school='%s' AND power IN %s", $uid, $school, $rev_power)->delete())
			$this->success('删除成功！');
		else $this->error('访问错误！');
	}
}
