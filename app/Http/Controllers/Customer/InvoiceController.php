<?php

namespace App\Http\Controllers\Customer;

use App\Events\Notifycations;
use App\Http\Controllers\Controller;
use App\Models\Customer\Invoince;
use App\Models\Customer\Payment;
use App\Models\Customer\SellDetail;
use App\Models\Employee\Storage;
use App\Models\Emplyee\Product;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use PhpParser\Node\Expr\ArrayItem;

class InvoiceController extends Controller
{
    public function MaxBill()
    {
        $maxBill = DB::table('invoinces')->orderBy('invId', 'desc')->first();
        $auto = '';
        if (!is_null($maxBill)) {
            $auto = $maxBill->invId;
        } else {
            $auto = "IN00";
        }
        return ++$auto;
    }
    public function SaveBill(Request $request)
    {
        $maxbill =  $this->MaxBill();
        $validators = Validator::make($request->all(), [
            "customer_Id" => "required",
            "invQty" => "required",
            "invPrice" => "required",
            "invState" => "required"
        ]);
        if ($validators->fails()) {
            return response()->json([
                "status" => "error required",
                "message" => $validators->errors()
            ], 500);
        } else {
            try {
                $ID = $this->MaxBill();
                $invoice = $request->all();
                $invMaxId = ["invId" => $ID];
                $invoinceValue = $invMaxId + $invoice;

                $save =  Invoince::create($invoinceValue);
                $ListValue = '';
                $listSell = $request->list;
                $index = count($listSell);

                for ($i = 0; $i < $index; $i++) {
                    $NumberBill = array("invoince_Id" => $ID);
                    $ListValue = $listSell[$i] + $NumberBill;
                    SellDetail::create($ListValue);

                    $storages = Storage::where('srId',  $ListValue['storage_Id'])->first();
                    if (!is_null($storages)) {
                        $plus = $storages->srqty;
                        $plus -= $ListValue['sdQty'];
                        $storages->update(['srqty' => $plus]);
                    }
                }
                $name = null;
                broadcast(new Notifycations($name));
                return response()->json([
                    "statue" => "success",
                    "message" => "ສັ່ງຊື່ສຳເລັດແລ້ວ",
                    "data" => $save
                ], 200);
            } catch (Exception $e) {
                return response()->json([
                    "statue" => "error",
                    "message" => "can't ordered",
                ], 500);
            }
        }
    }
    public function CancellBill(Request $request, $id)
    {
        try {
            $index = count($request->all());
            $ListValue = $request->all();

            for ($i = 0; $i < $index; $i++) {
                $storages = Storage::where('srId',  $ListValue[$i]['srId'])->first();
                if (!is_null($storages)) {
                    $plus = $storages->srqty;
                    $plus += $ListValue[$i]['sdQty'];
                    $storages->update(['srqty' => $plus]);
                }
            }
            Payment::where('invoince_Id', $id)->delete();
            SellDetail::where('invoince_Id',  $id)->delete();
            Invoince::where('invId', $id)->delete();

            broadcast(new Notifycations("ຍົກເລີກບີນ: .$id."));

            return response()->json([
                "status" => "success",
                "message" => "ຍົກເລີກສຳເລັດແລ້ວ"
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                "statue" => "error",
                "message" => "can't delete Order",
            ], 500);
        }
    }

    public function savePayment(Request $request)
    {
        $validators = Validator::make($request->all(), [
            "invoince_Id" => "required",
            "bank_Id" => "required",
            "pmImage" => "required|mimes:jpeg,jpg,png",
            "pmNumbersix" => "required",
            "pmPrice" => "required",
            "pmState" => "required",
        ]);
        if ($validators->fails()) {
            return response()->json([
                "status" => "error required",
                "messagbe" => $validators->errors()
            ], 500);
        } else {
            if ($request->file('pmImage')) {
                try {
                    $files = $request->pmImage;
                    $photo = time() . rand(1, 100) . '.' . $files->getClientOriginalName();
                    $files->move(\public_path('storage/images/payment'), $photo);

                    $saves = new Payment();
                    $saves->invoince_Id = $request->invoince_Id;
                    $saves->bank_Id = $request->bank_Id;
                    $saves->pmImage = \json_encode($photo);
                    $saves->pmNumbersix = $request->pmNumbersix;
                    $saves->pmPrice = $request->pmPrice;
                    $saves->pmState = $request->pmState;
                    $saves->save();

                    return response()->json([
                        "status" => "success",
                        "message" => "ຈ່າຍເງິນສຳເລັດແລ້ວ"
                    ], 200);
                } catch (Exception $e) {
                    return response()->json([
                        "status" => "error",
                        "message" => "can't Paymenting"
                    ], 500);
                }
            } else {
                return response()->json([
                    "status" => "error",
                    "message" => "please select transfer image "
                ], 404);
            }
        }
    }
};
