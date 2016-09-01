<?php
namespace Home\Controller;
use Think\Controller;

class IndexController extends Controller {
    /**
        显示首页
    */
    public function index() {
        $tb_a = M('article');
        $tb_n = M('nav');
        $tb_f = M('friend');
        $tb_r = M('report_1');

        $this->assign('article_tongzhi', $tb_a->where("label='通知公告'")->order('time DESC')->limit(0, 7)->select());
        $this->assign('article_gongzuo', $tb_a->where("label='工作动态'")->order('time DESC')->limit(0, 5)->select());
        $this->assign('reports', $tb_r->order('s_time DESC')->limit(0, 5)->select());
        $this->assign('article_fagui', $tb_a->where("label='法规文件'")->order('time DESC')->limit(0, 5)->select());
        $this->assign('nav_list', $tb_n->limit(0, 7)->order("nid")->select());
        $this->assign('friend_list', $tb_f->limit(0, 6)->order("fid")->select());
        $this->display();
    }
    public function view() {
        $aid = I('aid');

        $tb_n = M('nav');
        $tb_a = M('article');

        $article = $tb_a->where("aid=%d", $aid)->find();
        if($article==null) {
            $this->error('文章不存在...');
        } else {
            $this->assign('nav_list', $tb_n->limit(0, 7)->order("nid")->select());
            $this->assign('article', $article);
            $this->display();
        }
    }
    /**
        根据标签和页码显示文章列表
    */
    public function lists() {
        $label = I('label');
        if(M('label')->where("name='%s'", $label)->find()==null)
            $this->error('访问出错...', U('Index/index'));
        $page = I('page');

        if($page=='' || $page<1) { $start = 0; $page = 1; }
        else $start = 15 * ($page-1);
        $tb_a = M('article');
        $tb_n = M('nav');
        $article_list = $tb_a->where("label='%s'", $label)->limit($start, 15)->order('time DESC')->select();

        $this->assign('label', $label);
        $this->assign('page', $page);
        $this->assign('article_list', $article_list);
        $this->assign('nav_list', $tb_n->limit(0, 7)->order("nid")->select());
        $this->display();
    }
    public function datas() {
        $tb_r = M('report_1');
        $tb_n = M('nav');

        $page = I('page');
        $item = I('item');
        $val  = I('val');
        $p_count = (int)($tb_r->count()/15)+1;

        if($page=='' || $page<1) $page = 1;
        if($page > $p_count) $page = $p_count;

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

        $start = 15 * ($page-1);
        $report_list = $tb_r->where("s_status='1' AND $items LIKE '%%%s%%'", $val)->limit($start, 15)->order('s_time DESC')->select();

        $this->assign('page', $page);
        $this->assign('item', $item=="''" ? '' : $item);
        $this->assign('val', $val);
        $this->assign('p_count', $p_count);
        $this->assign('label', '综合数据库');
        $this->assign('report_list', $report_list);
        $this->assign('nav_list', $tb_n->limit(0, 7)->order("nid")->select());
        $this->display();
    }

    /**
    显示成果报告页面
     */
    public function report() {
        $rid = I('rid');
        $tb_r = M('report_1');
        if($info=$tb_r->where("rid=%d", $rid)->find()) {
            $info['keyword'] = preg_split('/\^/', $info['keyword']);
            $this->assign('info', $info);
        } else {
            $this->error('报告不存在...');
        }
        $this->display();
    }

    public function demands() {
        $tb_d = M('demand');
        $tb_n = M('nav');

        $page = I('page');
        $item = I('item');
        $val  = I('val');
        $p_count = (int)($tb_d->count()/15)+1;

        if($page=='' || $page<1) $page = 1;
        if($page > $p_count) $page = $p_count;
        if($val=='') $val = '';

        $start = 15 * ($page-1);
        $demand_list = $tb_d->where("s_status='1'")->limit($start, 15)->order('s_time DESC')->select();

        $this->assign('page', $page);
        $this->assign('item', $item=="''" ? '' : $item);
        $this->assign('val', $val);
        $this->assign('p_count', $p_count);
        $this->assign('label', '综合数据库');
        $this->assign('demand_list', $demand_list);
        $this->assign('nav_list', $tb_n->limit(0, 7)->order("nid")->select());
        $this->display();
    }

    /**
        显示成果报告页面
    */
    public function demand() {
        $did = I('did');
        $tb_d = M('demand');
        if($info=$tb_d->where("did=%d", $did)->find()) {
            $this->assign('info', $info);
        } else {
            $this->error('报告不存在...');
        }
        $this->display();
    }

    /**
        专家信息表界面
    */
    public function expert() {
        $tb_s = M('school');
        $this->assign('school', $tb_s->select());
        $this->display();
    }

    /**
        添加专家
    */
    public function expert_add() {

    }

}



