<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use App\Models\Role;
use App\Models\RoleHasPermisson;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
class RoleController extends Controller
{
    //
    public function index() {
        return view('role.index');
    }

    public function create() {
        $rolePermissions = Permission::pluck('roles_name','id')->toArray();
        $permissions = [];

        return view('role.create', compact('rolePermissions', 'permissions'));
    }

    public function store(Request $request) {
        $this->validate($request, [
            'name' => 'required',
            'permissions' => 'required',
            'percentage' => 'required'
        ], [
            'name.required' => 'Tên vai trò bắt buộc',
            'permissions.required' => 'Quyền bắt buộc',
            'percentage.required' => 'Phần trăm hoa hồng là bắt buộc'
        ], []);
        $role = Role::create([
            'name' => $request->name,
            'guard_name' => 'web',
            'percentage' =>  $request->percentage
        ]);
        // dd($request->permissions);
        if (!empty($request->permissions)) {
            foreach ($request->permissions as $val) {
                RoleHasPermisson::create([
                    'role_id' => $role->id,
                    'permission_id' => $val,
                ]);
            }
        }

        return redirect()->route('roles.index')->with('success','Thêm mới vai trò thành công');
    }

    public function edit($id) {
        $data = Role::find($id);
        $rolePermissions = Permission::pluck('roles_name','id')->toArray();
        $permissions = $this->getRolePermissions($id);
        // $permissions = DB::table('role_has_permissions')->where('role_id',$id)->pluck('permission_id')->toArray();
        return view('role.edit', compact('data', 'permissions', 'rolePermissions'));
    }

    public function update($id, Request $request) {
        $this->validate($request, [
            'name' => 'required',
            'permissions' => 'required',
            'percentage' => 'required'
        ], [
            'name.required' => 'Tên vai trò bắt buộc',
            'permissions.required' => 'Quyền bắt buộc',
            'percentage.required' => 'Phần trăm hoa hồng là bắt buộc'
        ], []);
        $role = Role::findOrFail($id);
        $role->name = $request->name;
        $permissions = $request->permissions;
        $role->percentage = $request->percentage;
        // dd($permissions);
        // $arrPer = [];
        // if (!empty($permissions)) {
        //     foreach ($permissions as $key => $val) {
        //         $arrPer[] = $key;

        //     }
        // }
        // dd($arrPer);
        $role->syncPermissions($permissions);
        $role->save();
        // dd($request);
        // if (!empty($request->permissions)) {
        //     foreach ($request->permissions as $val) {
        //         RoleHasPermisson::where('role_id', $id)->delete();
        //     }
        //     dd($request->permissions);
        //     $role->givePermissionTo($request->permissions);
        //     // foreach ($request->permissions as $val) {
        //     //     RoleHasPermisson::create([
        //     //         'role_id' => $id,
        //     //         'permission_id' => $val,
        //     //     ]);
        //     // }
        // }

        return redirect()->route('roles.index')->with('success','Cập nhật vai trò thành công');
    }

    function getRolePermissions ($idRole) {
        $rolePermissions = RoleHasPermisson::where('role_has_permissions.role_id', $idRole)
            ->pluck('role_has_permissions.permission_id','role_has_permissions.permission_id')
            ->all();

        $dataPermission = [];
        foreach ($rolePermissions as $permission) {
            $dataPermission[] = $permission;
        }

        return $dataPermission;
    }
}
