<?php

namespace App\Http\Controllers\api;

use App\Helpers\ResponseBuilder;
use App\Http\Controllers\Controller;
use App\Http\Requests\auth\LoginRequest;
use App\Http\Requests\auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\support\Facades\Log;
use Illuminate\support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    public function signup(RegisterRequest $request)
    {
        try {
            DB::beginTransaction();

            $user = User::create([
                'name'=>$request->name,
                'email'=>$request->email,
                'password'=>Hash::make($request->password)
            ]);

            $token = $user->createToken('authToken')->accessToken;
            DB::commit();
            $this->response = new UserResource($user);
            return ResponseBuilder::successWithToken($token, $this->response, 'Login Successfully');
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollback();

            return ResponseBuilder::error("error", $this->errorStatus);
        }
    }

    public function login(LoginRequest $request)
    {
        try {

            $data = $request->only(['email', 'password']);
            if (auth()->attempt($data)) {
                $token = auth()->user()->createToken('authToken')->accessToken;
                // $this->response = new UserResource($request->user('api'));
                $this->response = new UserResource(auth()->user());
//
                return ResponseBuilder::successWithToken($token, $this->response, "Login Successfully");
            }
        } catch (\Exception $e) {
            Log::error($e);
            return ResponseBuilder::error($e, $this->errorStatus);
        }
    }


    public function logout(Request $request)
    {
        $request->user('api')->token()->revoke();
        return ResponseBuilder::success(null,"Logout Successfully");
    }
}
