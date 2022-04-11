<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportingController extends Controller
{
    /// report sale
    public function ReportSale()
    {
        $value = DB::table('tb_reportsale')
            ->selectRaw('sdId,invId,pdId,pdname,bname,ctname,sname,SUM(sdQty) as sdQty,sdPrice,created_at, day(created_at) as datatime')
            ->groupByRaw('pdId,pdname,bname,ctname,sname,datatime')
            ->orderByDesc('datatime')
            ->get();

        if (!is_null($value)) {
            return response()->json([
                "status" => "success",
                "count" => count($value),
                "data" => $value
            ], 200);
        } else {
            return response()->json([
                "status" => "error",
                "message" => "can't loaded server reportsale !!!"
            ], 404);
        }
    }
    public function ReportDay(Request $request)
    {
        $value = DB::table('tb_reportsale')
            ->selectRaw('sdId,invId,pdId,pdname,bname,ctname,sname,SUM(sdQty) as sdQty,sdPrice,created_at, day(created_at) as datatime')
            ->where('created_at', 'like', '%' . $request->datetime . '%')
            ->groupByRaw('pdId,pdname,bname,ctname,sname,datatime')
            ->orderByDesc('datatime')
            ->get();

        if (!is_null($value)) {
            return response()->json([
                "status" => "success",
                "count" => count($value),
                "data" => $value
            ], 200);
        } else {
            return response()->json([
                "status" => "error",
                "message" => "can't loaded server reportday !!!"
            ], 500);
        }
    }

    ///end report sale

    ///report product
    public function ReportProduct()
    {
        $value = DB::table('tb_reportproduct')
            ->selectRaw('pdId,pdname,pdfullname,bname,supfullname,ctname,cname,pdcost,pdrate,pdprice,SUM(srqty) as QTY,created_at')
            ->groupByRaw('pdId,pdname,bname,supfullname,ctname,cname,pdcost,pdrate,pdprice')
            ->orderByDesc('pdId')
            ->get();
        if (!is_null($value)) {
            return response()->json([
                "status" => "success",
                "count" => count($value),
                "data" => $value
            ], 200);
        } else {
            return response()->json([
                "status" => "error",
                "message" => "can't loaded server reportday !!!"
            ], 500);
        }
    }

    /// end report product

    /// report customer
    public function ReportCustomer()
    {
        $cus = DB::table('customers')
            ->selectRaw("`cusId`, `cus_gender`, `cus_fullname`, `cus_phone`,CONCAT('ແຂວງ:',cus_provint , ' ເມືອງ:',cus_distric,' ບ້ານ:',cus_home,' ອື່ນໆ:', cus_description) as address, `email`, `password`, `cus_state`, `created_at`")
            ->orderByDesc('cusId')
            ->get();
        if (!is_null($cus)) {
            return response()->json([
                "status" => "success",
                "count" => count($cus),
                "data" => $cus
            ], 200);
        } else {
            return response()->json([
                "status" => "error",
                "message" => "can't loaded server reportCustomer !!!"
            ], 500);
        }
    }

    public function ReportImport(Request $request)
    {
        $value = DB::table('tb_reportimport')
            ->selectRaw('impId,pdId,pdname,supfullname,SUM(reciev_qty) as QTY,imlprice,created_at,month(created_at) as month')
            ->where('created_at', 'like', '%' . $request->month . '%')
            ->groupByRaw('impId,pdId,pdname,supfullname,imlprice,month')
            ->get();
        if (!is_null($value)) {
            return response()->json([
                "status" => "success",
                "count" => count($value),
                "data" => $value
            ], 200);
        } else {
            return response()->json([
                "status" => "error",
                "message" => "can't loaded server reportImport !!!"
            ], 500);
        }
    }

    public function viewDetailImport($impId)
    {
        $value = DB::table('tb_reportdetialimport')->where('impId', $impId)->get();
        if (!is_null($value)) {
            return response()->json([
                "status" => "success",
                "count" => count($value),
                "data" => $value
            ], 200);
        } else {
            return response()->json([
                "status" => "error",
                "message" => "can't loaded server report detail Import !!!"
            ], 500);
        }
    }
}
