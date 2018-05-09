<?php

namespace App\Http\Controllers\Alumni;

use App\Models\Alumnus;
use App\Models\Organization;
use App\Models\OrganizationSphere;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class InfoController extends BaseController
{
    public function organization(Request $request)
    {
        $user = $this->user();
        if ($request->isMethod('post')) {
            $organization = $this->organization_detail($user->organization_id);
            $this->validate($request, [
                'name' => 'required',
                'register_date' => 'required',
                'phone' => 'required',
                'province' => 'required',
                'city' => 'required',
                'district' => 'required',
                'address' => 'required',
                'website' => 'required',
                'principal' => 'required',
                'identity' => 'required',
                'intro' => 'required',
                'public_offering' => 'required|in:0,1',
                'total_donation' => 'nullable|numeric|min:0',
                'total_times' => 'nullable|numeric|min:0',
                'mission' => 'required',
                'coverage' => 'required',
            ]);

            //如果具有公募资质,需要传证件
            if ($request->input('public_offering') == 1) {
                if (empty($organization->donation_certificate)) {
                    $this->validate($request, [
                        'donation_certificate' => 'required',
                    ]);
                }
                if (empty($organization->certificate)) {
                    $this->validate($request, [
                        'certificate' => 'required'
                    ]);
                }
            }
            //如果没有创建过机构
            if(is_null($organization)){
                $this->validate($request, [
                    'logo' => 'required'
                ]);
            }

            $input = $request->all();
            if ($request->hasFile('logo')) {
                $input['logo'] = $request->file('logo')->store('upload/img');
            }
            //保存负责人头像
            if ($request->hasFile('avatar')) {
                $input['avatar'] = $request->file('avatar')->store('upload/img');
            }
            //保存公开募捐证书
            if ($request->hasFile('donation_certificate')) {
                $input['donation_certificate'] = $request->file('donation_certificate')->store('upload/img');
            }
            //保存法人登记证书
            if ($request->hasFile('certificate')) {
                $input['certificate'] = $request->file('certificate')->store('upload/img');
            }
            //获取高德地址坐标信息
            $amap = getAmap($input['address'], $input['city']);
            if(empty($amap->geocodes[0]->location)){
                return back()->with(['message' => "没有获取到地址坐标信息,请重新输入地址", 'status' => 1]);
            }

            $input['amap_coordinate'] = $amap->geocodes[0]->location ?? '';
            $organizationSphere = $input['sphere'];
            $input['sphere'] = implode(',',$input['sphere']);
            unset($input['organization_sphere']);
            if(empty($input['total_donation'])){
                $input['total_donation'] = 0;
            }
            if(empty($input['total_times'])){
                $input['total_times'] = 0;
            }
            if (is_null($organization)) {
                $organization = Organization::create($input);
                Alumnus::where('id', $this->user()->id)->update(['organization_id' => $organization->id]);
            } else {
                $organization->update($input);
            }

            OrganizationSphere::where(['organization_id' => $organization->id])->delete();
            foreach ($organizationSphere as $item) {
                $organizationSphere = [
                    'organization_id' => $organization->id,
                    'sphere' => $item
                ];
                OrganizationSphere::create($organizationSphere);
            }

            return redirect('/alumni/info/organization')->with('message', '编辑成功');

        } else {
            $organization = $this->organization_detail($user->organization_id);

            return view('alumni.info.organization', compact('organization'));
        }
    }

    public function alumnus(Request $request)
    {
        $user = $this->user();
        if ($request->isMethod('post')) {
            $alumnus = $this->alumnus_detail($user->id);
            $this->validate($request, [
                'name' => 'required|max:30',
                'identity' => 'required',
                'class' => 'required',
                'isedited' => 'required|in:0,1',
                'stud_no' => 'required',
            ]);

            $input = [
                'name' => $request->input('name'),
                'identity' => $request->input('identity'),
                'class' => $request->input('class'),
                'stud_no' => $request->input('stud_no')
            ];

            //如果修改密码
            if ($request->input('isedited', 0) == 1) {
                $oldpassword = $request->input('password_old');
                $data = $request->all();
                $rules = [
                    'password_old' => 'required|between:6,20',
                    'password' => 'required|between:6,20|confirmed',
                    'password_confirmation' => 'required|between:6,20',
                ];
                $messages = [
                    'between' => '密码必须是6~20位之间',
                    'confirmed' => '新密码和确认密码不匹配'
                ];
                $validator = Validator::make($data, $rules, $messages);
                $validator->after(function ($validator) use ($oldpassword, $user) {
                    if (!\Hash::check($oldpassword . $user->salt, $user->password)) {
                        $validator->errors()->add('old_password', '原密码错误');
                    }
                });
                if ($validator->fails()) {
                    return back()->withErrors($validator);
                }

                $salt = genSalt();
                $input['salt'] = $salt;
                $input['password'] = bcrypt($request->input('password') . $salt);
            }
            $alumnus->update($input);
            return redirect('/alumni/info/alumnus')->with('message', '编辑成功');
        } else {
            $alumnus = $this->alumnus_detail($user->id, ['name', 'identity', 'class', 'phone', 'stud_no']);
            return view('alumni.info.alumnus', compact('alumnus'));
        }
    }

    /**
     * 根据ID查询校友详情
     * @param $id ID
     * @param array $columns 查询字段
     * @return mixed 返回查询结果
     * @throws \ErrorException
     */
    public function alumnus_detail($id, $columns = ['*'])
    {
        $result = Alumnus::find($id, $columns);
        if (!$result) {
            throw new \ErrorException('数据不存在');
        }
        return $result;
    }

    /**
     * 根据ID查询机构详情
     * @param $id ID
     * @param array $columns 查询字段
     * @return mixed 返回查询结果
     * @throws \ErrorException
     */
    public function organization_detail($id, $columns = ['*'])
    {
        $result = Organization::find($id, $columns);
        return $result;
    }
}
