<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Auths\Customer;
use App\Models\Auths\SMSmodel;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    public function __construct()
    {
        Auth::shouldUse('cust');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $customer = Customer::where('cus_state', '=', 'on')->get();
        if (!is_null($customer)) {
            return response()->json([
                "status" => "success",
                "count" => count($customer),
                "data" => $customer
            ], 200);
        } else {
            return response()->json([
                "status" => "error",
                "data" => "error incurrect server",
            ], 503);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validators = Validator::make($request->all(), [
            "cus_gender" => "required|string|max:15",
            "cus_fullname" => "required|string|max:100",
            "cus_phone" => "required|string|max:15",
            "email" => "required|string|email|unique:customers",
            "password" =>  "required|string|min:6",
            "cus_state" => "required|max:15"
        ]);

        if ($validators->fails()) {
            return response()->json([
                "statu" => "failed",
                "message" => $validators->errors()
            ], 500);
        } else {

            try {

                if ($validators->fails()) {
                    return response()->json($validators->errors()->toJson(), 400);
                }

                $customers = Customer::create(array_merge(
                    $validators->validated(),
                    ['password' => bcrypt($request->password)]
                ));


                return response()->json([
                    "status" => "success",
                    "customer" => $customers,
                ], 200);
            } catch (Exception $e) {
                return response()->json([
                    "state" => "error",
                    "message" => $e
                ], 500);
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($email)
    {
        $customers = Customer::where("email", $email)->where('cus_state', '=', 'on')->first();
        if (!is_null($customers)) {
            return response()->json([
                "status" => "success",
                "data" => $customers
            ], 200);
        } else {
            return response()->json([
                "status" => "error",
                "data" => "data ID mismatch"
            ], 200);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editpassword(Request $request, $id)
    {

        $customers = Customer::where("cusId", $id)->first();
        if (!is_null($customers)) {

            if (!auth()->attempt($request->only("email", "password"))) {
                return response()->json(['error' => 'password invalid'], 401);
            } else {
                ///update pwd
                $customers->update(['password' => bcrypt($request->newpassword)]);
            }
            return response()->json([
                "status" => "success",
                "data" => $customers
            ], 200);
        } else {
            return response()->json([
                "status" => "error",
                "data" => "data ID mismatch"
            ], 500);
        }
    }

    public function ResetOTP($OTP)
    {
        $cus = SMSmodel::where('userOTP', $OTP)->first();
        if (!is_null($cus)) {
            return response()->json([
                "status" => "success",
                "data" => $cus
            ], 200);
        } else {
            return response()->json([
                "status" => "error",
                "message" => "OTP EXP"
            ], 500);
        }
    }
    public function Resetpassword(Request $request, $otp)
    {

        $validate = Validator::make($request->all(), [
            "password" => "required"
        ]);
        if ($validate->fails()) {
            return response()->json([
                "status" => "error required",
                "message" => $validate->errors()
            ], 503);
        } else {

            $cus = SMSmodel::where('userOTP', $otp)->first();
            if (!is_null($cus)) {
                $cus->update([
                    "userOTP" => ""
                ]);

                $customers = Customer::where("cusId", $cus->userId)->first();
                if (!is_null($customers)) {
                    $customers->update(['password' => bcrypt($request->password)]);
                }
                return response()->json([
                    "status" => "success",
                    "data" => $customers
                ], 200);
            } else {
                return response()->json([
                    "status" => "error",
                    "message" => "OTP EXP"
                ], 500);
            }
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $cus = Customer::where("cusId", $id)->first();
        if (!is_null($cus)) {
            try {
                $cus->update($request->all());
                return response()->json([
                    "status" => "success",
                    "message" => "ແກ້ໄຂ ຂໍ້ມູນລູກຄ້າສຳເລັດແລ້ວ",
                    "data" => $cus
                ], 200);
            } catch (Exception $e) {
                return response()->json([
                    "status" => "error",
                    "message" => "ບໍ່ສາມາດ ຂໍ້ມູນລູກຄ້າ"
                ], 500);
            }
        } else {
            return response()->json([
                "status" => "error",
                "message" => "data ID not found"
            ], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
