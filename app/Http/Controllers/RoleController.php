<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseController;
use DB;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends BaseController
{
    public function __construct()
    {
        $this->middleware('permission:role-list|role-create|role-edit|role-delete', ['only' => ['index', 'show']]);
        $this->middleware('permission:role-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:role-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:role-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        $roles = Role::latest()->get();

        $data['roles'] = $roles;
        return $this->sendResponse($data, 'Role information');
    }

    public function create()
    {
        $permissions = Permission::all();

        $data['permissions'] = $permissions;
        return $this->sendResponse($data, 'Permission information');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:roles,name',
            'permission' => 'required',
        ]);

        try {
            $role = new Role();
            $role->name = $request->name;

            if ($role->save()) {
                $role->syncPermissions($request->input('permission'));
                $data['role'] = $role;
                return $this->sendResponse($data, 'The role has been saved successfully');
            }
        } catch (\Exception $e) {
            return $this->sendError('Process error', $e->getMessage());
        }
    }

    public function show($id)
    {
        $role = Role::find($id);
        $rolePermissions = Permission::join("role_has_permissions", "role_has_permissions.permission_id", "=", "permissions.id")
            ->where("role_has_permissions.role_id", $id)
            ->get();

        $data['role'] = $role;
        $data['rolePermissions'] = $rolePermissions;
        return $this->sendResponse($data, 'Role information');
    }

    public function edit($id)
    {
        $role = Role::find($id);
        $permissions = Permission::get();
        $rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id", $id)
            ->pluck('role_has_permissions.permission_id', 'role_has_permissions.permission_id')
            ->all();

        $data['role'] = $role;
        $data['permissions'] = $permissions;
        $data['rolePermissions'] = $rolePermissions;
        return $this->sendResponse($data, 'Role information');
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required|unique:roles,name,' . $id,
            'permission' => 'required',
        ]);

        try {
            $role = Role::findOrFail($id);
            $role->name = $request->name;

            if ($role->save()) {
                $role->syncPermissions($request->input('permission'));
                $data['role'] = $role;
                return $this->sendResponse($data, 'The role has been updated successfully');
            }
        } catch (\Exception $e) {
            return $this->sendError('Process error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $role = Role::findOrFail($id);

            if ($role->delete()) {
                $data['role'] = $role;
                return $this->sendResponse($data, 'The role has been deleted successfully');
            }
        } catch (\Exception $e) {
            return $this->sendError('Process error', $e->getMessage());
        }
    }
}
