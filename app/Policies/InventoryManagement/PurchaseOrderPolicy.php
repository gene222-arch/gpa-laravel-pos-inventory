<?php

namespace App\Policies\InventoryManagement;

use App\Models\User;
use App\Models\PurchaseOrder;
use Illuminate\Auth\Access\HandlesAuthorization;

class PurchaseOrderPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\PurchaseOrder $purchaseOrder
     * @return mixed
     */
    public function viewAny(User $user, PurchaseOrder $purchaseOrder)
    {
        return $user->hasPermissionTo('view_purchase_order_details', 'api');
    }


    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\PurchaseOrder $purchaseOrder
     * @return mixed
     */
    public function view(User $user, PurchaseOrder $purchaseOrder)
    {
        return $user->hasPermissionTo('show_purchase_order_details', 'api');
    }


    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\PurchaseOrder $purchaseOrder
     * @return mixed
     */
    public function create(User $user, PurchaseOrder $purchaseOrder)
    {
        return $user->hasPermissionTo('purchase_order', 'api');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\PurchaseOrder $purchaseOrder
     * @return mixed
     */
    public function update(User $user, PurchaseOrder $purchaseOrder)
    {
        return $user->hasPermissionTo('update_purchase_order', 'api');
    }

    /**
     * Determine whether the user can receive purchase order
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\PurchaseOrder $purchaseOrder
     * @return mixed
     */
    public function receivePurchaseOrder(User $user, PurchaseOrder $purchaseOrder)
    {
        return $user->hasPermissionTo('receive_purchase_order', 'api');
    }


    /**
     * Determine whether the user can mark all as received an order of supplies.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\PurchaseOrder $purchaseOrder
     * @return mixed
     */
    public function markAllPurchaseOrderAsReceived(User $user, PurchaseOrder $purchaseOrder)
    {
        return $user->hasPermissionTo('mark_all_purchase_order_as_received', 'api');
    }


    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\PurchaseOrder $purchaseOrder
     * @return mixed
     */
    public function delete(User $user, PurchaseOrder $purchaseOrder)
    {
        return $user->hasPermissionTo('delete_purchase_order', 'api');
    }


        /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\PurchaseOrder $purchaseOrder
     * @return mixed
     */
    public function deletePurchaseOrderPerProduct(User $user, PurchaseOrder $purchaseOrder)
    {
        return $user->hasPermissionTo('delete_purchase_order_per_product', 'api');
    }

}
