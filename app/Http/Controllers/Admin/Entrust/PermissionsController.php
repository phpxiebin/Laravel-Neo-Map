<?php namespace App\Http\Controllers\Admin\Entrust;

use App\Http\Controllers\Admin\BaseController;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PermissionsController extends BaseController
{
    private $role;

    private $permission;

    public function index()
    {
        $permissions = Permission::paginate(10);
        return view('admin.entrust.permissions.index', compact('permissions'));
    }

    public function create()
    {
        return view('admin.entrust.permissions.create');
    }

    public function store()
    {
        $request = request()->all();
        $rules = [
            'name' => 'required',
            'display_name' => 'required',
        ];
        $messages = [
            'name.required' => '名称不能为空',
            'display_name.required' => '展示名称不能为空',
        ];
        $validator = Validator::make($request, $rules, $messages);
        if ($validator->fails()) {
            return back()->withErrors($validator);
        }
        Permission::create($request);
        return redirect('/admin/entrust/permissions')->with('message', '保存成功');
    }

    public function edit()
    {
        $id = request()->input("id");
        $permission = Permission::find($id);
        return view('admin.entrust.permissions.edit', compact('permission'));
    }

    public function update()
    {
        $request = request()->all();
        $permission = Permission::find($request['id']);
        $permission->update($request);
        return redirect('/admin/entrust/permissions')->with('message', '修改成功');
    }

    public function destroy()
    {
        $id = request()->input("id");

        Permission::where('id', '=', $id)->delete();
        return redirect('/admin/entrust/permissions')->with('message', '删除成功');
    }
}
