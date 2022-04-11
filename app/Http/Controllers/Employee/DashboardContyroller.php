<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardContyroller extends Controller
{
    public function AnalyticelCustomer()
    {
        $datetime = date("Y-m");
        $cus = DB::table('customers')->get();
        $month = DB::table('customers')
            ->where('created_at', 'like', '%' . $datetime . '%')
            ->get();

        if (!is_null($cus)) {
            return response()->json([
                "status" => "success",
                "count" => count($cus),
                "mount" => count($month)

            ], 200);
        } else {
            return response()->json([
                "status" => "error",
                "message" => "can't loading customer!!!!",

            ], 500);
        }
    }

    public function SaleDay()
    {
        $datetime = date("y-m-d");
        // $datetime = date("2021-06-18");
        $saledaycount = DB::table('tb_viewsale')
            ->selectRaw('created_at,SUM(total) as Total')
            ->where('created_at', 'like', '%' . $datetime . '%')
            ->get();
        $saledaylist = DB::table('tb_viewsale')
            ->selectRaw('created_at,SUM(total) as Total')
            ->where('created_at', 'like', '%' . $datetime . '%')
            ->groupBy('created_at')
            ->get();
        if (!is_null($saledaylist)) {
            return response()->json([
                "status" => "success",
                "count" => count($saledaylist),
                "amount" => $saledaycount,
                "day" => $datetime,

            ], 200);
        } else {
            return response()->json([
                "status" => "error",
                "message" => "can't loading sale Day!!!!",

            ], 500);
        }
    }

    public function saleAll()
    {
        $saleAll = DB::table('tb_viewsale')
            ->selectRaw('SUM(sdQty)as amount,SUM(total) as total')
            ->get();
        if (!is_null($saleAll)) {
            return response()->json([
                "status" => "success",
                "amount" => $saleAll,

            ], 200);
        } else {
            return response()->json([
                "status" => "error",
                "message" => "can't loading sale All!!!!",

            ], 500);
        }
    }

    public function importAll()
    {
        $importAll = DB::table('tb_viewimports')
            ->selectRaw('SUM(reciev_qty)as amount,SUM(total) as total')
            ->get();
        if (!is_null($importAll)) {
            return response()->json([
                "status" => "success",
                "amount" => $importAll,

            ], 200);
        } else {
            return response()->json([
                "status" => "error",
                "message" => "can't loading import All!!!!",

            ], 500);
        }
    }

    public function AnalyticalSale()
    {

        $topsale = DB::table('tb_viewsale')
            ->select('pdname', DB::raw('SUM(sdQty) as amount'))
            ->groupBy('pdname')
            ->orderByDesc('amount')
            ->limit(12)
            ->get();

        if ($topsale) {
            return response()->json([
                "success" => "success",
                "Topten" => $topsale
            ], 200);
        } else {
            return response()->json([
                "success" => "error",
                "message" => "error Analytical SaleBest"
            ], 500);
        }
    }

    public function ViewAdmin()
    {
        $product = DB::table('tb_dashboardproduct')->get();
        if ($product) {
            return response()->json([
                "success" => "success",
                "count" => count($product),
                "data" => $product
            ], 200);
        } else {
            return response()->json([
                "success" => "error",
                "message" => "error Analytical SaleBest"
            ], 500);
        }
    }

    public function CustomerSaleBest()
    {
        $listBest = DB::table('invoinces')
            ->join('customers', 'invoinces.customer_Id', '=', 'customers.cusId')
            ->selectRaw('customers.cusId,customers.cus_gender,customers.cus_fullname,customers.cus_phone,SUM(invoinces.invQty) as amount,customers.created_at')
            ->groupByRaw('customers.cusId,customers.cus_gender,customers.cus_fullname,customers.cus_phone,customers.created_at')
            ->get();
        if ($listBest) {
            return response()->json([
                "success" => "success",
                "count" => count($listBest),
                "data" => $listBest
            ], 200);
        } else {
            return response()->json([
                "success" => "error",
                "message" => "error Analytical CustomerSaleBest"
            ], 500);
        }
    }
}
