<?php

namespace App\Http\Controllers\Admin;

use App\Models\Activity;
use App\Models\Alumnus;
use App\Models\Organization;
use App\Models\OrganizationAdmin;
use App\Models\OrganizationSphere;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class OrganizationController extends BaseController
{
    public function index()
    {
        return view('admin.organization.index');
    }

    public function data(Request $request)
    {
        $activity = OrganizationAdmin::select([
            'id',
            'logo',
            'name',
            'address',
            'principal',
            'phone',
            'location',
            'sphere',
            'coverage',
            'status',
            'public_offering',
            'created_at'
        ]);


        $datatables = Datatables::of($activity)
            ->filter(function ($query) use ($request) {
                if ($request->filled('name')) {
                    $query->where('name', 'like', "%{$request->input('name')}%");
                }
                if ($request->filled('public_offering')) {
                    $query->where('public_offering', $request->input('public_offering'));
                }
                if ($request->filled('address')) {
                    $query->where('address', 'like', "%{$request->input('address')}%");
                }
                if ($request->filled('principal')) {
                    $query->where('principal', 'like', "%{$request->input('principal')}%");
                }
                if ($request->filled('phone')) {
                    $query->where('phone', 'like', "%{$request->input('phone')}%");
                }
                if ($request->filled('sphere')) {
                    $query->where('sphere', 'like', "%{$request->input('sphere')}%");
                }
            });

        return $datatables
            ->editColumn('logo', function ($activity) {
                return '<img width="80" src="' . $activity->logo . '">';
            })
            ->editColumn('public_offering', function ($activity) {
                return $activity->public_offering == 1 ? '是' : '否';
            })
            ->editColumn('sphere', function ($activity) {
                $sphere = explode(',', $activity->sphere);
                $arr = [];
                foreach ($sphere as $value) {
                    $arr[] = config('dataconfig.sphere_name')[$value];
                }
                return implode(',', $arr);
            })
            ->editColumn('status', function ($activity) {
                return $activity->status == 1 ?
                    '<span class="text-navy"><i class="fa fa-arrow-circle-up"></i> 已上线</span>'
                    : '<span class="text-warning"><i class="fa fa-arrow-circle-down"></i> 已下线</span>';
            })
            ->addColumn('action', function ($activity) {
                $update = '<a href="/admin/organization/update?id=' . $activity->id . '" class="btn btn-white btn-sm">
                <i class="fa fa-pencil"></i> 编辑 </a>';

                $destroy = '<a href="/admin/organization/destroy?id=' . $activity->id . '" class="btn btn-danger btn-sm del-confirm">
                <i class="fa fa-trash-o fa-lg"></i> 删除 </a>';

                $action = '';
                if (entrust('admin-organization-update')) {
                    $action .= $update;
                }
                if (entrust('admin-organization-destroy')) {
                    $action .= $destroy;
                }
                return $action;
            })
            ->addIndexColumn()
            ->rawColumns(['logo', 'action', 'status'])
            ->make();
    }

    public function store(Request $request)
    {
        if ($request->isMethod('post')) {
            $this->validate($request, [
                'logo' => 'required',
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
                'sphere' => 'required',
                'intro' => 'required',
                'public_offering' => 'required|in:0,1',
                'total_donation' => 'nullable|numeric|min:0',
                'total_times' => 'nullable|numeric|min:0',
                'mission' => 'required',
                'coverage' => 'required',
            ]);

            //如果具有公募资质,需要传证件
            if ($request->input('public_offering') == 1) {
                $this->validate($request, [
                    'donation_certificate' => 'required',
                    'certificate' => 'required'
                ]);
            }

            $input = $request->all();
            if ($request->hasFile('logo')) {
                $input['logo'] = $request->file('logo')->store('upload/img');
            }
            //保存负责人头像
            if ($request->hasFile('avatar')) {
                $input['avatar'] = $request->file('avatar')->store('upload/img');
            }else{
                $input['avatar'] = 'upload/img/OQwp7aC9pEPSGIBeaS2AuY1vgfink9SE4Bt4dzAJ.jpeg';
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
            if (empty($amap->geocodes[0]->location)) {
                return back()->with(['message' => "没有获取到地址坐标信息,请重新输入地址", 'status' => 1]);
            }
            $input['amap_coordinate'] = $amap->geocodes[0]->location ?? '';
            unset($input['organization_sphere']);
            $organizationSphere = $input['sphere'];
            $input['sphere'] = implode(',', $input['sphere']);
            if(empty($input['total_donation'])){
                $input['total_donation'] = 0;
            }
            if(empty($input['total_times'])){
                $input['total_times'] = 0;
            }
            $organization = Organization::create($input);

            OrganizationSphere::where(['organization_id' => $organization->id])->delete();
            foreach ($organizationSphere as $item) {
                $organizationSphere = [
                    'organization_id' => $organization->id,
                    'sphere' => $item
                ];
                OrganizationSphere::create($organizationSphere);
            }

            return redirect('/admin/organization')->with('message', '保存成功');

        } else {
            return view('admin.organization.form');
        }
    }

    public function update(Request $request)
    {
        $id = $request->input('id');
        if ($request->isMethod('post')) {
            $organization = Organization::find($id);
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
                'sphere' => 'required',
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
            if (empty($amap->geocodes[0]->location)) {
                return back()->with(['message' => "没有获取到地址坐标信息,请重新输入地址", 'status' => 1]);
            }
            $input['amap_coordinate'] = $amap->geocodes[0]->location ?? '';

            OrganizationSphere::where(['organization_id' => $id])->delete();
            foreach ($input['sphere'] as $item) {
                $organizationSphere = [
                    'organization_id' => $id,
                    'sphere' => $item
                ];
                OrganizationSphere::create($organizationSphere);
            }

            $input['sphere'] = implode(',', $input['sphere']);
            unset($input['_token']);
            if(empty($input['total_donation'])){
                $input['total_donation'] = 0;
            }
            if(empty($input['total_times'])){
                $input['total_times'] = 0;
            }
            Organization::where('id', '=', $id)->update($input);
            return redirect('/admin/organization')->with('message', '修改成功');

        } else {
            $organization = Organization::find($id);
            return view('admin.organization.form', compact('organization'));
        }
    }

    /**
     * 删除机构
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        Organization::find($request->input('id'))->delete();
        Alumnus::where(['organization_id' => $request->input('id')])->delete();
        Activity::where(['organization_id' => $request->input('id')])->delete();
        return redirect('/admin/organization')->with('message', '删除成功');
    }
}
