<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    //register user
    public function register(Request $request)
    {

        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|string',
            'password_repeat' => 'required|string',
        ]);

        if ($request->password !== $request->password_repeat) return response()->json([
            "message" => "Password Repeat Does Not Match!!"
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json([
            "message" => "User Registered Successfully",
            "data" => $user
        ]);
    }

    // login user
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string'
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            return response()->json([
                "message" => "Logged In"
            ]);
        } else {
            return response()->json([
                "message" => "Invalid Login credentials"
            ]);
        };
    }

    public function update(Request $request,$id)
    {
        $user = User::find($id);

        if ($user) {
            $update = $user->update($request->all());
            return response()->json([
                "message" => "User Datails Updated",
                "data" => $update
            ]);
        } else {
            return response()->json([
                "message" => "Unable to update user details"
            ]);
        }
    }

    public function delete($id)
    {
        $user = User::find($id);
        if ($user) {
            $user->delete();
            return response()->json([
                "message" => "User Deleted"
            ]);
        } else {
            return response()->json([
                "message" => "Unable to deleted User"
            ]);
        }
    }
}
