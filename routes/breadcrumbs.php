<?php
/**
 * 面包屑配置
 */
//校友平台首页
Breadcrumbs::register('alumni.home.dashboard', function ($breadcrumbs) {
    $breadcrumbs->push('控制台', route('alumni.home.dashboard'));
});
//机构信息
Breadcrumbs::register('alumni.info.organization', function ($breadcrumbs) {
    $breadcrumbs->parent('alumni.home.dashboard');
    $breadcrumbs->push('机构信息', route('alumni.info.organization'));
});
//校友信息
Breadcrumbs::register('alumni.info.alumnus', function ($breadcrumbs) {
    $breadcrumbs->parent('alumni.home.dashboard');
    $breadcrumbs->push('校友信息', route('alumni.info.alumnus'));
});
// 活动列表
Breadcrumbs::register('alumni.activity', function ($breadcrumbs) {
    $breadcrumbs->parent('alumni.home.dashboard');
    $breadcrumbs->push('活动列表', route('alumni.activity'));
});
// 添加活动
Breadcrumbs::register('alumni.activity.store', function ($breadcrumbs) {
    $breadcrumbs->parent('alumni.activity');
    $breadcrumbs->push('添加活动', route('alumni.activity.store'));
});
// 修改活动
Breadcrumbs::register('alumni.activity.update', function ($breadcrumbs) {
    $breadcrumbs->parent('alumni.activity');
    $breadcrumbs->push('修改活动', route('alumni.activity.update'));
});

//管理后台首页
Breadcrumbs::register('admin.home.dashboard', function ($breadcrumbs) {
    $breadcrumbs->push('控制台', route('admin.home.dashboard'));
});
// 校友列表
Breadcrumbs::register('admin.alumnus', function ($breadcrumbs) {
    $breadcrumbs->parent('admin.home.dashboard');
    $breadcrumbs->push('校友列表', route('admin.alumnus'));
});
// 添加校友
Breadcrumbs::register('admin.alumnus.store', function ($breadcrumbs) {
    $breadcrumbs->parent('admin.alumnus');
    $breadcrumbs->push('添加校友', route('admin.alumnus.store'));
});
//更新校友信息
Breadcrumbs::register('admin.alumnus.update', function ($breadcrumbs) {
    $breadcrumbs->parent('admin.alumnus');
    $breadcrumbs->push('更新校友信息', route('admin.alumnus.update'));
});
//删除校友信息
Breadcrumbs::register('admin.alumnus.destroy', function ($breadcrumbs) {
    $breadcrumbs->parent('admin.alumnus');
    $breadcrumbs->push('删除校友', route('admin.alumnus.destroy'));
});
// 机构列表
Breadcrumbs::register('admin.organization', function ($breadcrumbs) {
    $breadcrumbs->parent('admin.home.dashboard');
    $breadcrumbs->push('机构列表', route('admin.organization'));
});
// 添加机构
Breadcrumbs::register('admin.organization.store', function ($breadcrumbs) {
    $breadcrumbs->parent('admin.organization');
    $breadcrumbs->push('添加机构', route('admin.organization.store'));
});
//更新机构
Breadcrumbs::register('admin.organization.update', function ($breadcrumbs) {
    $breadcrumbs->parent('admin.organization');
    $breadcrumbs->push('更新机构', route('admin.organization.update'));
});
//删除机构
Breadcrumbs::register('admin.organization.destroy', function ($breadcrumbs) {
    $breadcrumbs->parent('admin.organization');
    $breadcrumbs->push('删除机构', route('admin.organization.destroy'));
});
// 活动列表
Breadcrumbs::register('admin.activity', function ($breadcrumbs) {
    $breadcrumbs->parent('admin.home.dashboard');
    $breadcrumbs->push('活动列表', route('admin.activity'));
});
//更新活动
Breadcrumbs::register('admin.activity.update', function ($breadcrumbs) {
    $breadcrumbs->parent('admin.activity');
    $breadcrumbs->push('更新活动', route('admin.activity.update'));
});
//删除活动a
Breadcrumbs::register('admin.activity.destroy', function ($breadcrumbs) {
    $breadcrumbs->parent('admin.activity');
    $breadcrumbs->push('删除活动', route('admin.activity.destroy'));
});
//权限
Breadcrumbs::register('admin.entrust', function ($breadcrumbs) {
    $breadcrumbs->parent('admin.home.dashboard');
});
//权限
Breadcrumbs::register('admin.entrust.permissions', function ($breadcrumbs) {
    $breadcrumbs->parent('admin.entrust');
    $breadcrumbs->push('权限', route('admin.entrust.permissions'));
});
Breadcrumbs::register('admin.entrust.permissions.create', function ($breadcrumbs) {
    $breadcrumbs->parent('admin.entrust');
    $breadcrumbs->push('创建', route('admin.entrust.permissions.create'));
});
Breadcrumbs::register('admin.entrust.permissions.edit', function ($breadcrumbs) {
    $breadcrumbs->parent('admin.entrust');
    $breadcrumbs->push('编辑', route('admin.entrust.permissions.edit'));
});
//权限
Breadcrumbs::register('admin.entrust.users', function ($breadcrumbs) {
    $breadcrumbs->parent('admin.entrust');
    $breadcrumbs->push('用户', route('admin.entrust.users'));
});
Breadcrumbs::register('admin.entrust.users.create', function ($breadcrumbs) {
    $breadcrumbs->parent('admin.entrust');
    $breadcrumbs->push('创建', route('admin.entrust.users.create'));
});
Breadcrumbs::register('admin.entrust.users.edit', function ($breadcrumbs) {
    $breadcrumbs->parent('admin.entrust');
    $breadcrumbs->push('编辑', route('admin.entrust.users.edit'));
});
//权限
Breadcrumbs::register('admin.entrust.roles', function ($breadcrumbs) {
    $breadcrumbs->parent('admin.entrust');
    $breadcrumbs->push('用户', route('admin.entrust.roles'));
});
Breadcrumbs::register('admin.entrust.roles.create', function ($breadcrumbs) {
    $breadcrumbs->parent('admin.entrust');
    $breadcrumbs->push('创建', route('admin.entrust.roles.create'));
});
Breadcrumbs::register('admin.entrust.roles.edit', function ($breadcrumbs) {
    $breadcrumbs->parent('admin.entrust');
    $breadcrumbs->push('编辑', route('admin.entrust.roles.edit'));
});
