<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login','signUp']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        
        $credentials = request(['email', 'password']);
      
        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['status' => false,'message' =>"Invalid Credentials"], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'data'=> $this->me()
        ]);
    }

    public function signUp(){
        $getData = request()->all();
        $ins['name'] =$getData['name']; 
        $ins['email'] =$getData['email']; 
        $ins['password'] =Hash::make($getData['password']); 
        $user =DB::table('users')->insert($ins); 
        if($user){
            $msg= "User registred Succesfully";
            $status= true;
            $code = 200;
        }else{
            $msg= "something went wrong.please try again later.";
            $status= false;
            $code=400;
        }
        return response()->json([
            'status' =>  $status,
            'msg'=>$msg
        ], $code);
    }
}