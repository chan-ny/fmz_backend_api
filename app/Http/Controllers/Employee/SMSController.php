<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Auths\Customer;
use App\Models\Auths\SMSmodel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Nexmo\Laravel\Facade\Nexmo;

class SMSController extends Controller
{
    public function __construct()
    {
        Auth::shouldUse('cust');
    }

    public function CheckPhone(Request $request)
    {
        $checkPhone = Customer::where('cus_phone', $request->phone)->first();
        if (!is_null($checkPhone)) {
            return response()->json([
                "status" => "success",
                "data" => $checkPhone
            ], 200);
        } else {
            return response()->json([
                "status" => "error",
                "message" => "not found phone number"
            ], 500);
        }
    }

    public function sendSMS(Request $request)
    {
        $rands = rand(100000, 900000);
        $typeUser = "customer";

        $validate = Validator::make($request->all(), [
            "phone" => "required",
        ]);

        if ($validate->fails()) {
            return response()->json([
                "status" => "error required",
                "message" => $validate->errors()
            ], 404);
        } else {
            $customer = Customer::where('cus_phone', $request->phone)->first();
            if (!is_null($customer)) {

                Nexmo::message()->send([
                    'to' => $customer->cus_phone,
                    'from' => 'MONO Store',
                    'text' => "OTP code .$rands."
                ]);

                $updateotp = SMSmodel::where('userId', $customer->cusId)->first();
                if (!is_null($updateotp)) {

                    $updateotp->update([
                        "userOTP" => $rands
                    ]);
                    return response()->json([
                        "status" => "success",
                        "message" => "update and Send SMS SuccessFully"
                    ], 200);
                } else {
                    $otp = new SMSmodel();
                    $otp->userId = $customer->cusId;
                    $otp->type_user = $typeUser;
                    $otp->userOTP = $rands;
                    $otp->save();
                    return response()->json([
                        "status" => "success",
                        "message" => "save and Send SMS SuccessFully"
                    ], 200);
                }
            }
        }
    }
}
