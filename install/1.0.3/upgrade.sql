ALTER TABLE `t_products` ADD `isdelete` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '0未删除,1已删除';
ALTER TABLE `t_products_card` ADD `isdelete` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '0未删除,1已删除';
ALTER TABLE `t_products_type` ADD `isdelete` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '0未删除,1已删除';
ALTER TABLE `t_order` ADD `isdelete` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '0未删除,1已删除';
INSERT INTO `t_config` (`id`, `catid`, `name`, `value`, `tag`, `lock`, `updatetime`) VALUES
(8, 0, 'notice', '本系统商品均可正常购买。开源下载地址：github地址:&lt;a href=&quot;https://github.com/zlkbdotnet/zfaka/&quot; target=&quot;_blank&quot;&gt;https://github.com/zlkbdotnet/zfaka/&lt;/a&gt;', '首页公告', 1, 1453452674);
