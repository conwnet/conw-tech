<?php
	function power2job($power) {
		switch ($power) {
			case '0':
				return '管理员';
				break;
			case '1':
				return '校级管理员';
				break;
			case '2':
				return '二级管理员';
				break;
			case '3':
				return '项目负责人';
				break;
			default:
				return '不存在';
				break;
		}
	}
	function user_can_mgr($power) {
		switch ($power) {
			case '1':
				return '(2, 3)';
				break;
			case '2':
				return '(3)';
				break;
			default:
				return '(-1)';
				break;
		}
	}
	function URL() {
	    $sys_protocal = isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443' ? 'https://' : 'http://';
	    $php_self = $_SERVER['PHP_SELF'] ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_NAME'];
	    $path_info = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '';
	    $relate_url = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : $php_self.(isset($_SERVER['QUERY_STRING']) ? '?'.$_SERVER['QUERY_STRING'] : $path_info);
	    return $sys_protocal.(isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '').$relate_url;
	}
?>
