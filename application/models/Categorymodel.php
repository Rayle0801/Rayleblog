<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

/************************************************************************
	* File Name: Categorymodel.php
	* Author: rayle
	* Mail: rayle0801@gmail.com
	* Created Time: 2016年08月25日 星期三 11时43分22秒
	* Updated Time: 2016年08月25日 星期三 11时43分22秒
    ************************************************************************/

class Categorymodel extends CI_Model {

	public function __construct(){
		parent::__construct();
	}

	public function getAllCategory(){
		$sql		= "select * from category order by category_order";
		$data_tmp 	= $this->db->query($sql)->result_array();
		foreach ($data_tmp as $value) {
			$category_id 							= $value['id'];
			$data["$category_id"]['id'] 			= $value['id'];
			$data["$category_id"]['category'] 		= $value['category'];
			$data["$category_id"]['category_order'] = $value['category_order'];
		}
		return $data;
	}


	public function getAllArticles($category_id){
		$sql="select * from articles where category={$category_id}";
		$data = $this->db->query($sql)->result_array();
		return $data;
	}

	public function getCategory($category_id){
		$sql	= "select * from category where id={$category_id}";
		$data 	= $this->db->query($sql)->result_array();
		return $data;
	}

	public function getCategoryDuring($offset,$row){
		$sql	= "select * from category order by id DESC limit {$offset},{$row}";
		$data 	= $this->db->query($sql)->result_array();
		return $data;
	}
}