<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

/************************************************************************
    * File Name: Articles.php
    * Author: rayle
    * Mail: rayle0801@gmail.com
    * Created Time: 2016年08月25日 星期三 11时43分22秒
    * Updated Time: 2016年08月25日 星期三 11时43分22秒
    ************************************************************************/

Class Articles extends CI_Controller
{
    /**
     * 构造函数
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {

        //获取分页配置
        $config     = $this->pageconfig();
        $this->pagination->initialize($config);

        $row                = $this->uri->segment(3);
        $row                = isset($row) ? $row : 0;

        $data['articles']   = $this->articlesmodel->getArticlesDuring($row, $config['per_page']);
        $data['categorys']  = $this->categorymodel->getAllCategory();
        $data['siteinfos']  = $this->siteinfomodel->getSiteinfo();
        $date['titile']     = array('active', '', '', '');


        var_dump($config);
        exit();
        $this->load->view('header', $data);
        $this->load->view('menu', $data);
        $this->load->view('articles_index', $data);
        $this->load->view('footer');

    }

    /**
     * 分页配置函数
     */
    public function pageconfig()
    {
        $config     = array(
            'base_url'          => site_url('Articles/index'),
            'total_rows'        => $this->db->count_all('articles'),
            'per_page'          => '5',
            'num_links'         => 3,
            'last_link'         => '末页',
            'first_link'        => '首页',
            'prev_link'         => false,
            'next_link'         => false,
            'first_tag_open'    => '<li>',
            'first_tag_close'   => '</li><li><a>...</a></li>',
            'first_tag_open'    => '<li><a>...</a></li><li>',
            'first_tag_close'   => '</li>',
            'first_tag_open'    => '<li class="active"><a href="">',
            'first_tag_close'   => '</li></a>',
            'first_tag_open'    => '<li>',
            'first_tag_close'   => '</li>',
            'first_tag_open'    => '<li>',
            'first_tag_close'   => '</li>',
            'first_tag_open'    => '<li>',
            'first_tag_close'   => '</li>',
            );
        return $config;
    }

}