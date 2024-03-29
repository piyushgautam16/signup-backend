<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        // Retrieve users from session storage if needed
        $users = session('users', []);

        if (!empty($users)) {
            return response()->json([
                'success' => true,
                'users' => $users
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'No users were found'
            ], 404);
        }
    }

    public function signup(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:100',
            'email' => 'required|email|max:100',
            'password' => 'required|min:6',
            'phone' => 'required|string|max:20',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->messages()
            ], 400);
        } else {
            // Create user array
            $user = [
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone' => $request->phone
            ];

            // Store user data in session
            $users = session('users', []);
            $users[] = $user;
            session(['users' => $users]);

            return response()->json([
                'success' => true,
                'message' => 'Account has been created'
            ], 201);
        }
    }
}
