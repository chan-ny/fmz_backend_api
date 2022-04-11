<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ViewSaleController extends Controller
{
    public function ViewSale()
    {
        $value = DB::table('tb_viewsale')->orderByDesc('invId')->get();
        if (!is_null($value)) {
            return response()->json([
                "status" => "success",
                "count" => count($value),
                "data" => $value
            ], 200);
        } else {
            return response()->json([
                "status" => "error",
                "message" => "can't loading value ViewSale"
            ], 404);
        }
    }

    public function AnalyticalSale()
    {
        $totalsale = DB::table('tb_viewsale')
            ->select('invState', DB::raw('SUM(sdQty) as amount,SUM(total) as total'))
            ->groupBy('invState')
            ->get();
        $topsale = DB::table('tb_viewsale')
            ->select('pdname', DB::raw('SUM(sdQty) as amount'))
            ->groupBy('pdname')
            ->orderByDesc('amount')
            ->limit(6)
            ->get();
        $customer = DB::table('customers')->get();
        $transfer = DB::table('payments')->get();
        if ($totalsale) {
            return response()->json([
                "success" => "success",
                "total" => $totalsale,
                "Cus" => count($customer),
                "pay" => count($transfer),
                "Topsix" => $topsale
            ], 200);
        } else {
            return response()->json([
                "success" => "error",
                "message" => "error Analytical Sale"
            ], 500);
        }
    }
}
