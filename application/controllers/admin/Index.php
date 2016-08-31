<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Index extends CI_Controller {
    public $urser_info;

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $data['cur_title']  = array('active', '', '', '', '');

        $this->load->view('admin/header');
        $this->load->view('admin/menu', $data);
        $this->load->view('admin/index');
        $this->load->view('admin/footer');
    }


    public function login()
    {
        $username   = trim($this->input->post('username', true));
        $password   = trim($this->input->post('password', true));

        $this->db->where('username', $username);
        $this->urser_info   = $this->db->get('user')->result_array();

        $this->form_validation->set_rules('username', 'Username', 'trim|callback_username_check');
        $this->form_validation->set_rules('password', 'Password', 'md5|callback_password_check');

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('admin/index_login');
        } else {
            $userdata   = array(
                'username'  => $username,
                'password'  => $password,
                );

            $this->session->set_userdata($userdata);
            redirect('admin/Idenx/idenx');
        }
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
            return FLASE;
        } else {
            return TRUE;
        }
    }

    public function password_check($str)
    {
        $password   = isset($this->user_info[0]['password']) ? $this->user_info[0]['password'] : 0;

        if (md5($str != $password)) {
            $this->form_validation->ser_message('password_check', '密码错误');
            return FALSE;
        } else {
            return TRUE;
        }
    }
}
