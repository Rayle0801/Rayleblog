<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

/************************************************************************
    * File Name: Category.php
    * Author: rayle
    * Mail: rayle0801@gmail.com
    * Created Time: 2016年08月25日 星期三 11时43分22秒
    * Updated Time: 2016年08月25日 星期三 11时43分22秒
    ************************************************************************/

class Category extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function show($id)
    {
        $data['articles']           = $this->categorymodel->getAllArticles($id);
        $data['cur_category']       = $this->categorymodel->getCategory($id);
        $data['categorys']          = $this->categorymodel->getAllCategory();

        $data['title']              = array('' , 'active', '', '');

        $data['siteinfos']          = $this->siteinfomodel->getSiteInfo();

        $this->load->view('header', $data);
        $this->load->view('menu', $data);
        $this->load->view('category_show', $data);
        $this->load->view('footer');
    }
}