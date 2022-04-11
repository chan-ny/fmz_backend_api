<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Auths\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ViewCustomerController extends Controller
{
    public function __construct()
    {
        Auth::shouldUse('admin');
    }
    public function index()
    {
        $customer = Customer::where('cus_state', '=', 'on')->get();
        if (!is_null($customer)) {
            return response()->json([
                "status" => "success",
                "count" => count($customer),
                "data" => $customer
            ], 200);
        } else {
            return response()->json([
                "status" => "error",
                "data" => "error incurrect server",
            ], 503);
        }
    }
}
