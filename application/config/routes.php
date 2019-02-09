<?php
/**
 * @name 生蚝科技统一身份认证平台-路由设置
 * @author Jerry Cheung <master@xshgzs.com>
 * @since 2019-01-19
 * @version 2019-02-08
 */

defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller'] = 'Main/index';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;


/******* 基础页面 *******/
$route['error/(:any)']='Main/error/$1';
$route['dashborad']='Main/dashborad';


/******* API-用户 *******/
$route['api/user/resetPassword']['POST']='API/API_User/resetPassword';
$route['api/user/delete']['POST']='API/API_User/delete';
$route['api/user/getAllUser']='API/API_User/getAllUser';
$route['api/user/checkDuplicate/(:any)/(:any)']='API/API_User/checkDuplicate/$1/$2';
$route['api/user/getUserInfo']['POST']='API/API_User/getUserInfo';
$route['api/user/updateUserInfo']['POST']='API/API_User/updateUserInfo';


/******* API-角色 *******/
$route['api/role/getRoleInfo']='API/API_Role/getRoleInfo';// 获取所有角色
$route['api/role/getRoleInfo/(:num)']='API/API_Role/getRoleInfo/$1';// 获取某一角色
$route['api/role/getUserMenu']='API/API_Role/getUserMenu';
$route['api/role/getRoleMenuForZtree/(:num)']='API/API_Role/getRoleMenuForZtree/$1';


/******* API-通知 *******/
$route['api/notice/get']='API/API_Notice/get';


/******* API-小程序 *******/
$route['api/wxmp/getQrCode/(:any)']='API/API_WXMP/getQrCode/$1';
$route['api/wxmp/getQrCode/(:any)/(:any)']='API/API_WXMP/getQrCode/$1/$2';
$route['api/wxmp/checkStatus']='API/API_WXMP/checkStatus';
$route['api/wxmp/getOpenId']='API/API_WXMP/getOpenId';
$route['api/wxmp/handler']['POST']='API/API_WXMP/handler';
$route['api/wxmp/bindUser']['POST']='API/API_WXMP/bindUser';


/******* 登录登出 *******/
$route['login']='Login/login';
$route['login/(:any)/(:any)']='Login/login/$1/$2';// 有AppId的登录
$route['logout']='Login/logout';
$route['logout/(:any)']='Login/logout/$1';
$route['logout/(:any)/(:any)/(:any)']='Login/logout/$1/$2/$3';


/******* 注册 *******/
$route['register_step1']='Login/register_step1';
$route['toRegister_step1']='Login/toRegister_step1';
$route['register_step2/(:any)']='Login/register_step2/$1';
$route['toRegister_step2']='Login/toRegister_step2';


/******* 后台-用户 *******/
$route['admin/user/list']='Admin/Admin_User/list';


/******* 后台-角色 *******/
$route['admin/role/list']='Admin/Admin_Role/list';
$route['admin/role/delete']['POST']='Admin/Admin_Role/delete';
$route['admin/role/toSetRolePermission']['POST']='Admin/Admin_Role/toSetRolePermission';
