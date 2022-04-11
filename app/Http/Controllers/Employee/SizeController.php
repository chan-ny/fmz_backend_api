<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Emplyee\Size;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SizeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sizes = Size::where("state", "!=", "remove")->orderBy("sId", "asc")->get();
        $productsizes = Size::where("state", "=", "on")
            ->orderBy("sId","asc")
            ->get();

        $maxId = Size::max('sId');
        if (!is_null($sizes)) {
            return response()->json([
                "status" => "success",
                "count" => count($sizes),
                "maxId" => $maxId + 1,
                "data" => $sizes,
                "productsize" => $productsizes
            ], 200);
        } else {
            return response()->json([
                "status" => "error",
                "message" => "server dos't loaded"
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
            "sname" => "required|min:1|max:15",
            "sdetail" => "required",
            "state" => "required|min:2|max:8",
        ]);

        if ($validators->fails()) {
            return response()->json([
                "status" => "error Required",
                "message" => $validators->errors()
            ], 500);
        } else {
            try {
                $sizes = Size::create($request->all());

                return response()->json([
                    "status" => "success",
                    "message" => "ບັນທຶກຂະໜາດສິນຄ້າສຳເລັດແລ້ວ",
                    "data" => $sizes
                ], 200);
            } catch (Exception $e) {
                return response()->json([
                    "status" => "error",
                    "message" => "can't save size"
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
            "sId" => "required|min:1|max:5",
            "sname" => "required|min:1|max:15",
            "sdetail" => "required",
            "state" => "required|min:2|max:8",
        ]);

        if ($validators->fails()) {
            return response()->json([
                "status" => "error Required",
                "message" => $validators->errors()
            ], 500);
        } else {
            $sizes = Size::where('sId', $id)->first();
            if (!is_null($sizes)) {
                try {
                    $sizes->update($request->all());

                    return response()->json([
                        "status" => "success",
                        "message" => "ແກ້ໄຂຂະໜາດສິນຄ້າສຳເລັດແລ້ວ",
                        "data" => $sizes
                    ], 200);
                } catch (Exception $e) {
                    return response()->json([
                        "status" => "error",
                        "message" => "can't update size!!!!"
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
        $sizes = Size::where('sId', $id)->first();
        if (!is_null($sizes)) {
            try {
                $sizes->update(['state' => 'remove']);
                return response()->json([
                    "status" => "success",
                    "message" => "ລືບຂໍ້ມູນຂະໜາດສຳເລັດແລ້ວ"
                ], 200);
            } catch (Exception $e) {
                return response()->json([
                    "status" => "error",
                    "message" => "can't delete Size"
                ], 500);
            }
        } else {
            return response()->json([
                "status" => "error",
                "message" => "Data Id mismatch"
            ], 500);
        }
    }
}
