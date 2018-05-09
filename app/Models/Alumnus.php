<?php

namespace App\Models;

use App\Scopes\OrderScope;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Notifications\Notifiable;

/**
 * 校友信息表模型
 * Class Alumnus
 * @package App
 */
class Alumnus extends Model implements
    AuthenticatableContract,
    AuthorizableContract,
    CanResetPasswordContract
{
    protected $table = 'alumnus';

    use Authenticatable, Authorizable, CanResetPassword, Notifiable;

    public function getAuthPassword()
    {
        return ['password' => $this->attributes['password'], 'salt' => $this->attributes['salt']];
    }

    /**
     * 获取与机构的关系
     */
    public function organizationDetail()
    {
        return $this->belongsTo('App\Models\Organization', 'organization_id', 'id')
            ->select(['id', 'name', 'sphere', 'public_offering']);
    }

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

    /**
     * 获取身份
     * @return mixed
     */
    public function getIdentityExplainAttribute()
    {
        return config('dataconfig.organization_identity')[$this->identity];
    }

    /**
     * 获取班级
     * @return mixed
     */
    public function getClassExplainAttribute()
    {
        return config('dataconfig.alumnus_class')[$this->class];
    }

}
