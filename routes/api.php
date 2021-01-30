<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Admin\AdminController;
use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\Api\Users\CashierController;
use App\Http\Controllers\Api\Invoice\InvoiceController;
use App\Http\Controllers\Api\Products\ProductsController;
use App\Http\Controllers\Api\Customer\CustomersController;
use App\Http\Controllers\Api\Exports\ExportInvoiceController;
use App\Http\Controllers\Api\Exports\ExportPaymentController;
use App\Http\Controllers\Api\Products\CategoriesController;
use App\Http\Controllers\Api\InventoryManagement\BadOrdersController;
use App\Http\Controllers\Api\InventoryManagement\SuppliersController;
use App\Http\Controllers\Api\InventoryManagement\PurchaseOrdersController;
use App\Http\Controllers\Api\Pos\PosController;
use App\Http\Controllers\Api\SalesReturn\SalesReturnController;
use Illuminate\Support\Facades\App;

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


Route::group(['middleware' => 'api'], function ()
{
    /**
     * Authentication
     */
    Route::post('/register', [ RegisterController::class, 'register' ]);
    Route::post('/login', [ LoginController::class, 'login' ]);

    Route::group(['middleware' => 'auth:api'], function ()
    {
        Route::post('/logout', [ LoginController::class, 'logout' ]);
    });

});


/**
 * Get current authenticated user
 */

Route::group(['middleware' => 'auth:api'], function ()
{
    Route::get('/authenticated-user', [AuthController::class, 'getAuthenticatedUser']);
    Route::get('/authenticated-user-roles', [AuthController::class, 'getAuthenticatedUserWithRoles' ]);
});


/**
 * Cashier Controller
 */
Route::group([
        'prefix' => 'cashier',
        'middleware' => 'auth:api'
], function ()
{
    Route::get('/auth', [CashierController::class, 'cashier']);
});


/**
 * Admin Controller
 */
Route::group([
    'prefix' => 'admin',
    'middleware' => 'auth:api'
], function()
{
    Route::get('/auth', [AdminController::class, 'admin']);
 });


 /**
  * * Category Controller
  */

Route::group(['prefix' => 'category'], function ()
{
    Route::get('/', [CategoriesController::class, 'index']);
    Route::post('/', [CategoriesController::class, 'store']);
    Route::put('/', [CategoriesController::class, 'update']);
    Route::delete('/', [CategoriesController::class, 'destroy']);
});


/**
 * *  Product Controller
 */

Route::group(['prefix' => 'product'], function ()
{
    Route::get('/', [ProductsController::class, 'index']);
    Route::post('/', [ProductsController::class, 'store']);
    Route::put('/', [ProductsController::class, 'update']);
    Route::delete('/', [ProductsController::class, 'destroy']);
});


/**
 * * Suppliers
 */

Route::group(['prefix' => 'supplier'], function ()
{
    Route::get('/', [SuppliersController::class, 'index']);
    Route::post('/', [SuppliersController::class, 'store']);
    Route::put('/', [SuppliersController::class, 'update']);
    Route::delete('/', [SuppliersController::class, 'destroy']);
});


/**
 * * Purchase Order
 */

Route::group(['prefix' => 'purchase-order'], function ()
{
    Route::get('/', [PurchaseOrdersController::class, 'index']);
    Route::post('/purchase-order-detail', [PurchaseOrdersController::class, 'show']);
    Route::post('/', [PurchaseOrdersController::class, 'store']);
    Route::put('/', [PurchaseOrdersController::class, 'upsert']);
    Route::put('/to-receive', [PurchaseOrdersController::class, 'toReceivePurchaseOrder']);
    Route::put('/mark-all-as-received', [PurchaseOrdersController::class, 'markAllPurchasedOrderAsReceived']);
    Route::delete('/', [PurchaseOrdersController::class, 'destroy']);
    Route::delete('/products', [PurchaseOrdersController::class, 'deleteProducts']);
});


/**
 * Bad Order
 *
 * Todo: Update Method
 */

Route::group(['prefix' => 'bad-orders'], function ()
{
    Route::get('/', [BadOrdersController::class, 'index']);
    Route::post('/details', [BadOrdersController::class, 'show']);
    Route::post('/', [BadOrdersController::class, 'store']);
    Route::put('/', [BadOrdersController::class, 'update']);
    Route::delete('/', [BadOrdersController::class, 'destroy']);
});


/**
 * Customers
 *
 * * Done
 */

Route::group(['prefix' => 'customers'], function ()
{
    Route::get('/', [CustomersController::class, 'index']);
    Route::post('/details', [CustomersController::class, 'show']);
    Route::post('/', [CustomersController::class, 'store']);
    Route::put('/', [CustomersController::class, 'update']);
    Route::delete('/', [CustomersController::class, 'destroy']);
});


/**
 *
 * Pos
 */

Route::group(['prefix' => 'pos'], function ()
{
    Route::get('/order-lists', [PosController::class, 'index']);
    Route::post('/cart', [PosController::class, 'show']);
    Route::post('/add-to-cart', [PosController::class, 'store']);
    Route::post('/to-pay', [PosController::class, 'showAmountToPay']);
    Route::post('/process-payment', [PosController::class, 'processPayment']);
    Route::post('/invoice', [PosController::class, 'invoice']);
    Route::put('/increase-item-qty', [PosController::class, 'incrementQuantity']);
    Route::put('/decrease-item-qty', [PosController::class, 'decrementQuantity']);
    Route::put('/item-qty', [PosController::class, 'update']);
    Route::delete('/item', [PosController::class, 'removeItems']);
    Route::delete('/cancel-orders', [PosController::class, 'cancelOrders']);
});


/**
 *
 * Invoice
 */
Route::group(['prefix' => 'invoices'], function ()
{
    Route::get('/', [InvoiceController::class, 'index']);
    Route::post('/details', [InvoiceController::class, 'show']);
    Route::put('', [InvoiceController::class, 'update']);
    Route::delete('/', [InvoiceController::class, 'destroy']);
});



/**
 *
 * Todo: Update Method
 *
 * Sales Return
 */

Route::group(['prefix' => 'sales-return'], function ()
{
    Route::get('/', [SalesReturnController::class, 'index']);
    Route::post('/', [SalesReturnController::class, 'store']);
    Route::put('/', [SalesReturnController::class, 'update']);
    Route::delete('/', [SalesReturnController::class, 'destroy']);
    Route::delete('/items', [SalesReturnController::class, 'removeItems']);
});



/**
 * PDF
 * Todo: export
 */
Route::group(['prefix' => 'pdf-export'], function ()
{
    Route::get('/invoice', [ExportInvoiceController::class, 'export']);
    Route::get('/pos-payment', [ExportPaymentController::class, 'export']);
});


/**
 * Excel
 * Todo: export
 */
Route::group(['prefix' => 'excel-export'], function ()
{
    Route::get('/invoice', [ExportInvoiceController::class, 'export']);
    Route::get('/pos-payment', [ExportPaymentController::class, 'export']);
});


/**
 * CSV
 * Todo: export
 */
Route::group(['prefix' => 'csv-export'], function ()
{
    Route::get('/invoice', [ExportInvoiceController::class, 'export']);
    Route::get('/pos-payment', [ExportPaymentController::class, 'export']);
});



