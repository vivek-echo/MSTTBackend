<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendRegisterOtp;
use App\Mail\LoginOtp;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'signUp', 'checkUser','validateRegisterOtp', 'validateOtp', 'sendRegisterOtp', 'sendregotp']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $credentials = request(['email', 'password']);
        if (!$token = auth()->attempt($credentials)) {
            return response()->json(['status' => false, 'message' => "Invalid Credentials"], 401);
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
        DB::table('users')->where('id', Auth::user()->id)->update([
            'otp' => null
        ]);

        $loginData['id'] = Crypt::encryptString(Auth::user()->id);
        $loginData['firstname'] = Auth::user()->firstname;
        $loginData['lastName'] = Auth::user()->lastName;
        $loginData['memberType'] = Auth::user()->memberType;
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'data' =>  $loginData
        ]);
    }

    public function signUp()
    {

        $getData = request()->all();


        $ins['memberType'] = $getData['memeberType'];
        $ins['firstname'] = $getData['firstName'];
        $ins['lastname'] = $getData['lastName'];
        $ins['email'] = $getData['email'];
        $ins['shopname'] = $getData['shopName'];
        $ins['dob'] = $getData['dob'];
        $ins['mobile'] = $getData['mobileNo'];
        $ins['address'] = $getData['address'];
        $ins['gender'] = $getData['gender'];
        $ins['password'] = Hash::make($getData['password']);
        $user = DB::table('users')->insert($ins);
        if ($user) {
            $msg = "User registred Succesfully";
            $statusResponse = true;
            $statusCode = 200;
            $status = 'SUCCESS';
        } else {
            $msg = "something went wrong.please try again later.";
            $statusResponse = false;
            $statusCode = 400;
            $status = 'ERROR';
        }
        return response()->json([
            'statusCode' => $statusCode,
            'statusResponse' => $statusResponse,
            'status' => $status,
            'msg' => $msg
        ], $statusCode);
    }

    public function checkUser()
    {
        $status = '';
        $msg = "";
        $otp = '';
        // dd("vjnhnh");
        $getData = request()->all();
        // $credentials = request(['email', 'password']);
        $exist = DB::table('users')->where('email', $getData['email'])->first();
        if ($exist) {
            if (Hash::check($getData['password'], $exist->password)) {
                $otp = mt_rand(100000, 999999);
                $ottps = $otp;
                $updateOtp =  DB::table('users')->where('id', $exist->id)->update([
                    'otp' => $otp
                ]);
                if ($updateOtp) {
                    $status = 200;
                    $msg = "Otp Sent to your registered Email.";
                    $maildata = [
                        'name' => $exist->firstname ." ".$exist->lastname,
                        'email'=>$getData['email'] , 
                        'otp'=>$otp
                    ];
                    $this->loginMailOtp( $maildata);
                    $otp = Crypt::encryptString($otp);
                } else {
                    $status = 400;
                    $msg = "Something Went Wrong .Please try again later";
                    $otp = '';
                }
            } else {
                $status = 400;
                $msg = "Incorrect Password.Please check";
                $otp = '';
            }
        } else {
            $status = 400;
            $msg = "Invalid user.Please check";
            $otp = '';
        }
        return response()->json([
            'status' =>  $status,
            'msg' => $msg,
            'otp' => $otp, 'ottps' => $ottps
        ], $status);
    }

    public function validateOtp()
    {
        $status = '';
        $msg = "";
        $getData = request()->all();

        $getOtp =  DB::table('users')->where('email', $getData['email'])->first()->otp;
        $valOtp = $getData['userOtp'];

        if ($getOtp == $valOtp) {
            $credentials['email'] = $getData['email'];
            $credentials['password'] = $getData['password'];

            if (!$token = auth()->attempt($credentials)) {
                return response()->json(['status' => false, 'message' => "Invalid Credentials"],  400);
            }

            return $this->respondWithToken($token);
        } else {
            $status =  400;
            $msg = "Invalid OTP.Please check";
            return response()->json([
                'status' =>  $status,
                'msg' => $msg
            ], $status);
        }
    }

    public function sendRegisterOtp()
    {
        $getData = request()->all();
        $msg = "";
        $status =  "INVALID";
        $statusCode =  400;
        $statusResponse =  false;
        $otp = "";
        $otpEnc = "";
        $checkUser = DB::table('users')->where('deletedFlag', 0)->where('email', $getData['email'])->count('id');
        $checkMobile = DB::table('users')->where('deletedFlag', 0)->where('mobile', 9031976434)->count('id');
        if ($checkUser > 0 && $checkMobile > 0) {
            $msg = "User Allready Exists.Please try with different Email and Mobile No.";
        } else {
            $otp = mt_rand(100000, 999999);
            $otpEnc = Crypt::encryptString($otp);
            $msg = "Otp has been Sent to Your Emial and Mobile No.";
            $status =  "SUCCESS";
            $statusCode =  200;
            $statusResponse =  true;
            array_push($getData, ['otp' => $otp]);
            $this->sendregotp($getData);
        }

        return response()->json([
            'statusCode' => $statusCode,
            'statusResponse' => $statusResponse,
            'status' => $status,
            'msg' => $msg,
            'otpEnc' => $otpEnc,
            'otp' => $otp
        ], $statusCode);
    }

    public function sendregotp($data)
    {
        Mail::to($data['email'])->send(new SendRegisterOtp($data));
    }

    public function loginMailOtp($data)
    {
        Mail::to($data['email'])->send(new LoginOtp($data));
    }

    public function validateRegisterOtp()
    {
        $getData = request()->all();
        $status =  "INVALID";
        $statusCode =  400;
        $statusResponse =  false;
        $msg = "";


        $otp = $getData['otp'];
        $encotp = Crypt::decryptString($getData['encOtp']);
        if ($otp == $encotp) {

            $msg = "OTP is valid";
            $status =  "SUCCESS";
            $statusCode =  200;
            $statusResponse =  true;
        } else {
            $msg = "Invalid OTP";
        }

        return response()->json([
            'statusCode' => $statusCode,
            'statusResponse' => $statusResponse,
            'status' => $status,
            'msg' => $msg
        ], $statusCode);
    }
}
