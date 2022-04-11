<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Emplyee\Supplier;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $suppliers = Supplier::where("supstate", "!=", 'remove')->orderBy("supId", "desc")->get();
        $productsuppliers = Supplier::where("supstate", "=", 'on')
            ->orderBy("supId", "desc")
            ->get();

        $maxId = Supplier::max('supId');
        if (!is_null($suppliers)) {
            return response()->json([
                "status" => "success",
                "count" => count($suppliers),
                "maxId" => $maxId + 1,
                "data" => $suppliers,
                "productsup" => $productsuppliers
            ], 200);
        } else {
            return response()->json([
                "Status" => "error",
                "Message" => "server dos't loading!!!"
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
            "supgender" => "required|min:4|max:10",
            "supfullname" => "required|min:6|max:100",
            "suptell" => "required|min:8|max:15",
            "supaddress" => "required|min:6",
            "supemail" => "required|email|unique:suppliers",
            "supstate" => "required|min:2|max:8",
        ]);

        if ($validators->fails()) {
            return response()->json([
                "status" => "error Required",
                "message" => $validators->errors()
            ], 500);
        } else {
            try {
                $suppliers = Supplier::create($request->all());
                return response()->json([
                    "status" => "success",
                    "message" => "ບັນທຶກຂໍ້ມູນຜູ້ສະໜອງສິນຄ້າສຳເລັດແລ້ວ",
                    "data" => $suppliers
                ], 200);
            } catch (Exception $e) {
                return response()->json([
                    "status" => "error",
                    "message" => "can't save suppiler!!!"
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
        $suppliers = Supplier::where('supId', $id)->first();
        if (!is_null($suppliers)) {
            return response()->json([
                "status" => "sucess",
                "data" => $suppliers
            ], 200);
        } else {
            return response()->json([
                "status" => "error",
                "message" => "ລະຫັດທີ່ຄົ້ນຫາບໍ່ຕົງກັບຂໍ້ມູນໃດໆ"
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
            "supId" => "required|min:1|max:5",
            "supgender" => "required|min:4|max:10",
            "supfullname" => "required|min:6|max:100",
            "suptell" => "required|min:8|max:15",
            "supaddress" => "required|min:6",
            "supstate" => "required",
        ]);

        if ($validators->fails()) {
            return response()->json([
                "status" => "error Required",
                "message" => $validators->errors()
            ], 500);
        } else {
            $suppliers = Supplier::where('supId', $id)->first();
            if (!is_null($suppliers)) {
                try {
                    $suppliers->update($request->all());

                    return response()->json([
                        "status" => "success",
                        "message" => "ແກ້ໄຂຂໍ້ມູນຜູ້ສະໜອງສິນຄ້າສຳເລັດແລ້ວ",
                        "data" => $suppliers
                    ], 200);
                } catch (Exception $e) {
                    return response()->json([
                        "status" => "error",
                        "message" => "can't update suppliers !!!"
                    ], 500);
                }
            } else {
                return response()->json([
                    "status" => "error Incurrent",
                    "message" => "data Id mismatch ??"
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
        $suppliers = Supplier::where('supId', $id)->first();
        if (!is_null($suppliers)) {
            try {
                $suppliers->update(["supstate" => 'remove']);
                return response()->json([
                    "status" => "success",
                    "message" => "ລືບຂໍ້ມູນສຳເລັດແລ້ວ"
                ], 200);
            } catch (Exception $e) {
                if (!is_null($suppliers)) {
                    return response()->json([
                        "status" => "error",
                        "message" => "ບໍ່ສາມາດລືບຂໍ້ມູນຜູ້ສະໜອງ"
                    ], 500);
                }
            }
        } else {
            return response()->json([
                "status" => "error",
                "message" => "data Id mismatch"
            ], 500);
        }
    }
}
