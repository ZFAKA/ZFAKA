<?php
class Bootstrap extends \Yaf\Bootstrap_Abstract
{
    public function _initCore()
    {
        ini_set('yaf.library', LIB_PATH);
        \Yaf\Loader::import(CORE_PATH.'/Helper.php');
        \Yaf\Loader::import(CORE_PATH.'/Basic.php');
        \Yaf\Loader::import(CORE_PATH.'/Model.php');
        \Yaf\Loader::import(CORE_PATH.'/BasicProduct.php');
        \Yaf\Loader::import(CORE_PATH.'/BasicMember.php');
        \Yaf\Loader::import(CORE_PATH.'/BasicAdmin.php');
        \Yaf\Loader::import(FUNC_PATH.'/F_Basic.php');
        \Yaf\Loader::import(FUNC_PATH.'/F_Network.php');
        \Yaf\Loader::import(FUNC_PATH.'/F_String.php');
        \Yaf\Loader::import(FUNC_PATH.'/F_Validate.php');
    }
   
    public function _initLoader()
    {
        \Yaf\Loader::import(APP_PATH . '/vendor/autoload.php');
    }
    
    public function _initRoute() {
        $router = Yaf\Dispatcher::getInstance()->getRouter();
        
        // 文章路由 - /article/1.html 格式 (数字ID)
        $article_html = new Yaf\Route\Regex(
            '#^/article/([0-9]+)\.html$#',
            array('controller' => 'Article', 'action' => 'index'),
            array(1 => 'id')
        );
        $router->addRoute('article_html', $article_html);
    
        // 文章路由 - /article/1 格式 (数字ID)
        $article_id = new Yaf\Route\Regex(
            '#^/article/([0-9]+)/?$#',
            array('controller' => 'Article', 'action' => 'index'),
            array(1 => 'id')
        );
        $router->addRoute('article_id', $article_id);
    
        // 文章slug路由
        $article_slug_html = new Yaf\Route\Regex(
            '#^/article/([a-zA-Z][a-zA-Z0-9\-_]*)\.html$#',
            array('controller' => 'Article', 'action' => 'slug'),
            array(1 => 'slug')
        );
        $router->addRoute('article_slug_html', $article_slug_html);

        $article_slug = new Yaf\Route\Regex(
            '#^/article/([a-zA-Z][a-zA-Z0-9\-_]*)/?$#',
            array('controller' => 'Article', 'action' => 'slug'),
            array(1 => 'slug')
        );
        $router->addRoute('article_slug', $article_slug);

        // 文章列表路由
        $article_list = new Yaf\Route\Regex(
            '#^/articles/?$#',
            array('controller' => 'Article', 'action' => 'list'),
            array()
        );
        $router->addRoute('article_list', $article_list);
        
        // 原有的产品路由
        $products_detail = new Yaf\Route\Regex(
            '#product/([0-9]+).html#',
            array('module' => 'product', 'controller' => 'detail', 'action' => 'index'),
            array(1 => 'pid')
        );
        $router->addRoute('products_detail', $products_detail);
        
        $products_group = new Yaf\Route\Regex(
            '#group/([0-9]+).html#',
            array('module' => 'product', 'controller' => 'group', 'action' => 'index'),
            array(1 => 'tid')
        );
        $router->addRoute('products_group', $products_group);
        
        $order_query = new Yaf\Route\Regex(
            '#query/([A-Za-z]+)/([0-9A-Za-z]+).html#',
            array('module' => 'product', 'controller' => 'query', 'action' => 'index'),
            array(1 => 'zlkbmethod',2 => 'orderid')
        );
        $router->addRoute('order_query', $order_query);
        
        $order_notify = new Yaf\Route\Regex(
            '#notify/([0-9A-Za-z]+).html#',
            array('module' => 'product', 'controller' => 'notify', 'action' => 'index'),
            array(1 => 'paymethod')
        );
        $router->addRoute('order_notify', $order_notify);
        
        
    }
   
    public function _initPlugin(\Yaf\Dispatcher $dispatcher)
    {
        $router = new RouterPlugin();
        $dispatcher->registerPlugin($router);
    }
}