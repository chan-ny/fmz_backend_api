<?php

namespace App\Http\Controllers\Customer;

use App\Events\Notifycations;
use App\Http\Controllers\Controller;
use App\Models\Customer\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use function GuzzleHttp\json_decode;
use Illuminate\Support\Facades\Auth;

class SellController extends Controller
{
    public function __construct()
    {
        Auth::shouldUse('cust');
    }
    public function sellBest()
    {
        $data = DB::table("tb_sellbest")
            ->select(
                "pdId",
                "pdphotos_main",
                "pdname",
                "ctname",
                "cname",
                "sname",
                "sdPrice",
                DB::raw("SUM(sdQty) as amount")
            )
            ->groupBy("pdId", "pdphotos_main", "pdname")
            ->orderBy("amount", "desc")
            ->paginate(8);
        // ->get();
        foreach ($data as $value) {
            $value->pdphotos_main = json_decode($value->pdphotos_main);
        }

        if (!is_null($data)) {
            return response()->json([
                "status" => "success",
                "count" => count($data),
                "data" => $data
            ], 200);
        } else {
            return response()->json([
                "status" => "error",
                "message" => "Can't loading Server",
            ], 404);
        }
    }

    public function vireOrderPDF($invId)
    {
        $list = DB::table("tb_orderpdf")->where("invId", $invId)->get();
        if (!is_null($list)) {
            return response()->json([
                "status" => "success",
                "count" => count($list),
                "data" => $list
            ], 200);
        } else {
            return response()->json([
                "status" => "success",
                "count" => "Data not mismat",
            ], 500);
        }
    }
    public function CheckTransfer()
    {
        $list = DB::table("tb_checkpayment")
            ->select(
                "invId",
                'cus_fullname',
                'cus_phone',
                DB::raw("CONCAT('ແຂວງ:',cus_provint , ' ເມືອງ:', cus_distric,' ບ້ານ:', cus_home,' ອື່ນໆ:', cus_description) as address"),
                'pmNumbersix',
                'pmPrice',
                'invQty',
                'invPrice',
                'pmState',
                'created_at'
            )
            ->where("pmState", '=', "pay")
            ->orderBy("invId", 'desc')->get();
        if (!is_null($list)) {
            return response()->json([
                "status" => "success",
                "count" => count($list),
                "data" => $list
            ], 200);
        } else {
            return response()->json([
                "status" => "success",
                "count" => "cna't loading checkTransfer",
            ], 500);
        }
    }
    public function finalTransfer()
    {
        $list = DB::table("tb_checkpayment")
            ->select(
                "invId",
                'cus_fullname',
                'cus_phone',
                DB::raw("CONCAT('ແຂວງ:',cus_provint , ' ເມືອງ:', cus_distric,' ບ້ານ:', cus_home,' ອື່ນໆ:', cus_description) as address"),
                'pmNumbersix',
                'pmPrice',
                'invQty',
                'invPrice',
                'pmState',
                'created_at'
            )
            ->where("pmState", '=', "success")
            ->orderBy("invId", 'desc')->get();
        if (!is_null($list)) {
            return response()->json([
                "status" => "success",
                "count" => count($list),
                "data" => $list
            ], 200);
        } else {
            return response()->json([
                "status" => "success",
                "count" => "cna't loading checkTransfer",
            ], 500);
        }
    }
    public function ViewTransfer($invId)
    {
        $values = DB::table("tb_payment")->where("invId", $invId)->get();
        foreach ($values as $value) {
            $value->pmImage = json_decode($value->pmImage);
        }
        if (!is_null($values)) {
            return response()->json([
                "status" => "success",
                "data" => $values
            ], 200);
        } else {
            return response()->json([
                "status" => "success",
                "count" => "cna't loading viewTransfer",
            ], 500);
        }
    }

    public function CommitTranfer($pmId)
    {
        $update = Payment::where('pmId', $pmId)->first();
        if (!is_null($update)) {
            $update->update([
                "pmState" => "success"
            ]);

            broadcast(new Notifycations("ແຈ້ງໂອນ"));
            return response()->json([
                "status" => "success",
                "message" => "ຍອມຮັບສຳເລັດແລ້ວ"
            ], 200);
        } else {
            return response()->json([
                "status" => "success",
                "count" => "data not mismat",
            ], 500);
        }
    }
}
