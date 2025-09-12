<?php
/**
 * File: M_Admin_login_log.php
 * Functionality: 用户登录日志  model
 * Author: 资料空白
 * Date: 2016-03-23
 */

class M_Admin_login_log extends Model
{

    public function __construct()
    {
        $this->table = TB_PREFIX . 'admin_login_log';
        parent::__construct();
    }

    public function logLogin($adminid)
    {
        if ($adminid AND is_numeric($adminid) AND $adminid > 0) {
            $params['adminid'] = $adminid;
            $params['ip'] = getClientIP();
            $params['addtime'] = time();
            if ($this->Insert($params)) {
                return TRUE;
            }
        }
        return FALSE;
    }
}