<?php

namespace App\Http\Controllers\Wap;

use App\Http\Controllers\Controller;

/**
 * WAP端控制器基类
 * Class BaseController
 * @package App\Http\Controllers\Wap
 */
class BaseController extends Controller
{
    /**
     * 统一返回错误的请求
     * @param $validator 验证对象
     * @return array
     */
    protected function errorBadRequest($validator)
    {
        $result = [];
        $messages = $validator->errors()->toArray();
        if ($messages) {
            foreach ($messages as $field => $errors) {
                foreach ($errors as $error) {
                    $result[] = [
                        'field' => $field,
                        'msg' => $error,
                    ];
                }
            }
        }
        return $result;
    }

    /**
     * 根据请求对象和验证对象获取验证后的字段
     * @param $request 请求对象
     * @param $validator 验证对象
     * @return mixed
     */
    protected function getRuleInput($request, $validator)
    {
        //@todo 获取请求中实际存在的输入数据时
        return array_intersect_key($request->all(), array_flip(array_keys($validator->getRules())));
    }
}