<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Employee\Store;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class StoreController extends Controller
{
    public function __construct()
    {
        Auth::shouldUse('admin');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $store = DB::table('stores')->get();
        if (!is_null($store)) {

            foreach ($store as $value) {
                $value->store_image = json_decode($value->store_image);
            }

            return response()->json([
                "status" => "success",
                "count" => count($store),
                "data" => $store
            ], 200);
        } else {
            return response()->json([
                "status" => "error",
                "message" => "server not found"
            ], 501);
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
        $valilator = Validator::make($request->all(), [
            "store_name" => "required|max:50",
            "store_phone" => "required|max:15",
            "store_email" => "required|max:50",
            "store_website" => "required|max:30",
            "store_address" => "required",
            "store_state" => "required|max:15"
        ]);
        if ($valilator->fails()) {
            return response()->json([
                "status" => "error required",
                "message" => $valilator->errors()
            ], 500);
        } else {
            if ($request->file('store_image')) {
                $files = $request->store_image;
                $photos = time() . rand(1, 100) . '.' . $files->getClientOriginalName();
                $files->move(public_path("storage/images/store"), $photos);

                try {
                    $store = new  Store();
                    $store->store_name = $request->store_name;
                    $store->store_phone = $request->store_phone;
                    $store->store_email = $request->store_email;
                    $store->store_website = $request->store_website;
                    $store->store_address = $request->store_address;
                    $store->store_image = json_encode($photos);
                    $store->store_state = $request->store_state;
                    $store->save();

                    return response()->json([
                        "status" => "success",
                        "message" => "ບັນທືກຂໍ້ມູນຮ້ານສຳເລັດແລ້ວ",
                        "data" => $store
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
    public function show()
    {
        $store = DB::table('stores')->where('store_state', 'on')->get();
        if (!is_null($store)) {
            return response()->json([
                "status" => "success",
                "count" => count($store),
                "data" => $store
            ], 200);
        } else {
            return response()->json([
                "status" => "error",
                "message" => "server not found"
            ], 404);
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
        $valilator = Validator::make($request->all(), [
            "storeId" => "required",
            "store_name" => "required|max:50",
            "store_phone" => "required|max:15",
            "store_email" => "required|max:50",
            "store_website" => "required|max:30",
            "store_address" => "required",
            "store_state" => "required|max:15"
        ]);
        if ($valilator->fails()) {
            return response()->json([
                "status" => "error required",
                "message" => $valilator->errors()
            ], 500);
        } else {

            if ($request->store_state == 'on') {
                $store = Store::where('store_state', 'on')->first();
                $store->update([
                    "store_state" => 'off'
                ]);

                $store = Store::where('storeId', $request['storeId'])->first();
                $store->update([
                    "store_state" => $request->store_state,
                ]);
                if ($request->file('store_image')) {
                    ///add image
                    $files = $request->store_image;
                    $photos = time() . rand(1, 100) . '.' . $files->getClientOriginalName();
                    $files->move(public_path("storage/images/store"), $photos);


                    ///remove image
                    $photo_old = str_replace('"', '', $store->store_image);
                    $imges = 'storage/images/store' . $photo_old;
                    if (file_exists(public_path($imges))) {
                        unlink(public_path($imges));
                    }

                    try {
                        $store->update([
                            "store_name" => $request->store_name,
                            "store_phone" => $request->store_phone,
                            "store_email" => $request->store_email,
                            "store_website" => $request->store_website,
                            "store_address" => $request->store_address,
                            "store_image" => json_encode($photos),

                        ]);
                        return response()->json([
                            "status" => "success",
                            "message" => "ແກ້ໄຂຂໍ້ມູນຮ້ານສຳເລັດແລ້ວ",
                            "data" => $store
                        ], 200);
                    } catch (Exception $e) {
                        return response()->json([
                            "status" => "erroe",
                            "message" => $e,
                        ], 500);
                    }
                } else {
                    try {
                        $store->update([
                            "store_name" => $request->store_name,
                            "store_phone" => $request->store_phone,
                            "store_email" => $request->store_email,
                            "store_website" => $request->store_website,
                            "store_address" => $request->store_address,
                        ]);
                        return response()->json([
                            "status" => "success",
                            "message" => "ແກ້ໄຂຂໍ້ມູນຮ້ານສຳເລັດແລ້ວ",
                            "data" => $store
                        ], 200);
                    } catch (Exception $e) {
                        return response()->json([
                            "status" => "erroe",
                            "message" => $e,
                        ], 500);
                    }
                }
            } else {
                $store = Store::where('storeId', $request['storeId'])->first();
                if ($request->file('store_image')) {
                    ///add image
                    $files = $request->store_image;
                    $photos = time() . rand(1, 100) . '.' . $files->getClientOriginalName();
                    $files->move(public_path("storage/images/store"), $photos);


                    ///remove image
                    $photo_old = str_replace('"', '', $store->store_image);
                    $imges = 'storage/images/store' . $photo_old;
                    if (file_exists(public_path($imges))) {
                        unlink(public_path($imges));
                    }

                    try {
                        $store->update([
                            "store_name" => $request->store_name,
                            "store_phone" => $request->store_phone,
                            "store_email" => $request->store_email,
                            "store_website" => $request->store_website,
                            "store_address" => $request->store_address,
                            "store_image" => json_encode($photos),
                            // "store_state" => $request->store_state,
                        ]);
                        return response()->json([
                            "status" => "success",
                            "message" => "ແກ້ໄຂຂໍ້ມູນຮ້ານສຳເລັດແລ້ວ",
                            "data" => $store
                        ], 200);
                    } catch (Exception $e) {
                        return response()->json([
                            "status" => "erroe",
                            "message" => $e,
                        ], 500);
                    }
                } else {
                    try {
                        $store->update([
                            "store_name" => $request->store_name,
                            "store_phone" => $request->store_phone,
                            "store_email" => $request->store_email,
                            "store_website" => $request->store_website,
                            "store_address" => $request->store_address,
                            // "store_state" => $request->store_state,
                        ]);
                        return response()->json([
                            "status" => "success",
                            "message" => "ແກ້ໄຂຂໍ້ມູນຮ້ານສຳເລັດແລ້ວ",
                            "data" => $store
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
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $store = Store::where('storeId', $id)->first();
        if ($store->store_state != 'on') {
            Store::where('storeId', $id)->delete();
            return response()->json([
                "statue" => "success",
                "message" => "ລືມຂໍ້ມູນສຳເລັດແລ້ວ"
            ], 200);
        } else {
            return response()->json([
                "statue" => "error",
                "message" => "can not delete"
            ], 404);
        }
    }
}
