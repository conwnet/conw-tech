<?php
namespace Home\Controller;
use Think\Controller;

class ArticleController extends AutoController {

	/**
		限制权限
	*/
	public function _initialize() {
		parent::_initialize();
		if($_SESSION['power']!=0)
			$this->error("访问出错...");
	}

	/**
		创建文章的界面，也承担修改文章内容的任务
	*/
	public function create() {
		$tb_l = M('label');
		$tb_a = M('article');
		$label_list = $tb_l->select();
		$this->assign('label_list', $label_list);
		if(I('aid')!=null) {
			$aid = I('aid');
			if($article=$tb_a->where("aid=%d", $aid)->find()) {
				$this->assign('article', $article);
			}
		}
		$this->display();
	}
	/**
		添加文章或者更新文章的后台处理
	*/
	public function article_add() {
		$tb_a = M('article');
		$aid = I('aid');
		$article['title'] = I('title');
		$article['content'] = I('content');
		$article['label'] = I('label');
		$article['author'] = $_SESSION['user'];
		$article['status'] = 1;
		if($tb_a->where("aid=%d", $aid)->find()) {
			$tb_a->where("aid=%d", $aid)->save($article);
			$this->success("文章修改成功！", U('Article/edit'));
		} else {
			$article['time'] = time();
			$tb_a->add($article);
			$this->success("文章创建成功！", U('Article/edit'));
		}
	}
	/**
		删除文章
	*/
	public function article_delete() {
		$db = M('article');
		$aid = I('aid');
		$db->where("aid=%d", $aid)->delete();
		redirect(U('Article/edit'));
	}
	/**
		编辑文章的界面，其实只是显示一个文章列表
	*/
	public function edit() {
		$db = M('article');
		$article_list = $db->order('time DESC')->select();
		$this->assign('article_list', $article_list);
		$this->display();
	}
	/**
		标签管理的页面
	*/
	public function label() {
		$db = M('label');
		$label_list = $db->select();
		$this->assign('label_list', $label_list);
		$this->display();
	}
	/**
		删除标签的后台处理
	*/
	public function label_delete() {
		$db = M('label');
		$lid = I('lid');
		$db->where("lid=%d", $lid)->delete();
		redirect(U('Article/label'));
	}
	/**
		添加标签的后台处理
	*/
	public function label_add() {
		$db = M('label');
		$db->create();
		$db->add();
		redirect(U('Article/label'));
	}
}
