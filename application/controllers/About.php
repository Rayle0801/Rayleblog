<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

/************************************************************************
    * File Name: About.php
    * Author: rayle
    * Mail: rayle0801@gmail.com
    * Created Time: 2016年08月25日 星期三 11时43分22秒
    * Updated Time: 2016年08月25日 星期三 11时43分22秒
    ************************************************************************/


class About extends CI_Controller
{
    
    function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $data['categoerys']     = $this->categorymodel->getAllCategory();

        $data['abouts']         = $this->aboutmodel->getAboutInfo();

        $data['siteinfos']      = $this->siteinfomodel->getSiteInfo();

        $data['title']          = array('', '', '', 'active');

        $this->load->view('header', $data);
        $this->load->view('menu', $data);
        $this->load->view('about', $data);
        $this->load->view('footer');
    }
}