<?php

namespace App\Policies\InventoryManagement;

use App\Models\StockAdjustment;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class StockAdjustmentPolicy
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
        return $user->hasPermissionTo('view_stock_adjustments', 'api');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\StockAdjustment  $stockAdjustment
     * @return mixed
     */
    public function view(User $user, StockAdjustment $stockAdjustment)
    {
        return $user->hasPermissionTo('show_stock_adjustments', 'api');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermissionTo('create_stock_adjustment', 'api');
    }

}
