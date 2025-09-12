<?php

/*
 * 功能：后台中心－文章管理
 * Author:资料空白
 * Date:20250831
 */

class ArticleController extends AdminBasicController
{
    private $m_article;
    
    public function init()
    {
        parent::init();
        $this->m_article = $this->load('article');
    }

    public function indexAction()
    {
        if ($this->AdminUser == FALSE && empty($this->AdminUser)) {
            $this->redirect('/' . ADMIN_DIR . "/login");
            return FALSE;
        }

        $data = array();
        $this->getView()->assign($data);
    }

    // ajax获取文章列表
    public function ajaxAction()
    {
        if ($this->AdminUser == FALSE && empty($this->AdminUser)) {
            $data = array('code' => 1000, 'msg' => '请登录');
            Helper::response($data);
        }

        $where = array();
        
        $page = $this->get('page');
        $page = is_numeric($page) ? $page : 1;
        
        $limit = $this->get('limit');
        $limit = is_numeric($limit) ? $limit : 10;
        
        $total = $this->m_article->Where($where)->Total();
        
        if ($total > 0) {
            if ($page > 0 && $page < (ceil($total / $limit) + 1)) {
                $pagenum = ($page - 1) * $limit;
            } else {
                $pagenum = 0;
            }
            
            $limits = "{$pagenum},{$limit}";
            $field = array('id', 'title', 'status', 'views', 'sort', 'created_at', 'updated_at');
            $items = $this->m_article->Field($field)->Where($where)->Limit($limits)->Order(array('sort' => 'DESC', 'id' => 'DESC'))->Select();
            
            if (empty($items)) {
                $data = array('code' => 1002, 'count' => 0, 'data' => array(), 'msg' => '无数据');
            } else {
                // 格式化时间
                foreach ($items as &$item) {
                    $item['created_at_formatted'] = date('Y-m-d H:i:s', $item['created_at']);
                    $item['updated_at_formatted'] = date('Y-m-d H:i:s', $item['updated_at']);
                }
                $data = array('code' => 0, 'count' => $total, 'data' => $items, 'msg' => '有数据');
            }
        } else {
            $data = array('code' => 1001, 'count' => 0, 'data' => array(), 'msg' => '无数据');
        }
        Helper::response($data);
    }

    // 添加文章
    public function addAction()
    {
        if ($this->AdminUser == FALSE && empty($this->AdminUser)) {
            $this->redirect('/' . ADMIN_DIR . "/login");
            return FALSE;
        }
        
        $data = array();
        $this->getView()->assign($data);
    }

    // 编辑文章
    public function editAction()
    {
        if ($this->AdminUser == FALSE && empty($this->AdminUser)) {
            $this->redirect('/' . ADMIN_DIR . "/login");
            return FALSE;
        }
        
        $id = $this->get('id');
        if ($id && $id > 0) {
            $data = array();
            $item = $this->m_article->SelectByID('', $id);
            if (!$item) {
                $this->redirect('/' . ADMIN_DIR . "/article");
                return FALSE;
            }
            $data['item'] = $item;
            $this->getView()->assign($data);
        } else {
            $this->redirect('/' . ADMIN_DIR . "/article");
            return FALSE;
        }
    }

    // 保存文章（添加或编辑）
    public function saveAction()
    {
        if ($this->AdminUser == FALSE && empty($this->AdminUser)) {
            $data = array('code' => 1000, 'msg' => '请登录');
            Helper::response($data);
        }

        $method = $this->getPost('method', false);
        $id = $this->getPost('id', false);
        $title = $this->getPost('title', false);
        $content = $this->getPost('content', false);
        $excerpt = $this->getPost('excerpt', false);
        $slug = $this->getPost('slug', false);
        $status = $this->getPost('status', false);
        $keywords = $this->getPost('keywords', false);
        $description = $this->getPost('description', false);
        $sort = $this->getPost('sort', false);

        if (!$title || !$content) {
            $data = array('code' => 1002, 'msg' => '标题和内容不能为空');
            Helper::response($data);
        }

        // 处理slug
        if (empty($slug)) {
            $slug = $id ? $id : '';
        }

        // 检查slug唯一性
        if ($slug && $this->m_article->checkSlugExists($slug, $id)) {
            $data = array('code' => 1003, 'msg' => 'URL别名已存在');
            Helper::response($data);
        }

        $articleData = array(
            'title' => htmlspecialchars($title),
            'content' => $content,
            'excerpt' => htmlspecialchars($excerpt),
            'slug' => $slug,
            'status' => $status ? 1 : 0,
            'keywords' => htmlspecialchars($keywords),
            'description' => htmlspecialchars($description),
            'sort' => is_numeric($sort) ? $sort : 0,
            'updated_at' => time()
        );

        if ($method == 'add') {
            $articleData['created_at'] = time();
            $result = $this->m_article->Insert($articleData);
            
            if ($result) {
                // 如果没有设置slug，使用新插入的ID作为slug
                if (empty($slug)) {
                    $this->m_article->UpdateByID(array('slug' => $result), $result);
                }
                $data = array('code' => 1, 'msg' => '添加成功');
            } else {
                $data = array('code' => 1004, 'msg' => '添加失败');
            }
        } elseif ($method == 'edit' && $id > 0) {
            $result = $this->m_article->UpdateByID($articleData, $id);
            
            if ($result) {
                $data = array('code' => 1, 'msg' => '更新成功');
            } else {
                $data = array('code' => 1005, 'msg' => '更新失败');
            }
        } else {
            $data = array('code' => 1006, 'msg' => '未知方法');
        }

        Helper::response($data);
    }

    // 删除文章
    public function deleteAction()
    {
        if ($this->AdminUser == FALSE && empty($this->AdminUser)) {
            $data = array('code' => 1000, 'msg' => '请登录');
            Helper::response($data);
        }

        $id = $this->get('id');
        if ($id && $id > 0) {
            $result = $this->m_article->DeleteByID($id);
            if ($result) {
                $data = array('code' => 1, 'msg' => '删除成功');
            } else {
                $data = array('code' => 1001, 'msg' => '删除失败');
            }
        } else {
            $data = array('code' => 1002, 'msg' => '参数错误');
        }

        Helper::response($data);
    }
}