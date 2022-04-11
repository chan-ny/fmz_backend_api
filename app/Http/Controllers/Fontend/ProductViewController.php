<?php

namespace App\Http\Controllers\Fontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ProductViewController extends Controller
{
    public function __construct()
    {
        Auth::shouldUse('cust');
    }
    public function ProductHome(Request $request)
    {
        $products = DB::table("tb_fontproduct")->where('pdprogression', '=', 'complete')->where('ctname', 'like', '%' . $request['ctname'] . '%')->orderBy('pdId', 'desc')->get();
        foreach ($products as $value) {
            $value->pdphotos_main = json_decode($value->pdphotos_main);
        }
        if (!is_null($products)) {
            return response()->json([
                "status" => "success",
                "conut" => count($products),
                "data" => $products
            ], 200);
        } else {
            return response()->json([
                "status" => "erroe",
                "message" => "can't loaded servered",
            ], 404);
        }
    }

    public function ViewCategory()
    {
        $categories = DB::table('categories')->get();
        if (!is_null($categories)) {
            return response()->json([
                "status" => "success",
                "conut" => count($categories),
                "data" => $categories
            ], 200);
        } else {
            return response()->json([
                "status" => "erroe",
                "message" => "can't loaded servered",
            ], 404);
        }
    }

    public function detailProduct($pdId)
    {
        $detail = DB::table("tb_detaillproduct")->where('pdprogression', '=', "complete")->where('pdId', $pdId)->get();
        foreach ($detail as $value) {
            $value->pdphotos_main = json_decode($value->pdphotos_main);
            $value->pdphotos_sub = json_decode($value->pdphotos_sub);
        }
        if (!is_null($detail)) {
            return response()->json([
                "status" => "success",
                "data" => $detail
            ], 200);
        } else {
            return response()->json([
                "status" => "erroe",
                "message" => "can't loaded detail product",
            ], 404);
        }
    }

    public function viewSize($pdId)
    {
        $view = DB::table("tb_viewsize")->where("srqty", '>', 0)->where("state", '=', "on")->where("pdId", $pdId)->get();
        foreach ($view as $value) {
            $value->pdphotos_main = json_decode($value->pdphotos_main);
        }
        if (!is_null($view)) {
            return response()->json([
                "status" => "success",
                "conut" => count($view),
                "data" => $view
            ], 200);
        } else {
            return response()->json([
                "status" => "erroe",
                "message" => "can't loaded servered viewSize",
            ], 404);
        }
    }


    ///product
    public function DisplayProduct(Request $request)
    {
        $products = DB::table("tb_fontproduct")->where('pdprogression', '=', 'complete')->where('pdname', 'like', '%' . $request['pdname'] . '%')->orderBy('pdId', 'desc')->get();
        foreach ($products as $value) {
            $value->pdphotos_main = json_decode($value->pdphotos_main);
        }
        if (!is_null($products)) {
            return response()->json([
                "status" => "success",
                "count" => count($products),
                "data" => $products
            ], 200);
        } else {
            return response()->json([
                "status" => "erroe",
                "message" => "can't loaded servered",
            ], 404);
        }
    }
}
