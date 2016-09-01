<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
* 后台文章管理
*/
class Articles extends CI_Controller
{
    
    function __construct()
    {
        parent::__construct();
    }

    //后台管理文章显示
    public function index()
    {
        $config     = $this->getPaginationConfig();
        $this->pagination->initialize($config);

        $row    = $this->uri->segment(4);
        $row    = isset($row) ? $row : 0;

        $data['data']   = $this->articlesmodel->getArticlesDuring($row, $config['per_page']);

        $data['cur_title']  = array('', 'active', '', '', '');
        $this->load->view('admin/header');
        $this->load->view('admin/menu', $data);
        $this->load->view('admin/articles_index', $data);;
        $this->load->view('admin/footer');
    }

    //后台文章编辑
    public function edit($id = 0)
    {
        $data['cur_title']  = array('', 'active', '', '', '');

        $data['all_category']   = $this->categorymodel->getAllCategory();

        if ($id != 0) {
            $data['article']    = $this->articlesmodel->getArticle($id);
        } else {
            $id = 0;
            $data['article']    = $this->articlesmodel->getArticle($id);
        }

        $this->load->view('admin/header');
        $this->load->view('admin/menu', $data);
        $this->load->view('admin/articles_edit', $data);;
        $this->load->view('admin/footer');
    }

    //后台文章更新
    public function update()
    {
        $data['cur_title']  = array('', 'active', '', '', '');
        $data['data']       = array(
            'id'            => $this->input->post('id', true),
            'title'         => $this->input->post('title', true),
            'keyword'       => $this->input->post('keyword', true),
            'description'   => $this->input->post('description', true),
            'content'       => $this->input->post('content', true),
            'published_at'  => $this->input->post('published_at', true),
            'category'      => $this->input->post('category', true),
            'tag'           => $this->input->post('tag', true),
            'pv'            => $this->input->post('pv', true),
            );

        //获取表中该文章相关标签
        $tmp    = $this->tagmodel->getTagById($data['data']['id']);

        $article_tag    = array();

        foreach ($tmp as $key => $value) {
            array_push($article_tag, $value['tag_name']);
        }

        //输入标签内容
        $tag_arr    = explode(',', $data['data']['tag']);

        //比较输入内容与原文标签的不同
        $diff        = array_merge(array_diff($article_tag, $tag_arr), array_diff($tag_arr, $article_tag));

        //获取所有标签信息
        $all_tags   = $this->tagmodel->getAllTags();
        foreach ($all_tags as $key => $value) {
            $all_tags_name[]  = $value['tag_name'];
        }

        //判断用户输入的标签是否存在，如果不存在，创建标签， 并随机选择一个颜色
        $random_color   = array('primary', 'success', 'info', 'warning', 'danger');
        foreach ($tag_arr as $key => $value) {
            if (!in_array($value, $all_tags_name)) {
                $color  = array_rand($random_color);
                $sql    = "insert into `tag`(`tag_name`, `tag_button_type`) values ('$value', "."'$random_color[$color]'".")";
                $this->db->query($sql);
            }
        }

        if ($data['data']['id'] != 0) {
            //将标签录入articles_tag表

            //判断标签与文章的关系，进行删除或者添加关系的操作
            foreach ($diff as $key => $value) {
                if (in_array($value, $article_tag)) {
                    $sql1   = "select a.article_id, a.tag_id from article_tag as a join articles as b on a.article_id=b.id join tag as c c.id=a.tag_id where b.id = '{$data['data']['id']}' and c.tag_name='{$value}'";
                    $delete_link    = $this->db->query($sql1)->result_array();

                    $sql2   = "delete from article_tag where article_id='{$delete_link['0']['article_id']}' and tag_id='{delete_link['0']['tag_id']}'";
                    $this->db->query($sql2);
                }
                if (!in_array($value, $article_tag)) {
                    $sql3   = "select b.id as article_id, c.id as tag_id from articles as b join tag as c where b.id='{$data['data']['id']}' and c.tag_name='{$value}'";
                    $insert_link    = $this->db->query($sql3)->result_array();

                    $sql4   = "insert into article_tag(article_id, tag_id) values ({$insert_link['0']['article_id']},{$insert_link['0']['tag_id']})";
                    $this->db->query($sql4);
                }
            }
            $this->db->where('id', $data['data']['id']);
            $this->db->replace('articles', $data['data']);
        } else {
            $this->db->insert('articles', $data['data']);

            foreach ($diff as $key => $value) {
                $sql5   = "select b.id as article_id,c.id as tag_id from articles as b join tag as c where b.title='{$data['data']['title']}' and c.tag_name='{$value}'";
                $insert_link    = $this->db->query($sql5)->result_array();

                $sql6   = "insert into article_tag(article_id, tag_id) values ({$insert_link['0']['article_id']}, {$insert_link['0']['tag_id']})";
                $this->db->query($sql6);
            }
        }

        redirect('/admin/articles/index');
    }

    //后台删除文章
    public function delete($id)
    {
        $data['cur_title']  = array('', 'active', '', '', '');

        $this->db->where('id', $id);
        $this->db->delete('articles');

        redirect('/admin/articles/index');
    }

    //获取分页配置
    private function getPaginationConfig()
    {   
        $config     = array(
            'base_url'          => site_url('admin/Articles/index'),
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