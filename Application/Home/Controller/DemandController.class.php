<?php
namespace Home\Controller;
use Think\Controller;

/**
需求报告管理控制器
 */
class DemandController extends AutoController {
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
        $did = I('did');
        if($did != '') {
            $tb_d = M('demand');
            if($info=$tb_d->where("did=%d", $did)->find()) {
                $this->assign('info', $info);
            }
        }
        $this->display();
    }
    /**
    执行插入或更新操作
     */
    public function add() {
        $did = I('did');
        $tb_d = M('demand');
        if($did == '') {
            $_POST['s_user'] = $_SESSION['user'];
            $_POST['s_time'] = time();
            $_POST['s_company'] = $_SESSION['school'];
            $tb_d->create();
            $tb_d->add();
            $this->success('插入成功！');
        } else if($tb_d->where("did=%d", $did)->find()) {
            $tb_d->create();
            $tb_d->where("did=%d", $did)->save();
            $this->success('更新成功！');
        }
    }
    /**
    审核页面
     */
    public function review() {
        $tb_d = M('demand');
        $school = $_SESSION['school'];
        $demand_list = $tb_d->where("s_status='0'")->order('s_time DESC')->select();
        $this->assign('demand_list', $demand_list);
        $this->display();
    }
    /**
    审核通过
     */
    public function demand_ok() {
        $did = I('did');
        $tb_d = M('demand');
        $school = $_SESSION['school'];
        $tb_d->where("did=%d", $did)->save(array('s_status'=>1));
        $this->success("审核成功！");
    }
    /**
    已通过审核列表
     */
    public function update() {
        $tb_d = M('demand');
        $page = I('page');
        if($page==null || $page<1) $page = 1;
        $start = 15 * ($page-1);
        if(1 || $_SESSION['power'] == 0)
            $demand_list = $tb_d->where("s_status='1'")->limit($start, 15)->order('s_time DESC')->select();
        else
            $demand_list = $tb_d->where("s_status='1' and company like '%s%%'", $_SESSION['school'])->limit($start, 15)->order('s_time DESC')->select();
        $this->assign('page', $page);
        $this->assign('demand_list', $demand_list);
        $this->display();
    }
    /**
    报告删除操作
     */
    public function delete() {
        $did = I('did');
        $tb_d = M('demand');
        $tb_d->where("did=%d", $did)->delete();
        $this->success("删除成功！");
    }
}


