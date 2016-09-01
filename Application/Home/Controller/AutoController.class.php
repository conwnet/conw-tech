<?php
namespace Home\Controller;
use Think\Controller;

/**
    登录检查
*/
class AutoController extends Controller {
	public function _initialize() {
		if($_SESSION['login']!==true)
			$this->error('访问出错...');
	}
};