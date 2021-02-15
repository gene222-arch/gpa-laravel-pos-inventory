<?php

namespace App\Providers;

use App\Models\AccessRights;
use App\Models\BadOrder;
use App\Models\Product;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Discount;
use App\Models\Employee;
use App\Models\Invoice;
use App\Models\Pos;
use App\Models\PurchaseOrder;
use App\Models\ReceivedStock;
use App\Models\SalesReturn;
use App\Models\StockAdjustment;
use App\Models\Supplier;
use App\Policies\Customer\CustomerPolicy;
use App\Policies\Employee\AccessRightsPolicy as EmployeeAccessRightsPolicy;
use App\Policies\Employee\EmployeePolicy;
use App\Policies\InventoryManagement\BadOrderPolicy;
use App\Policies\InventoryManagement\PurchaseOrderPolicy;
use App\Policies\InventoryManagement\ReceivedStocksPolicy;
use App\Policies\InventoryManagement\StockAdjustmentPolicy;
use App\Policies\InventoryManagement\SupplierPolicy;
use App\Policies\Invoice\InvoicePolicy;
use App\Policies\Pos\PosPolicy;
use Laravel\Passport\Passport;
use App\Policies\Products\ProductPolicy;
use App\Policies\Products\CategoryPolicy;
use App\Policies\Products\DiscountPolicy;
use App\Policies\SalesReturn\SalesReturnPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;


class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Category::class => CategoryPolicy::class,
        Product::class => ProductPolicy::class,
        Supplier::class => SupplierPolicy::class,
        PurchaseOrder::class => PurchaseOrderPolicy::class,
        BadOrder::class => BadOrderPolicy::class,
        Invoice::class => InvoicePolicy::class,
        Customer::class => CustomerPolicy::class,
        SalesReturn::class => SalesReturnPolicy::class,
        Pos::class => PosPolicy::class,
        Discount::class => DiscountPolicy::class,
        Employee::class => EmployeePolicy::class,
        AccessRights::class=> EmployeeAccessRightsPolicy::class,
        StockAdjustment::class => StockAdjustmentPolicy::class,
        ReceivedStock::class => ReceivedStocksPolicy::class
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        Passport::routes();
    }
}

