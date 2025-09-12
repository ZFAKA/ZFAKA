<?php

/*
 * 功能：前台文章展示
 * Author:资料空白
 * Date:20250831
 */

class ArticleController extends BasicController
{
    private $m_article;
    protected $config = array();
    
    public function init()
    {
        parent::init();
        $this->m_article = $this->load('article');
        
        // 获取配置信息
        $m_config = $this->load('config');
        $this->config = $m_config->getConfig();

        $uinfo = $this->getSession('uinfo');
        if(is_array($uinfo) AND !empty($uinfo) AND $uinfo['expiretime']>time()){
            $groupName = $this->load('user_group')->getConfig();
            $uinfo['groupName'] = $groupName[$uinfo['groupid']];
            $uinfo['expiretime'] = time() + 15*60;
            $this->setSession('uinfo',$uinfo);
            $this->login = true;
            $this->uinfo = $uinfo;
        } else {
            $this->login = false;
            $this->unsetSession('uinfo');
        }
        
        $config = array(
            'backgroundimage' => $this->config['backgroundimage'] ?? '',
            'shortcuticon' => $this->config['shortcuticon'] ?? '',
            'headermenucolor' => $this->config['headermenucolor'] ?? '',
            'logo' => $this->config['logo'] ?? '',
            'loginswitch' => $this->config['loginswitch'] ?? '',
            'registerswitch' => $this->config['registerswitch'] ?? '',
            'tongji' => $this->config['tongji'] ?? '',
            'webname' => $this->config['webname'] ?? ''
        );

        $data = array(
            'config' => $config,
            'login' => $this->login,
            'uinfo' => $this->uinfo
        );
        $this->getView()->assign($data);
    }

    // 文章详情页
    public function indexAction()
    {
        $id = $this->getParam('id');
        
        if (!$id || !is_numeric($id)) {
            $this->show_404();
            return FALSE;
        }

        $article = $this->m_article->getPublishedById($id);
        
        if (!$article) {
            $this->show_404();
            return FALSE;
        }

        // 增加阅读量
        $this->m_article->incrementViews($id);
        
        // 设置SEO信息
        $seo = array(
            'title' => $article['title'] . ' - ' . $this->config['webname'],
            'keywords' => $article['keywords'] ?: $this->config['keywords'],
            'description' => $article['description'] ?: ($article['excerpt'] ?: $this->config['description'])
        );

        $data = array(
            'article' => $article,
            'seo' => $seo
        );

        $this->getView()->assign($data);
    }

    // 文章列表页
    public function listAction()
    {
        $page = $this->get('page', false);
        $page = is_numeric($page) && $page > 0 ? $page : 1;
        $limit = 100;
        
        $offset = ($page - 1) * $limit;
        $articles = $this->m_article->getPublishedArticles("{$offset},{$limit}");
        
        $total = $this->m_article->Where(array('status' => 1))->Total();
        $totalPages = ceil($total / $limit);

        $seo = array(
            'title' => '文章列表 - ' . $this->config['webname'],
            'keywords' => $this->config['keywords'],
            'description' => $this->config['description']
        );

        $data = array(
            'articles' => $articles,
            'page' => $page,
            'totalPages' => $totalPages,
            'total' => $total,
            'seo' => $seo
        );

        $this->getView()->assign($data);
    }

    public function slugAction()
    {
        $slug = $this->getParam('slug'); // 自定义的getParam函数默认带过滤
        
        if (!$slug) {
            $this->show_404();
            return FALSE;
        }
    
        $article = $this->m_article->getPublishedBySlug($slug);
        
        if (!$article) {
            $this->show_404();
            return FALSE;
        }
    
        // Redirect to the canonical ID-based URL
        $redirectUrl = '/article/' . $article['id'] . '.html';
        header('HTTP/1.1 301 Moved Permanently');
        header('Location: ' . $redirectUrl);
        exit();
    }

    private function show_404()
    {
        header('HTTP/1.1 404 Not Found');
        $this->getView()->display('404');
    }
}