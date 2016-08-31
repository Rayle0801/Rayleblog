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
        $data['title']      = array('active', '', '', '');

        $this->load->view('header', $data);
        $this->load->view('menu', $data);
        $this->load->view('articles_index', $data);
        $this->load->view('footer');

    }


    public function article($id)
    {
        //统计文章访问次数
        $user_ip_name       = 'user_ip_'.$id;
        if (empty($_SESSION[$user_ip_name])) {
            $this->db->set('pv', 'pv + 1', FALSE);
            $this->db->where('id', $id);
            $this->db->update('articles');
            $user_ip    = $_SERVER["REMOTE_ADDR"];
            $this->session->set_userdata($user_ip);
        }

        $data_tmp['articles']  = $this->articlesmodel->getArticle($id);
        $tag_info               = $this->articlesmodel->getTagsType();
        foreach ($data_tmp as $key => $value) {
            foreach ($value as $value1) {
                $data['articles']['0']['id']             = $value1['id'];
                $data['articles']['0']['title']          = $value1['title'];
                $data['articles']['0']['keyword']        = $value1['keyword'];
                $data['articles']['0']['description']    = $value1['description'];

                $data['articles']['0']['content']        = $value1['content'];
                $data['articles']['0']['category']       = $value1['category'];
                $data['articles']['0']['pv']             = $value1['pv'];

                if ($value1['tag'] != '') {
                    $tag_str    = explode(',', $value1['tag']);
                    $tag_str    = implode("','", $tag_str);
                    $tag_str    = "('".$tag_str."')";

                    $sql        = "select id, tag_name from tag where tag_name in {$tag_str}";
                    $tag_arr    = $this->db->query($sql)->result_array();
                    foreach ($tag_arr as $key => $value) {
                        $data['articles']['0']['tag'][$value['id']]  = $value['tag_name'];
                    }
                }

                $data['articles']['0']['published_at']   = $value1['published_at'];
            }
        }

        foreach ($tag_info['button_type'] as $value) {
            $tag_name                   = $value['tag_name'];
            $button_type["$tag_name"]   = $value['tag_button_type'];
        }
        $data['button_type']         = $button_type;
        $data['categorys']           = $this->categorymodel->getAllCategory();

        $data['title']               = array('active', '', '', '');

        $this->load->view('articles_header', $data);
        $this->load->view('menu', $data);
        $this->load->view('articles_article', $data);
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
            'last_tag_open'    => '<li><a>...</a></li><li>',
            'last_tag_close'   => '</li>',
            'cur_tag_open'    => '<li class="active"><a href="">',
            'cur_tag_close'   => '</li></a>',
            'num_tag_open'    => '<li>',
            'num_tag_close'   => '</li>',
            'prev_tag_open'    => '<li>',
            'prev_tag_close'   => '</li>',
            'next_tag_open'    => '<li>',
            'next_tag_close'   => '</li>',
            );
        return $config;
    }

}