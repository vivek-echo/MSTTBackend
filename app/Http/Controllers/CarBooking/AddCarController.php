<?php

namespace App\Http\Controllers\CarBooking;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
class AddCarController extends Controller
{
    public function addCar()
    {
       
        $statusCode = $this->errorStatusCode;
        $statusResponse = $this->errorStatusResponse;
        $status = $this->errorStatus;
        $msg = "";
        $getData = request()->all();
        
        $validator = Validator::make($getData, [
            'userId'=>"required",
            'carType'  => "required",
            'carBrand' => "required",
            'carYear' => "required",
            'fuelType' => "required",
            'mileage' => "required",
            'ownerName' => "required",
            'stateId' => "required|integer|gt:0",
            // 'stateName' => "required",
            'cityId'=>"required|integer|gt:0",
            // 'cityName' => "required",
            'address' => "required",
            'RCNo' => "required",
            'chassisNo' => "required",
            'insuranceValidFrom' => "required",
            'insuranceValidTill' => "required",
            'pollutionValidFrom' => "required",
            'pollutionValidTill' => "required",
        ]);
        if ($validator->fails()) {
            return $this->valMsg($validator->errors());
        }
       
        try{

            DB::beginTransaction();
            $ins['userId'] = $getData['userId']; 
            $ins['carType'] = $getData['carType']; 
            $ins['carBrand'] = $getData['carBrand']; 
            $ins['carYear'] = date('Y-m-d', strtotime($getData['carYear']))  ; 
            $ins['fuelType'] = $getData['fuelType']; 
            $ins['milage'] = $getData['mileage']; 
            $ins['ownerName'] = $getData['ownerName']; 
            $ins['stateId'] = $getData['stateId']; 
            // $ins['stateName'] = $getData['stateName']; 
            $ins['cityId'] = $getData['cityId']; 
            // $ins['cityName'] = $getData['cityName']; 
            $ins['address'] = $getData['address']; 
            $ins['rcNo'] = $getData['RCNo']; 
            $ins['chassisNo'] = $getData['chassisNo']; 
            $ins['insValidFrom'] = date('Y-m-d', strtotime($getData['insuranceValidFrom']));
            $ins['insValidTill'] =date('Y-m-d', strtotime($getData['insuranceValidTill'])) ;
            $ins['pollutionValidFrom'] =date('Y-m-d', strtotime($getData['pollutionValidTill'])); 
            $ins['pollutionValidTill'] =date('Y-m-d', strtotime($getData['insuranceValidTill']))  ; 
            $ins['createdOn'] =now(); 
                $trans = DB::transaction(function () use ($ins) { 
                   DB::table('addCar')->insert($ins);
                });
                if( is_null($trans)){
                    $statusCode = $this->successStatusCode;
                    $statusResponse = $this->successStatusResponse;
                    $status = $this->successStatus;
                    $msg = "Cab Added succesfully.Please wait for verification.";
                }
                DB::commit();
        } catch (\Exception $t) {
            DB::rollBack();
            Log::error("Error", [
                'Controller' => 'MasterDataController',
                'Method' => 'studentMasterData',
                'Error' => $t->getMessage(),
            ]);

            $statusCode = $this->errorStatusCode;
            $statusResponse = $this->errorStatusResponse;
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

    public function viewCar(){
        $statusCode = $this->errorStatusCode;
        $statusResponse = $this->errorStatusResponse;
        $status = $this->errorStatus;
        $msg = "";
        $responseData = [];
        $getData = request()->all();
        $validator = Validator::make($getData, [
            'userId'=>"required"
        ]);

        if ($validator->fails()) {
            return $this->valMsg($validator->errors());
        }

        try{
           $data= DB::table('addCar')->where('deletedFlag',0)->get();
           $responseData =  $data;
           $statusCode = $this->successStatusCode;
           $statusResponse = $this->successStatusResponse;
           $status = $this->successStatus;
           $msg = "Data genertated succesfully";
        }catch (\Exception $t) {
            DB::rollBack();
            Log::error("Error", [
                'Controller' => 'MasterDataController',
                'Method' => 'studentMasterData',
                'Error' => $t->getMessage(),
            ]);

            $statusCode = $this->errorStatusCode;
            $statusResponse = $this->errorStatusResponse;
            $status = $this->errorStatus;
            $msg = "Something went wrong. please try again later.";
            
        }

        return response()->json([
            'statusCode'=> $statusCode,
            'statusResponse'=>$statusResponse,
            'status'=>$status,
            'msg'=>$msg,
            'responseData' =>$responseData
        ],$statusCode);

    }

}
