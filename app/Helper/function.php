<?php
/**
 * 通用辅助函数
 */
if (!function_exists('jsonResponse')) {

    /**
     * 格式化返回数据格式
     *
     * @param  string $codeLang 状态码language
     * @param  array $data 当返回表示成功时，可以传此参数替换默认的文案
     * @return array  格式化后的数据
     *                成功示例：['respCode' => '0', 'respMsg' => '成功', 'data' => [...]]
     *                错误示例：['respCode' => '1', 'respMsg' => '失败']
     */
    function jsonResponse($codeLang = 'suc', $data = [])
    {
        $response = [
            'respCode' => trans('code.' . $codeLang . '.code'),
            'respMsg' => trans('code.' . $codeLang . '.msg'),
        ];

        if (is_object($data) || is_array($data)) {
            $response['data'] = $data;
        }

        //临时为前端错误展示修改
        if (!in_array($response['respCode'], ['0', '5'])) {
            $response['respGroup'] = $response['respMsg'];
            $response['respMsg'] = $data['error'] ?? $response['respMsg'];
        }

        return response()->json($response);
    }

}

if (!function_exists('genSalt')) {

    /**生成用户密码salt
     * @return string 返回salt
     */
    function genSalt()
    {
        return substr(str_replace('+', '.', base64_encode(pack('N4', mt_rand(), mt_rand(), mt_rand(), mt_rand()))), 0, 22);
    }
}

if (!function_exists('getCurrentAction')) {
    /**
     * 获取当前控制器与方法
     * @return array 返回结果数组
     */
    function getCurrentAction()
    {
        $action = \Route::currentRouteAction();
        list($class, $method) = explode('@', $action);
        $edit_name = ['store' => '添加', 'update' => '修改'];
        $method_name = array_has($edit_name, $method) ? $edit_name[$method] : $method;
        return ['controller' => $class, 'method' => $method, 'method_name' => $method_name];
    }
}

if (!function_exists('getAmap')) {
    /**
     * 获取高德地图信息
     * @param $address 地址
     * @param string $city 所在城市
     * @return mixed
     */
    function getAmap($address, $city = "")
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "http://restapi.amap.com/v3/geocode/geo?key=8c6d14c6c0fa21617b595cba5f6768bc&address=" . $address . "&city=" . $city,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "cache-control: no-cache",
                "postman-token: 794392fd-270c-63ea-8535-a4b8a4b700a8",
            ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            return json_decode($response);
        }
    }
}

if (!function_exists('getOssPath')) {
    /**
     * 获取oss地址
     * @param $path 文件地址
     * @return mixed
     */
    function getOssPath($path)
    {
        return request()->getScheme() . '://' . env('STORAGE_DOMAIN', 'mana-static-local.oss-cn-hangzhou.aliyuncs.com') . '/' . $path;
    }
}

if (!function_exists('resetOssPath')) {
    /**
     * 还原img地址
     * @param $path 文件地址
     * @return mixed
     */
    function resetOssPath($path)
    {
        return str_replace(request()->getScheme() . '://' . env('STORAGE_DOMAIN', 'mana-static-local.oss-cn-hangzhou.aliyuncs.com')
            . '/', '', $path);
    }
}

if (!function_exists('entrust')) {
    /**
     * 验证是否有访问权限
     * @param $name 权限名称
     * @return boolean
     */
    function entrust($name)
    {
        return auth('admin')->user()->may($name);
    }
}

if (!function_exists('null_filter')) {
    /**
     * 过滤数组值为null的
     * @param $arr
     * @param null $str
     * @return mixed
     */
    function null_filter($arr, $str = null)
    {
        foreach ($arr as $key => &$val) {
            if (is_array($val)) {
                $val = null_filter($val);
            } else {
                if ($val === null) {
                    if(is_null($str)){
                        unset($arr[$key]);
                    }else{
                        $val = $str;
                    }
                }
            }
        }
        return $arr;
    }
}
