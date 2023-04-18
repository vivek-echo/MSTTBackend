<?php

namespace App\Http\Controllers\CarBooking;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class AddCarController extends Controller
{
    public function addCar()
    {
        $statusCode = $this->errorStatusCode;
        $statusResponse = $this->errorstatusResponse;
        $status = $this->errorStatus;
        $msg = "";
        $getData = request()->all();
        $validator = Validator::make($getData, [
            'carType'  => "required",
            'carBrand' => "required",
            'carYear' => "required",
            'fuelType' => "required",
            'mileage' => "required",
            'ownerName' => "required",
            'stateName' => "required",
            'cityName' => "required",
            'address' => "required",
            'RCNo' => "required",
            'chassisNo' => "required",
            'insuranceValidFrom' => "required",
            'insuranceValidTill' => "required",
            'pollutionValidTill' => "required",
            'pollutionValidFrom' => "required",
        ]);
        if ($validator->fails()) {
            return $this->valMsg($validator->errors());
        }

        try{
            
        } catch (\Exception $t) {
            Log::error("Error", [
                'Controller' => 'MasterDataController',
                'Method' => 'studentMasterData',
                'Error' => $t->getMessage(),
            ]);

            $statusCode = $this->errorStatusCode;
            $statusResponse = $this->errorstatusResponse;
            $status = $this->errorStatus;
            $msg = "Something went wrong. please try again later.";
            
        }

        return response()->json([
            'statusCode'=> $statusCode,
            'statusResponse'=>$statusResponse,
            'status'=>$status,
            'msg'=>$msg
        ],$statusCode);

    }
}
