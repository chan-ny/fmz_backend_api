<?php

namespace App\Http\Controllers\Employee\Product;

use App\Http\Controllers\Controller;
use App\Models\Emplyee\Product;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        try {
            $products_view = DB::table('tb_products')->where('pdprogression','!=','remove')->orderBy('pdId','desc')->get();
            $products = Product::all();

            $auto = '';
            $maxID = Product::orderBy('pdId', 'desc')->first();
            if (!is_null($maxID)) {
                $auto = $maxID->pdId;
            } else {
                $auto = 'PD00';
            }

            foreach ($products_view as $value) {
                $value->pdphotos_main = json_decode($value->pdphotos_main);
            }
            foreach ($products as $value) {
                $value->pdphotos_main = json_decode($value->pdphotos_main);
            }

            if (!is_null($products_view)) {
                return response()->json([
                    "status" => "success",
                    "count" => count($products_view),
                    "maxId" => ++$auto,
                    "data" => $products_view,
                    "product" => $products,
                    "product_count" => count($products),
                ], 200);
            } else {
                return response()->json([
                    'status' => "error",
                    "message" => "data not Relationship"
                ], 500);
            }
        } catch (Exception $e) {
            return response()->json([
                'status' => "error",
                "message" => "server dos't loading!!!"
            ], 404);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $auto = '';
        $maxID = Product::orderBy('pdId', 'desc')->first();
        if (!is_null($maxID)) {
            $auto = $maxID->pdId;
        } else {
            $auto = 'PD00';
        }
        $validators = Validator::make($request->all(), [
            "brand_Id" => "required|min:1|max:5",
            "categories_Id" => "required|min:1|max:5",
            "colour_Id" => "required|min:1|max:5",
            "supplier_Id" => "required|min:1|max:5",
            "pdname" =>  "required|min:4|max:30",
            "pdfullname" =>  "required|min:8|max:120",
            "pdcost" => "required",
            "pdrate" => "required",
            "pdprice" => "required",
            "pdprogression" =>  "required|min:2|max:15",
            "pdphotos_main" =>  "required|mimes:png,jpg,jepg|max:1024",

        ]);

        if ($validators->fails()) {
            return response()->json([
                "status" => "error Required",
                "message" => $validators->errors()
            ], 500);
        } else {
            if (!$request->file('pdphotos_sub')) {
                return response()->json([
                    "status" => "error Required",
                    "meassage" => "image not found!!!"
                ], 500);
            } else {
                try {
                    $files = $request->pdphotos_main;
                    $photo_main = time() . rand(1, 100) . '.' . $files->getClientOriginalName();
                    $files->move(public_path('storage/images/clothing'), $photo_main);

                    $photo_sub = [];
                    if ($request->file('pdphotos_sub')) {
                        foreach ($request->file('pdphotos_sub') as $file) {
                            $sub = time() . rand(1, 100) . '.' . $file->getClientOriginalName();
                            $file->move(public_path('storage/images/clothing'), $sub);
                            $photo_sub[] = $sub;
                        }
                    }

                    $products = new Product();
                    $products->pdId =  ++$auto;
                    $products->brand_Id = $request->brand_Id;
                    $products->categories_Id = $request->categories_Id;
                    $products->colour_Id = $request->colour_Id;
                    $products->supplier_Id = $request->supplier_Id;
                    $products->pdname = $request->pdname;
                    $products->pdfullname = $request->pdfullname;
                    $products->pdcost = $request->pdcost;
                    $products->pdrate = $request->pdrate;
                    $products->pdprice = $request->pdprice;
                    $products->pdprogression = $request->pdprogression;
                    $products->pdphotos_main = json_encode($photo_main);
                    $products->pdphotos_sub = json_encode($photo_sub);
                    $products->save();

                    return response()->json([
                        "status" => "success",
                        "message" => "ບັນທືກຂໍ້ມູນສິນຄ້າສຳເລັດແລ້ວ",
                        "data" => $products
                    ], 200);
                } catch (Exception $e) {
                    return response()->json([
                        "status" => "error",
                        "message" => "can't save product!!!",
                    ], 500);
                }
            }
        }
    }

    // /**
    //  * Display the specified resource.
    //  *
    //  * @param  int  $id
    //  * @return \Illuminate\Http\Response
    //  */
    public function show($id)
    {

        $products = Product::where("pdId", $id)->first();
        if (!is_null($products)) {
            try {
                $products->update(["pdprogression" => "complete"]);
                return response()->json([
                    "status" => "sucess",
                    "message" => "editcomplete !!"
                ], 200);
            } catch (Exception $e) {
                return response()->json([
                    "status" => "failed",
                    "message" => "ບໍ່ສາມາດແກ້ໄຂ active "
                ], 500);
            }
        } else {
            return response()->json([
                "status" => "failed",
                "message" => "ຂໍ້ມູນບໍ່ຕົງກັນ"
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $sizes = Product::where('pdId', $id)->first();
        if (!is_null($sizes)) {
            try {
                $sizes->update(['pdprogression' => 'remove']);
                return response()->json([
                    "status" => "success",
                    "message" => "ລືບຂໍ້ມູນສິນຄ້າສຳເລັດແລ້ວ"
                ], 200);
            } catch (Exception $e) {
                return response()->json([
                    "status" => "error",
                    "message" => "can't delete product"
                ], 500);
            }
        } else {
            return response()->json([
                "status" => "error",
                "message" => "Data Id mismatch of product"
            ], 500);
        }
    }
}
