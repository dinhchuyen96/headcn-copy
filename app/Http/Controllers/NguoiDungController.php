<?php

namespace App\Http\Controllers;

use App\Models\RoleHasPermisson;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use App\Models\Role;
use Spatie\Permission\Models\Permission;
class NguoiDungController extends Controller {


    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request) {
        $query = User::query();
        if($request->UserName){
            $query->where('users.name','like','%'.trim($request->UserName).'%');
        }
        if($request->Email){
            $query->where('users.email','like','%'.trim($request->Email).'%');
        }
        if($request->username){
            $query->where('users.username','like','%'.trim($request->username).'%');
        }
        $users = $query->get();
        return view('nguoidung.index', compact('users'));
    }


    public function create() {
        $roles = Role::pluck('name','id');
        return view('nguoidung.create', compact('roles'));
    }


    public function store(Request $request) {
        $this->validate($request, [
            'name' => 'required',
            'username' => ['required', 'string', 'regex:/^\S*$/u',
             Rule::unique('users', 'username')->ignore($request->id)->whereNull('deleted_at')],
            'email' => ['required',
            Rule::unique('users', 'email')->ignore($request->id)->whereNull('deleted_at')],
            'password' => 'required',
            'password_confirm'=> 'required_with:password|same:password',
            'roles'=>'required'
        ], [
            'username.required' => 'Tên tài khoản là bắt buộc',
            'username.regex'=>'tài khoản không được chứa dấu cách',
            'username.unique' => 'Tên tài khoản đã tồn tại',
            'name.required' => 'Tên người dùng bắt buộc',
            'email.required' => 'Email bắt buộc',
            'head_name.required'=>'Head nhận xe bắt buộc',
            'email.unique' => 'Email đã tồn tại',
            'password.required' => 'Mật khẩu bắt buộc',
            'roles.required'=>'Người dùng phải có tối thiếu 1 quyền',
            'password_confirm.same' => 'Mật khẩu xác nhận phải giống mật khẩu',
            'password_confirm.required_with' => 'Mật khẩu xác nhận bắt buộc',
        ], []);
        // dd($request->roles);
        $user = User::create([
            'username' => $request->username,
            'name' =>  $request->name,
            'email' => $request->email,
            'email_verified_at' => now(),
            'password' => bcrypt($request->password),
            'positions' => ($request->positions)
        ]);
        $user->assignRole($request->input('roles'));
        $user->syncRoles($request->roles);
        $rolePermissions = RoleHasPermisson::whereIn('role_id', $request->roles)->get()->pluck('permission_id')->unique()->toArray();
        $user->permissions()->sync($rolePermissions);
        $request->session()->put('success', 'Thêm mới người dùng thành công');
        return redirect()->route('nguoiDung.index');
    }


    public function show($slug) {
        $data = $this->product->getProductBySlug($slug, true);
        return view('products.show', $data);
    }

    public function edit($id) {
        $data = User::findOrFail($id);
        // dd($data);
        $roles = Role::pluck('name','id')->toArray();
        $rolesUser = DB::table('model_has_roles')->where('model_id',$id)->pluck('role_id')->toArray();
        return view('nguoidung.edit', compact('data','roles','rolesUser'));
    }

    public function update(Request $request, $id) {

        $passwordOld = User::find($id)->password;
        // $roleUser =User::where('id',$id)->update([
        //     'name' =>  $request->name,
        //     'password' => $request->password_new?bcrypt($request->password_new):$passwordOld,
        //     'positions' => $request->positions
        // ]);
        $roleUser = User::where('id',$id)->update([
            'name' =>  $request->name,
            'password' => $request->password_new?bcrypt($request->password_new):$passwordOld,
            'positions' => ($request->positions)
        ]);
        $this->validate($request, [
            'name' => 'required',
            'roles'=>'required',
            // 'email' => ['required',
            // Rule::unique('users', 'email')->ignore($roleUser->id ?? '')->whereNull('deleted_at')],
        ], [
            'name.required' => 'Tên người dùng bắt buộc',
            'head_name.required'=>'Head nhận xe bắt buộc',
            'roles.required'=>'Người dùng phải có tối thiếu 1 quyền',
        ], []);
        $roleUser = User::find($id);
        $roleUser->syncRoles($request->roles);
        $rolePermissions = RoleHasPermisson::whereIn('role_id', $request->roles)->get()->pluck('permission_id')->unique()->toArray();
        $roleUser->permissions()->sync($rolePermissions);
        $request->session()->put('success', 'Chỉnh sửa người dùng thành công');
        return redirect()->route('nguoiDung.index');
    }

    public function updateRole(Request $request)
    {
        $user = User::find($request->id);
        if(!empty($request->role_name)) {
            $user->syncRoles($request->role_name);
        }
        else DB::table('model_has_roles')->where('model_id',$request->id)->delete();
        $request->session()->put('success', 'Cập nhật quyền thành công');
        return back();
    }
}
