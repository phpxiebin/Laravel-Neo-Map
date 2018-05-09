<?php

namespace App\Http\Controllers\Alumni;

use App\Models\Alumnus;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

/**
 * 用户认证控制器
 * Class AuthController
 * @package App\Http\Controllers\Alumni
 */
class AuthController extends BaseController
{
    use AuthenticatesUsers;

    /**
     * 自定义 校友后台Guard
     * @return mixed
     */
    protected function guard()
    {
        return Auth::guard($this->guard);
    }

    /**
     * 使用username替代原始的email
     * @return string
     */
    public function username()
    {
        return 'phone';
    }

    /**
     * 修改原始跳转链接
     * @return string
     */
    protected function redirectTo()
    {
        return '/alumni';
    }

    /**
     * 打开登录页面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function authenticate()
    {
        return view('alumni.login');
    }

    public function sendFailedLoginResponse()
    {
        throw ValidationException::withMessages([
            $this->username() => [trans('auth.'.$this->guard.'.failed')],
        ]);
    }

    /**
     * 注册-测试
     * @return mixed
     */
    public function register()
    {
        $salt = genSalt();
        $password = '123456';
        $name = '测试校友';
        $phone = '15618291817';

        return Alumnus::create([
            'name' => $name,
            'phone' => $phone,
            'password' => bcrypt($password . $salt),
            'salt' => $salt
        ]);
    }

    /**
     * 检测登录是否正常
     * @return \Illuminate\Http\JsonResponse
     */
    public function check()
    {
        $data = ($this->guard()->check()) ? ['auth' => 'Authenticated'] : ['auth' => 'Unauthenticated'];
        return response()->json($data);
    }

    /**
     * 用户退出
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        $this->guard()->logout();
        return redirect('alumni/login');
    }
}
