<?php

/*
 * 功能：后台中心－升级（带备用源回退，且覆盖前做备份）
 * 作者: ZFAKA
 * 日期: 2025-09-13
 *
 * 当 GitHub 无法访问时，会尝试从 https://zk-cash.com/res/latest_version.txt 获取最新版本号，
 * 并可在下载失败时改为从 https://zk-cash.com/res/release.zip 获取更新包。
 * 在覆盖前会把当前所有文件和文件夹打包为 TEMP_PATH/backup备份.zip 作为备份。
 */

set_time_limit(0);
class UpgradeController extends AdminBasicController
{
    // GitHub API 用于读取 release 列表
    private $github_api = "https://api.github.com/repos/ZFAKA/ZFAKA/releases";
    // GitHub zip 下载地址模板：sprintf($this->zip_template, $tag);
    private $zip_template = "https://github.com/ZFAKA/ZFAKA/releases/download/%s/ZFAKA-main.zip";

    // 备用域名
    private $fallback_base = 'https://zk-cash.com/res';
    // 备用的版本文件与 zip 路径
    private $fallback_version_file = '/latest_version.txt';
    private $fallback_zip_file = '/release.zip';

    private $up_version = '';

    public function init()
    {
        parent::init();
    }

    // 入口页面
    public function indexAction()
    {
        // INSTALL_LOCK 检查逻辑
        if (file_exists(INSTALL_LOCK)) {
            // 管理员登录判断
            if ($this->AdminUser == FALSE AND empty($this->AdminUser)) {
                $this->redirect('/' . ADMIN_DIR . "/login");
                return FALSE;
            } else {
                // 读取 INSTALL_LOCK 中的版本信息
                $version = @file_get_contents(INSTALL_LOCK);
                $version = str_replace(array("\r", "\n", "\t"), "", $version);
                $version = strlen(trim($version)) > 0 ? trim($version) : '1.0.0';

                // 若安装记录版本 < 常量 VERSION，则跳转到安装/升级向导（保留原行为）
                if (version_compare($this->normalizeVersion($version), $this->normalizeVersion(VERSION), '<')) {
                    $this->redirect("/install/upgrade");
                    return FALSE;
                } else {
                    // 获取会话缓存的 up_version
                    $up_version = $this->getSession('up_version');
                    if (!$up_version) {
                        $up_version = $this->_getUpdateVersion();
                        $this->setSession('up_version', $up_version);
                    }

                    // 比较当前代码的 VERSION 与 远程 up_version
                    if (version_compare($this->normalizeVersion(VERSION), $this->normalizeVersion($up_version), '<')) {
                        $zipUrl = sprintf($this->zip_template, $up_version);
                        $data = array(
                            'url' => $this->github_api,
                            'up_version' => $up_version,
                            'zip' => $zipUrl,
                            'fallback_zip' => rtrim($this->fallback_base, '/') . $this->fallback_zip_file,
                        );
                        $this->getView()->assign($data);
                    } else {
                        $this->redirect('/' . ADMIN_DIR);
                        return FALSE;
                    }
                }
            }
        } else {
            $this->redirect("/install/");
            return FALSE;
        }
    }

    // 处理 AJAX / POST 下载请求
    public function getremotefileAction()
    {
        if ($this->AdminUser == FALSE AND empty($this->AdminUser)) {
            $data = array('code' => 1000, 'msg' => '请登录');
            Helper::response($data);
        }

        $method = $this->getPost('method', false);
        if ($method && $method == 'download') {
            $extractDir = null;
            $localZip = null;

            try {
                $up_version = $this->getSession('up_version');
                if (!$up_version) {
                    $up_version = $this->_getUpdateVersion();
                    $this->setSession('up_version', $up_version);
                }

                // 只有当本地 VERSION < up_version 才允许下载
                if (version_compare($this->normalizeVersion(VERSION), $this->normalizeVersion($up_version), '<')) {
                    // 优先尝试 GitHub 官方 zip
                    $gitZipUrl = sprintf($this->zip_template, $up_version);
                    try {
                        $localZip = $this->_download($gitZipUrl, TEMP_PATH);
                    } catch (\Exception $e) {
                        $localZip = false;
                    }

                    // 如果 GitHub 下载失败，尝试备用域名提供的 release.zip
                    if (!$localZip) {
                        $fallbackUrl = rtrim($this->fallback_base, '/') . $this->fallback_zip_file;
                        try {
                            $localZip = $this->_download($fallbackUrl, TEMP_PATH);
                        } catch (\Exception $e) {
                            throw new \Exception('下载失败：GitHub 与备用源均不可用. ' . $e->getMessage());
                        }
                    }

                    if ($localZip && file_exists($localZip)) {
                        // 引入工具函数
                        \Yaf\Loader::import(FUNC_PATH . '/F_File.php');

                        // 解压到临时目录
                        $extractDir = rtrim(TEMP_PATH, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . 'zfaka-' . $up_version;
                        if (!is_dir($extractDir)) {
                            @mkdir($extractDir, 0777, true);
                        }
                        $unzipOk = $this->_unzip($localZip, $extractDir);

                        if ($unzipOk) {
                            // === 新增：先备份当前 APP_PATH 到 TEMP_PATH/backup备份.zip ===
                            $backupZip = rtrim(TEMP_PATH, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . 'backup备份.zip';
                            // 若已存在同名备份，先删除（按你的要求使用该固定名称）
                            if (file_exists($backupZip)) {
                                @unlink($backupZip);
                            }
                            $zipOk = $this->_zipDirectory(APP_PATH, $backupZip);
                            if ($zipOk !== true) {
                                throw new \Exception('备份失败：无法打包当前项目到 ' . $backupZip);
                            }
                            // === 备份完成，继续覆盖 ===

                            // 保存管理员目录名
                            $admin_dir = ADMIN_DIR;

                            // 覆盖核心文件（把解压后的 ZFAKA-main 覆盖到 APP_PATH）
                            xCopy($extractDir . '/ZFAKA-main', APP_PATH, 1);

                            // 单独处理 Admin 覆盖（包内的 Admin 覆盖到实际 ADMIN_DIR）
                            $pkgAdminCandidates = array(
                                $extractDir . '/ZFAKA-main/application/modules/Admin',
                                $extractDir . '/application/modules/Admin',
                            );
                            $targetAdmin = rtrim(APP_PATH, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . 'application' . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR . $admin_dir;

                            foreach ($pkgAdminCandidates as $pkgAdmin) {
                                if (is_dir($pkgAdmin)) {
                                    if (!is_dir(dirname($targetAdmin))) {
                                        @mkdir(dirname($targetAdmin), 0777, true);
                                    }
                                    xCopy($pkgAdmin, $targetAdmin, 1);
                                    break;
                                }
                            }

                            $data = array('code' => 1, 'msg' => 'ok');
                        } else {
                            $data = array('code' => 1000, 'msg' => '解压失败');
                        }
                    } else {
                        $data = array('code' => 1000, 'msg' => '下载失败');
                    }
                } else {
                    $data = array('code' => 1000, 'msg' => '没有可用的升级包');
                }
            } catch (\Exception $e) {
                $data = array('code' => 1000, 'msg' => '更新失败: ' . $e->getMessage());
            } finally {
                // 统一清理下载文件与解压目录（备份文件保留）
                if ($localZip && file_exists($localZip)) {
                    @unlink($localZip);
                }
                if ($extractDir && is_dir($extractDir)) {
                    delDir($extractDir);
                }
            }
        } else {
            $data = array('code' => 1000, 'msg' => '缺失参数');
        }

        Helper::response($data);
    }

    // 获取远程最新版本（优先 GitHub API，失败回退到 zk-cash 的 latest_version.txt）
    private function _getUpdateVersion()
    {
        $version = VERSION;
        try {
            // 使用 cURL 获取 GitHub API 返回的 JSON（只取 body）
            $body = $this->_curlGet($this->github_api);
            $arr = json_decode($body, true);
            if (is_array($arr) && count($arr) > 0) {
                $latest = $arr[0];
                if (isset($latest['tag_name']) && strlen(trim($latest['tag_name'])) > 0) {
                    $version = trim($latest['tag_name']);
                }
            }

            // 如果解析失败或版本看起来非法，回退到备用域名
            if (empty($version) || $this->looksLikeInvalidVersion($version)) {
                $version = $this->_getVersionFromFallback();
            }
        } catch (\Exception $e) {
            // GitHub API 请求失败，尝试备用源
            $version = $this->_getVersionFromFallback();
        }

        return $version;
    }

    // 通过备用域名（zk-cash.com）获取版本号
    private function _getVersionFromFallback()
    {
        $version = VERSION;
        try {
            $url = rtrim($this->fallback_base, '/') . $this->fallback_version_file;
            // 优先使用 curl 获取
            $body = $this->_curlGet($url, array(), 10, false);
            if ($body !== false && strlen(trim($body)) > 0) {
                return trim($body);
            }
            // 最后尝试 file_get_contents
            $txt = @file_get_contents($url);
            if ($txt !== false && strlen(trim($txt)) > 0) {
                return trim($txt);
            }
        } catch (\Exception $e) {
            // 忽略异常，返回默认
        }
        return $version;
    }

    // 通过 cURL 获取 URL 的 body（带基本的 HTTP 状态检查）
    // $verifySSL 默认 true；当部分备用源需要跳过时可设置 false
    private function _curlGet($url, $params = array(), $timeout = 30, $verifySSL = true)
    {
        if (is_array($params) && !empty($params)) {
            $url .= '?' . http_build_query($params);
        }

        $ch = curl_init($url);
        $headers = array(
            "Accept: application/vnd.github.v3+json",
            "User-Agent: ZFAKA-Updater/1.0"
        );
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $verifySSL);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, $verifySSL ? 2 : 0);

        $body = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $err = curl_error($ch);
        curl_close($ch);

        if ($body === false) {
            throw new \Exception('请求失败: ' . $err);
        }
        if ($httpCode < 200 || $httpCode >= 300) {
            throw new \Exception('HTTP 错误: ' . $httpCode . ' ' . $err);
        }

        return $body;
    }

    // 下载文件（用 cURL），返回本地文件路径或抛出异常
    private function _download($url, $folder = "")
    {
        if ($folder && !is_dir($folder)) {
            mkdir($folder, 0777, true);
        }

        // 生成临时文件名，以 .zip 结尾
        $tmpPath = tempnam($folder, 'zfaka_dl_');
        $localFile = $tmpPath . '.zip';
        if (file_exists($tmpPath)) {
            @unlink($tmpPath);
        }

        $fp = fopen($localFile, 'w');
        if (!$fp) {
            throw new \Exception('无法创建本地文件: ' . $localFile);
        }

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 300);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_USERAGENT, "ZFAKA-Updater/1.0");

        // 执行下载
        curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $contentLength = curl_getinfo($ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD);
        $err = curl_error($ch);
        curl_close($ch);
        fclose($fp);

        // 检查 HTTP 状态
        if ($httpCode < 200 || $httpCode >= 300) {
            @unlink($localFile);
            throw new \Exception("下载失败 HTTP {$httpCode} {$err}");
        }

        // 如果服务器提供了 Content-Length，则做大小比对（容忍性处理）
        if ($contentLength > 0) {
            $filesize = filesize($localFile);
            if ($filesize != intval($contentLength)) {
                @unlink($localFile);
                throw new \Exception("下载长度不符（期望 {$contentLength}，实际 {$filesize}）");
            }
        } else {
            if (filesize($localFile) === 0) {
                @unlink($localFile);
                throw new \Exception("下载文件为空");
            }
        }

        return $localFile;
    }

    // 解压 zip 文件到指定目录（返回 true/false）
    private function _unzip($file = '', $folder = "")
    {
        $zip = new ZipArchive;
        if ($zip->open($file) === TRUE) {
            $res = $zip->extractTo($folder);
            $zip->close();
            return $res;
        } else {
            return false;
        }
    }

    // 辅助：去掉前导 v 或空白，统一版本格式
    private function normalizeVersion($v)
    {
        $v = trim($v);
        if (strlen($v) > 0 && ($v[0] == 'v' || $v[0] == 'V')) {
            $v = substr($v, 1);
        }
        return $v;
    }

    // 辅助：看起来是否像非法版本（极简单判断)
    private function looksLikeInvalidVersion($v)
    {
        if (empty($v)) return true;
        $nv = $this->normalizeVersion($v);
        return !preg_match('/^\d+(\.\d+)*/', $nv);
    }

    /**
     * 将目录 $source 打包为 zip 文件 $destination
     * 返回 true 表示成功，false 或抛异常表示失败
     */
    private function _zipDirectory($source, $destination)
    {
        if (!extension_loaded('zip')) {
            return false;
        }
        if (!file_exists($source)) {
            return false;
        }

        // 删除同名目标，防止 open 失败
        if (file_exists($destination)) {
            @unlink($destination);
        }

        $zip = new ZipArchive();
        if ($zip->open($destination, ZipArchive::CREATE) !== TRUE) {
            return false;
        }

        $source = realpath($source);
        if (is_dir($source)) {
            $files = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($source),
                RecursiveIteratorIterator::LEAVES_ONLY
            );
            foreach ($files as $name => $file) {
                if (!$file->isDir()) {
                    $filePath = $file->getRealPath();
                    // 计算相对路径，保留目录结构
                    $relativePath = substr($filePath, strlen($source) + 1);
                    // Windows 下处理反斜杠
                    $relativePath = str_replace('\\', '/', $relativePath);
                    $zip->addFile($filePath, $relativePath);
                }
            }
        } else {
            // 单文件
            $zip->addFile($source, basename($source));
        }

        $zip->close();
        return true;
    }
}
