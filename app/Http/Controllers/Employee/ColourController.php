<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Emplyee\Colour;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ColourController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $colours = Colour::where("cstate", "!=", "remove")->orderBy("cId", "desc")->get();
        $productcolours = Colour::where("cstate", "=", "on")
            ->orderBy("cId", "desc")
            ->get();

        $maxId = Colour::max('cId');
        if (!is_null($colours)) {
            return response()->json([
                "status" => "success",
                "count" => count($colours),
                "maxId" => $maxId + 1,
                "data" => $colours,
                "productcolour" => $productcolours
            ], 200);
        } else {
            return response()->json([
                "status" => "error",
                "message" => "server not found!!!"
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
        $validators = Validator::make($request->all(), [
            "cname" => "required|min:3|max:50",
            "cstate" => "required|min:2|max:8",
        ]);

        if ($validators->fails()) {
            return response()->json([
                "status" => "error Required",
                "message" => $validators->errors()
            ], 500);
        } else {
            try {
                $colours = Colour::create($request->all());

                return response()->json([
                    "status" => "success",
                    "message" => "ບັນທຶກສີສິນຄ້າສຳເລັດແລ້ວ",
                    "data" => $colours
                ], 200);
            } catch (Exception $e) {
                return response()->json([
                    "status" => "error",
                    "message" => "can't save colur"
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
            "cId" => "required|min:1|max:5",
            "cname" => "required|min:3|max:50",
            "cstate" => "required|min:2|max:8",
        ]);

        if ($validators->fails()) {
            return response()->json([
                "status" => "error Required",
                "message" => $validators->errors()
            ], 500);
        } else {
            $colours = Colour::where('cId', $id)->first();
            if (!is_null($colours)) {
                try {
                    $colours->update($request->all());

                    return response()->json([
                        "status" => "success",
                        "message" => "ແກ້ໄຂຂໍ້ມູນສີສິນຄ້າສຳເລັດແລ້ວ",
                        "data" => $colours
                    ], 200);
                } catch (Exception $e) {
                    return response()->json([
                        "status" => "error",
                        "message" => "ບໍ່ສາມາດແກ້ໄຂຂໍ້ມູນສີສິນຄ້າ!!!!"
                    ], 500);
                }
            } else {
                return response()->json([
                    "status" => "error Incurrent",
                    "message" => "data ID mismatch"
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
        $colours = Colour::where('cId', $id)->first();
        if (!is_null($colours)) {
            try {
                $colours->update(["cstate" => "remove"]);
                return response()->json([
                    "status" => "success",
                    "message" => "ລືບຂໍ້ມູນສຳເລັດແລ້ວ"
                ], 200);
            } catch (Exception $e) {
                return response()->json([
                    "status" => "error",
                    "message" => "can't remove colur"
                ], 500);
            }
        } else {
            return response()->json([
                "status" => "error",
                "message" => "data Id mismatch"
            ], 500);
        }
    }
}
