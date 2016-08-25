<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

/************************************************************************
	* File Name: Articlesmodel.php
	* Author: rayle
	* Mail: rayle0801@gmail.com
	* Created Time: 2016年08月25日 星期三 11时43分22秒
	* Updated Time: 2016年08月25日 星期三 11时43分22秒
    ************************************************************************/

class Articlesmodel extends CI_Model {
	/**
	 * 构造方法
	 */
	public function __construct(){
		parent::__construct();
	}

	/**
	 * 获取所有文章列表
	 */
	public function getAllArticles(){
		$sql	= "select * from articles";
		$query	= $this->db->query($sql);
		foreach($query->result_array() as $row){
			$data[] = $row;
		}
		return $data;
	}

	/**
	 * 获取文章
	 */
	public function getArticle($id){
		$sql	= "select * from articles where id={$id}";
		$data 	= $this->db->query($sql)->result_array();
		return $data;
	}

	public function getFeeds($limit = 5){
		$sql	= "select * from articles order by published_at DESC limit {$limit}";
		$data 	= $this->db->query($sql)->result_array();
		return $data;
	}

	public function getArticlesDuring($offset,$row){
		$sql	= "select * from articles order by published_at DESC limit {$offset},{$row}";
		$data 	= $this->db->query($sql)->result_array();
		return $data;
	}

	public function getArticlesTag($tag_id){
		$sql	= "select c.id, c.title, c.published_at, c.category, c.tag, a.id as tag_id, a.tag_name, a.tag_button_type from tag as a join article_tag as b on b.tag_id=a.id join articles as c on c.id=b.article_id where a.id='{$tag_id}'";
		$data 	= $this->db->query($sql)->result_array();
		return $data;
	}

	public function getTagsType(){
		$sql="select * from tag";
		$data['button_type'] = $this->db->query($sql)->result_array();
		return $data;
	}
}