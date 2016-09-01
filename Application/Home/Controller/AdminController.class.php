<?php
namespace Home\Controller;
use Think\Controller;

/**
    后台管理控制器
*/
class AdminController extends AutoController {
	/**
		后台登录主页（内部是内联框架）
	*/
	public function index() {
		$this->display();
	}
	/**
		后台登录主页框架默认页面
	*/
	public function hello() {
		$this->display();
	}
};