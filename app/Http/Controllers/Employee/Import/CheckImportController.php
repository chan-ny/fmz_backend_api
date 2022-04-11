<?php

namespace App\Http\Controllers\Employee\Import;

use App\Http\Controllers\Controller;
use App\Models\Emplyee\Imports\Import;
use App\Models\Emplyee\Imports\Import_list;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CheckImportController extends Controller
{
    public function checkImport()
    {
        $checkImport = DB::table('imports')
            ->join('suppliers', 'imports.supplier_Id', '=', 'suppliers.supId')
            ->select('imports.impId', 'suppliers.supfullname', 'imports.created_at')
            ->where('imports.impstate', '=', 'on')
            ->get();
        if (!is_null($checkImport)) {
            return response()->json([
                "Status" => "success",
                "count" => count($checkImport),
                "data" => $checkImport
            ], 200);
        } else {
            return response()->json([
                "Status" => "success",
                "message" => "can not load Bill import"
            ], 500);
        }
    }
    public function listProduct($impId)
    {
        $listproduct = DB::table('import_lists')
            ->join('imports', 'import_lists.import_Id', '=', 'imports.impId')
            ->join('storages', 'import_lists.storage_Id', '=', 'storages.srId')
            ->join('sizes', 'storages.size_Id', '=', 'sizes.sId')
            ->join('products', 'storages.product_Id', '=', 'products.pdId')
            ->join('categories', 'products.categories_Id', '=', 'categories.ctId')
            ->select(
                'import_lists.imlId',
                'products.pdname',
                'categories.ctname',
                'sizes.sname',
                'import_lists.order_qty',
                'import_lists.reciev_qty',
                'import_lists.imlprice'
            )
            ->where('imports.impId', '=', $impId)
            ->where('imports.impstate', '=', 'on')
            ->get();
        if (!is_null($listproduct)) {
            return response()->json([
                "Status" => "success",
                "importbill" => $impId,
                "count" => count($listproduct),
                "data" => $listproduct
            ], 200);
        } else {
            return response()->json([
                "Status" => "success",
                "message" => "can not load list product"
            ], 500);
        }
    }
    public function checkNumber(Request $request, $imlId)
    {
        $validators = Validator::make($request->all(), [
            "iml_after_qty" => "required"
        ]);

        if ($validators->fails()) {
            return response()->json([
                "status" => "failed",
                "message" => $validators->errors()
            ], 500);
        } else {
            $ch_number = Import_list::where("imlId", $imlId);
            if (!is_null($ch_number)) {
                try {
                    $ch_number->update(["reciev_qty" => $request->iml_after_qty]);
                    return response()->json([
                        "status" => "success",
                        "data" => $ch_number
                    ], 200);
                } catch (Exception $e) {
                    return response()->json([
                        "status" => "failed",
                        "data" => $e
                    ], 500);
                }
            } else {
                return response()->json([
                    "status" => "failed",
                    "data" => 'value not  defind'
                ], 500);
            }
        }
    }
    public function DeleteItemImport($imlId)
    {
        $del = Import_list::where("imlId", $imlId);
        if (!is_null($del)) {
            try {
                $del->delete();
                return response()->json([
                    "status" => "success",
                    "message" => "ລືບສຳເລັດແລ້ວ"
                ], 200);
            } catch (Exception $e) {
                return response()->json([
                    "status" => "failed",
                    "data" => $e
                ], 500);
            }
        } else {
            return response()->json([
                "status" => "failed",
                "data" => 'value not  defind'
            ], 500);
        }
    }

    public function CheckFinal($billId)
    {
        $checkfinal = Import::where('impId', $billId)->first();
        if (!is_null($checkfinal)) {
            try {
                $checkfinal->update(['impstate' => 'final']);
                return response()->json([
                    "status" => "success",
                    "message" => "ກວດສອບສຳເລັດແລ້ວ"
                ], 200);
            } catch (Exception $e) {
                return response()->json([
                    "status" => "failed",
                    "data" => $e
                ], 500);
            }
        } else {
            return response()->json([
                "status" => "failed",
                "data" => 'value not  defind'
            ], 500);
        }
    }
    public function StoriesImport()
    {
        $story = DB::table("tb_checkimport")
            ->where('impstate', '=', 'finalend')
            ->orWhere('impstate', '=', 'final')
            ->orderByDesc('srId')
            ->get();
        if (!is_null($story)) {
            return response()->json([
                "status" => "success",
                "cont" => count($story),
                "data" => $story
            ], 200);
        } else {
            return response()->json([
                "status" => "error",
                "data" => "data empty"
            ], 500);
        }
    }
    public function DeleteBillImport($imId)
    {
        $del = Import_list::where("import_Id", $imId);
        if (!is_null($del)) {
            try {
                $del->delete();
                Import::where("impId", $imId)->delete();
                return response()->json([
                    "status" => "success",
                    "message" => "ລືບສຳເລັດແລ້ວ"
                ], 200);
            } catch (Exception $e) {
                return response()->json([
                    "status" => "failed",
                    "data" => $e
                ], 500);
            }
        } else {
            return response()->json([
                "status" => "failed",
                "data" => 'value not  defind'
            ], 500);
        }
    }
}
