<?php

use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\Auths\AdminAuthController;
use App\Http\Controllers\Auths\CustomerAuthController;
use App\Http\Controllers\Customer\BankAccountController;
use App\Http\Controllers\Customer\CustomerController;
use App\Http\Controllers\Customer\InvoiceController;
use App\Http\Controllers\Customer\ReportingController;
use App\Http\Controllers\Customer\SellController;
use App\Http\Controllers\Customer\ViewImportController;
use App\Http\Controllers\Customer\ViewOrderController;
use App\Http\Controllers\Customer\ViewSaleController;
use App\Http\Controllers\Employee\AdminController;
use App\Http\Controllers\Employee\BrandsCodetroller;
use App\Http\Controllers\Employee\CategoriesController;
use App\Http\Controllers\Employee\CheckTranferController;
use App\Http\Controllers\Employee\ColourController;
use App\Http\Controllers\Employee\DashboardContyroller;
use App\Http\Controllers\Employee\Import\CheckImportController;
use App\Http\Controllers\Employee\Import\ImportController;
use App\Http\Controllers\Employee\Product\ProductController;
use App\Http\Controllers\Employee\Product\StorageController;
use App\Http\Controllers\Employee\Product\UpdateProductController;
use App\Http\Controllers\Employee\Reporting\ReportProductController;
use App\Http\Controllers\Employee\SizeController;
use App\Http\Controllers\Employee\SMSController;
use App\Http\Controllers\Employee\StockinController;
use App\Http\Controllers\Employee\StoreController;
use App\Http\Controllers\Employee\SupplierController;
use App\Http\Controllers\Employee\ViewCustomerController;
use App\Http\Controllers\Fontend\ProductViewController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Route::post("register", [AuthenticationController::class, 'register']);
// Route::post("login", [AuthenticationController::class, 'login']);

// Route::group(['middleware' => ['auth:sanctum']], function () {
//     Route::get("access", [AuthenticationController::class, 'access']);
// });



Route::get('hello', function () {
    return "hello";
});
Route::post('admin_register', [AdminAuthController::class, 'register']);
Route::post('admin_login', [AdminAuthController::class, 'login']);

Route::group(['middleware' => 'auth:admin'], function () {
    Route::get('admin', [AdminAuthController::class, 'userProfile']);

    ///router for Employee
    Route::resource("brand", BrandsCodetroller::class);
    Route::resource("category", CategoriesController::class);
    Route::resource("colour", ColourController::class);
    Route::resource("size", SizeController::class);
    Route::resource("supplier", SupplierController::class);

    ///router for product
    Route::resource("product", ProductController::class);
    Route::resource("storage", StorageController::class);
    Route::post("addstorage", [StorageController::class, "createStorage"]);


    ////router for Employee Update Product
    Route::get('showproduct/{id}', [UpdateProductController::class, "showproduct"]);
    Route::get('showstorage/{id}', [UpdateProductController::class, "showstorage"]);
    Route::get('showcolour/{id}', [UpdateProductController::class, "showcolour"]);
    Route::get('showcategory/{id}', [UpdateProductController::class, "showcategory"]);
    Route::get('showbrand/{id}', [UpdateProductController::class, "showbrand"]);
    Route::get('showsupplier/{id}', [UpdateProductController::class, "showsupplier"]);
    Route::post('updateproduct', [UpdateProductController::class, "updateProduct"]);

    //router for Employee Import
    Route::get('selectproduct/{supid}', [ImportController::class, "searchProduct"]);
    Route::get('selectstorage/{product}/{size}', [ImportController::class, "searchStorage"]);
    Route::post('import', [ImportController::class, "storeImport"]);
    Route::post('importlist', [ImportController::class, "storeImportlist"]);
    Route::get('checkbill/{id}', [ImportController::class, "checkBillImport"]);
    Route::get('showimportlist', [ImportController::class, "displayImport_list"]);
    Route::get('searchsize/{id}', [ImportController::class, 'SearchSize']);

    ///router for Employee check import
    Route::get('checkbillimport', [CheckImportController::class, "checkImport"]);
    Route::get('listproduct/{impId}', [CheckImportController::class, "listProduct"]);
    Route::put('ch_number/{id}', [CheckImportController::class, "checkNumber"]);
    Route::get('deleteitem/{id}', [CheckImportController::class, "DeleteItemImport"]);
    Route::get('deletebill/{id}', [CheckImportController::class, "DeleteBillImport"]);
    Route::get('ch_final/{id}', [CheckImportController::class, "CheckFinal"]);
    Route::get('storycheck', [CheckImportController::class, "StoriesImport"]);

    ///router stockIn
    Route::get('searchbill', [StockinController::class, "SearchBill"]);
    Route::get('listbill/{id}', [StockinController::class, "listDetail"]);
    Route::put('stockadd/{impId}', [StockinController::class, "Store"]);
    Route::get('historystock', [StockinController::class, "historyStock"]);

    // router view customer
    Route::get("view_customer", [ViewCustomerController::class, "index"]);
    Route::get("checktranfer", [SellController::class, "CheckTransfer"]);
    Route::get("finaltranfer", [SellController::class, "finalTransfer"]);
    Route::get("viewtranfer/{id}", [SellController::class, "ViewTransfer"]);
    Route::put("committ/{id}", [SellController::class, "CommitTranfer"]);
    Route::put("cencellbill/{id}", [InvoiceController::class, "CancellBill"]);


    /// router view
    Route::get("viewimport", [ViewImportController::class, 'ViewimportFinal']);
    Route::get("analytic", [ViewImportController::class, 'Analytical']);

    Route::get("viewsale", [ViewSaleController::class, 'ViewSale']);
    Route::get("analyticalsale", [ViewSaleController::class, 'AnalyticalSale']);

    /// router Report sale
    Route::get('reportsale', [ReportingController::class, 'ReportSale']);
    Route::post('rsday', [ReportingController::class, 'ReportDay']);
    Route::get('reportproduct', [ReportingController::class, 'ReportProduct']);
    Route::get('reportcus', [ReportingController::class, 'ReportCustomer']);

    //// router report product
    Route::get("reportproduct/{pdId}", [ReportProductController::class, "ReportProduct"]);
    Route::get("reportstorage/{pdId}", [ReportProductController::class, "ReportStorage"]);

    ///router report customer
    Route::get("reportcustomer/{cusId}", [ReportProductController::class, "ReportCustomer"]);

    //router report import
    Route::post('reportimport', [ReportingController::class, 'ReportImport']);
    Route::get('reportdetail_import/{id}', [ReportingController::class, 'viewDetailImport']);

    ////router Dashboard
    Route::get('acus', [DashboardContyroller::class, 'AnalyticelCustomer']);
    Route::get('sale_day', [DashboardContyroller::class, 'SaleDay']);
    Route::get('sale_all', [DashboardContyroller::class, 'saleAll']);
    Route::get('import_all', [DashboardContyroller::class, 'importAll']);
    Route::get('analytical_salebest', [DashboardContyroller::class, 'AnalyticalSale']);
    Route::get('viewadmin', [DashboardContyroller::class, 'ViewAdmin']);
    Route::get('viewcustomer_salebest', [DashboardContyroller::class, 'CustomerSaleBest']);

    //checktranfer
    Route::post('tranfercommit', [CheckTranferController::class, 'SavePayment']);

    //store
    Route::resource('store', StoreController::class);
    Route::post('update_store', [StoreController::class, 'update']);
    Route::get('select_store', [StoreController::class, 'show']);

});



////router Customer
Route::post('cust_register', [CustomerController::class, 'store']);
Route::post('cust_login', [CustomerAuthController::class, 'login']);

Route::group(['middleware' => 'auth:cust'], function () {
    Route::get('customer', [CustomerAuthController::class, 'userProfile']);
    Route::get("cust_logout", [CustomerAuthController::class, 'logout']);
    Route::resource("customer", CustomerController::class);

    //order
    Route::post("savebill", [InvoiceController::class, 'SaveBill']);
    Route::post("savepayment", [InvoiceController::class, 'savePayment']);

    ///pwd
    // Route::put('changpwd/{id}', [CustomerController::class, 'editpassword']);
});

////fontend
// Route::post("customer", [CustomerController::class, "store"]);
Route::post('producthome', [ProductViewController::class, "ProductHome"]);
Route::get('viewcategories', [ProductViewController::class, "ViewCategory"]);
Route::get('detailproduct/{pdId}', [ProductViewController::class, "detailProduct"]);
Route::get('viewsize/{pdId}', [ProductViewController::class, "viewSize"]);
Route::post('viewproduct', [ProductViewController::class, "DisplayProduct"]);

Route::get("orderpay/{id}", [ViewOrderController::class, 'OrderPayment']);
Route::get("vieworder/{id}", [ViewOrderController::class, 'ViewOrder']);
Route::get("sellbest", [SellController::class, 'sellBest']);
Route::get("viewpdf/{id}", [SellController::class, 'vireOrderPDF']);

Route::post('checkphone', [SMSController::class, 'CheckPhone']);
Route::post('SMS', [SMSController::class, 'sendSMS']);
Route::get('otp/{id}', [CustomerController::class, 'ResetOTP']);
Route::put('resetpassword/{id}', [CustomerController::class, 'Resetpassword']);

Route::put('changpwd/{id}', [CustomerController::class, 'editpassword']);

///bank
Route::resource("bank", BankAccountController::class);
Route::post("updatebank", [BankAccountController::class, 'update']);

Route::get('select_store', [StoreController::class, 'show']);
