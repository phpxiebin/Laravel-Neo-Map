<?php

namespace App\Http\Controllers\Admin;

use App\Models\Activity;
use App\Models\Organization;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class ActivityController extends BaseController
{
    public function index()
    {
        $organization = Organization::all();
        return view('admin.activity.index', compact('organization'));
    }

    public function data(Request $request)
    {
        $activity = Activity::select([
            'id',
            'title',
            'digest',
            'organization_id',
            'cover',
            'type',
            'address',
            'begin_date',
            'mark',
            'province',
            'city',
            'status',
            'created_at'])
            ->with('organizationDetail');

        $datatables = DataTables::of($activity)
            ->filter(function ($query) use ($request) {
                if($request->filled('title')){
                    $query->where('title', 'like', "%{$request->input('title')}%");
                }
                if($request->filled('address')){
                    $query->where('address', 'like', "%{$request->input('address')}%");
                }
                if($request->filled('type')){
                    $query->where('type', $request->input('type'));
                }
                if($request->filled('province')){
                    $query->where('province', $request->input('province'));
                }
                if($request->filled('status')){
                    $query->where('status', $request->input('status'));
                }
                if($request->filled('organization')){
                    $query->where('organization_id', $request->input('organization'));
                }
                if($request->filled('begin_date')){
                    $query->where('begin_date', '>=', $request->input('begin_date'));
                }
                if($request->filled('end_date')){
                    $query->where('end_date', '<=', $request->input('end_date'));
                }
            });

        return $datatables
            ->addColumn('cover', function ($activity) {
                return '<img alt="image" style="width: 120px" src="'.$activity->cover.'" style="object-fit: cover">';
            })
            ->addColumn('organization', function ($activity) {
                return $activity->organizationDetail->name;
            })
            ->addColumn('type', function ($activity) {
                return  config('dataconfig.activity.type')[$activity->type];
            })
            ->addColumn('province', function ($activity) {
                return  config('dataconfig.province')[$activity->province];
            })
            ->addColumn('mark', function ($activity) {
                return $activity->mark?
                    '<a href="#" class="btn btn-success btn-sm">
                <i class="fa fa-level-up"></i> 已置顶 </a>':'<a href="#" class="btn btn-white btn-sm">
                <i class="fa fa-minus-circle"></i> 未置顶 </a>';
            })
            ->addColumn('action', function ($activity) {
                $update = '<a href="/admin/activity/update?id=' . $activity->id . '" class="btn btn-white btn-sm">
                <i class="fa fa-pencil"></i> 编辑 </a>';

                $destroy = '<a href="/admin/activity/destroy?id=' . $activity->id . '" class="btn btn-danger btn-sm del-confirm">
                <i class="fa fa-remove"></i> 删除 </a>';

                $action = '';
                if (entrust('admin-activity-update')) {
                    $action .= $update;
                }
                if (entrust('admin-activity-destroy')) {
                    $action .= $destroy;
                }
                return $action;
            })
            ->editColumn('status', function ($activity) {
                return $activity->status == 1 ?
                    '<span class="text-navy"><i class="fa fa-play"></i> 报名中</span>'
                    : '<span class="text-warning"><i class="fa fa-stop"></i> 已结束</span>';
            })
            ->addIndexColumn()
            ->rawColumns(['cover', 'action', 'status', 'mark'])
            ->make(true);
    }

    public function update(Request $request)
    {
        $id = $request->input('id');
        if ($request->isMethod('post')) {
            $this->validate($request, [
                'title' => 'required',
                'begin_date' => 'required',
                'end_date' => 'required',
                'province' => 'required',
//                'city' => 'required',
                'address' => 'required',
                'type' => 'required',
                'details' => 'required',
                'mark' => 'required',
                'status' => 'required',
                'display' => 'required',
            ]);
            $input = $request->only(
                [
                    'title', 'digest', 'begin_date', 'end_date',
                    'province', 'city', 'address', 'type', 'number_limitation',
                    'details', 'mark', 'display', 'status'
                ]
            );
            if ($request->hasFile('cover')) {
                $input['cover'] = $request->file('cover')->store('upload/img');
            }
            Activity::where('id', $id)->update(null_filter($input));
            return redirect('/admin/activity')->with('message', '修改成功');

        } else {
            $activity = Activity::find($id);
            return view('admin.activity.form', compact('activity'));
        }
    }
    
    /**
     * 删除活动
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        Activity::find($request->input('id'))->delete();
        return redirect('/admin/activity')->with('message', '删除成功');
    }
}
