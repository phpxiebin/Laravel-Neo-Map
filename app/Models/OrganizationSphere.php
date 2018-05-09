<?php

namespace App\Models;

/**
 * 机构专注领域表模型
 * Class OrganizationSphere
 * @package App
 */
class OrganizationSphere extends Model
{
    protected $table = 'organization_sphere';

    /**
     * 映射访问器
     * @param  string $value 原值
     * @return string 新值
     */
    public function setSphereAttribute($value)
    {
        $this->attributes['sphere'] = config('dataconfig.sphere_name')[$value];
    }
}
