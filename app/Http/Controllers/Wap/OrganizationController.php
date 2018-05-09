<?php

namespace App\Http\Controllers\Wap;

use App\Models\Activity;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * 机构相关WAP端接口
 * Class OrganizationController
 * @package App\Http\Controllers\Wap
 */
class OrganizationController extends BaseController
{

    /**
     * @api {get} /organization/map 机构信息-地图展示
     *
     * @apiGroup Organization
     * @apiName OrganizationMap
     * @apiVersion 1.0.0
     * @apiDescription 机构信息-地图展示 开发者:谢兵
     *
     * @apiParam {String} province 省份或直辖市名称(展示全国的时候点击省份需传入)
     * @apiParam {String} city 城市名称(定位未获取到传空,为空返回全国)
     * @apiParam {String} [southwest] 当前视野内地图西南坐标(可视化区域展示预留)
     * @apiParam {String} [northeast] 当前视野内地图东北坐标(可视化区域展示预留)
     *
     *
     * @apiSuccess (成功) {Number} respCode 返回码
     * @apiSuccess (成功) {String} respMsg  返回提示信息
     *
     * @apiSuccess (定位失败情况) {String} name 省份或城市名称
     * @apiSuccess (定位失败情况) {String} center 坐标
     * @apiSuccess (定位失败情况) {String} count 机构总数
     *
     * @apiSuccess (定位成功情况) {Number} total 总记录数
     * @apiSuccess (定位成功情况) {Number} last_page 最后一页页码
     * @apiSuccess (定位成功情况) {Number} currentPage 当前页码
     * @apiSuccess (定位成功情况) {Array} organizations 关联的机构信息
     * @apiSuccess (定位成功情况) {String} organizations.id 机构Id
     * @apiSuccess (定位成功情况) {String} organizations.name 机构名称
     * @apiSuccess (定位成功情况) {String} organizations.center 坐标
     * @apiSuccess (定位成功情况) {String} organizations.logo Logo地址
     * @apiSuccess (定位成功情况) {String} organizations.mission 机构使命
     * @apiSuccess (定位成功情况) {Array} organizations.sphere 关联的机构专注领域
     * @apiSuccess (定位成功情况) {String} organizations.sphere.organization_id 机构Id
     * @apiSuccess (定位成功情况) {String} organizations.sphere.sphere 专注领域
     *
     * @apiSuccessExample {json} Success-Response:
     *  HTTP/1.1 200 OK
     *  A.定位获取城市失败的情况:(首先会返回全国有机构的省信息,前端交互再传入省名称后端返回对应省有机构的市信息,
     *  再把市名称传给后台返回和系统定位流程一致)
     *   {
     *       respCode: 0,
     *       respMsg: "成功",
     *       data: [
     *           {
     *               name: "北京市",
     *               center: "116.407394,39.904211",
     *               count: 1
     *           },
     *           {
     *               name: "上海市",
     *               center: "121.473662,31.230372",
     *               count: 2
     *           },
     *           {
     *               name: "湖南省",
     *               center: "112.9836,28.112743",
     *               count: 1
     *           }
     *       ]
     *   }
     *
     *
     *  B.成功定位到城市的情况:(返回该市底下所有机构信息)
     *   {
     *       respCode: 0,
     *       respMsg: "成功",
     *       data: {
     *           total: 2,
     *           last_page: 1,
     *           currentPage: 1,
     *           organizations: [
     *               {
     *                   name: "真爱梦想",
     *                   center: "121.586866,31.202233",
     *                   organization_id: "3aacbfb3-5b7d-327f-981e-659f7a7cb6d9",
     *                   logo: "http://oss.nbschools.com/upload/img/logo.jpg",
     *                   mission: "我们致力于改善中国教育的不均衡状况",
     *                   sphere: [
     *                       {
     *                           organization_id: "3aacbfb3-5b7d-327f-981e-659f7a7cb6d9",
     *                           sphere: "扶贫脱困"
     *                       },
     *                       {
     *                           organization_id: "3aacbfb3-5b7d-327f-981e-659f7a7cb6d9",
     *                           sphere: "教育助学"
     *                       }
     *                   ]
     *               },
     *               {
     *                   name: "上海玛娜基金会",
     *                   center: "121.58769,31.205507",
     *                   organization_id: "3aacbfb3-5b7d-327f-981e-659f7a7cb6d9",
     *                   logo: "http://oss.nbschools.com/upload/img/logo.jpg",
     *                   mission: "捍卫个人数据权益,我的数据我做主",
     *                   sphere: [
     *                       {
     *                           organization_id: "3aacbfb3-5b7d-327f-981e-659f7a7cb6d9",
     *                           sphere: "扶贫脱困"
     *                       },
     *                       {
     *                           organization_id: "3aacbfb3-5b7d-327f-981e-659f7a7cb6d9",
     *                           sphere: "教育助学"
     *                       }
     *                   ]
     *               }
     *           ]
     *       }
     *   }
     * @apiError (错误) {Number} respCode  返回码
     * @apiError (错误) {String} respMsg   返回提示信息
     *
     * @apiErrorExample {json} Error-Response:
     *  HTTP/1.1 200 OK
     *   {
     *      "respCode": 1,
     *      "respMsg": "失败"
     *   }
     *
     */
    public function map(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'province' => 'nullable|string',
            'city' => 'nullable|string',
        ]);
        if ($validator->fails()) {
            return $this->errorBadRequest($validator);
        }

        $province = $request->input('province', '');
        $city = $request->input('city', '');

        //如果省市都没有传或者传入的是空字符串
        if (empty($province) && empty($city)) {
            $result['province'] = (new Organization())->mapProvince();
        } elseif ($request->has('city') && !empty($city)) { //传入市区名称
            $result = (new Organization())->mapOrganization($city);
            $result = [
                'total' => $result->total(),
                'last_page' => $result->lastPage(),
                'currentPage' => $result->currentPage(),
                'organizations' => $result->items(),
            ];
        } elseif ($request->has('province')) { //传入省份名称
            $result['city'] = (new Organization())->mapCity($province);
        }
        return jsonResponse('suc', $result);
    }

    /**
     * @api {get} /organization/data 机构信息-列表搜索
     *
     * @apiGroup Organization
     * @apiName OrganizationData
     * @apiVersion 1.0.0
     * @apiDescription 机构信息-列表搜索 开发者:谢兵
     *
     * @apiParam {Number} [page=1] 页数
     * @apiParam {Number} [limit=10] 每页数量
     * @apiParam {String} [name] 机构名称
     * @apiParam {String} [sphere] 专注领域
     * @apiParam {String} [coverage] 服务区域
     * @apiParam {String} [public_offering] 机构性质
     *
     * @apiSuccess (成功) {Number} respCode 返回码
     * @apiSuccess (成功) {String} respMsg  返回提示信息
     *
     * @apiSuccess (data) {Number} total 总记录数
     * @apiSuccess (data) {Number} last_page 最后一页页码
     * @apiSuccess (data) {Number} currentPage 当前页码
     *
     * @apiSuccess (data) {Array} items 机构列表
     * @apiSuccess (data) {String} items.id 机构Id
     * @apiSuccess (data) {String} items.name 机构名称
     * @apiSuccess (data) {String} items.logo Logo地址
     * @apiSuccess (data) {String} items.mission 机构使命
     * @apiSuccess (data) {Array} items.sphere 关联的机构专注领域
     * @apiSuccess (data) {String} items.sphere.id 机构Id
     * @apiSuccess (data) {String} items.sphere.sphere 专注领域
     *
     * @apiSuccessExample {json} Success-Response:
     *  HTTP/1.1 200 OK
     *   {
     *       respCode: 0,
     *       respMsg: "成功",
     *       data: {
     *           total: 1,
     *           last_page: 1,
     *           currentPage: 1,
     *           items: [
     *               {
     *                   id: "3aacbfb3-5b7d-327f-981e-659f7a7cb6d9",
     *                   name: "真爱梦想",
     *                   logo: "http://oss.nbschools.com/upload/img/logo.jpg",
     *                   mission: "你我多一份参与，公益多一份美 ",
     *                   sphere: [
     *                       {
     *                           organization_id: "3aacbfb3-5b7d-327f-981e-659f7a7cb6d9",
     *                           sphere: "扶贫脱困"
     *                       },
     *                       {
     *                           organization_id: "3aacbfb3-5b7d-327f-981e-659f7a7cb6d9",
     *                           sphere: "教育助学"
     *                       }
     *                   ]
     *               }
     *           ]
     *       }
     *   }
     *
     *
     * @apiError (错误) {Number} respCode  返回码
     * @apiError (错误) {String} respMsg   返回提示信息
     *
     * @apiErrorExample {json} Error-Response:
     *  HTTP/1.1 200 OK
     *   {
     *      "respCode": 1,
     *      "respMsg": "失败"
     *   }
     *
     */
    public function data(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string',
            'sphere' => 'nullable|string',
            'coverage' => 'nullable|string',
            'public_offering' => 'nullable|string',
            'limit' => 'integer',
            'page' => 'integer',
        ]);
        if ($validator->fails()) {
            return $this->errorBadRequest($validator);
        }
        $input = array_filter($this->getRuleInput($request, $validator));
        $name = $request->input('name', '');
        $limit = $request->input('limit', 10);

        if($request->filled('sphere')){
            $input['sphere'] = array_search($input['sphere'], config('dataconfig.sphere_name'));
        }
        if($request->filled('public_offering')){
            $input['public_offering'] = array_search($input['public_offering'], config('dataconfig.public_offering'));
        }

        $result = (new Organization())->search($name, $input, ['organization.id', 'name', 'logo', 'mission'], $limit);

        $data = [
            'total' => $result->total(),
            'last_page' => $result->lastPage(),
            'currentPage' => $result->currentPage(),
            'items' => $result->items(),
        ];
        return jsonResponse('suc', $data);
    }

    /**
     * @api {get} /organization/detail 机构详情
     *
     * @apiGroup Organization
     * @apiName OrganizationDetail
     * @apiVersion 1.0.0
     * @apiDescription 机构详情 开发者:谢兵
     *
     * @apiParam {String} id 机构Id
     * @apiParam {String} [type=all] 获取方式(all-全部 brief-简要)
     *
     * @apiSuccess (成功) {Number} respCode 返回码
     * @apiSuccess (成功) {String} respMsg  返回提示信息
     *
     * @apiSuccess (brief-简要) {String} id 机构Id
     * @apiSuccess (brief-简要) {String} logo 机构logo
     * @apiSuccess (brief-简要) {String} name 机构名称
     * @apiSuccess (brief-简要) {String} mission 组织使命
     * @apiSuccess (brief-简要) {String} avatar 负责人头像
     * @apiSuccess (brief-简要) {String} principal 负责人名称
     * @apiSuccess (brief-简要) {String} identity 负责人身份
     * @apiSuccess (brief-简要) {String} intro 机构简介
     * @apiSuccess (brief-简要) {Array} sphere 关联的机构专注领域
     * @apiSuccess (brief-简要) {String} sphere.organization_id 机构Id
     * @apiSuccess (brief-简要) {String} sphere.sphere 专注领域
     *
     * @apiSuccess (all-全部) {String} id 机构Id
     * @apiSuccess (all-全部) {String} logo 机构logo
     * @apiSuccess (all-全部) {String} name 机构名称
     * @apiSuccess (all-全部) {String} register_date 注册时间
     * @apiSuccess (all-全部) {String} location 所在地
     * @apiSuccess (all-全部) {String} phone 电话号码
     * @apiSuccess (all-全部) {String} address 机构详细地址
     * @apiSuccess (all-全部) {String} amap_coordinate 机构坐标
     * @apiSuccess (all-全部) {String} website 官方网站
     * @apiSuccess (all-全部) {String} avatar 负责人头像
     * @apiSuccess (all-全部) {String} principal 负责人名称
     * @apiSuccess (all-全部) {String} identity 负责人身份
     * @apiSuccess (all-全部) {String} intro 机构简介
     * @apiSuccess (all-全部) {String} public_offering 是否具有公募资质
     * @apiSuccess (all-全部) {String} donation_certificate 公开募捐证书
     * @apiSuccess (all-全部) {String} certificate 法人登记证书
     * @apiSuccess (all-全部) {String} total_donation 募捐总善款
     * @apiSuccess (all-全部) {String} total_times 捐款总人次
     * @apiSuccess (all-全部) {String} mission 组织使命
     * @apiSuccess (all-全部) {String} coverage 服务区域
     * @apiSuccess (all-全部) {Array} sphere 关联的机构专注领域
     * @apiSuccess (all-全部) {String} sphere.organization_id 机构Id
     * @apiSuccess (all-全部) {String} sphere.sphere 专注领域
     *
     * @apiSuccess (all-全部) {Array} alumnus 关联的校友信息
     * @apiSuccess (all-全部) {String} alumnus.organization_id 机构Id
     * @apiSuccess (all-全部) {String} alumnus.status 激活状态(0-未激活 1-已激活)
     * @apiSuccessExample {json} Success-Response:
     *  HTTP/1.1 200 OK
     *   {
     *       respCode: 0,
     *       respMsg: "成功",
     *       data: {
     *           id: "3aacbfb3-5b7d-327f-981e-659f7a7cb6d9",
     *           logo: "http://oss.nbschools.com/upload/img/logo.jpg",
     *           name: "真爱梦想",
     *           register_date: "2010-10-12",
     *           location: "上海市",
     *           phone: "13012345678",
     *           address: "上海市浦东新区张江高科",
     *           amap_coordinate: "121.586866,31.202233",
     *           website: "http://www.zhengai.com",
     *           avatar: "http://oss.nbschools.com/upload/img/avatar.jpg",
     *           principal: "负责人",
     *           identity: "基金会秘书长",
     *           intro: "简介中国慈善机构",
     *           public_offering: "0",
     *           donation_certificate: "",
     *           certificate: "",
     *           total_donation: "10000000",
     *           total_times: "100000",
     *           mission: "你我多一份参与，公益多一份美 ",
     *           coverage: "全国区域",
     *           sphere: [
     *               {
     *                   organization_id: "3aacbfb3-5b7d-327f-981e-659f7a7cb6d9",
     *                   sphere: "扶贫脱困"
     *               },
     *               {
     *                   organization_id: "3aacbfb3-5b7d-327f-981e-659f7a7cb6d9",
     *                   sphere: "教育助学"
     *               }
     *           ],
     *           alumnus: {
     *               organization_id: "3aacbfb3-5b7d-327f-981e-659f7a7cb6d9",
     *               status: 1
     *           }
     *       }
     *   }
     * @apiError (错误) {Number} respCode  返回码
     * @apiError (错误) {String} respMsg   返回提示信息
     *
     * @apiErrorExample {json} Error-Response:
     *  HTTP/1.1 200 OK
     *   {
     *      "respCode": 1,
     *      "respMsg": "失败"
     *   }
     *
     */
    public function detail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|string',
            'type' => 'nullable|string|in:all,brief'
        ]);
        if ($validator->fails()) {
            return $this->errorBadRequest($validator);
        }
        $type = $request->input('type', 'all');
        $columns = ['all' => ['id', 'logo', 'name', 'register_date', 'location', 'phone', 'address', 'amap_coordinate',
            'website', 'avatar', 'principal', 'identity', 'intro', 'public_offering', 'donation_certificate',
            'certificate', 'total_donation', 'total_times', 'mission', 'coverage', 'activated', 'province', 'city'
        ],
            'brief' => ['id', 'logo', 'name', 'mission', 'avatar', 'principal', 'intro', 'identity', 'province', 'city']];
        $result = Organization::with(['sphere', 'areaProvince', 'areaCity', 'alumnusMultiple'])->find($request->input('id'), $columns[$type]);
        if (empty($result)) {
            return jsonResponse('err', $result);
        }
        $result['identity'] = $result['identity_explain'];
        $result['location'] = $result->areaCity->lable;
        $result['alumnus'] = ['organization_id'=>$result->id, 'status'=>$result->activated];

        foreach ($result->alumnusMultiple as &$v){
            $v['identity_explain'] = $v->identity_explain;
//            $v['class_explain'] = $v->class_explain;
        }
        return jsonResponse('suc', $result);
    }

    /**
     * @api {get} /organization/activity 机构发起的活动
     *
     * @apiGroup Organization
     * @apiName OrganizationActivity
     * @apiVersion 1.0.0
     * @apiDescription 机构发起的活动 开发者:谢兵
     *
     * @apiParam {String} organization_id 机构Id
     * @apiParam {Number} [page=1] 页数
     * @apiParam {Number} [limit=10] 每页数量
     *
     * @apiSuccess (成功) {Number} respCode 返回码
     * @apiSuccess (成功) {String} respMsg  返回提示信息
     * @apiSuccess (data) {Number} total 总记录数
     * @apiSuccess (data) {Number} last_page 最后一页页码
     * @apiSuccess (data) {Number} currentPage 当前页码
     *
     * @apiSuccess (data) {Array} items 机构列表
     * @apiSuccess (data) {String} items.id 活动Id
     * @apiSuccess (data) {String} items.title 活动标题
     * @apiSuccess (data) {String} items.digest 活动摘要
     * @apiSuccess (data) {String} items.begin_date 开始时间
     * @apiSuccess (data) {String} items.end_date 结束时间
     * @apiSuccess (data) {String} items.address 活动地点
     * @apiSuccess (data) {String} items.province 活动省份
     * @apiSuccess (data) {String} items.city 活动城市
     * @apiSuccess (data) {Number} items.number_limitation 活动限制人数[0不限制]
     * @apiSuccess (data) {String} items.type 活动类型
     * @apiSuccess (data) {String} items.cover 封面
     * @apiSuccess (data) {String} items.details 活动详情
     * @apiSuccess (data) {String} items.status 状态
     * @apiSuccess (data) {String} items.organization_id 机构Id
     * @apiSuccess (data) {String} items.status_explain 状态解释
     * @apiSuccess (data) {Object} items.organization_detail 关联机构信息
     * @apiSuccess (data) {String} items.organization_detail.id 机构Id
     * @apiSuccess (data) {String} items.organization_detail.name 机构名称
     * @apiSuccess (data) {String} items.organization_detail.logo 机构logo
     * @apiSuccess (data) {String} items.organization_detail.mission 机构使命
     *
     * @apiSuccessExample {json} Success-Response:
     *  HTTP/1.1 200 OK
     *   {
     *       respCode: 0,
     *       respMsg: "成功",
     *       data: {
     *           total: 2,
     *           last_page: 1,
     *           currentPage: 1,
     *           items: [
     *           {
     *               id: "0c4c0f10-d3da-11e7-8909-f656ccfe94ea",
     *               type: "教育助学",
     *               cover: "http://192.168.2.35/cover.jpeg",
     *               province: "省份",
     *               city: "城市",
     *               title: "真爱梦想教育助学义卖巡演活动11",
     *               digest: "活动摘要",
     *               begin_date: "2017-11-27",
     *               organization_id: "3aacbfb3-5b7d-327f-981e-659f7a7cb6d9",
     *               status: 1,
     *               status_explain: "报名中",
     *               organization_data: {
     *                   id: "3aacbfb3-5b7d-327f-981e-659f7a7cb6d9",
     *                   name: "真爱梦想"
     *               }
     *           },
     *           {
     *               id: "0920bf20-d3da-11e7-8909-f656ccfe94ea",
     *               type: "教育助学",
     *               cover: "http://192.168.2.35/cover.jpeg",
     *               province: "省份",
     *               city: "城市",
     *               title: "真爱梦想教育助学义卖巡演活动9",
     *               digest: "活动摘要",
     *               begin_date: "2017-11-27",
     *               organization_id: "3aacbfb3-5b7d-327f-981e-659f7a7cb6d9",
     *               status: 1,
     *               status_explain: "报名中",
     *               organization_data: {
     *                   id: "3aacbfb3-5b7d-327f-981e-659f7a7cb6d9",
     *                   name: "真爱梦想"
     *               }
     *           }]
     *      }
     *  }
     *
     * @apiError (错误) {Number} respCode  返回码
     * @apiError (错误) {String} respMsg   返回提示信息
     *
     * @apiErrorExample {json} Error-Response:
     *  HTTP/1.1 200 OK
     *   {
     *      "respCode": 1,
     *      "respMsg": "失败"
     *   }
     *
     */
    public function activity(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'organization_id' => 'required|string',
            'limit' => 'integer',
            'page' => 'integer',
        ]);
        if ($validator->fails()) {
            $errMsgArr = $validator->getMessageBag()->toArray();
            return [
                'respCode' => trans('code.err.code'),
                'respMsg'  => current(current($errMsgArr)),
            ];
        }

        $limit = $request->input('limit', 10);
        $result = Activity::with('organizationData', 'areaProvince', 'areaCity')
            ->select(['id', 'type', 'cover', 'province', 'city', 'title', 'digest', 'begin_date', 'status', 'organization_id'])
            ->where('organization_id', $request->input('organization_id'))
            ->where('display', 0)
            ->orderBy('created_at', 'desc')
            ->paginate($limit);

        $items = $result->items();
        foreach ($items as &$item){
            $item->province = $item->areaProvince->lable;
            $item->city = $item->areaCity->lable;
        }

        $data = [
            'total' => $result->total(),
            'last_page' => $result->lastPage(),
            'currentPage' => $result->currentPage(),
            'items' => $items,
        ];

        return jsonResponse('suc', $data);
    }
}
