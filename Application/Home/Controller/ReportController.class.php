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
        $item = I('item');
        $val  = I('val');

        if($item==1) $items = 'p_name';
        else if($item==2) $items = 'company';
        else if($item==3) $items = 'p_code_name';
        else if($item==4) $items = 'owner';
        else if($item==5) $items = 'area';
        else if($item==6) $items = 'p_link';
        else if($item==7) $items = 'view';
        else { $items = "''"; $val = ''; }

        $area = array(
            '先进制造' => '1',
            '新材料' => '2',
            '电子与信息' => '3',
            '生物与医药' => '4',
            '新能源与高效节能技术' => '5',
            '资源与环境' => '6',
            '现代农业' => '7',
            '民用航空' => '8',
            '其他' => '9',
        );

        if($items == 'area')
            $val = $area[trim($val)];

        $p_link = array(
            '高档数机床及自动化生产线' => '1',
            '核电装备' => '2',
            '特高压输变电设备' => '3',
            '轨道交通装备' => '4',
            '工程机械' => '5',
            '工业机器人与专用机器人' => '6',
            'IC装备' => '7',
            '新一代信息技术' => '8',
            '激光设备' => '9',
            '海洋工程装备' => '10',
            '风电装备' => '11',
            '新能源汽车' => '12',
            '航空装备' => '13',
            '新能源汽车' => '14',
            '生物医药' => '15',
            '节能环保' => '16',
            '海洋资源利用' => '17',
            '农业种子' => '18',
            '其他' => '19',
        );

        if($items == 'p_link')
            $val = $p_link[trim($val)];

        $view = array(
            '发明专利' => '1',
            '实用新型专利' => '2',
            '集成电路设计权' => '3',
            '软件著作权' => '4',
            '其他' => '5',
        );

        if($items == 'view')
            $val = $view[trim($val)];

        $report_list = $tb_r->where("s_status='0' and company like '%s%%' AND $items LIKE '%%%s%%'", $_SESSION['school'],  $val)->order('s_time DESC')->select();



        //$report_list = $tb_r->where("s_status='0' and company like '%s%%'", $_SESSION['school'])->order('s_time DESC')->select();
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


