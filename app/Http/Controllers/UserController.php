<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [

            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
    
        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
    
        $token=  $user->createToken('bearer_token')->plainTextToken;

        return response()->json(['message' => 'User created successfully', 'user'=>$user, 'token'=>$token], 200);
    }

   
    public function  login(Request $request)
    { 

        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }



        if (Auth::attempt(['email'=>$request->email,'password'=>$request->password])){

            $user = Auth::user();
            $token = $user->createToken('bearer_token')->plainTextToken;
        }
        else{
            return response()->json(['message'=>'invalid credentials', 'user'=>''], 400);
        }

       

        return response()->json(['message' => 'User login successful', 'user'=>$user, 'token'=>$token], 200);
    }
    
}
