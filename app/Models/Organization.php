<?php

namespace App\Models;

/**
 * 机构信息表模型
 * Class Organization
 * @package App
 */
class Organization extends Model
{
    protected $table = 'organization';

    /**
     * 获取与专注领域的关系
     */
    public function sphere()
    {
        return $this->hasMany('App\Models\OrganizationSphere', 'organization_id', 'id')
            ->select(['organization_id', 'sphere']);
    }

    /**
     * 获取与校友的关系
     */
    public function alumnus()
    {
        return $this->hasOne('App\Models\Alumnus', 'organization_id', 'id')
            ->select(['organization_id', 'status'])
            ->withDefault([
                'organization_id' => '',
                'status' => 0
            ]);
    }

    /**
     * 获取与校友的关系(机构多校友模式)
     */
    public function alumnusMultiple()
    {
        return $this->hasMany('App\Models\Alumnus', 'organization_id', 'id')
            ->select(['organization_id', 'name', 'avatar', 'email', 'identity', 'class', 'stud_no'])
            -(['class_explain']);
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
     * 机构信息列表
     * @param string $name 查询机构名字(模糊匹配)
     * @param array $input 查询其他字段(强匹配)
     * @param array $select 返回字段
     * @param int $limit 返回条数
     * @return mixed
     */
    public function search($name = '', $input = [], $select = ['*'], $limit = 10)
    {
        $sphere = $input['sphere'] ?? '';
        $input = array_except($input, ['sphere', 'name', 'limit', 'page']);
        $query = $this::with('sphere')
            ->select($select)
            ->where(['status' => 1])
            ->where('name', 'like', '%' . $name . '%')
            ->where($input);
        if(isset($sphere)){
            $query->where('sphere', 'like', '%' .$sphere . '%');
        }

        return $query->paginate($limit);
    }

    /**
     * 省市地图展示省份查询
     * @return array
     */
    public function mapProvince()
    {
        //查询省份数据,并按城市分组
        $result = $this::select(['organization.province'])
            ->join('area', function ($join) {
                $join->on('area.code', '=', 'organization.province');
            })->where(['status' => 1])
            ->groupBy('organization.province')->get();

        $arr = [];
        foreach ($result as $item) {
            $city = $this::where(['province' => $item->province])
                ->join('area', function ($join) use ($item) {
                    $join->on('area.code', '=', 'organization.province');
                });
            $arr[] = [
                'name' => $city->value('lable'),
                'center' => $city->value('center'),
                'count' => $city->count()
            ];
        }
        return $arr;
    }

    /**
     * 省市地图展示城市查询
     * @param string $province
     * @return array
     */
    public function mapCity($province = '')
    {
        //查询省份数据,并按城市分组
        $result = $this::select(['organization.city'])
            ->where(['status' => 1])
            ->join('area', function ($join) use ($province) {
                $join->on('area.code', '=', 'organization.province')
                    ->where(['lable' => $province]);
            })->groupBy('organization.city')->get();

        $arr = [];
        foreach ($result as $item) {
            $city = $this::where(['city' => $item->city])
                ->join('area', function ($join) use ($item) {
                    $join->on('area.code', '=', 'organization.city');
                });
            $arr[] = [
                'name' => $city->value('lable'),
                'center' => $city->value('center'),
                'count' => $city->count()
            ];
        }
        return $arr;
    }

    /**
     * 省市地图展示机构查询
     * @param string $city
     * @return array
     */
    public function mapOrganization($city = '')
    {
        //查询省份数据,并按城市分组
        $result = $this::with('sphere')
            ->select(
                ['organization.id as id', 'name', 'amap_coordinate as center',
                    'organization.id as organization_id', 'logo', 'mission'])
            ->where(['status' => 1])
            ->join('area', function ($join) use ($city) {
                $join->on('area.code', '=', 'organization.city')
                    ->where(['lable' => $city]);
            });

        return ($result->paginate(1000));
    }


    public function getLogoAttribute($value)
    {
        return getOssPath($value);
    }

    public function getAvatarAttribute($value)
    {
        return getOssPath($value);
    }

    public function getDonationCertificateAttribute($value)
    {
        return getOssPath($value);
    }

    public function getCertificateAttribute($value)
    {
        return getOssPath($value);
    }

    public function getIdentityExplainAttribute()
    {
        return config('dataconfig.organization_identity')[$this->identity];
    }
}
