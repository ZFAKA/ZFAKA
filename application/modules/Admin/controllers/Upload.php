<?php

/*
 * 功能：后台文件上传处理
 * Author: 资料空白
 * Date: 20250901
 */

class UploadController extends AdminBasicController
{
    public function init()
    {
        parent::init();
    }

    // 图片上传
    public function imageAction()
    {
        if ($this->AdminUser == FALSE && empty($this->AdminUser)) {
            $data = array('errno' => 1, 'message' => '请先登录');
            Helper::response($data);
        }

        if (!isset($_FILES['file'])) {
            $data = array('errno' => 1, 'message' => '没有文件被上传');
            Helper::response($data);
        }

        $file = $_FILES['file'];
        
        // 检查上传错误
        if ($file['error'] !== UPLOAD_ERR_OK) {
            $data = array('errno' => 1, 'message' => '文件上传失败');
            Helper::response($data);
        }

        // 允许的图片类型
        $allowedTypes = array('image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp');
        $allowedExts = array('jpg', 'jpeg', 'png', 'gif', 'webp');
        
        // 检查文件类型
        if (!in_array($file['type'], $allowedTypes)) {
            $data = array('errno' => 1, 'message' => '不支持的文件类型');
            Helper::response($data);
        }

        // 检查文件扩展名
        $fileExt = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($fileExt, $allowedExts)) {
            $data = array('errno' => 1, 'message' => '不支持的文件扩展名');
            Helper::response($data);
        }

        // 检查文件大小 (5MB)
        if ($file['size'] > 5 * 1024 * 1024) {
            $data = array('errno' => 1, 'message' => '文件大小不能超过5MB');
            Helper::response($data);
        }

        // 创建上传目录
        $uploadDir = APP_PATH . '/public/res/upload/images/' . date('Y/m/');
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // 生成新文件名
        $fileName = date('YmdHis') . '_' . rand(1000, 9999) . '.' . $fileExt;
        $filePath = $uploadDir . $fileName;

        // 移动文件
        if (move_uploaded_file($file['tmp_name'], $filePath)) {
            // 返回wangEditor需要的格式
            $data = array(
                'errno' => 0,
                'data' => array(
                    'url' => '/res/upload/images/' . date('Y/m/') . $fileName,
                    'alt' => $fileName,
                    'href' => '/res/upload/images/' . date('Y/m/') . $fileName
                )
            );
            Helper::response($data);
        } else {
            $data = array('errno' => 1, 'message' => '文件保存失败');
            Helper::response($data);
        }
    }

    // 视频上传
    public function videoAction()
    {
        if ($this->AdminUser == FALSE && empty($this->AdminUser)) {
            $data = array('errno' => 1, 'message' => '请先登录');
            Helper::response($data);
        }

        if (!isset($_FILES['file'])) {
            $data = array('errno' => 1, 'message' => '没有文件被上传');
            Helper::response($data);
        }

        $file = $_FILES['file'];
        
        // 检查上传错误
        if ($file['error'] !== UPLOAD_ERR_OK) {
            $data = array('errno' => 1, 'message' => '文件上传失败');
            Helper::response($data);
        }

        // 允许的视频类型
        $allowedTypes = array('video/mp4', 'video/avi', 'video/mov', 'video/wmv', 'video/flv', 'video/webm');
        $allowedExts = array('mp4', 'avi', 'mov', 'wmv', 'flv', 'webm');
        
        // 检查文件类型
        if (!in_array($file['type'], $allowedTypes)) {
            $data = array('errno' => 1, 'message' => '不支持的视频格式');
            Helper::response($data);
        }

        // 检查文件扩展名
        $fileExt = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($fileExt, $allowedExts)) {
            $data = array('errno' => 1, 'message' => '不支持的文件扩展名');
            Helper::response($data);
        }

        // 检查文件大小 (50MB)
        if ($file['size'] > 50 * 1024 * 1024) {
            $data = array('errno' => 1, 'message' => '视频文件大小不能超过50MB');
            Helper::response($data);
        }

        // 创建上传目录
        $uploadDir = APP_PATH . '/public/res/upload/videos/' . date('Y/m/');
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // 生成新文件名
        $fileName = date('YmdHis') . '_' . rand(1000, 9999) . '.' . $fileExt;
        $filePath = $uploadDir . $fileName;

        // 移动文件
        if (move_uploaded_file($file['tmp_name'], $filePath)) {
            // 返回wangEditor需要的格式
            $data = array(
                'errno' => 0,
                'data' => array(
                    'url' => '/res/upload/videos/' . date('Y/m/') . $fileName
                )
            );
            Helper::response($data);
        } else {
            $data = array('errno' => 1, 'message' => '文件保存失败');
            Helper::response($data);
        }
    }
}