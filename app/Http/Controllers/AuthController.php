<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;

class AuthController extends BaseController
{
    /*public function register(Request $request)
    {
    $name = $request->name;
    $email = $request->email;
    $password = $request->password;

    // Check if field is empty
    if (empty($name) or empty($email) or empty($password)) {
    return response()->json(['status' => 'error', 'message' => 'You must fill all the fields']);
    }

    // Check if email is valid
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return response()->json(['status' => 'error', 'message' => 'You must enter a valid email']);
    }

    // Check if password is greater than 5 character
    if (strlen($password) < 6) {
    return response()->json(['status' => 'error', 'message' => 'Password should be min 6 character']);
    }

    // Check if user already exist
    if (User::where('email', '=', $email)->exists()) {
    return response()->json(['status' => 'error', 'message' => 'User already exists with this email']);
    }

    // Create new user
    try {
    $user = new User();
    $user->name = $request->name;
    $user->email = $request->email;
    $user->password = app('hash')->make($request->password);

    if ($user->save()) {
    return $this->login($request);
    }
    } catch (\Exception $e) {
    return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
    }
    }*/

    public function login(Request $request)
    {
        $email = $request->email;
        $password = $request->password;

        // Check if field is empty
        if (empty($email) or empty($password)) {
            return $this->sendError('You must fill all the fields');
        }

        $credentials = request(['email', 'password']);

        if (!$token = auth()->attempt($credentials)) {
            return $this->sendError('Unauthorized', [], 401);
        }

        $user = auth()->user();
        $user->roles = $user->getRoleNames();
        $user->permissions = $user->getPermissionNames();

        $data['token'] = $token;
        $data['token_type'] = 'bearer';
        $data['expires_in'] = auth()->factory()->getTTL() * 60;
        $data['user'] = $user;
        return $this->sendResponse($data, 'User logged in successfully');
    }

    public function logout()
    {
        auth()->logout();

        return $this->sendResponse([], 'User logged out successfully');
    }
}
