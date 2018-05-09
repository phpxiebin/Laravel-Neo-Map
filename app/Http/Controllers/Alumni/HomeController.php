<?php

namespace App\Http\Controllers\Alumni;

/**
 * 校友平台首页
 * Class AuthController
 * @package App\Http\Controllers\Alumni
 */
class HomeController extends BaseController
{

    public function dashboard()
    {
        return view('alumni.home');
    }
}