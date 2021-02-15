<?php

namespace App\Policies\Pos;

use App\Models\Pos;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PosPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->hasPermissionTo('view_orders_in_pos', 'api');
    }


    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function view(User $user)
    {
        return $user->hasPermissionTo('show_customer_pos_details', 'api');
    }


    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermissionTo('create_orders_in_pos', 'api');
    }


    /**
     * Determine whether the user can process customer payments
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function processPayment(User $user)
    {
        return $user->hasPermissionTo('process_customer_payment', 'api');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Pos  $pos
     * @return mixed
     */
    public function update(User $user, Pos $pos)
    {
        return $user->hasPermissionTo('update_orders_quantities_in_pos', 'api');
    }



    /**
     * Determine whether the user can assign a discount.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Pos  $pos
     * @return mixed
     */
    public function assignDiscount(User $user, Pos $pos)
    {
        return $user->hasPermissionTo('assign_discount_in_customers_order_in_pos', 'api');
    }



      /**
     * Determine whether the user can assign a discount.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Pos  $pos
     * @return mixed
     */
    public function applyDiscountAddQuantity(User $user, Pos $pos)
    {
        return $user->hasPermissionTo('apply_discount_add_quantity_in_pos', 'api');
    }


    /**
     * Determine whether the user can assign a discount.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Pos  $pos
     * @return mixed
     */
    public function removeDiscount(User $user, Pos $pos)
    {
        return $user->hasPermissionTo('remove_discount_in_customers_order_in_pos', 'api');
    }


    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Pos  $pos
     * @return mixed
     */
    public function removeItems(User $user, Pos $pos)
    {
        return $user->hasPermissionTo('remove_items_in_pos', 'api');
    }


    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Pos  $pos
     * @return mixed
     */
    public function cancelOrders(User $user, Pos $pos)
    {
        return $user->hasPermissionTo('cancel_orders_in_pos', 'api');
    }
}
