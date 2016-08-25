<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

/************************************************************************
	* File Name: Aboutmodel.php
	* Author: rayle
	* Mail: rayle0801@gmail.com
	* Created Time: 2016年08月25日 星期三 11时43分22秒
	* Updated Time: 2016年08月25日 星期三 11时43分22秒
    ************************************************************************/

class Aboutmodel extends CI_Model {

	public function __construct(){
		parent::__construct();
	}

	public function getAboutInfo($id=1){
		$sql	= "select * from about where id = {$id}";
		$data 	= $this->db->query($sql)->result_array();
		return $data;
	}
	public function updateAboutInfo($id=1){
		$data = array(
			    'title'		=> $this->security->xss_clean($this->input->post('title')),
			    'tag'		=> $this->input->post('tag',TRUE),
                'content'	=> $this->security->xss_clean($this->input->post('content')),
		);
		$this->db->where('id', $id);
		$this->db->update('about', $data);
	}
}