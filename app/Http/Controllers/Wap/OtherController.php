<?php

namespace App\Http\Controllers\Wap;

use App\Models\Area;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * 其他相关WAP端接口
 * Class OtherController
 * @package App\Http\Controllers\Wap
 */
class OtherController extends BaseController
{
    /**
     * @api {get} /other/filter 获取后台筛选值列表
     *
     * @apiGroup Other
     * @apiName OtherFilter
     * @apiVersion 1.0.0
     * @apiDescription 获取后台筛选值列表 开发者:谢兵
     *
     * @apiParam {String} name 选项值名称[sphere-专注领域 coverage-服务区域 public_offering-机构性质] 多个字段英文逗号隔开,如:sphere,coverage,public_offering
     *
     * @apiSuccess (成功) {Number} respCode 返回码
     * @apiSuccess (成功) {String} respMsg  返回提示信息
     *
     * @apiSuccess (data) {Array} name 传入的选项值列表
     *
     * @apiSuccessExample {json} Success-Response:
     *  HTTP/1.1 200 OK
     *   {
     *       respCode: 0,
     *       respMsg: "成功",
     *       data: {
     *           sphere: [
     *               "扶贫救灾",
     *               "灾害救助",
     *               "教育助学",
     *               "助困行动"
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
    public function filter(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
        ]);

        if ($validator->fails()) {
            $errMsgArr = $validator->getMessageBag()->toArray();
            return [
                'respCode' => trans('code.err.code'),
                'respMsg' => current(current($errMsgArr)),
            ];
        }

        $array = [
            'sphere' => config('dataconfig.sphere_name'),
            'alumnus_class' => config('dataconfig.alumnus_class'),
            'coverage' => config('dataconfig.coverage'),
            'public_offering' => config('dataconfig.public_offering'),
        ];

        $key = explode(',', $request->input('name'));
        $result = array_only($array, $key);
        return jsonResponse('suc', $result);
    }

    /**
     * @api {get} /other/organization-autocomple 机构名称自动补全
     *
     * @apiGroup Other
     * @apiName OtherOrganizationAutocomple
     * @apiVersion 1.0.0
     * @apiDescription 机构名称自动补全 开发者:谢兵
     *
     * @apiParam {String} name 机构名称
     * @apiParam {Number} [limit=10] 返回数量
     *
     * @apiSuccess (成功) {Number} respCode 返回码
     * @apiSuccess (成功) {String} respMsg  返回提示信息
     *
     * @apiSuccess (data) {Array} name 机构名称补全列表
     *
     * @apiSuccessExample {json} Success-Response:
     *  HTTP/1.1 200 OK
     *   {
     *       respCode: 0,
     *       respMsg: "成功",
     *       data: {
     *           name: [
     *               "真爱梦想",
     *               "真爱梦想1",
     *               "真爱梦想2",
     *               "真爱梦想3"
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
    public function organizationAutocomple(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'string',
            'limit' => 'integer',
        ]);
        if ($validator->fails()) {
            $errMsgArr = $validator->getMessageBag()->toArray();
            return [
                'respCode' => trans('code.err.code'),
                'respMsg' => current(current($errMsgArr)),
            ];
        }
        $result = [];
        if ($request->has('name')) {
            $result = Organization::where('name', 'like', $request->input('name') . '%')
                ->limit($request->input('limit', 10))
                ->pluck('name');
        }
        return jsonResponse('suc', ['name' => $result]);
    }

    /**
     * @api {get} /other/amap-sync 行政地区同步高德坐标(请勿随意调用)
     *
     * @apiGroup Other
     * @apiName OtherOrganizationAmapSync
     * @apiVersion 1.0.0
     * @apiDescription 行政地区同步高德坐标(请勿随意调用) 开发者:谢兵
     *
     * @apiSuccess (成功) {Number} respCode 返回码
     * @apiSuccess (成功) {String} respMsg  返回提示信息
     *
     * @apiSuccessExample {json} Success-Response:
     *  HTTP/1.1 200 OK
     *   {
     *       respCode: 0,
     *       respMsg: "成功"
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
    public function amapSync()
    {
        $items = Area::limit(1)->get();
        foreach ($items as $item) {
            $center = getAmap($item->lable)->geocodes[0]->location ?? '';
            Area::find($item->id)->update(['center' => $center]);
        }
        return jsonResponse('suc');
    }

}
