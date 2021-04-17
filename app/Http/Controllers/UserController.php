<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseController;
use App\Models\User;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Spatie\Permission\Models\Role;

class UserController extends BaseController
{
    public function __construct()
    {
        $this->middleware('permission:user-list|user-create|user-edit|user-delete', ['only' => ['index', 'show']]);
        $this->middleware('permission:user-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:user-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:user-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        $users = User::latest()->get();
        $data = array();
        foreach ($users as $user) {
            $user->roles = $user->getRoleNames();
            $data[] = $user;
        }
        return $this->sendResponse($data, 'User information');
    }

    public function create()
    {
        $data['roles'] = Role::pluck('name', 'name')->all();
        return $this->sendResponse($data, 'User information');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|same:confirm-password',
            'roles' => 'required',
        ]);

        $input = $request->all();
        $input['password'] = app('hash')->make($input['password']);

        try {
            $user = new User($input);

            if ($user->save()) {
                $user->assignRole($request->input('roles'));
                $data['user'] = $user;
                return $this->sendResponse($data, 'The user has been saved successfully');
            }
        } catch (\Exception $e) {
            return $this->sendError('Process error', $e->getMessage());
        }
    }

    public function show($id)
    {
        $user = User::find($id);
        $user->roles = $user->getRoleNames();

        $data['user'] = $user;
        return $this->sendResponse($data, 'User information');
    }

    public function edit($id)
    {
        $user = User::find($id);
        $roles = Role::pluck('name', 'name')->all();
        $userRole = $user->roles->pluck('name', 'name')->all();

        $data['user'] = $user;
        $data['roles'] = $roles;
        $data['userRole'] = $userRole;
        return $this->sendResponse($data, 'User information');
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'same:confirm-password',
            'roles' => 'required',
        ]);

        $input = $request->all();
        if (!empty($input['password'])) {
            $input['password'] = app('hash')->make($input['password']);
        } else {
            $input = Arr::except($input, array('password'));
        }

        try {
            $user = User::findOrFail($id);

            if ($user->update($input)) {
                DB::table('model_has_roles')->where('model_id', $id)->delete();
                $user->assignRole($request->input('roles'));

                $data['user'] = $user;
                return $this->sendResponse($data, 'The user has been updated successfully');
            }
        } catch (\Exception $e) {
            return $this->sendError('Process error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);

            if ($user->delete()) {
                $data['user'] = $user;
                return $this->sendResponse($data, 'The user has been deleted successfully');
            }
        } catch (\Exception $e) {
            return $this->sendError('Process error', $e->getMessage());
        }
    }
}
