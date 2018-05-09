<?php
namespace App\Models;

use App\Scopes\OrderScope;

/**
 * 机构信息表模型
 * Class Organization
 * @package App
 */
class OrganizationAdmin extends Organization
{
    /**
     * 数据模型的启动方法
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new OrderScope());
    }
}