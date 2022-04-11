<?php

namespace App\Http\Controllers\Employee\Reporting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportProductController extends Controller
{
    public function ReportProduct($pdId)
    {
        $product = DB::table('tb_products')->where('pdId', $pdId)->get();

        foreach ($product as  $value) {
            $value->pdphotos_main = json_decode($value->pdphotos_main);
        }

        if (!is_null($product)) {
            return response()->json([
                "status" => "success",
                "data" => $product
            ], 200);
        } else {
            return response()->json([
                "status" => "success",
                "message" => "can't search product Id"
            ], 500);
        }
    }
    public function ReportStorage($pdId)
    {
        $storage = DB::table('tb_storage')->where('pdId', $pdId)->get();
        if (!is_null($storage)) {
            return response()->json([
                "status" => "success",
                "data" => $storage
            ], 200);
        } else {
            return response()->json([
                "status" => "success",
                "message" => "can't search storage Id"
            ], 500);
        }
    }

    public function ReportCustomer($cusId)
    {
        $cus = DB::table('customers')->where('cusId', $cusId)->get();
        if (!is_null($cus)) {
            return response()->json([
                "status" => "success",
                "data" => $cus
            ], 200);
        } else {
            return response()->json([
                "status" => "success",
                "message" => "can't search Customer Id"
            ], 500);
        }
    }
}
