<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
* 后台分类管理
*/
class Category extends CI_Controller
{
    
    function __construct()
    {
        parent::__construct();
    }

    //分类显示
    public function index()
    {
        $config     = $this->getPaginationConfig();
        $this->pagination->initialize($config);

        $row    = $this->uri->segment(4);
        $row    = isset($row) ? $row : 0;

        $data['data']   = $this->categorymodel->getCategoryDuring($row, $config['per_page']);

        $data['cur_title']  = array('', '', 'active', '', '');

        $this->load->view('admin/header');
        $this->load->view('admin/menu', $data);
        $this->load->view('admin/category_index', $data);
        $this->load->view('admin/footer');
    }

    //分类添加
    public function add()
    {   
        $category   = $this->input->post('category', true);
        if ($category != '') {
            $data['data']   = array('category' => $category);
            $this->db->insert('category', $data['data']);
        }
        redirect('/admin/category/index');
    }

    //分类删除
    public function delete($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('category');

        redirect('/admin/category/index');
    }

    //获取分类配置
    public function getPaginationConfig()
    {
        $config     = array(
            'base_url'          => site_url('admin/Category/index'),
            'total_rows'        => $this->db->count_all('category'),
            'per_page'          => '5',
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
}