<?php

namespace App\Http\Controllers\Employee\Product;

use App\Http\Controllers\Controller;
use App\Models\Employee\Storage;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class StorageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createStorage(Request $request)
    {
        $validators = Validator::make($request->all(), [
            "product_Id" => "required",
            "size_Id" => "required",
            "srqty" => "required|max:11",
        ]);

        if ($validators->fails()) {
            return response()->json([
                "status" => "error",
                "message" => $validators->errors()
            ], 500);
        } else {
            $storage = Storage::where("product_Id", $request->product_Id)
                ->where("size_Id", $request->size_Id)
                ->first();

            if (is_null($storage)) {
                try {
                    $save = Storage::create($request->all());
                    return response()->json([
                        "status" => "success",
                        "message" => "ບັນທືກຂະໜາດສຳເລັດແລ້ວ",
                        "data" => $save
                    ], 200);
                } catch (Exception $e) {
                    return response()->json([
                        "status" => "error",
                        "message" => "can't save Storage"
                    ], 500);
                }
            } else {
                if ($storage->srstate == 'on') {
                    return response()->json([
                        "status" => "error",
                        "message" => "Size have Duplicate"
                    ], 500);
                } else {
                    try {
                        $storage->update([
                            'srstate' => 'on', 
                            'srqty' => $request->srqty,
                        ]);
                        return response()->json([
                            "status" => "success",
                            "message" => "ບັນທືກຂະໜາດສຳເລັດແລ້ວ",
                            "data" => $storage
                        ], 200);
                    } catch (Exception $e) {
                        return response()->json([
                            "status" => "error",
                            "message" => "can't save Storage"
                        ], 500);
                    }
                }
            }
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!is_null($request->all())) {
            try {
                foreach ($request->all() as $value) {
                    Storage::create($value);
                }
                return response()->json([
                    "status" => "sucess",
                    "message" => "ບັນທືກທີ່ຈັດເກັບສຳເລັດແລ້ວ"
                ], 200);
            } catch (Exception $e) {
                return response()->json([
                    "status" => "error",
                    "message" => $e
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
        $storages = DB::table("tb_storage")
            ->where("pdId", "=", $id)
            ->where("pdprogression", "=", "complete")
            ->where('srstate', '=', 'on')
            ->get();
        if (!is_null($storages)) {
            return response()->json([
                "status" => "sucess",
                "data" => $storages
            ], 200);
        } else {
            return response()->json([
                "status" => "erro",
                "message" => "data Id mismatch"
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
        $storages = Storage::where("srId", $id)->first();

        if (!is_null($request->all())) {
            try {
                $storages->update($request->all());
                return response()->json([
                    "status" => "sucess",
                    "message" => "ແກ້ໄຂຂໍ້ມູນທີ່ຈັດເກັບສຳເລັດແລ້ວ"
                ], 200);
            } catch (Exception $e) {
                return response()->json([
                    "status" => "error",
                    "message" => "can't update storage"
                ], 500);
            }
        } else {
            return response()->json([
                "status" => "error",
                "message" => "data Id mismatch"
            ], 500);
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
        $storage = Storage::where('srId', $id)->first();
        if (!is_null($storage)) {
            try {
                $storage->update([
                    'srstate' => 'remove', 'srate' => 0, 'srqty' => 0, 'srcost' => 0,
                    'srprice' => 0
                ]);
                return response()->json([
                    "status" => "success",
                    "message" => "ລືບຂໍ້ມູນທີຈັດເກັບສຳເລັດແລ້ວ",
                    "data" => $storage
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
