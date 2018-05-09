<?php

namespace App\Http\Controllers\Alumni;

use App\Http\Controllers\Controller;

/**
 * 校友后台统一控制器
 * Class BaseController
 * @package App\Http\Controllers\Alumni
 */
class BaseController extends Controller
{

    // 设置校友后台guard
    public $guard = 'alumni';

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
