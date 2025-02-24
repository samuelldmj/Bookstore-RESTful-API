<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Sanctum\HasApiTokens;

class AuthController extends Controller
{
    //

    use HasApiTokens;
    public function register(Request $request){

        //Basic validation

        $validator = Validator::make($request->all(),[
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|string|in:admin,user',
        ]);

        //if validation fails
        if($validator->fails()){
            return response()->json(['error' => $validator->errors(), 422]);
        }

        
        //create new user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role
        ]);

        return response()->json(['token' => $user->createToken('auth_token')->plainTextToken, 'message' => 'User created Successfully'], 201); 

    }


    public function login(Request $request){

        //Basic validation
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:8',
        ]);


          //if validation fails
          if($validator->fails()){
            return response()->json(['error' => $validator->errors(), 422]);
        }


        //find the user

        $user = User::where('email', $request->email)->first();

        //if the user does not exist, return an error response.
        if(!$user || !Hash::check($request->password, $user->password)){
            return response()->json([
                'message' => 'Invalid credentials'], 401);
        }

        //generate a token for the user
        return response()->json(['token' => $user->createToken('auth_token')->plainTextToken, "message" => 'User successfully logged in'], 200);


    }

    public function logout(Request $request){
        //Revoke the token for the user
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'User logged out successfully'], 200);
    }
}