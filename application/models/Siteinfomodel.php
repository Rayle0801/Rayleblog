<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

/************************************************************************
	* File Name: Siteinfomodel.php
	* Author: rayle
	* Mail: rayle0801@gmail.com
	* Created Time: 2016年08月25日 星期三 11时43分22秒
	* Updated Time: 2016年08月25日 星期三 11时43分22秒
    ************************************************************************/

class Siteinfomodel extends CI_Model {

	public function __construct(){
		parent::__construct();
	}

	public function getSiteInfo($id=1){
		$sql	= "select * from siteinfo  where id = {$id}";
		$data 	= $this->db->query($sql)->result_array();
		return $data;
	}

	public function updateSiteInfo($id=1){
		$data = array(
			    'url'			=> $this->security->xss_clean($this->input->post('url')),
			    'email'			=> $this->input->post('email',TRUE),
                'title'			=> $this->security->xss_clean($this->input->post('title')),
			    'keywords'		=> $this->security->xss_clean($this->input->post('keywords')),
				'description'	=> $this->input->post('description',TRUE),
				'statistic'		=> $this->input->post('statistic',TRUE),
		);
		$this->db->where('id', $id);
		$this->db->update('siteinfo', $data);
	}
	
	public function updateadmin($data){
		$this->db->where('id', $data['id']);
		$this->db->update('user', $data);
	}
}