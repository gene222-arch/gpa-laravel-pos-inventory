<?php

namespace App\Policies\InventoryManagement;

use App\Models\Stock;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class StockPolicy
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
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Stock  $stock
     * @return mixed
     */
    public function view(User $user, Stock $stock)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Stock  $stock
     * @return mixed
     */
    public function update(User $user, Stock $stock)
    {
        return $user->hasPermissionTo('adjust_stocks', 'api');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Stock  $stock
     * @return mixed
     */
    public function delete(User $user, Stock $stock)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Stock  $stock
     * @return mixed
     */
    public function restore(User $user, Stock $stock)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Stock  $stock
     * @return mixed
     */
    public function forceDelete(User $user, Stock $stock)
    {
        //
    }
}
