<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class CommonController extends Controller
{
    public function getState()
    {
        $statusCode = $this->errorStatusCode;
        $statusResponse = $this->errorStatusResponse;
        $status = $this->errorStatus;
        $msg = "";
        $sateData = [];
        $data = DB::table('state')->select('stateId', 'stateName')->get();
        if (!$data->isEmpty()) {
            $statusCode = $this->successStatusCode;
            $statusResponse = $this->successStatusResponse;
            $status = $this->successStatus;
            $msg = "Sate Data generated Successfully";
            $sateData = $data;
        } else {
            $msg = "No record found.";
            $sateData = [];
        }
        return response()->json([
            'statusCode' => $statusCode,
            'statusResponse' => $statusResponse,
            'status' => $status,
            'msg' => $msg,
            'stateData' => $sateData
        ], $statusCode);
    }

    public function getCity()
    {
        $getData = request()->all();
        $statusCode = $this->errorStatusCode;
        $statusResponse = $this->errorStatusResponse;
        $status = $this->errorStatus;
        $msg = "";
        $cityData = [];
        $validator = Validator::make($getData, [
            'stateId'=>"required|integer|gt:0",
           
        ]);
        if ($validator->fails()) {
            return $this->valMsg($validator->errors());
        }

        $data = DB::table('city')->select('cityId', 'cityName')->where('stateId',$getData['stateId'])->get();
        if (!$data->isEmpty()) {
            $statusCode = $this->successStatusCode;
            $statusResponse = $this->successStatusResponse;
            $status = $this->successStatus;
            $msg = "Sate Data generated Successfully";
            $cityData = $data;
        } else {
            $msg = "No record found.";
            $cityData = [];
        }
        return response()->json([
            'statusCode' => $statusCode,
            'statusResponse' => $statusResponse,
            'status' => $status,
            'msg' => $msg,
            'cityData' => $cityData
        ], $statusCode);
    }
}
