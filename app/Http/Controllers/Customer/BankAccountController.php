<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Customer\BankAccount;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class BankAccountController extends Controller
{
    public function __construct()
    {
        Auth::shouldUse('cust');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $bank = BankAccount::where("bkState", "!=", "remove")->get();
        $maxID = BankAccount::max("bkId");
        if (!is_null($bank)) {
            foreach ($bank as $value) {
                $value->bkImage = json_decode($value->bkImage);
            }
            return response()->json([
                "status" => "success",
                "count" => count($bank),
                "maxId" => $maxID,
                "data" => $bank
            ], 200);
        } else {
            return response()->json([
                "status" => "error",
                "message" => "server not loading!!!"
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
        $validatoins = Validator::make($request->all(), [
            "bkName" => "required",
            "bkAccount" => "required",
            "bkNumberic" => "required",
            "bkImage" => "required|mimes:png,jepg,jpg|max:1024"
        ]);
        if ($validatoins->fails()) {
            return response()->json([
                "status" => "error not required",
                "message" => $validatoins->errors()
            ], 500);
        } else {

            if ($request->file('bkImage')) {
                $files = $request->bkImage;
                $photos = time() . rand(1, 100) . '.' . $files->getClientOriginalName();
                $files->move(public_path("storage/images/bank"), $photos);

                try {
                    $banks = new  BankAccount();
                    $banks->bkName = $request->bkName;
                    $banks->bkAccount = $request->bkAccount;
                    $banks->bkNumberic = $request->bkNumberic;
                    $banks->bkImage = json_encode($photos);
                    $banks->bkState = $request->bkState;
                    $banks->save();

                    return response()->json([
                        "status" => "success",
                        "message" => "ບັນທືກຂໍ້ມູນ ຄິວອາໂຄດສຳເລັດແລ້ວ",
                        "data" => $banks
                    ], 200);
                } catch (Exception $e) {
                    return response()->json([
                        "status" => "erroe",
                        "message" => $e,
                    ], 500);
                }
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
        $banks = BankAccount::where("bkId", $id)->where("bkState", "=", "on")->get();
        foreach ($banks as $value) {
            $value->bkImage = json_decode($value->bkImage);
        }
        if (!is_null($banks)) {
            return response()->json([
                "status" => "success",
                "data" => $banks
            ], 200);
        } else {
            return response()->json([
                "status" => "error",
                "message" => "Data ID Mismapt"
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
    public function update(Request $request)
    {
        $validatoins = Validator::make($request->all(), [
            "bkId" => "required",
            "bkName" => "required",
            "bkAccount" => "required",
            "bkNumberic" => "required",
        ]);
        if ($validatoins->fails()) {
            return response()->json([
                "status" => "error not required",
                "message" => $validatoins->errors()
            ], 500);
        } else {

            $updateBank = BankAccount::where("bkId", $request['bkId'])->first();

            if ($request->file('bkImage')) {

                // update image
                $files = $request->bkImage;
                $photos = time() . rand(1, 100) . '.' . $files->getClientOriginalName();
                $files->move(public_path("storage/images/bank"), $photos);

                ///remove image
                $photo_old = str_replace('"', '', $updateBank->bkImage);
                $imges = 'storage/images/bank' . $photo_old;
                if (file_exists(public_path($imges))) {
                    unlink(public_path($imges));
                }

                try {
                    // $updates = BankAccount::where("bkId", $request['bkId'])->first();
                    $updateBank->update([
                        "bkName" => $request->bkName,
                        "bkAccount" => $request->bkAccount,
                        "bkNumberic" => $request->bkNumberic,
                        "bkImage" => json_encode($photos),
                        "bkState" => $request->bkState
                    ]);
                    return response()->json([
                        "status" => "success",
                        "message" => "ແກ້ໄຂຂໍ້ມູນ ຄິວອາໂຄດສຳເລັດແລ້ວ",
                        "data" => $updateBank
                    ], 200);
                } catch (Exception $e) {
                    return response()->json([
                        "status" => "error",
                        "message" => "can't update Bank Account",
                    ], 500);
                }
            } else {
                return response()->json([
                    "status" => "error",
                    "message" => "pleasc select Image before!!!",
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
        $remove = BankAccount::where("bkId", $id)->first();
        if (!is_null($remove)) {
            $remove->update([
                "bkState" => "remove"
            ]);
            return response()->json([
                "status" => "success",
                "message" => "ລືບຂໍ້ມູນ ຄິວອາໂຄດສຳເລັດແລ້ວ"
            ], 200);
        } else {
            return response()->json([
                "status" => "error",
                "message" => "can't remove bank values"
            ], 500);
        }
    }
}
