<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Emplyee\Categories;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $category = Categories::where("ctstate", "!=", "remove")->orderBy("ctId", "desc")->get();
        $productCategory = Categories::where("ctstate", "=", "on")
            ->orderBy("ctId", "desc")
            ->get();

        $maxId = Categories::max('ctId');
        if (!is_null($category)) {
            return response()->json([
                "status" => "success",
                "count" => count($category),
                "maxId" => $maxId + 1,
                "data" => $category,
                "productcategory" => $productCategory
            ], 200);
        } else {
            return response()->json([
                "status" => "error",
                "message" => "server not found!!"
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
            "ctId" => "required|min:1|max:5",
            "ctname" => "required|min:4|max:100",
            "ctstate" => "required|min:2|max:8",
        ]);

        if ($validators->fails()) {
            return response()->json([
                "status" => "erroe Required",
                "message" => $validators->errors()
            ], 500);
        } else {
            try {
                $category = Categories::create($request->all());

                return response()->json([
                    "status" => "success",
                    "message" => "ບັນທຶກປະເພດສິນຄ້າສຳເລັດແລ້ວ",
                    "data" => $category
                ], 200);
            } catch (Exception $e) {
                return response()->json([
                    "status" => "error",
                    "message" => "can't save category"
                ], 500);
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
        $category = Categories::where("ctId", $id)->where("ctstate", "!=", "remove")->first();
        if (!is_null($category)) {
            return response()->json([
                "data" => $category
            ], 200);
        } else {
            return response()->json([
                "error" => "id not baland"
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
            "ctId" => "required|min:1|max:5",
            "ctname" => "required|min:4|max:100",
            "ctstate" => "required|min:2|max:8",
        ]);

        if ($validators->fails()) {
            return response()->json([
                "status" => "error Required",
                "message" => $validators->errors()
            ], 500);
        } else {
            $category = Categories::where('ctId', $id)->first();
            if (!is_null($category)) {
                try {
                    $category->update($request->all());

                    return response()->json([
                        "status" => "success",
                        "message" => "ແກ້ໄຂປະເພດສິນຄ້າສຳເລັດແລ້ວ",
                        "data" => $category
                    ], 200);
                } catch (Exception $e) {
                    return response()->json([
                        "status" => "error",
                        "message" => "can't update category"
                    ], 500);
                }
            } else {
                return response()->json([
                    "status" => "error Incurrent",
                    "message" => "data Id mismatch"
                ], 500);
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
        $category = Categories::where('ctId', $id)->first();
        if (!is_null($category)) {
            try {
                $category->update(["ctstate" => "remove"]);
                return response()->json([
                    "status" => "success",
                    "message" => "ລືບຂໍ້ມູນສຳເລັດແລ້ວ"
                ], 200);
            } catch (Exception $e) {
                return response()->json([
                    "status" => "error",
                    "message" => "ບໍ່ສາມາດລືບຂໍ້ມູນ"
                ], 500);
            }
        } else {
            return response()->json([
                "status" => "error",
                "message" => "ຂໍ້ມູນບໍ່ຕົງກັນ"
            ], 500);
        }
    }
}
