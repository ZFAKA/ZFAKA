-- 删除不可用的支付方式
DELETE FROM `t_payment` WHERE `alias` IN ('codepayalipay', 'codepayqq', 'codepaywx', 'yzpay', 'coinpay');

-- 更新支付方式数据
UPDATE `t_payment` SET 
  `id` = 2
WHERE `payment` = '支付宝电脑网站支付(WEB)';

UPDATE `t_payment` SET 
  `id` = 3
WHERE `payment` = '微信扫码支付';

UPDATE `t_payment` SET 
  `id` = 4
WHERE `payment` = '微信H5支付';

UPDATE `t_payment` SET 
  `payment` = 'PAYPAL',
  `payname` = 'PAYPAL',
  `payimage` = '/res/images/pay/paypal.jpg',
  `alias` = 'paypal',
  `sign_type` = 'RSA2',
  `app_id` = '',
  `app_secret` = '',
  `ali_public_key` = '',
  `rsa_private_key` = '',
  `configure3` = 'live',
  `configure4` = '7',
  `overtime` = 0,
  `active` = 0
WHERE `id` = 5;

-- 添加新的支付方式
INSERT INTO `t_payment` (`id`, `payment`, `payname`, `payimage`, `alias`, `sign_type`, `app_id`, `app_secret`, `ali_public_key`, `rsa_private_key`, `configure3`, `configure4`, `overtime`, `active`) VALUES
(6, 'V免签微信', '微信', '/res/images/pay/weixin.jpg', 'vpaywx', 'MD5', '', '', '', '', '', '', 6000, 0);
INSERT INTO `t_payment` (`id`, `payment`, `payname`, `payimage`, `alias`, `sign_type`, `app_id`, `app_secret`, `ali_public_key`, `rsa_private_key`, `configure3`, `configure4`, `overtime`, `active`) VALUES
(7, 'V免签支付宝', '支付宝', '/res/images/pay/alipay.jpg', 'vpayalipay', 'MD5', '', '', '', '', '', '', 6000, 0);
INSERT INTO `t_payment` (`id`, `payment`, `payname`, `payimage`, `alias`, `sign_type`, `app_id`, `app_secret`, `ali_public_key`, `rsa_private_key`, `configure3`, `configure4`, `overtime`, `active`) VALUES
(8, 'U支付', 'USDT', '/res/images/pay/usdt.jpg', 'uzhifu', 'MD5', '', '', '', '', '', '', 6000, 0);

-- 创建新表
CREATE TABLE IF NOT EXISTS `t_article` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL COMMENT '文章标题',
  `content` longtext NOT NULL COMMENT '文章内容',
  `excerpt` text COMMENT '文章摘要',
  `slug` varchar(255) DEFAULT NULL COMMENT 'URL别名',
  `status` tinyint(1) DEFAULT 1 COMMENT '状态 1-发布 0-草稿',
  `views` int(11) DEFAULT 0 COMMENT '阅读量',
  `keywords` varchar(255) DEFAULT NULL COMMENT 'SEO关键词',
  `description` varchar(500) DEFAULT NULL COMMENT 'SEO描述',
  `sort` int(11) DEFAULT 0 COMMENT '排序',
  `created_at` int(11) NOT NULL COMMENT '创建时间',
  `updated_at` int(11) NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `idx_status` (`status`),
  KEY `idx_slug` (`slug`),
  KEY `idx_sort` (`sort`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='文章表';