<?php
/**
 * File: M_Article.php
 * Functionality: 文章 model
 * Author: 资料空白
 * Date: 2025-08-31
 */

class M_Article extends Model
{
    public function __construct()
    {
        $this->table = TB_PREFIX.'article';
        parent::__construct();
    }

    /**
     * 获取发布状态的文章
     */
    public function getPublishedArticles($limit = '')
    {
        $where = array('status' => 1);
        $order = array('sort' => 'DESC', 'id' => 'DESC');
        
        if ($limit) {
            return $this->Where($where)->Order($order)->Limit($limit)->Select();
        } else {
            return $this->Where($where)->Order($order)->Select();
        }
    }

    /**
     * 根据ID获取发布状态的文章
     */
    public function getPublishedById($id)
    {
        $where = array('id' => $id, 'status' => 1);
        return $this->Where($where)->SelectOne();
    }

    /**
     * 根据slug获取发布状态的文章
     */
    public function getPublishedBySlug($slug)
    {
        $where = array('slug' => $slug, 'status' => 1);
        return $this->Where($where)->SelectOne();
    }

    /**
     * 增加阅读量
     */
    public function incrementViews($id)
    {
        $where = array('id' => $id);
        return $this->Where($where)->UpdateOne(array('views' => '+1'), TRUE);
    }

    /**
     * 检查slug是否已存在
     */
    public function checkSlugExists($slug, $excludeId = 0)
    {
        $where = array('slug' => $slug);
        if ($excludeId > 0) {
            $where['id !='] = $excludeId;
        }
        return $this->Where($where)->Total() > 0;
    }
}