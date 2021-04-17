<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends BaseController
{
    public function __construct()
    {
        $this->middleware('permission:permission-list|permission-create|permission-edit|permission-delete', ['only' => ['index', 'show']]);
        $this->middleware('permission:permission-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:permission-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:permission-delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $permissions = Permission::all();

        $data['permissions'] = $permissions;
        return $this->sendResponse($data, 'Permission information');
    }

    public function create()
    {
        $data = array();
        return $this->sendResponse($data, 'Permission information');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:permissions,name',
        ]);

        try {
            $permission = new Permission();
            $permission->name = $request->input('name');

            if ($permission->save()) {
                $data['permission'] = $permission;
                return $this->sendResponse($data, 'The permission has been saved successfully');
            }
        } catch (\Exception $e) {
            return $this->sendError('Process error', $e->getMessage());
        }
    }

    public function show($id)
    {
        $permission = Permission::find($id);

        $data['permission'] = $permission;
        return $this->sendResponse($data, 'Permission information');
    }

    public function edit($id)
    {
        $permission = Permission::find($id);

        $data['permission'] = $permission;
        return $this->sendResponse($data, 'Permission information');
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required|unique:permissions,name,' . $id,
        ]);

        try {
            $permission = Permission::findOrFail($id);
            $permission->name = $request->input('name');

            if ($permission->save()) {
                $data['permission'] = $permission;
                return $this->sendResponse($data, 'The permission has been updated successfully');
            }
        } catch (\Exception $e) {
            return $this->sendError('Process error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $permission = Permission::findOrFail($id);

            if ($permission->delete()) {
                $data['permission'] = $permission;
                return $this->sendResponse($data, 'The permission has been deleted successfully');
            }
        } catch (\Exception $e) {
            return $this->sendError('Process error', $e->getMessage());
        }
    }
}
