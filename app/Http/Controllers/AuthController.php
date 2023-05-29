<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\StoreUserRequest;
use App\Models\Department;
use App\Models\User;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    use HttpResponses;

    public function login(LoginRequest $request){
        $credentials = $request->only('email', 'password');
        
        if(!Auth::attempt($credentials)){
            
            return $this->error('', 'Credentials do not match', 401);
        }

        $user = User::where('email', $request->email)->first();
        
        return $this->success([
            'user' => $user,
            'token' => $user -> createToken('The Token of ' . $user->name)->plainTextToken
        ]);
    }

    public function register(StoreUserRequest $request){
        $request->validated($request -> all());
        $department = Department::where('dept_name', $request['department_name'])->first();
        $user = User::create([
            'department_id' => $department -> id,
            'name' => $request-> name,
            'email'=> $request-> email,
            'password'=> Hash::make($request-> password),
            'gender'=> $request-> gender,
            'user_role' => $request-> user_role,
            
        ]);

        return $this->success([
            'user' => $user,
            'token' => $user -> createToken('The Token of ' . $user->name)->plainTextToken
        ]);
    }

    public function logout(){
        Auth::user()->currentAccessToken()->delete();

        return $this->success([
            'message' => 'You have successfully been logged out'
        ]);
    }
}
