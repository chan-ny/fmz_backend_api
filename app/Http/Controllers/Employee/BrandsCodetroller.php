<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Emplyee\Brand;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BrandsCodetroller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $brands = Brand::where("bstate", "!=", "remove")->orderBy("bId", "desc")->get();
        $productBrand = Brand::where("bstate", "=", "on")
            ->orderBy("bId", "desc")
            ->get();
        $brandmax = Brand::max('bId');
        if (!is_null($brands)) {
            return response()->json([
                "status" => "success",
                "count" => count($brands),
                "data" => $brands,
                "productbrand" => $productBrand,
                "maxId" => $brandmax + 1
            ], 200);
        } else {
            return response()->json([
                "status" => "error",
                "message" => "Can't load Server now!"
            ], 503);
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
        $validators = Validator::make($request->all(), [
            "bId" => "required|min:1|max:5",
            "bname" => "required|min:6|max:50",
            "bstate" => "required"
        ]);

        if ($validators->fails()) {
            return response()->json([
                "status" => "Fialed",
                "message" => $validators->errors()
            ], 500);
        } else {
            try {
                $brands = Brand::create($request->all());

                if (!is_null($brands)) {
                    return response()->json([
                        "status" => "success",
                        "message" => "ບັນທືກ ແບຣນສິນຄ້າ ສຳເລັດແລ້ວ",
                        "data" => $brands
                    ], 200);
                } else {
                    return response()->json([
                        "status" => "error",
                        "message" => "ບໍ່ສາມາດ ບັນທືກ ແບຣນສິນຄ້າ",
                    ], 500);
                }
            } catch (Exception $e) {
                return response()->json([
                    "status" => "server error",
                    "message" => "ເຊີເວິ ບໍ່ສາມາດຕອບສະໜອງໄດ້",
                    "data" => $e
                ], 503);
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $brands = Brand::where("bId", $id)->where("bstate", "!=", "remove")->first();
        if (!is_null($brands)) {
            return response()->json([
                "status" => "sucess",
                "data" => $brands
            ], 200);
        } else {
            return response()->json([
                "status" => "error",
                "message" => "ຂໍ້ມູນບໍຕົງກັບຂໍ້ມູນໃດ"
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
        $validators = Validator::make($request->all(), [
            "bId" => "required|min:1|max:5",
            "bname" => "required|min:6|max:50",
            "bstate" => "required"
        ]);

        if ($validators->fails()) {
            return response()->json([
                "status" => "Fialed",
                "message" => $validators->errors()
            ], 500);
        } else {
            $brands = Brand::where('bId', $id)->first();
            if (!is_null($brands)) {
                try {

                    $brands->update($request->all());

                    if (!is_null($brands)) {
                        return response()->json([
                            "status" => "success",
                            "message" => "ແກ້ໄຂ ແບຣນສິນຄ້າ ສຳເລັດແລ້ວ",
                            "data" => $brands
                        ], 200);
                    } else {
                        return response()->json([
                            "status" => "error",
                            "message" => "ບໍ່ສາມາດ ແກ້ໄຂ ແບຣນສິນຄ້າ",
                        ], 500);
                    }
                } catch (Exception $e) {
                    return response()->json([
                        "status" => "Server Error",
                        "message" => "ເຊີເວິ ບໍ່ສາມາດຕອບສະໜອງໄດ້",
                        "data" => $e
                    ], 503);
                }
            } else {
                return response()->json([
                    "status" => "Fialed",
                    "message" => "data id mismatch",
                    "data" => $e
                ], 503);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $brands = Brand::where('bId', $id)->first();
        if (!is_null($brands)) {
            try {
                $brands->update(["bstate" => "remove"]);
                return response()->json([
                    "status" => "success",
                    "message" => "ລືບຂໍ້ມູນແບຣນສຳເລັດແລ້ວ ID = .$id."
                ], 200);
            } catch (Exception $e) {
                return response()->json([
                    "status" => "error",
                    "message" => "ບໍ່ສາມາດລົບຂໍ້ມຸນ"
                ], 500);
            }
        } else {
            return response()->json([
                "status" => "error",
                "message" => "ຂໍ້ມູນບໍ່ຕົງກັນ !!!"
            ], 500);
        }
    }
}
