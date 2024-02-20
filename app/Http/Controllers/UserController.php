<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        
        if ($users->count() > 0) {
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
            'email' => 'required|email|max:100|unique:users',
            'password' => 'required|min:6',
            'phone' => 'required|string|max:20',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->messages()
            ], 400);
        } else {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone' => $request->phone
            ]);

            if ($user) {
                return response()->json([
                    'success' => true,
                    'message' => 'Account has been created'
                ], 201);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Something went wrong'
                ], 400);
            }
        }
    }
}
