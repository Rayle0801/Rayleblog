<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

/************************************************************************
    * File Name: About.php
    * Author: rayle
    * Mail: rayle0801@gmail.com
    * Created Time: 2016年08月25日 星期三 11时43分22秒
    * Updated Time: 2016年08月25日 星期三 11时43分22秒
    ************************************************************************/

class Search extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function show()
    {
        $pattern    = $_POST['pattern'];

        $articles   = $this->articlesmodel->getAllArticles();

        foreach ($articles as $key => $value) {
            preg_match_all("/{$pattern}/i", $value['title'], $title_matches);
            preg_match_all("/{$pattern}/i", $value['content'], $content_matches);

            if (!empty($title_matches[0]) || !empty($content_matches)) {
                $temp_arr['id']                 = $value['id'];
                $temp_arr['title']              = $value['title'];
                $temp_arr['published_at']       = $value['published_at'];
                $temp_arr['score']              = count($content_matches[0]) + count($title_matches[0])* 5;
                $show_arr[$temp_arr['score']]   = $temp_arr;
            }
        }

        //对搜索结果根据打分排序
        @krsort($show_arr);
        $data['pattern']    = $pattern;
        $data['show_arr']   = $show_arr;

        $data['title']      = array('active', '', '', '');

        $data['categorys']  = $this->categorymodel->getAllCategory();

        $data['siteinfos']  = $this->siteinfomodel->getSiteInfo();

        if (!empty($data['show_arr'])) {
            $this->load->view('header', $data);
            $this->load->view('menu', $data);
            $this->load->view('search_show', $data);
            $this->load->view('footer'); 
        } else {
            $this->load->view('header', $data);
            $this->load->view('menu', $data);
            $this->load->view('search_failed', $data);
            $this->load->view('footer');
        }
    }
}