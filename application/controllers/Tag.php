<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

/************************************************************************
    * File Name: Tag.php
    * Author: rayle
    * Mail: rayle0801@gmail.com
    * Created Time: 2016年08月25日 星期三 11时43分22秒
    * Updated Time: 2016年08月25日 星期三 11时43分22秒
    ************************************************************************/


class Tag extends CI_Controller
{
    
    function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $tag_info   = $this->tagmodel->getTagInfo();
        foreach ($tag_info as $key => $value) {
            if ($value['article_num'] <= 1) {
                $button_size    = 'btn-xs';
            } elseif ($value['article_num'] <= 10) {
                $button_size    = 'btn-sm';
            } else {
                $button_size    = 'btn-lg';
            }

            $data['tags'][]     = array(
                'tag_id'            => $value['tag_id'],
                'tag_name'          => $value['tag_name'],
                'tag_size'          => $button_size,
                'tag_button_type'   => $value['tag_button_type'],
                );
        }

        $data['categorys']          = $this->categorymodel->getAllCategory();

        $data['siteinfos']          = $this->siteinfomodel->getSiteInfo();

        $data['title']              = array('', '', 'active', '');

        $this->load->view('header', $data);
        $this->load->view('menu', $data);
        $this->load->view('tag_index', $data);
        $this->load->view('footer');
    }


    public function show($tag_id)
    {
        $data['articles']       = $this->articlesmodel->getArticlesTag($tag_id);

        $data['tag_name']       = $this->tagmodel->getTagByTagid($tag_id);

        $data['categorys']      = $this->categorymodel->getAllCategory();

        $data['title']          = array('', '', 'active', '');

        $data['siteinfos']      = $this->siteinfomodel->getSiteInfo();

        $this->load->view('header', $data);
        $this->load->view('menu', $data);
        $this->load->view('tag_show', $data);
        $this->load->view('footer');
    }
}