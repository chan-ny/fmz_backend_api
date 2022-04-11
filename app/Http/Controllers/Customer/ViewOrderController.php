<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ViewOrderController extends Controller
{
    public function __construct()
    {
        Auth::shouldUse('cust');
    }
    public function OrderPayment($cusId)
    {
        $view = DB::table("tb_payment")->where("customer_Id", $cusId)->orderBy('invId', 'desc')->get();
        foreach ($view as  $value) {
            $value->pmImage = json_decode($value->pmImage);
        }
        if (!is_null($view)) {
            return response()->json([
                "status" => "success",
                "count" => count($view),
                "data" => $view
            ], 200);
        } else {
            return response()->json([
                "status" => "error",
                "message" => "can't loading server!!!"
            ], 404);
        }
    }
    public function ViewOrder($invId)
    {
        $view = DB::table("tb_ordered")->where('invId', '=', $invId)->get();
        if (!is_null($view)) {
            return response()->json([
                "status" => "success",
                "count" => count($view),
                "data" => $view
            ], 200);
        } else {
            return response()->json([
                "status" => "error",
                "message" => "can't loading server!!!"
            ], 404);
        }
    }
}
