<?php

namespace App\Http\Controllers\Admin;

use App\Models\Alumnus;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\Datatables\Datatables;
use Excel;

class AlumnusController extends BaseController
{
    public function index()
    {
        $organization = Organization::all();
        return view('admin.alumnus.index', compact('organization'));
    }

    public function data(Request $request)
    {
        $activity = Alumnus::select([
            'alumnus.id',
            'organization_id',
            'alumnus.name',
            'alumnus.identity',
            'organization.name as organization_name',
            'class',
            'stud_no',
            'alumnus.phone',
            'alumnus.status',
            'alumnus.created_at'])
            ->join('organization',function($join) {
                $join->on('organization.id', '=', 'alumnus.organization_id');
            });
        ;
        if($request->filled('organization_name')){
            $activity->where('organization.id', '=', $request->input('organization_name'));
        }
        if($request->filled('public_offering')){
            $activity->where('organization.public_offering', '=', $request->input('public_offering'));
        }
        if($request->filled('sphere')){
            $activity->where('organization.sphere', 'like', "%{$request->input('sphere')}%");
        }
        if($request->filled('stud_no')){
            $activity->where('stud_no', 'like', "%{$request->input('stud_no')}%");
        }

        $datatables = Datatables::of($activity)

            ->filter(function ($query) use ($request) {
                if($request->filled('name')){
                    $query->where('alumnus.name', 'like', "%{$request->input('name')}%");
                }
                if($request->filled('class')){
                    $query->where('class', $request->input('class'));
                }
                if($request->filled('status')){
                    $query->where('alumnus.status', $request->input('status'));
                }
            });

        return $datatables
            ->editColumn('status', function ($alumnus) {
                return $alumnus->status == 1 ?
                    '<span class="text-navy"><i class="fa fa-unlock"></i> 已激活</span>'
                    : '<span class="text-warning"><i class="fa fa-lock"></i> 未激活</span>';
            })
            ->editColumn('identity', function ($alumnus) {
                return  config('dataconfig.organization_identity')[$alumnus->identity];
            })
            ->editColumn('class', function ($alumnus) {
                return  config('dataconfig.alumnus_class')[$alumnus->class];
            })
            ->editColumn('organization_detail.sphere', function ($alumnus) {
                $organization = $alumnus->organizationDetail;
                $sphere = explode(',',$organization->sphere);
                $arr = [];

                foreach ($sphere as $value) {
                    $arr[] = config('dataconfig.sphere_name')[$value];
                }
                return implode(';', $arr);
            })
            ->editColumn('organization_detail.public_offering', function ($alumnus) {
                    return $alumnus->organizationDetail->public_offering == 1 ? '有' : '否';
            })
            ->addColumn('action', function ($alumnus) {
                $update = '<a href="/admin/alumnus/update?id=' . $alumnus->id . '" class="btn btn-white btn-sm">
                <i class="fa fa-pencil"></i> 编辑 </a>';

                $destroy = '<a href="/admin/alumnus/destroy?id=' . $alumnus->id . '" class="btn btn-danger btn-sm del-confirm">
                <i class="fa fa-trash-o fa-lg"></i> 删除 </a>';

                $action = '';
                if (entrust('admin-alumnus-update')) {
                    $action .= $update;
                }
                if (entrust('admin-alumnus-destroy')) {
                    $action .= $destroy;
                }
                return $action;
            })
            ->addIndexColumn()
            ->rawColumns(['status', 'action'])
            ->make();
    }

    public function store(Request $request)
    {
        if ($request->isMethod('post')) {
            $this->validate($request, [
                'name' => 'required|max:9',
                'phone' => 'required',
                'identity' => 'required|max:20',
                'class' => 'required|max:30',
                'password' => 'required|between:6,20|confirmed',
                'password_confirmation' => 'required|between:6,20',
                'stud_no' => 'required',
            ]);
            $input = [
                'name' => $request->input('name'),
                'phone' => $request->input('phone'),
                'identity' => $request->input('identity'),
                'organization_id' => $request->input('organization_id'),
                'class' => $request->input('class'),
                'stud_no' => $request->input('stud_no')
            ];
            $salt = genSalt();
            $input['salt'] = $salt;
            $input['password'] = bcrypt($request->input('password') . $salt);
            Alumnus::create($input);
            return redirect('/admin/alumnus')->with('message', '添加成功');
        } else {
            $organization = Organization::all();
            return view('admin.alumnus.form',compact('organization'));
        }
    }

    public function update(Request $request)
    {
        $id = $request->input('id');
        if ($request->isMethod('post')) {
            $alumnus = $this->alumnus_detail($id);
            $this->validate($request, [
                'name' => 'required|max:9',
                'phone' => 'required',
                'identity' => 'required|max:20',
                'class' => 'required|max:30',
                'stud_no' => 'required',
            ]);
            $input = [
                'name' => $request->input('name'),
                'phone' => $request->input('phone'),
                'identity' => $request->input('identity'),
                'organization_id' => $request->input('organization_id'),
                'class' => $request->input('class'),
                'stud_no' => $request->input('stud_no')
            ];
            //如果修改密码
            if ($request->input('isedited', 0) == 1) {
                $data = $request->all();
                $rules = [
                    'password' => 'required|between:6,20|confirmed',
                    'password_confirmation' => 'required|between:6,20',
                ];
                $messages = [
                    'between' => '密码必须是6~20位之间',
                    'confirmed' => '新密码和确认密码不匹配'
                ];
                $validator = Validator::make($data, $rules, $messages);
                if ($validator->fails()) {
                    return back()->withErrors($validator);
                }

                $salt = genSalt();
                $input['salt'] = $salt;
                $input['password'] = bcrypt($request->input('password') . $salt);
            }
            $alumnus->update($input);
            return redirect('/admin/alumnus')->with('message', '添加成功');
        } else {
            $organization = Organization::all();
            $alumnus = Alumnus::find($id);
            return view('admin.alumnus.form',compact('organization','alumnus'));
        }
    }
    /**
     * 删除校友
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        Alumnus::find($request->input('id'))->delete();
        return redirect('/admin/alumnus')->with('message', '删除成功');
    }

    /**
     * 导入Excel
     */
    public function import(Request $request){

//        return redirect('/admin/alumnus')->with('message', '导入成功');
        $validator = Validator::make($request->all(), [
            'filedata' => 'required|max:10240',
        ]);
        if ($validator->fails()) {
            $errMsgArr = $validator->getMessageBag()->toArray();
            return [
                'respCode' => trans('code.err.code'),
                'respMsg'  => current(current($errMsgArr)),
            ];
        }
        $file = $request->file('filedata');

        $res = [];
        Excel::load($file, function($reader) use( &$res ) {
            $reader = $reader->getSheet(0);
            $res = $reader->toArray();
        });
        //表格模板
        $template = [
            '学号',
            '姓名',
            '成绩'
        ];
        sort($template);
        sort($res[0]);
        if ($template != $res[0] ) {
            return [
                'respCode' => trans('code.upload_err.code'),
                'respMsg'  => '表格格式错误',
            ];
        }

        return jsonResponse('suc');
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
}
