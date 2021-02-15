<?php

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Pos\PosController;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Admin\AdminController;
use App\Http\Controllers\Api\Auth\ForgotPasswordController;
use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\Api\Auth\ResetPasswordController;
use App\Http\Controllers\Api\Users\CashierController;
use App\Http\Controllers\Api\Invoice\InvoiceController;
use App\Http\Controllers\Api\Products\ProductsController;
use App\Http\Controllers\Api\Customer\CustomersController;
use App\Http\Controllers\Api\Employee\EmployeesController;
use App\Http\Controllers\Api\ExportControllers\ExportBadOrdersController;
use App\Http\Controllers\Api\ExportControllers\ExportCustomersController;
use App\Http\Controllers\Api\Products\CategoriesController;
use App\Http\Controllers\Api\SalesReturn\SalesReturnController;
use App\Http\Controllers\Api\InventoryManagement\BadOrdersController;
use App\Http\Controllers\Api\InventoryManagement\SuppliersController;
use App\Http\Controllers\Api\ExportControllers\ExportInvoiceController;
use App\Http\Controllers\Api\ExportControllers\ExportLowStockController;
use App\Http\Controllers\Api\ExportControllers\ExportPaymentsController;
use App\Http\Controllers\Api\ExportControllers\ExportProductsController;
use App\Http\Controllers\Api\ExportControllers\ExportPurchaseOrdersController;
use App\Http\Controllers\Api\ExportControllers\ExportSalesReturnController;
use App\Http\Controllers\Api\Imports\ImportProductsController;
use App\Http\Controllers\Api\InventoryManagement\PurchaseOrdersController;
use App\Http\Controllers\Api\InventoryManagement\StockAdjustmentsController;
use App\Http\Controllers\Api\Products\DiscountsController;
use App\Http\Controllers\Api\Reports\ReportsController;

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

/**
 * Authentication
 */
Route::group(['middleware' => 'api'], function ()
{
    Route::post('/auth/register', [ RegisterController::class, 'register' ]);
    Route::post('/auth/login', [ LoginController::class, 'login' ]);
    Route::post('/forgot-password/email', [ForgotPasswordController::class, 'sendResetLinkEmail']);
    Route::post('/forgot-password/reset', [ResetPasswordController::class, 'reset']);


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
 * *  Product Controller
 */

Route::group(['prefix' => 'products'], function ()
{
    Route::get('/', [ProductsController::class, 'index']);
    Route::post('/', [ProductsController::class, 'store']);
    Route::put('/', [ProductsController::class, 'update']);
    Route::delete('/', [ProductsController::class, 'destroy']);
});


 /**
  * Categories
  */

Route::group(['prefix' => 'categories'], function ()
{
    Route::get('/', [CategoriesController::class, 'index']);
    Route::post('/', [CategoriesController::class, 'store']);
    Route::put('/', [CategoriesController::class, 'update']);
    Route::delete('/', [CategoriesController::class, 'destroy']);
});


/**
 * Discounts
 */
Route::group(['prefix' => 'discounts'], function ()
{
    Route::get('/', [DiscountsController::class, 'index']);
    Route::post('/detail', [DiscountsController::class, 'show']);
    Route::post('/', [DiscountsController::class, 'store']);
    Route::put('/', [DiscountsController::class, 'update']);
    Route::delete('/', [DiscountsController::class, 'destroy']);
});


/**
 * * Suppliers
 */

Route::group(['prefix' => 'suppliers'], function ()
{
    Route::get('/', [SuppliersController::class, 'index']);
    Route::post('/', [SuppliersController::class, 'store']);
    Route::put('/', [SuppliersController::class, 'update']);
    Route::delete('/', [SuppliersController::class, 'destroy']);
});


/**
 * Stocks
 */
Route::prefix('stocks')->group(function ()
{
    Route::get('/stock-adjustments', [StockAdjustmentsController::class, 'index']);
    Route::post('/stock-adjustment', [StockAdjustmentsController::class, 'show']);
    Route::post('/stock-adjustments', [StockAdjustmentsController::class, 'store']);
});


/**
 * * Purchase Order
 */

Route::group(['prefix' => 'purchase-orders'], function ()
{
    Route::get('/', [PurchaseOrdersController::class, 'index']);
    Route::post('/purchase-order-detail', [PurchaseOrdersController::class, 'show']);
    Route::post('/', [PurchaseOrdersController::class, 'store']);
    Route::put('/', [PurchaseOrdersController::class, 'upsert']);
    Route::put('/to-receive', [PurchaseOrdersController::class, 'toReceivePurchaseOrder']);
    Route::put('/mark-all-as-received', [PurchaseOrdersController::class, 'markAllPurchasedOrderAsReceived']);
    Route::delete('/', [PurchaseOrdersController::class, 'destroy']);
    Route::delete('/products', [PurchaseOrdersController::class, 'deleteProducts']);
    Route::post('/mail-supplier', [PurchaseOrdersController::class, 'sendMailToSupplier']);
});


/**
 * Bad Order
 *
 * Todo: Update Method
 * Todo: Add Low Stock Notification
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
 * Employeees
 */
Route::group(['prefix' => 'employees'], function ()
{
    Route::get('/', [EmployeesController::class, 'index']);
    Route::get('/access_rights', [EmployeesController::class, 'employeeAccessRights']);
    Route::post('/detail', [EmployeesController::class, 'show']);
    Route::post('/', [EmployeesController::class, 'store']);
    Route::put('/', [EmployeesController::class, 'update']);
    Route::delete('/', [EmployeesController::class, 'destroy']);
});


/**
 * Pos
 */

Route::group(['prefix' => 'pos'], function ()
{
    Route::get('/order-lists', [PosController::class, 'index']);
    Route::post('/cart-details', [PosController::class, 'showCartDetails']);
    Route::post('/add-to-cart', [PosController::class, 'store']);
    Route::post('/to-pay', [PosController::class, 'showAmountToPay']);
    Route::post('/process-payment', [PosController::class, 'processPayment']);
    Route::post('/invoice', [PosController::class, 'invoice']);
    Route::put('/discount', [PosController::class, 'assignDiscount']);
    Route::put('/discount-all', [PosController::class, 'assignDiscountToAll']);
    Route::put('/increase-item-qty', [PosController::class, 'incrementQuantity']);
    Route::put('/decrease-item-qty', [PosController::class, 'decrementQuantity']);
    Route::put('/item-qty', [PosController::class, 'update']);
    Route::delete('/discount', [PosController::class, 'removeDiscount']);
    Route::delete('/discount-all', [PosController::class, 'removeDiscountToAll']);
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
 * Sales Return
 * Todo: Update Method
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
 * Export or Import
 */
Route::group(['middleware' => ['role:admin|manager']], function ()
{
    /**
     * PDF Export
     */
    Route::group(['prefix' => 'pdf-export'], function ()
    {
        Route::get('/invoices', [ExportInvoiceController::class, 'toPDF']);
        Route::get('/purchase-order', [ExportPurchaseOrdersController::class, 'toPDF']);
        Route::get('/pos-payment', [ExportPaymentsController::class, 'toPDF']);
    });


    /**
     * Excel Export
     */
    Route::group(['prefix' => 'excel-export'], function ()
    {
        Route::get('/invoices', [ExportInvoiceController::class, 'toExcel']);
        Route::get('/payments', [ExportPaymentsController::class, 'toExcel']);
        Route::get('/customers', [ExportCustomersController::class, 'toExcel']);
        Route::get('/products', [ExportProductsController::class, 'toExcel']);
        Route::get('/bad-orders', [ExportBadOrdersController::class, 'toExcel']);
        Route::get('/sales-returns', [ExportSalesReturnController::class, 'toExcel']);
    });


    /**
     * CSV Export
     */
    Route::group(['prefix' => 'csv-export'], function ()
    {
        Route::get('/invoices', [ExportInvoiceController::class, 'toCSV']);
        Route::get('/payments', [ExportPaymentsController::class, 'toCSV']);
        Route::get('/customers', [ExportCustomersController::class, 'toCSV']);
        Route::get('/products', [ExportProductsController::class, 'toCSV']);
        Route::get('/bad-orders', [ExportBadOrdersController::class, 'toCSV']);
        Route::get('/sales-returns', [ExportSalesReturnController::class, 'toCSV']);
    });


    /**
     * Excel/CSV Import
     */
    Route::get('import/products', [ImportProductsController::class, 'import']);

});



/**
 * Summary Reports
 */
Route::group([
    'prefix' => 'report',
    'middleware' => 'role:admin|manager'
], function ()
{
    /**
     * General
     */
    Route::get('/general', [ReportsController::class, 'generalAnalytics']);

    /**
     * Item
     */
    Route::group(['prefix' => 'sales-by-item'], function ()
    {
        Route::post('/top-5', [ReportsController::class, 'getTopFiveSalesByItem']);
        Route::post('/reports', [ReportsController::class, 'getSalesByItemReports']);
    });

    /**
     * Category
     */
    Route::post('/sales-by-category', [ReportsController::class, 'getSalesByCategory']);

    /**
     * Payment Type
     */
    Route::post('/sales-by-payment-type', [ReportsController::class, 'getSalesByPaymentType']);

    /**
     * Employee
     */
    Route::post('/sales-by-employee', [ReportsController::class, 'getSalesByEmployee']);
});


