<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Others extends CI_Controller 
{

    //文章备份显示
    public function back_up()
    {
        $config     = $this->getPaginationConfig();
        $this->pagination->initialize($config);

        $row    = $this->uri->segment(4);
        $row    = isset($row) ? $row : 0;

        $data['data']       = $this->articlesmodel->getArticlesDuring($row, $config['per_page']);
        $data['path']       = dirname(dirname(dirname(dirname(__FILE__)))).'\\article\\';
        $data['cur_title']  = array('', '', '', '', 'active');

        $this->load->view('admin/header');
        $this->load->view('admin/menu', $data);
        $this->load->view('admin/others_backup', $data);
        $this->load->view('admin/footer');
    }

    //文章备份
    public function backup()
    {
        $backup_article     = $this->input->post('backup_article', true);

        $path   = $this->input->post('backup_path', true);

        foreach ($backup_article as $key => $value) {
            $this->db->where('id', $key);
            $article    = $this->db->get('articles')->result_array();
            $str        = 'title:'.$article[0]['title']."\r\ncategory:".$article[0]['category']."\r\ntag:".$article[0]['tag']."\r\npublished_at:".$article[0]['published_at']."\r\n\r\n====================\r\n\r\n".$article[0]['content'];

            $file   = $path.$value.'.txt';
            $file   = iconv("utf-8", "gb2312//IGNORE", $file);

            $fp     = fopen($file, 'w');
            if ($fp) {
                fwrite($fp, $str);
                fclose($fp);
            }
        }

        $data['cur_title']  = array('', '', '', '', 'active');

        $this->load->view('admin/header');
        $this->load->view('admin/menu', $data);
        $this->load->view('admin/others_backup_success', $data);
        $this->load->view('admin/footer');
    }

    //站点配置显示
    public function show_siteinfo()
    {
        $data['data']   = $this->siteinfomodel->getSiteInfo();

        $data['cur_title']  = array('', '', '', '', 'active');

        $this->load->view('admin/header', $data);
        $this->load->view('admin/menu', $data);
        $this->load->view('admin/others_siteinfo', $data);
        $this->load->view('admin/footer');
    }

    //站点配置
    public function set_siteinfo()
    {
        $site_info  = $this->siteinfomodel->updateSiteInfo();

        $data['cur_title']  = array('', '', '', '', 'active');

        $this->load->view('admin/header');
        $this->load->view('admin/menu', $data);
        $this->load->view('admin/others_siteinfo_success', $data);
        $this->load->view('admin/footer');
    }

    //关于显示
    public function about()
    {
        $data['data']   = $this->aboutmodel->getAboutInfo();

        $data['cur_title']  = array('', '', '', '', 'active');

        $this->load->view('admin/header', $data);
        $this->load->view('admin/menu', $data);
        $this->load->view('admin/others_about', $data);
        $this->load->view('admin/footer');
    }

    //关于编辑
    public function edit_about()
    {
        $data['data']   = $this->aboutmodel->updateAboutInfo();

        $data['cur_title']  = array('', '', '', '', 'active');

        $this->load->view('admin/header', $data);
        $this->load->view('admin/menu', $data);
        $this->load->view('admin/others_about_success', $data);
        $this->load->view('admin/footer', $data);
    }

    //修改密码
    public function change_password()
    {
        $username   = $this->session->userdata('username');
        $this->db->where('username', $username);
        $this->user_info    = $this->db->get('user')->result_array();

        $this->form_validation->set_rules('old_password', 'old_password', 'callback_password_check');
        $this->form_validation->set_rules('new_password', 'new_password', 'md5');
        $this->form_validation->set_rules('new_password_conf', 'new_password_conf', 'md5|matches[new_password]');
        $this->form_validation->set_message('matches', '两次输入不一致！');
        $this->form_validation->set_error_delimiters('<p class="text-danger">', '</p>');
        // var_dump($this->input->post('new_password'));
        // var_dump($this->input->post('old_password'));
        // var_dump($this->input->post('new_password_conf'));
        // exit;
        $data['cur_title']  = array('', '', '', '', 'active');

        if ($this->form_validation->run() ==FALSE) {
            $this->load->view('admin/header');
            $this->load->view('admin/menu', $data);
            $this->load->view('admin/others_change_password');
            $this->load->view('admin/footer');
        } else {
            $new_password = array('password' => $this->input->post('new_password', true));
            $this->db->where('username' , $this->session->userdata('username'));
            $this->db->update('user', $new_password);

            $this->load->view('admin/header');
            $this->load->view('admin/menu', $data);
            $this->load->view('admin/others_change_password_success');
            $this->load->view('admin/footer');
        }
    }

    //获取分页配置
    public function getPaginationConfig()
    {
        $config     = array(
            'base_url'          => site_url('Others/output'),
            'total_rows'        => $this->db->count_all('articles'),
            'per_page'          => '10',
            'num_links'         => 3,
            'last_link'         => '末页',
            'first_link'        => '首页',
            'prev_link'         => false,
            'next_link'         => false,
            'first_tag_open'    => '<li>',
            'first_tag_close'   => '</li><li><a>...</a></li>',
            'last_tag_open'     => '<li><a>...</a></li><li>',
            'last_tag_close'    => '</li>',
            'cur_tag_open'      => '<li class="active"><a href="">',
            'cur_tag_close'     => '</li></a>',
            'num_tag_open'      => '<li>',
            'num_tag_close'     => '</li>',
            'prev_tag_open'     => '<li>',
            'prev_tag_close'    => '</li>',
            'next_tag_open'     => '<li>',
            'next_tag_close'    => '</li>',
            );  

        return $config;
    }

    public function password_check($str)
    {
        $password   = isset($this->user_info[0]['password']) ? $this->user_info[0]['password'] : 0;
        
        if (md5($str) != $password) {
            $this->form_validation->set_message('password_check' , '密码错误');
            return FALSE;
        } else {
            return TRUE;
        }
    }
}
