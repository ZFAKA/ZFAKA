<p align="center">
  <img width="455" height="116" alt="new_logo" src="https://github.com/user-attachments/assets/56c6e3ff-2e89-4996-b9f7-55fb0aef9ed9" />
</p>
<p align="center">
  <img src="https://img.shields.io/badge/OS-Windows | Linux-blue">
  <img src="https://img.shields.io/badge/version-1.4.6-blue">
  <img src="https://img.shields.io/badge/PHP-7.x-blue">
  <a href="https://t.me/ZFAKA_dev">
    <img src="https://img.shields.io/badge/Telegram-纸飞机-blue?logo=telegram" />
  </a>
</p>

# ZFAKA自动售货系统

>**郑重申明：本项目为开源程序，仅做技术交流使用**

演示地址：https://zk-cash.com/  
永久免费、完全开源，欢迎提供各种需求和意见与建议。  
[加入群组](https://t.me/ZFAKA_group)  
历史漏洞已修复，可放心使用  

[![Telegram](https://img.shields.io/badge/Telegram-联系作者-blue?logo=telegram&style=for-the-badge)](https://t.me/ZFAKA_dev)  
# 模板展示

模板1

<img width="800" alt="template1" src="https://github.com/user-attachments/assets/58fe1d1a-8bb0-4b80-8c4d-e9078d6cd1ca" />

模板2

<img width="800" alt="template2" src="https://github.com/user-attachments/assets/267e960a-2ea7-4114-a402-756965262a15" />

模板3

<img width="800" alt="template3" src="https://github.com/user-attachments/assets/a58a11cc-b96d-4164-8bf0-222177c33b84" />

# 文章页面

<img width="800" alt="article1" src="https://github.com/user-attachments/assets/109985da-554a-4c8b-9bc4-ed80960e4b29" />

# 系统优势  
* 支持USDT收款
* 支持windows和linux  
* SEO优化：新增文章模块  
* 安全：旧版漏洞已修复  
* 全部开源，永久免费，长期技术更新支持  
* 丰富的前端模板  
* 易于扩展，可自行添加支付方式和前端模板  

# 一、系统介绍
>包含自动/手工发卡功能，有会员中心和后台中心。

1.1 会员模块
* 默认情况下，不支持注册，当然后台可以开放注册；

* 注册成会员可查看历史购买记录。
	
1.2 购买模块
* 支持自动发卡和手工发卡模式；

1.3 后台模块
* 包含设置模块、订单模块、商品模块、配置模块、卡密导入导出等；后台可对首页模版进行切换，验证码、注册、登录、找回密码进行后台开关控制；

1.4 文章模块
* 支持在后台编辑并发布文章；
	
1.5 支付渠道
* 官方接口－U支付 （USDT TRC20） （教程：[配置支付方式‐U支付（USDT TRC20）](https://github.com/ZFAKA/ZFAKA/wiki/%E9%85%8D%E7%BD%AE%E6%94%AF%E4%BB%98%E6%96%B9%E5%BC%8F%E2%80%90U%E6%94%AF%E4%BB%98%EF%BC%88USDT-TRC20%EF%BC%89)）

* 官方接口－V免签 （教程：[配置支付方式‐V免签（微信 支付宝）](https://github.com/ZFAKA/ZFAKA/wiki/%E9%85%8D%E7%BD%AE%E6%94%AF%E4%BB%98%E6%96%B9%E5%BC%8F%E2%80%90V%E5%85%8D%E7%AD%BE%EF%BC%88%E5%BE%AE%E4%BF%A1-%E6%94%AF%E4%BB%98%E5%AE%9D%EF%BC%89)）

* 官方接口－支付宝当面付

* 官方接口－支付宝电脑网站支付

* 官方接口－微信扫码支付

* 官方接口－微信H5支付

* 官方接口－PayPal支付

# 二、系统部署
>**友情提示：很多人安装失败都是因为没有仔细看所有的wiki，所以请仔细看完所有的wiki再操作**

## 2.1 安装zfaka

>ZFAKA并不是非得在宝塔环境下才能安装，只是这种安装方式是最快也是最稳定的，熟悉之后三分钟就能搭好，完全避免浪费时间在环境配置上。如果你自己在非宝塔环境下安装遇到问题，请自行寻找解决方法。

### 2.1.1 宝塔环境安装zfaka
>参考：[宝塔环境安装zfaka](https://github.com/ZFAKA/ZFAKA/wiki/%E5%AE%9D%E5%A1%94%E7%8E%AF%E5%A2%83%E5%AE%89%E8%A3%85zfaka).

### 2.1.2 lnmp环境 (宝塔安装不需要看)
>参考：[lnmp环境中如何进行配置](https://github.com/ZFAKA/ZFAKA/wiki/lnmp%E7%8E%AF%E5%A2%83%E4%B8%AD%E5%A6%82%E4%BD%95%E8%BF%9B%E8%A1%8C%E9%85%8D%E7%BD%AE).

### 2.1.2.1 lnmp环境安装YAF (宝塔安装不需要看)
>参考：[lnmp环境中如何安装yaf](https://github.com/ZFAKA/ZFAKA/wiki/lnmp%E7%8E%AF%E5%A2%83%E4%B8%AD%E5%A6%82%E4%BD%95%E5%AE%89%E8%A3%85yaf).

### 2.1.3 rewrite配置 (宝塔安装不需要看)
>参考：[rewrite配置](https://github.com/ZFAKA/ZFAKA/wiki/rewrite%E9%85%8D%E7%BD%AE).

### 2.1.4 lamp环境（apache）安装ZFAKA (宝塔安装不需要看)
>参考：[lamp环境(apache)中如何安装ZFAKA](https://github.com/ZFAKA/ZFAKA/wiki/lamp%E7%8E%AF%E5%A2%83(apache)%E4%B8%AD%E5%A6%82%E4%BD%95%E5%AE%89%E8%A3%85ZFAKA).

## 非宝塔安装出现问题请检查：

* 务必：配置nginx vhost中root路径一定要加上public目录，例如:  /alidata/wwwroot/faka.zlkb.net/public;

* 务必：配置nginx vhost中一定要添加rewrite规则

* 务必：取消防跨站攻击(open_basedir)

* 务必：注意nginx环境下path_info的配置(记的要取消)

* 务必：YAF配置开启命名空间 yaf.use_namespace=1

* 务必：项目运行给站点用户权限

## 2.2 修改默认管理员账号和密码

安装完成后请访问后台（默认路径为Goadmin，实际取决于你安装过程中的配置）修改管理员账号和密码  
默认管理员账号：demo@demo.com  
默认密码：123456  

## 2.3 定时任务（可选）

> 发送邮件方式如果选择系统自动，则不需要配置定时任务

### 2.3.1 安装计划任务crontab模块,配置定时计划,用于定时发送邮件
* lnmp环境计划任务crontab的部署
>参考：[lnmp环境中如何部署计划任务](https://github.com/ZFAKA/ZFAKA/wiki/lnmp%E7%8E%AF%E5%A2%83%E4%B8%AD%E5%A6%82%E4%BD%95%E9%83%A8%E7%BD%B2%E8%AE%A1%E5%88%92%E4%BB%BB%E5%8A%A1)

* 宝塔环境计划任务crontab的部署
>参考：[宝塔环境中如何部署计划任务](https://github.com/ZFAKA/ZFAKA/wiki/%E5%AE%9D%E5%A1%94%E7%8E%AF%E5%A2%83%E4%B8%AD%E5%A6%82%E4%BD%95%E9%83%A8%E7%BD%B2%E8%AE%A1%E5%88%92%E4%BB%BB%E5%8A%A1).

# 三、免责声明
请查看 [/disclaimer.md](/disclaimer.md)
