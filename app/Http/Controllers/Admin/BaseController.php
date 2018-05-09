<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

/**
 * 国际公益学院地图管理后台统一控制器
 * Class BaseController
 * @package App\Http\Controllers\Alumni
 */
class BaseController extends Controller
{

    // 设置国际公益学院地图管理后台guard
    public $guard = 'admin';

    /**
     * 获取用户信息
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function user()
    {
        return auth($this->guard)->user();
    }

    /**
     * 消息提示
     * @param string $message 消息内容
     * @param int $status 状态 [0-成功 1-失败]
     * @return \Illuminate\Http\RedirectResponse
     */
    public function tips($message = '', $status = 0)
    {
        return back()->with(['message' => $message, 'status' => $status]);
    }
}
