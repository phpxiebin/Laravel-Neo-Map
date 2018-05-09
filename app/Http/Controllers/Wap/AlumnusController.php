<?php

namespace App\Http\Controllers\Wap;

use App\Models\Alumnus;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * 校友相关WAP端接口
 * Class AlumnisController
 * @package App\Http\Controllers\Wap
 */
class AlumnusController extends BaseController
{
    /**
     * @api {post} /alumnus/activate 校友激活
     *
     * @apiGroup Alumnus
     * @apiName AlumnusActivate
     * @apiVersion 1.0.0
     * @apiDescription 校友激活 开发者:谢兵
     *
     * @apiParam {String} name 姓名
     * @apiParam {String} phone 手机号
     * @apiParam {String} organization_id 机构Id
     * @apiParam {String} class 班级
     *
     * @apiSuccess (成功) {Number} respCode 返回码
     * @apiSuccess (成功) {String} respMsg  返回提示信息
     *
     * @apiSuccessExample {json} Success-Response:
     *  HTTP/1.1 200 OK
     *   {
     *       respCode: 0,
     *       respMsg: "成功",
     *       data: [ ]
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
    public function activate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'phone' => 'required|string',
            'organization_id' => 'required|string',
            'class' => 'required|string',
        ]);
        if ($validator->fails()) {
            $errMsgArr = $validator->getMessageBag()->toArray();
            return [
                'respCode' => trans('code.err.code'),
                'respMsg' => current(current($errMsgArr)),
            ];
        }

        $class = array_search($request->input('class'), config('dataconfig.alumnus_class'));

        // 查询校友
        $result = Alumnus::where([
            'name' => $request->input('name'),
            'phone' => $request->input('phone'),
            'organization_id' => $request->input('organization_id'),
            'class' => $class,
        ])->select(['id', 'status'])->first();

        // 判断激活信息
        if (is_null($result)) { //待激活校友数据不存在
            return jsonResponse('not_data');
        } elseif ($result->status == 1) { //该校友数据曾经已经激活
            return jsonResponse('existence_data');
        }

        //激活校友
        $result->update(['status' => 1]);

        //同步机构激活状态
        Organization::where('id', $request->input('organization_id'))
            ->update(['activated'=>1]);

        return jsonResponse('suc');
    }
}
