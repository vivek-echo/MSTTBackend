<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    protected $errorStatusCode , $errorStatusResponse , $errorStatus ,$successStatusCode , $successStatusResponse , $successStatus;
    public function __construct()
    {
        $this->errorStatusCode =  400; 
        $this->errorStatusResponse = false ; 
        $this->errorStatus = "INVALID"; 
        $this->successStatusCode = 200; 
        $this->successStatusResponse = true; 
        $this->successStatus = "SUCCESS";
     
    }

    public function valMsg($errors){
        $msg = array();
        foreach($errors->all() as $messages){
            $msg[] = $messages;
        }
        $msg = implode(',',$msg);
        return response()->json([
            'statusCode' => $this->errorStatusCode,
            'statusResponse' => $this->errorStatusResponse ,
            'status' => $this->errorStatus,
            'msg'=> $msg
        ],$this->errorStatusCode);
    }

    // public function addCar()
    // {
    //     $statusCode = $this->errorStatusCode;
    //     $statusResponse = $this->errorstatusResponse;
    //     $status = $this->errorStatus;
    //     $msg = "";
    //     $getData = request()->all();
    //     $validator = Validator::make($getData, [
    //         'carType'  => "required",
    //         'carBrand' => "required",
    //         'carYear' => "required",
    //         'fuelType' => "required",
    //         'mileage' => "required",
    //         'ownerName' => "required",
    //         'stateName' => "required",
    //         'cityName' => "required",
    //         'address' => "required",
    //         'RCNo' => "required",
    //         'chassisNo' => "required",
    //         'insuranceValidFrom' => "required",
    //         'insuranceValidTill' => "required",
    //         'pollutionValidTill' => "required",
    //         'pollutionValidFrom' => "required",
    //     ]);
    //     if ($validator->fails()) {
    //         return $this->valMsg($validator->errors());
    //     }

    //     try{

    //     } catch (\Exception $t) {
    //         Log::error("Error", [
    //             'Controller' => 'MasterDataController',
    //             'Method' => 'studentMasterData',
    //             'Error' => $t->getMessage(),
    //         ]);

    //         $statusCode = $this->errorStatusCode;
    //         $statusResponse = $this->errorstatusResponse;
    //         $status = $this->errorStatus;
    //         $msg = "Something went wrong. please try again later.";
            
    //     }

    //     return response()->json([
    //         'statusCode'=> $statusCode,
    //         'statusResponse'=>$statusResponse,
    //         'status'=>$status,
    //         'msg'=>$msg
    //     ],$statusCode);

    // }

    
}
