<?php

namespace App\Http\Controllers\Employee\Import;

use App\Http\Controllers\Controller;
use App\Models\Emplyee\Imports\Import;
use App\Models\Emplyee\Imports\Import_list;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ImportController extends Controller
{
    public function searchProduct($sup)
    {
        $products = DB::table('products')->select("products.pdId", "products.pdname", "products.pdcost", "products.supplier_Id", "products.brand_Id", "products.categories_Id")
            ->where("products.supplier_id", $sup)
            ->get();

        $auto = '';
        $maxID = Import::orderBy('impId', 'desc')->first();
        if (!is_null($maxID)) {
            $auto = $maxID->impId;
        } else {
            $auto = 'IM00';
        }
        if (!is_null($products)) {
            return response()->json([
                "data" => $products,
                "maxId" => ++$auto
            ], 200);
        } else {
            return response()->json([
                "error" => " error values incurrect"
            ], 200);
        }
    }
    public function searchStorage($productId, $sizeId)
    {
        $storage = DB::table('storages')
            ->join('products', 'storages.product_Id', '=', 'products.pdId')
            ->join('sizes', 'storages.size_Id', '=', 'sizes.sId')
            ->select('storages.srId', 'products.pdId', 'sizes.sId')
            ->where('products.pdId', $productId)
            ->where('sizes.sId', $sizeId)
            ->get();
        if (!is_null($storage)) {
            return response()->json([
                "data" => $storage,
            ], 200);
        } else {
            return response()->json([
                "error" => " error can't search storage"
            ], 200);
        }
    }
    public function storeImport(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "supplier_Id" => "required",
            "impstate" => "required"
        ]);

        if ($validator->fails()) {
            return response()->json([
                "status" => "error required",
                "message" => $validator->errors()
            ], 500);
        } else {
            try {

                $auto = '';
                $maxID = Import::orderBy('impId', 'desc')->first();
                if (!is_null($maxID)) {
                    $auto = $maxID->impId;
                } else {
                    $auto = 'IM00';
                }
                $imports = new  Import();
                $imports->impId = ++$auto;
                $imports->supplier_Id = $request->supplier_Id;
                $imports->impstate = $request->impstate;
                $imports->save();

                return response()->json([
                    "status" => "success",
                    "message" => "ສັ່ງຊື້ສຳເລັດແລ້ວ",
                ], 200);
            } catch (Exception $e) {
                return response()->json([
                    "status" => "error not save",
                    "message" => $e
                ], 500);
            }
        }
    }
    public function storeImportlist(Request $request)
    {
        try {
            foreach ($request->all() as $value) {
                Import_list::create($value);
            }
            return response()->json([
                "status" => "success",
                "message" => "ສັ່ງຊື້ສຳເລັດແລ້ວ"
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                "status" => "error",
                "message" => $e
            ], 500);
        }
    }
    public function displayImport_list()
    {
        $lists = DB::table("tb_import_list")->where("impstate", "=", "on")->get();
        if (!is_null($lists)) {
            return response()->json([
                "count" => count($lists),
                "data" => $lists
            ], 200);
        } else {
            return response()->json([
                "error" => " error import list values incurrect"
            ], 200);
        }
    }
    public function checkBillImport($id)
    {
        $check = DB::table("tb_import_list")
            ->select(DB::raw("SUM(order_qty) as Amount,SUM(Amount) as total"))
            ->where("tb_import_list.impId", $id)
            ->get();
        if (!is_null($check)) {
            return response()->json([
                "status" => "sucsess",
                "data" => $check
            ], 200);
        }
    }
    
    public function SearchSize($pdId)
    {
        $view = DB::table("storages")
            ->join('products', 'storages.product_Id', '=', 'products.pdId')
            ->join('sizes', 'storages.size_id', '=', 'sizes.sId')
            ->select('storages.srId', 'products.pdId', 'sizes.sId', 'sizes.sname')
            ->where('storages.product_Id', $pdId)
            ->orderBy('storages.srId', 'asc')
            ->get();

        if (!is_null($view)) {
            return response()->json([
                "status" => "success",
                "data" => $view
            ], 200);
        } else {
            return response()->json([
                "status" => "error",
                "message" => "can't search Sizes"
            ], 500);
        }
    }
}
