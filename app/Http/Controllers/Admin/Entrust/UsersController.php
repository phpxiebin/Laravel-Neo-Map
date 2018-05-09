<?php namespace App\Http\Controllers\Admin\Entrust;

use App\Http\Controllers\Controller;
use App\Models\AdminUser;
use App\Models\Role;
use App\Models\RoleUser;
use Validator;

class UsersController extends Controller
{
    public function index()
    {
        $adminUsers = AdminUser::leftJoin("role_user as r", "admin_users.id", "=", "r.user_id")
            ->leftJoin("roles", "r.role_id", "=", "roles.id")
            ->select("admin_users.id", "admin_users.name", "r.role_id", "roles.name as role_name", "roles.display_name")
            ->paginate(10);

        return view('admin.entrust.users.index', compact('adminUsers'));
    }

    public function create()
    {
        return view('admin.entrust.users.create');
    }

    public function store()
    {
        $request = request()->all();
        $rules = [
            'name' => 'required',
            'password' => 'required',
        ];
        $messages = [
            'name.required' => '用户名不能为空',
            'password.required' => '密码不能为空',
        ];
        $validator = Validator::make($request, $rules, $messages);
        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        $salt = mb_substr(md5(time()), 0, 22);

        AdminUser::create([
            'name' => trim($request['name']),
            'password' => bcrypt(trim($request['password']) . $salt),
            'salt' => $salt,
        ]);

        return redirect('/admin/entrust/users')->with('message', '添加成功');
    }

    public function edit()
    {
        $id = request()->input("id");
        $adminUser = AdminUser::leftJoin("role_user as r", "admin_users.id", "=", "r.user_id")
            ->leftJoin("roles", "r.role_id", "=", "roles.id")
            ->select("admin_users.id", "admin_users.name", "r.role_id", "roles.name as role_name", "roles.display_name")
            ->where("admin_users.id", "=", $id)
            ->first();

        $roles = Role::all();
        return view('admin.entrust.users.edit', compact('adminUser', 'roles'));
    }

    public function update()
    {
        $request = request()->all();

        RoleUser::updateOrCreate([
            'user_id' => $request['id'],
        ], [
            'role_id' => $request['role_id'],
        ]);
        return redirect('/admin/entrust/users')->with('message', '修改成功');
    }

    public function destroy()
    {
        $id = request()->input("id");
        RoleUser::where('user_id', '=', $id)->delete();
        return redirect('/admin/entrust/users')->with('message', '删除成功');
    }
}
