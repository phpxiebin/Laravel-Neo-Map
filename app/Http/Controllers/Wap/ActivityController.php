<?php

namespace App\Http\Controllers\Wap;

use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * 活动相关WAP端接口
 * Class ActivityController
 * @package App\Http\Controllers\Wap
 */
class ActivityController extends BaseController
{
    /**
     * @api {get} /activity/data 活动列表
     *
     * @apiGroup Activity
     * @apiName ActivityData
     * @apiVersion 1.0.0
     * @apiDescription 活动列表 开发者:谢兵
     *
     * @apiParam {Number} [page=1] 页数
     * @apiParam {Number} [limit=10] 每页数量
     * @apiParam {String} [mark=0] 活动标记[0-无 1-热门]
     * @apiParam {String} [type] 活动类型(预留)
     *
     * @apiSuccess (成功) {Number} respCode 返回码
     * @apiSuccess (成功) {String} respMsg  返回提示信息
     *
     * @apiSuccess (data) {Number} total 总记录数
     * @apiSuccess (data) {Number} last_page 最后一页页码
     * @apiSuccess (data) {Number} currentPage 当前页码
     *
     * @apiSuccess (data) {Array} items 关联的活动信息
     * @apiSuccess (data) {String} items.id 活动Id
     * @apiSuccess (data) {String} items.type 活动类型
     * @apiSuccess (data) {String} items.cover 活动封面截图
     * @apiSuccess (data) {String} items.province 活动省份
     * @apiSuccess (data) {String} items.city 活动城市
     * @apiSuccess (data) {String} items.title 活动标题
     * @apiSuccess (data) {String} items.begin_time 活动开始时间
     * @apiSuccess (data) {String} items.organization_id 机构Id
     * @apiSuccess (data) {String} items.status 状态
     * @apiSuccess (data) {String} items.status_explain 状态解释
     *
     * @apiSuccess (data) {Object} items.organization_data 关联机构信息
     * @apiSuccess (data) {String} items.organization.id 机构Id
     * @apiSuccess (data) {String} items.organization.name 机构名称
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
     *               {
     *                   id: "0e96bb58-d3da-11e7-8909-f656ccfe94ea",
     *                   type: "教育助学",
     *                   cover: "http://oss.nbschools.com/upload/img/cover.jpg",
     *                   province: "省份",
     *                   city: "城市",
     *                   title: "真爱梦想教育助学义卖巡演活动2",
     *                   begin_date: "2017-11-27",
     *                   organization_id: "21525b6c-d353-11e7-8909-f656ccfe94ea",
     *                   status: 1,
     *                   status_explain: "报名中",
     *                   organization_data: {
     *                       id: "21525b6c-d353-11e7-8909-f656ccfe94ea",
     *                       name: "真爱梦想"
     *                   }
     *               },
     *               {
     *                   id: "0fd636d8-d3da-11e7-8909-f656ccfe94ea",
     *                   type: "教育助学",
     *                   cover: "http://oss.nbschools.com/upload/img/cover.jpg",
     *                   province: "省份",
     *                   city: "城市",
     *                   title: "真爱梦想教育助学义卖巡演活动1",
     *                   begin_date: "2017-11-27",
     *                   organization_id: "21525b6c-d353-11e7-8909-f656ccfe94ea",
     *                   status: 1,
     *                   status_explain: "报名中",
     *                   organization_data: {
     *                       id: "21525b6c-d353-11e7-8909-f656ccfe94ea",
     *                       name: "真爱梦想"
     *                   }
     *               }
     *           ]
     *       }
     *   }
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
            'limit' => 'integer',
            'page' => 'integer',
            'type' => 'nullable|string',
            'mark' => 'nullable|string|in:0,1'
        ]);
        if ($validator->fails()) {
            $errMsgArr = $validator->getMessageBag()->toArray();
            return [
                'respCode' => trans('code.err.code'),
                'respMsg'  => current(current($errMsgArr)),
            ];
        }

        $limit = $request->input('limit', 10);
        $mark = $request->input('mark', 0);
        $query = Activity::with('organizationData', 'areaProvince', 'areaCity')
            ->select(['id', 'type', 'cover', 'province', 'city', 'title', 'begin_date', 'organization_id', 'status'])
            ->where('status', 1) //@todo 列表仅展示 <报名中> 活动
            ->where('display', 0)
            ->where('mark', $mark);

        if ($request->filled('type')) {
            $query->where('type', $request->input('type'));
        }

        $result = $query->orderBy('created_at', 'desc')
            ->paginate($limit);

        $items = $result->items();
        foreach ($items as &$item){
            $item->province = $item->areaProvince->lable;
            $item->city = $item->areaCity->lable;
            $item->type = $item->typeExplain;

        }

        $data = [
            'total' => $result->total(),
            'last_page' => $result->lastPage(),
            'currentPage' => $result->currentPage(),
            'items' => $items,
        ];

        return jsonResponse('suc', $data);
    }

    /**
     * @api {get} /activity/detail 活动详情
     *
     * @apiGroup Activity
     * @apiName ActivityDetail
     * @apiVersion 1.0.0
     * @apiDescription 活动详情 开发者:谢兵
     *
     * @apiParam {String} id 活动Id
     *
     * @apiSuccess (成功) {Number} respCode 返回码
     * @apiSuccess (成功) {String} respMsg  返回提示信息
     *
     * @apiSuccess (data) {String} title 活动标题
     * @apiSuccess (data) {String} digest 活动摘要
     * @apiSuccess (data) {String} begin_date 开始时间
     * @apiSuccess (data) {String} end_date 结束时间
     * @apiSuccess (data) {String} address 活动地点
     * @apiSuccess (data) {String} province 活动省份
     * @apiSuccess (data) {String} city 活动城市
     * @apiSuccess (data) {Number} number_limitation 活动限制人数[0不限制]
     * @apiSuccess (data) {String} type 活动类型
     * @apiSuccess (data) {String} cover 封面
     * @apiSuccess (data) {String} details 活动详情
     * @apiSuccess (data) {String} status 状态
     * @apiSuccess (data) {String} organization_id 机构Id
     * @apiSuccess (data) {String} status_explain 状态解释
     * @apiSuccess (data) {Object} organization_detail 关联机构信息
     * @apiSuccess (data) {String} organization_detail.id 机构Id
     * @apiSuccess (data) {String} organization_detail.name 机构名称
     * @apiSuccess (data) {String} organization_detail.logo 机构logo
     * @apiSuccess (data) {String} organization_detail.mission 机构使命
     *
     * @apiSuccessExample {json} Success-Response:
     *  HTTP/1.1 200 OK
     *   {
     *       respCode: 0,
     *       respMsg: "成功",
     *       data: {
     *           title: "真爱梦想教育助学义卖巡演活动3",
     *           digest: "活动摘要",
     *           begin_date: "2017-11-27",
     *           end_date: "2017-11-28",
     *           address: "这里是地址",
     *           province: "省份",
     *           city: "城市",
     *           number_limitation: 0,
     *           type: "教育助学",
     *           cover: "http://oss.nbschools.com/upload/img/cover.jpg",
     *           details: "<p>活动详情HTML</p>",
     *           status: 2,
     *           organization_id: "21525b6c-d353-11e7-8909-f656ccfe94ea",
     *           status_explain: "已结束",
     *           organization_detail: {
     *               id: "21525b6c-d353-11e7-8909-f656ccfe94ea",
     *               name: "真爱梦想",
     *               logo: "http://oss.nbschools.com/upload/img/logo.jpg",
     *               mission: "你我多一份参与，公益多一份美 "
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
        ]);

        if ($validator->fails()) {
            $errMsgArr = $validator->getMessageBag()->toArray();
            return [
                'respCode' => trans('code.err.code'),
                'respMsg'  => current(current($errMsgArr)),
            ];
        }

        $columns = ['title', 'digest', 'begin_date', 'end_date', 'address', 'province', 'city',
            'number_limitation', 'type', 'cover', 'details', 'status', 'organization_id'];

        $result = Activity::with('organizationDetail', 'areaProvince', 'areaCity')->findOrFail($request->input('id'), $columns);
        $result['province'] = $result->areaProvince->lable;
        $result['city'] = $result->areaCity->lable;
        $result['type'] = $result->typeExplain;

        return jsonResponse('suc', $result);
    }
}
