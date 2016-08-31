<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
* 后台标签管理
*/
class Tag extends CI_Controller
{
    
    function __construct()
    {
        parent::__construct()    ;
    }

    //标签显示
    public function index()
    {
        $config     = $this->getPaginationConfig();
        $this->pagination->initialize($config);

        $row    = $this->uri->segment(4);
        $row    = isset($row) ? $row : 0;

        $data['data']       = $this->tagmodel->getTagDuring($row, $config['per_page']);
        $data['cur_title']  = array('', '', '', 'active', '');

        $this->load->view('admin/header');
        $this->load->view('admin/menu', $data);
        $this->load->view('admin/tag_index', $data);
        $this->load->view('admin/footer');
    }

    //标签添加
    public function add()
    {
        $color_array    = array('priamry', 'success', 'info', 'warning', 'danger');
        $tagname        = trim($this->input->post('tag_name', true));

        if (!empty($tagname)) {
            $data['data']   = array(
                'tag_name'          => $tagname,
                'tag_button_type'   => $color_array[array_rand($color_array)],
                );
            $this->db->insert('tag', $data['data']);
        }
        redirect('/admin/tag/index');
    }

    //标签编辑
    public function edit($tag_id)
    {
        $data['tag_info']           = $this->tagmodel->getTagByTagid($tag_id);
        $data['articles_by_tag']    = $this->articlesmodel->getArticlesTag($tag_id);
        $data['all_category']       = $this->categorymodel->getAllCategory();
        
        $data['cur_title']          = array('', '', '', 'active', '');
      
        $this->load->view('admin/header');
        $this->load->view('admin/menu', $data);
        $this->load->view('admin/tag_edit', $data);
        $this->load->view('admin/footer');
    }

    //检查标题下是否有文章
    public function check($tag_id)
    {
        $check_arr  = $this->articlesmodel->getArticlesTag($tag_id);

        if (empty($check_arr)) {
            redirect("/admin/tag/delete/$tag_id");
        } else {
            $config     = $this->getPaginationConfig();
            $this->pagination->initialize($config);

            $row    = $this->uri->segment(5);
            $row    = isset($row) ? $row : 0;

            $data['data']       = $this->tagmodel->getTagDuring($row, $config['per_page']);
            $data['cur_title'] = array('', '', '', 'active', '');

            $this->load->view('admin/header');
            $this->load->view('admin/menu', $data);
            $this->load->view('admin/tag_index', $data);
            $this->load->view('admin/tag_check');
            $this->load->view('admin/footer');
        }
    }

    //标签删除
    public function delete($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('tag');
        redirect('/admin/tag/index');
    }

    //标签链接删除
    public function delete_link($article_id, $tag_id)
    {
        $this->db->where('article_id', $article_id);
        $this->db->where('tag_id', $tag_id);
        $this->db->delete('article_tag');

        $sql        = "select tag_name form tag where id='{$tag_id}'";
        $tag_name   = $this->db->query($sql)->result_array();

        $sql        = "select tag from articles where id='{$article_id}'";
        $data       = $this->db->query($sql)->result_array();
        $data       = explode(',', $data[0]['tag']);

        foreach ($data as $key => $value) {
            if ($value != $tag_name[0]['tag_name']) {
                $new_tag[]  = $value;
            }
        }

        $new_tag    = implode(',', $new_tag);
        $arr        = array('tag' => $new->tag, );

        $check_arr  = $this->articlesmodel->getArticlesTag($tag_id);
        if (empty($check_arr)) {
            $this->db->where('id', $tag_id);
            $this->db->delete('tag');
        }

        redirect('/admin/tag/index');
    }

    //获取分页配置
    public function getPaginationConfig()
    {
        $config     = array(
            'base_url'          => site_url('admin/Tag/index'),
            'total_rows'        => $this->db->count_all('tag'),
            'per_page'          => '5',
            'num_links'         => 3,
            'last_link'         => '末页',
            'first_link'        => '首页',
            'prev_link'         => false,
            'next_link'         => false,
            'first_tag_open'    => '<li>',
            'first_tag_close'   => '</li><li><a>...</a></li>',
            'last_tag_open'     => '<li><a>...</a></li><li>',
            'last_tag_close'    => '</li>',
            'cur_tag_open'      => '<li class="active"><a href="">',
            'cur_tag_close'     => '</li></a>',
            'num_tag_open'      => '<li>',
            'num_tag_close'     => '</li>',
            'prev_tag_open'     => '<li>',
            'prev_tag_close'    => '</li>',
            'next_tag_open'     => '<li>',
            'next_tag_close'    => '</li>',
            );
        return $config;
    }
}