<?php
namespace Home\Controller;
use Think\Controller;

/**
	成功报告管理控制器
*/
class ReportController extends AutoController {
	/**
		限制权限
	*/
	public function _initialize() {
		parent::_initialize();
		/**
		if($_SESSION['power']!=0 && $_SESSION['power']!=2 && $_SESSION['power']!=3)
			$this->error("访问出错...");
		*/
	}
	/**
		插入或更新成果报告页面
	*/
	public function insert() {
		$rid = I('rid');
		//$tb_s = M('school');
		if($rid != '') {
			$tb_r = M('report_1');
			if($info=$tb_r->where("rid=%d", $rid)->find()) {
				$info['keyword'] = preg_split('/\^/', $info['keyword']);
				$this->assign('info', $info);
			}
		}
		//$this->assign('school', $tb_s->select());
		$this->display();
	}
	/**
		执行插入或更新操作
	*/
	public function report_add() {
		$rid = I('rid');
		$tb_r = M('report_1');
		$_POST['keyword'] = $_POST['keyword'][0] . '^' . $_POST['keyword'][1] . '^' . $_POST['keyword'][2];
		if($rid == '') {
			$_POST['author'] = $_SESSION['user'];
			$_POST['s_time'] = time();
			$tb_r->create();
			$tb_r->add();
			$this->success('插入成功！');
		} else if($tb_r->where("rid=%d", $rid)->find()) {
			$tb_r->create();
			$tb_r->where("rid=%d", $rid)->save();
			$this->success('更新成功！');
		}
	}
	/**
		报告审核页面
	*/
	public function review() {
		$tb_r = M('report_1');
		$school = $_SESSION['school'];
		$report_list = $tb_r->where("s_status='0' and company like '%s%%'", $_SESSION['school'])->order('s_time DESC')->select();
		$this->assign('report_list', $report_list);
		$this->display();
	}
	/**
		报告审核通过
	*/
	public function report_ok() {
		$rid = I('rid');
		$tb_r = M('report_1');
		$school = $_SESSION['school'];
		$tb_r->where("rid=%d", $rid)->save(array('s_status'=>1));
		$this->success("审核成功！");
	}
	/**
		已通过审核报告列表
	*/
	public function update() {
		$tb_r = M('report_1');
		$page = I('page');
		if($page==null || $page<1) $page = 1;
        $start = 15 * ($page-1);
		if($_SESSION['power'] == 0)
			$report_list = $tb_r->where("s_status='1'")->limit($start, 15)->order('s_time DESC')->select();
		else
			$report_list = $tb_r->where("s_status='1' and company like '%s%%'", $_SESSION['school'])->limit($start, 15)->order('s_time DESC')->select();
		$this->assign('page', $page);
		$this->assign('report_list', $report_list);
		$this->display();
	}
	/**
		报告删除操作
	*/
	public function report_delete() {
		$rid = I('rid');
		$tb_r = M('report_1');
		$tb_r->where("rid=%d", $rid)->delete();
		$this->success("删除成功！");
	}
}


