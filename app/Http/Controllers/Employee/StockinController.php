<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Employee\Storage;
use App\Models\Emplyee\Imports\Import;
use App\Models\Emplyee\StockIn;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

use function GuzzleHttp\Promise\all;

class StockinController extends Controller
{
    public function SearchBill()
    {
        $searchbill = DB::table('import_lists')
            ->join('imports', 'import_lists.import_Id', '=', 'imports.impId')
            ->select(DB::raw('imports.impId,SUM(import_lists.reciev_qty)as amount,imports.created_at'))
            ->groupBy('impId', 'created_at')
            ->where('imports.impstate', '=', 'final')
            ->get();

        if (!is_null($searchbill)) {
            return response()->json([
                "status" => "success",
                "data" => $searchbill
            ], 200);
        } else {
            return response()->json([
                "status" => "error",
                "data" => "can't loading bill"
            ], 500);
        }
    }
    public function listDetail($impId)
    {
        $list = DB::table("tb_searchbill")
            ->where('import_Id', $impId)
            ->get();
        if (!is_null($list)) {
            return response()->json([
                "status" => "success",
                "count" => count($list),
                "data" => $list
            ], 200);
        } else {
            return response()->json([
                "status" => "error",
                "data" => "can't search import"
            ], 500);
        }
    }


    public function Store($impId, Request $request)
    {

        if (!is_null($request->all())) {
            try {
                foreach ($request->all() as $value) {

                    StockIn::create($value);
                }
                ///update import
                $imports = Import::where('impId', $impId)->first();
                $imports->update(['impstate' => 'finalend']);

                ///plus value storage
                foreach ($request->all() as $value) {
                    $storages = Storage::where('srId',  $value['storage_Id'])->first();
                    if (!is_null($storages)) {
                        $plus = $storages->srqty;
                        $plus += $value['stQty'];
                        $storages->update(['srqty' => $plus]);
                    }
                }
                return response()->json([
                    "status" => "success",
                    "message" => "ບັນທືກສະຕ໊ອກສຳເລັດແລ້ວ",
                    // "data" => $imports
                ], 200);
            } catch (Exception $e) {
                return response()->json([
                    "status" => "error",
                    "message" => "not save stockIn"
                ], 500);
            }
        } else {
            return response()->json([
                "status" => "error",
                "message" => "data Id Not Found"
            ], 500);
        }
    }
    public function historyStock()
    {
        $stock = DB::table('tb_stockin')->where('impstate', '=', 'finalend')->get();
        if (!is_null($stock)) {
            return  response()->json([
                'status' => 'success',
                'count' => count($stock),
                'data' => $stock
            ], 200);
        } else {
            return  response()->json([
                'status' => 'error',
                'data' => "can't loading value history Stock"
            ], 500);
        }
    }
}
