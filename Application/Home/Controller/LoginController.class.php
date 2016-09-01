<?php
namespace Home\Controller;
use Think\Controller;

/**
    登录管理控制器
*/
class LoginController extends Controller {
    /**
        登录检查
    */
    public function check() {
    	if(!isset($_POST['user']))
    		$this->error("访问出错...");
    	$tb_u = M('user');
    	$user = $_POST['user'];
    	$info = $tb_u->where("user='%s'", $user)->find();
    	if($info['pass']==md5($_POST['pass'])) {
    		if($info['status']==0) {
    			$this->error('账户不可用...');
    		} else {
	    		$_SESSION['login'] = true;
	    		$_SESSION['uid'] = $info['uid'];
	    		$_SESSION['user'] = $info['user'];
	    		$_SESSION['nick'] = $info['nick'];
                $_SESSION['school'] = $info['school'];
	    		$_SESSION['power'] = $info['power'];
	    		$this->success('登录成功！', U('Index/index'));
			}
    	} else {
    		$this->error('密码不正确...');
    	}
    }

    /**
        注销登录
    */
    public function logout() {
    	if($_SESSION['login']===false)
    		$this->error("访问出错...");
    	$_SESSION['login'] = false;
    	$this->success('注销成功！', U('Index/index'));
    }

    /**
        注册页面
    */
    public function register() {
        $tb_s = M('school');
        $school_list = $tb_s->select();
        $this->assign('school_list', $school_list);
    	$this->display();
    }

    /**
        注册确认
    */
    public function insert() {
        if(I('who')!=2 && I('who')!=3)
            $this->error('访问出错...');

        if(strlen($_POST['user'])<3 && strlen($_POST['pass'])<3)
            $this->error('用户名或密码太短！');
        $user = I('user');
        $tb_u = M('user');
        if($tb_u->where("user='%s'", $user)->find()!=null)
            $this->error('用户名已经存在...');

        $_POST['pass'] = md5($_POST['pass']);
        $_POST['power'] = I('who');
        $_POST['time'] = time();
        $tb_u->create();
        $tb_u->add();
        $this->success('注册成功！', U('Index/index'));
    }
}
