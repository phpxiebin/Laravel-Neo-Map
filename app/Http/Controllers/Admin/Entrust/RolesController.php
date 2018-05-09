<?php namespace App\Http\Controllers\Admin\Entrust;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\PermissionRole;
use App\Models\Role;
use DB;
use Illuminate\Http\Request;
use Validator;

class RolesController extends Controller
{
    public function index()
    {
        $roles = Role::paginate(10);
        return view('admin.entrust.roles.index', compact('roles'));
    }

    public function create()
    {
        return view('admin.entrust.roles.create');
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

        $iex = Role::where("name", "=", $request["name"])->exists();
        if (!empty($iex)) {
            return redirect('/admin/entrust/roles/create')->with('message', '重复的名称');
        }

        try {
            Role::create($request);
        } catch (Exception $e) {
            return back()->withErrors($e);
        }
        return redirect('/admin/entrust/roles')->with('message', '保存成功');
    }

    public function edit()
    {
        $id = request()->input("id");
        $rules = [
            'id' => 'numeric',
        ];
        $messages = [
            'id.numeric' => '非法ID',
        ];
        $validator = Validator::make([$id], $rules, $messages);
        if ($validator->fails()) {
            return back()->withErrors($validator);
        }
        $role = Role::find((int) $id);

        //get permission_ids of the role
        $rolePermissions = (array) PermissionRole::where("role_id", "=", $id)->select("permission_id")->get()->toArray();
        $permissionIDs = array_column($rolePermissions, "permission_id");

        //all permission
        $permissions = Permission::all()->toArray();
        $datas = [];
        foreach ($permissions as $key => $p) {
            $bool =
            $datas[$p['display_name']][] = [
                'id' => $p['id'],
                'name' => $p['name'],
                'display_name' => $p['display_name'],
                'description' => $p['description'],
                'isset' => (int) in_array($p['id'], $permissionIDs),
            ];
        }

        return view('admin.entrust.roles.edit', compact('role', 'datas'));
    }

    public function update()
    {
        $request = request()->all();
        $role = Role::find((int) $request['id']);

        $rules = [
            'id' => 'numeric',
        ];
        $messages = [
            'id.numeric' => '非法ID',
        ];
        $validator = Validator::make($request, $rules, $messages);
        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        $role->update([
            'name' => $request['name'],
            'display_name' => $request['display_name'],
            'description' => $request['description'],
        ]);

        $rolePermissions = (array) PermissionRole::where("role_id", "=", $role->id)->select("permission_id")->get()->toArray();
        $permissionIDs = array_column($rolePermissions, "permission_id");
        $insert = array_diff($request['permission_role'], $permissionIDs);
        $delete = array_diff($permissionIDs, $request['permission_role']);
        try {
            DB::beginTransaction();
            foreach ($insert as $i) {
                PermissionRole::create([
                    "permission_id" => $i,
                    "role_id" => $role->id,
                ]);
            }
            foreach ($delete as $d) {
                PermissionRole::where('role_id', '=', $role->id)->where("permission_id", "=", $d)->delete();
            }
        } catch (Exception $e) {
            DB::rollBack();
        }
        DB::commit();
        return redirect('/admin/entrust/roles')->with('message', '修改成功');
    }

    public function destroy()
    {
        $id = request()->input("id");
        $role = Role::find((int) $id);
        $rules = [
            'id' => 'numeric',
        ];
        $messages = [
            'id.numeric' => '非法ID',
        ];
        $validator = Validator::make([$id], $rules, $messages);
        if ($validator->fails()) {
            return back()->withErrors($validator);
        }
        Role::where('id', '=', (int) $id)->delete();
        return redirect('/admin/entrust/roles')->with('message', '删除成功');
    }
}
