<?php

namespace App\Http\Controllers\Employee\Product;

use App\Http\Controllers\Controller;
use App\Models\Emplyee\Brand;
use App\Models\Emplyee\Categories;
use App\Models\Emplyee\Colour;
use App\Models\Emplyee\Product;
use App\Models\Emplyee\Supplier;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UpdateProductController extends Controller
{
    public function showproduct($id)
    {
        $products = Product::where("pdId", "=", $id)->where("pdprogression", "=", "complete")->get();

        foreach ($products as $value) {
            $value->pdphotos_sub = json_decode($value->pdphotos_sub);
        }

        if (!is_null($products)) {
            return response()->json([
                "data" => $products
            ], 200);
        } else {
            return response()->json([
                "message" => "ຂໍ້ມູນບໍ່ຕົງກັນ"
            ], 500);
        }
    }

    public function showcategory($id)
    {
        $category = Categories::where("ctId", $id)->first();
        if (!is_null($category)) {
            return response()->json([
                "data" => $category
            ], 200);
        } else {
            return response()->json([
                "message" => "ຂໍ້ມູນບໍ່ຕົງກັນ"
            ], 500);
        }
    }
    public function showbrand($id)
    {
        $brand = Brand::where("bId", $id)->first();
        if (!is_null($brand)) {
            return response()->json([
                "Data" => $brand
            ], 200);
        } else {
            return response()->json([
                "message" => "ຂໍ້ມູນບໍ່ຕົງກັນ"
            ], 500);
        }
    }
    public function showsupplier($id)
    {
        $supplier = Supplier::where("supId", $id)->first();
        if (!is_null($supplier)) {
            return response()->json([
                "data" => $supplier
            ], 200);
        } else {
            return response()->json([
                "message" => "ຂໍ້ມູນບໍ່ຕົງກັນ"
            ], 500);
        }
    }
    public function showcolour($id)
    {
        $colours = Colour::where("cId", $id)->first();
        if (!is_null($colours)) {
            return response()->json([
                "data" => $colours
            ], 200);
        } else {
            return response()->json([
                "message" => "ຂໍ້ມູນບໍ່ຕົງກັນ"
            ], 500);
        }
    }
    public function updateProduct(Request $request)
    {
        $validators = Validator::make($request->all(), [
            "pdId" => "required|min:1|max:5",
            "brand_Id" => "required|min:1|max:5",
            "categories_Id" => "required|min:1|max:5",
            "colour_Id" => "required|min:1|max:5",
            "supplier_Id" => "required|min:1|max:5",
            "pdname" =>  "required|min:4|max:30",
            "pdfullname" =>  "required|min:8|max:120",
            "pdprogression" =>  "required|min:2|max:15",
        ]);

        if ($validators->fails()) {
            return response()->json([
                "Status" => "Feiled Required",
                "Message" => $validators->errors()
            ], 500);
        } else {
            $updateproduct = Product::find($request->pdId);

            if (!is_null($updateproduct)) {
                try {
                    if ($request->file('pdphotos_main')) {
                        //add photo main
                        $photo_new = $request->pdphotos_main;
                        $photos = time() . rand(1, 100) . '.' .  $photo_new->getClientOriginalName();
                        $photo_new->move(public_path('storage/images/clothing'), $photos);


                        //remove photo main
                        $photos_main = str_replace('"', '', $updateproduct->pdphotos_main);
                        $image = 'storage/images/clothing/' . $photos_main;
                        if (file_exists(public_path($image))) {

                            unlink(public_path($image));
                        }

                        //update photo main
                        $upphotomain = Product::where("pdId", $request->pdId)->first();
                        $upphotomain->update(
                            ["pdphotos_main" => json_encode($photos)]
                        );
                    }
                    $photo_sub = [];
                    if ($request->file('pdphotos_sub')) {
                        foreach ($request->file('pdphotos_sub') as $file) {
                            $sub = time() . rand(1, 100) . '.' . $file->getClientOriginalName();
                            $file->move(public_path('storage/images/clothing'), $sub);
                            $photo_sub[] = $sub;
                        }

                        $updateproductsub = Product::where("pdId", $request->pdId)->get();

                        foreach ($updateproductsub as $value) {
                            $value->pdphotos_sub = json_decode($value->pdphotos_sub);
                        }

                        foreach ($updateproductsub[0]->pdphotos_sub as $file) {
                            $path_sub = 'storage/images/clothing/' . $file;

                            if (file_exists(public_path($path_sub))) {

                                unlink(public_path($path_sub));
                            }
                        }
                    }
                    if ($request->file('pdphotos_main') && $request->file('pdphotos_sub')) {
                        try {

                            $updateproducts = Product::where("pdId", $request->pdId)->first();
                            $updateproducts->update(
                                ["pdphotos_sub" => json_encode($photo_sub)]
                            );

                            return response()->json([
                                "Status" => "success",
                                "Message" => "ແກ້ໄຂຂໍ້ມູນສິນຄ້າສຳເລັດແລ້ວ",
                                "data" => "products"
                            ], 200);
                        } catch (Exception $e) {
                            return response()->json([
                                "Status" => "error",
                                "Message" => "ເກີດຂໍ້ມູນຜີດພາດໃນການແກ້ໄຂສິນຄ້າ ກະລູນາລອງໄໝ່ !!!",
                            ], 500);
                        }
                    } else {

                        $updateproducts = Product::where("pdId", $request->pdId)->first();
                        $updateproducts->update($request->all());

                        return response()->json([
                            "Status" => "success",
                            "Message" => "ແກ້ໄຂຂໍ້ມູນສິນຄ້າສຳເລັດແລ້ວ",
                            "data" => "products"
                        ], 200);
                    }

                    echo "ok";
                } catch (Exception $e) {
                    return response()->json([
                        "Status" => "Failed",
                        "message"  => $e
                    ], 500);
                }
            } else {
                return response()->json([
                    "Status" => "Failed",
                    "message"  => "values id not found"
                ], 500);
            }
        }
    }

}
