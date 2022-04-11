<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ViewImportController extends Controller
{
    public function ViewimportFinal()
    {
        $value = DB::table("tb_viewimports")->where('impstate', '=', 'finalend')->orderBy("imlId", 'desc')->get();
        if (!is_null($value)) {
            return response()->json([
                "status" => "success",
                "count" => count($value),
                'data' => $value
            ], 200);
        } else {
            return response()->json([
                "status" => "error",
                'message' => "can't loading data"
            ], 404);
        }
    }

    public function Analytical()
    {
        $total = DB::table("tb_viewimports")
            ->select(DB::raw('SUM(reciev_qty) as amount,SUM(total) as total'))
            ->get();
        $ordering = DB::table('tb_viewimports')
            ->select(DB::raw('SUM(order_qty) as amount'), 'impstate')
            ->groupBy('impstate')
            ->where('impstate', '=', 'on')
            ->get();
        $checkorder = DB::table('tb_viewimports')
            ->select(DB::raw('SUM(reciev_qty) as amount'), 'impstate')
            ->groupBy('impstate')
            ->where('impstate', '=', 'final')
            ->get();
        $frequency = DB::table('tb_viewimports')
            ->select('pdId', 'pdname',DB::raw('SUM(reciev_qty) as amount'))
            ->groupBy('pdId', 'pdname')
            ->orderByDesc('amount')
            ->limit(6)
            ->get();


        if (!is_null($total)) {
            return response()->json([
                "status" => "success",
                "count" => count($total),
                'Total' => $total,
                'Ordering' => $ordering,
                'CheckO' => $checkorder,
                'Frequency' => $frequency
            ], 200);
        } else {
            return response()->json([
                "status" => "error",
                'message' => "can't loading data"
            ], 404);
        }
    }
}
