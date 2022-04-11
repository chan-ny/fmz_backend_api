<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Employee\CheckTransfer;
use Illuminate\Http\Request;

class CheckTranferController extends Controller
{
    public function SavePayment(Request $request)
    {
        if (!is_null($request->all())) {
            CheckTransfer::create($request->all());
            return response()->json([
                "status" => "success",
                "message" => "save sucess"
            ], 200);
        }
    }
}
