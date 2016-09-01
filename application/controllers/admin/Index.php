<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Index extends MY_Controller {
    private  $user_info;

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->login();
    }

    //后台注册
    public function register()
    {
        $username   = trim($this->input->post('username', true));
        $password   = trim($this->input->post('password', true));
        $acpassword = trim($this->input->post('acpassword', true));
        $email      = trim($this->input->post('email', true));

        $this->form_validation->set_rules('username', '用户名', 'trim|required|min_length[6]|max_length[20]');
        $this->form_validation->set_rules('password', '密码', 'trim|required|matches[pasaconf]|min_length[6]|max_length[20]');
        $this->form_validation->set_rules('passconf', '确认密码', 'trim|required');
        $this->form_validation->set_rules('email', '邮箱', 'trim|required|valid_email');
        $this->form_validation->set_error_delimiters('<p class="text-danger">', '</p>');


        $this->form_validation->set_message('required', '必须填写');  
        $this->form_validation->set_message('valid_email', '不是有效的email'); 
        $this->form_validation->set_message('min_length', '至少6个字符'); 
        $this->form_validation->set_message('max_length', '不能超过20个字符'); 
        $this->form_validation->set_message('matches', '两次密码不匹配'); 

        if ($this->form_validation->run() == FALSE) {
           $this->load->view('admin/register');
        } else {
            $data   = array(
                'username'  => $username,
                'password'  => md5($password),
                'email'     => $email,
                );
          
            $res    = $this->db->insert('user', $data);
            redirect('admin/Index/login');
        }
        
    }

    //后台登录
    public function login()
    {
        $username   = trim($this->input->post('username', true));
        $password   = trim($this->input->post('password', true));
      
        $this->db->where('username', $username);
        $this->user_info   = $this->db->get('user')->result_array();

        $this->form_validation->set_rules('username', 'Username', 'trim|callback_username_check');
        $this->form_validation->set_rules('password', 'Password', 'md5|callback_password_check');
        $this->form_validation->set_error_delimiters('<p class="text-danger">', '</p>');

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('admin/index_login');
        } else {
            $userdata   = array(
                'username'  => $username,
                'password'  => $password,
                );

            $this->session->set_userdata($userdata);
            redirect('admin/Index/show');
        }
    }

    //后台首页显示
    public function show()
    {
        $data['cur_title']  = array('active', '', '', '', '');

        $data['user']   = $this->session->userdata('username');
        
        $this->load->view('admin/header');
        $this->load->view('admin/menu', $data);
        $this->load->view('admin/index');
        $this->load->view('admin/footer');
    }

    public function logout()
    {
        session_destroy();
        redirect(site_url('Articles/index'));
    }

    public function username_check($str)
    {
        if ($str == '') {
            $this->form_validation->set_message('username_check', '用户名不能为空');
            return FALSE;
        } elseif ($this->user_info == null) {
            $this->form_validation->set_message('username_check', '用户名不存在');
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function password_check($str)
    {
        $password   = isset($this->user_info[0]['password']) ? $this->user_info[0]['password'] : 0;

        if ($str != $password) {
            $this->form_validation->set_message('password_check', '密码错误');
            return FALSE;
        } else {
            return TRUE;
        }
    }
}
