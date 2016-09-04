<?php defined('BASEPATH') OR exit('No direct script access allowed');
 
class MY_Controller extends CI_Controller
{
  protected $data = array();
  function __construct()
  {
    parent::__construct();
    //页面数据
    $this->data['page_title'] = 'CI App';//标题
    $this->data['before_head'] = '';     //头部之前
    $this->data['before_body'] ='';      //body之前
  }
  //渲染页面 参数: 视图, 模板
  protected function render($the_view = NULL, $template= 'master')
  {
    //对于json请求
    if ($template=='json'||$this->input->is_ajax_request()) 
    {
      header('Content-Type: application/json');
      echo json_encode($this->data);
    }else{
      //把$the_view设为内容
      $this->data['the_view_content'] = (is_null($the_view)) ? '' : $this->load->view($the_view,$this->data, TRUE);
      //加载视图
      $this->load->view('templates/'.$template.'_view', $this->data);
    }
  }
}
//管理界面控制器
class Admin_Controller extends MY_Controller
{
  function __construct()
  {
    parent::__construct();
    $this->load->library('ion_auth');
    if (!$this->ion_auth->logged_in()) 
    {
      //重定向至登录页
      redirect('admin/user/login','refresh');
    }
    $this->data['current_user'] = $this->ion_auth->user()->row();
    $this->data['current_user_menu'] = '';
    if ($this->ion_auth->in_group('admin')) 
    {
      $this->data['current_user_menu'] = $this->load->view('templates/_parts/user_menu_admin_view.php', NULL, TRUE);
    }
    //设置标题
    $this->data['page_title'] = 'CI App - Dashboard';
  }
  //设置模板
  protected function render($the_view = NULL, $template='admin_master')
  {
    //在此之前要设置好data(网页内容)
    //进行渲染
    parent::render($the_view,$template);
  }
}
 
class Public_Controller extends MY_Controller
{
  function __construct()
  {
    parent::__construct();
  }
}