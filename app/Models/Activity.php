<?php

namespace App\Models;
use App\Scopes\OrderScope;

/**
 * 活动信息表模型
 * Class Activity
 * @package App
 */
class Activity extends Model
{
    protected $table = 'activity';

    protected $appends = ['status_explain'];


    /**
     * 获取与机构的关系(列表用)
     */
    public function organizationData()
    {
        return $this->belongsTo('App\Models\Organization', 'organization_id', 'id')
            ->select(['id', 'name']);
    }

    /**
     * 获取与机构的关系(详情页用)
     */
    public function organizationDetail()
    {
        return $this->belongsTo('App\Models\Organization', 'organization_id', 'id')
            ->select(['id', 'name', 'logo', 'mission']);
    }

    /**
     * 获取与地区省份的关系
     */
    public function areaProvince()
    {
        return $this->hasOne('App\Models\Area', 'code', 'province')
            ->select(['code', 'lable'])
            ->withDefault([
                'code' => '',
                'lable' => ''
            ]);
    }

    /**
     * 获取与地区城市的关系
     */
    public function areaCity()
    {
        return $this->hasOne('App\Models\Area', 'code', 'city')
            ->select(['code', 'lable'])
            ->withDefault([
                'code' => '',
                'lable' => ''
            ]);
    }

    /**
     * 状态映射访问器
     * @param  string $value 原值
     * @return string 新值
     */
    public function getStatusExplainAttribute($value)
    {
        $status = ['1' => '报名中', '2' => '已结束'];
        return $status[$this->status];
    }

    public function getCoverAttribute($value)
    {
        return getOssPath($value);
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
     * 活动类型映射访问器
     * @return string 新值
     */
    public function getTypeExplainAttribute()
    {
        return config('dataconfig.activity.type')[$this->type] ?? '';
    }
}
