# 生蚝科技统一身份认证平台

---

## 简介

▲ 主要功能：SSO单点登录，用户资料统一管理

▲ 开发商：广州市生蚝科技有限公司（生蚝科技）

▲ 代码版本信息：***Build 00521***

▲ 系统版本信息：***1.0.0***

▲ 遵循协议：GNU GPLv3

▲ 特点：允许跨父域/二级域名/文件夹 | 每个用户拥有唯一的SSO-UnionID便于管理 | 支持第三方互联登录 | 支持微信扫码登录

---

## 待开发 TODO

▲ SSO平台管理平台

---

## 第三方应用接入方法 Third Party Application Access Method

① 在SSO中心数据库的app表添加你的app信息（appId尽量用同一套算法生成25位字符串！！）

② 仿照testApp的ssoLogin.php，发送请求到SSO中心API获取用户信息

---

## 插件 Plugin

▲ 微信小程序扫码登录：[`API_WXMP.php`](https://github.com/OysterTech/OT-SSO/blob/master/application/controllers/API/API_WXMP.php)填入小程序AppID和AppSecret

---

## 第三方登录配置 Third-party Login Setting

▲ Github：[`login.php`](https://github.com/OysterTech/OT-SSO/blob/master/login.php#L109)填入clientId，[`github.php`](https://github.com/OysterTech/OT-SSO/blob/master/thirdLogin/github.php)填入clientId和clientSecret

---

## 鸣谢

* [`CodeIgniter`](http://codeigniter.org.cn/) 后端框架

* [`Vue.js`](https://vuejs.org/) 前端框架

* [`AdminLTE`](https://github.com/almasaeed2010/AdminLTE) UI框架
