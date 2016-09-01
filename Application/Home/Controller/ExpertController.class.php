<?php
namespace Home\Controller;
use Think\Controller;

/**
    登录管理控制器
*/
class ExpertController extends Controller {
    public function _initialize() {
        if($_SESSION['login'] !== true)
            $this->error('请先登录...');
    }

    /**
        项目评估主界面
    */
    public function index() {
        redirect(U('Expert/insert'));
    }

    /**
        专家注册界面
    */
    public function insert() {
        $eid = I('eid');
        if($eid != '') {
            $tb_e = M('expert');
            if($info=$tb_e->where("eid=%d", $eid)->find()) {
                $info['work'] = preg_split('/\^/', $info['work']);
                $this->assign('info', $info);
            }
        }
        $tb_s = M('school');
        $this->assign('school', $tb_s->select());
        $this->display();
    }

    /**
        专家添加
    */
    public function expert_add() {
        $eid = I('eid');
        $tb_e = M('expert');
        $_POST['work'] = $_POST['work'][0] . '^' . $_POST['work'][1]
            . '^' . $_POST['work'][2] . '^' . $_POST['work'][3]
            . '^' . $_POST['work'][4] . '^' . $_POST['work'][5]
            . '^' . $_POST['work'][6] . '^' . $_POST['work'][7]
            . '^' . $_POST['work'][8] . '^' . $_POST['work'][9]
            . '^' . $_POST['work'][10] . '^' . $_POST['work'][11]
            . '^' . $_POST['work'][12] . '^' . $_POST['work'][13]
            . '^' . $_POST['work'][14] . '^' . $_POST['work'][15];
        if($eid == '') {
            $_POST['s_user'] = $_SESSION['user'];
            $_POST['s_time'] = time();
            $_POST['status'] = 0;
            $tb_e->create();
            $tb_e->add();
            $this->success('提交成功！专家信息正在审核...！');
        } else if($tb_e->where("eid=%d", $eid)->find()) {
            $tb_e->create();
            $tb_e->where("eid=%d", $eid)->save();
            $this->success('更新成功！');
        }
    }

    /**
        专家审核页面
     */
    public function review() {
        $eid = I('eid');
        if($eid == '') {
            $tb_e = M('expert');
            $school = $_SESSION['school'];
            $expert_list = $tb_e->where("status='0' and school='%s'", $school)->order('s_time DESC')->select();
            $this->assign('expert_list', $expert_list);
            $this->display();
        } else {
            $eid = I('eid');
            $tb_e = M('expert');
            $tb_e->where("eid=%d", $eid)->save(array('status'=>1));
            $this->success("审核成功！");
        }
    }

    /**
        已通过审核专家列表
     */
    public function update() {
        $tb_e = M('expert');
        $page = I('page');
        if($page==null || $page<1) $page = 1;
        $start = 15 * ($page-1);
        if($_SESSION['power'] == 0)
            $expert_list = $tb_e->where("status='1'")->limit($start, 15)->order('s_time DESC')->select();
        else
            $expert_list = $tb_e->where("status='1' and school='%s'", $_SESSION['school'])->limit($start, 15)->order('s_time DESC')->select();
        $this->assign('page', $page);
        $this->assign('expert_list', $expert_list);
        $this->display();
    }

    /**
        专家删除操作
     */
    public function expert_delete() {
        $eid = I('eid');
        $tb_e = M('expert');
        $tb_e->where("eid=%d", $eid)->delete();
        $this->success("删除成功！");
    }
}

