<?php

namespace App\Policies\InventoryManagement;

use App\Models\BadOrder;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BadOrderPolicy
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
        return $user->hasPermissionTo('view_bad_orders', 'api');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\BadOrder  $badOrder
     * @return mixed
     */
    public function view(User $user, BadOrder $badOrder)
    {
        return $user->hasPermissionTo('show_bad_orders', 'api');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermissionTo('create_bad_orders', 'api');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\BadOrder  $badOrder
     * @return mixed
     */
    public function update(User $user, BadOrder $badOrder)
    {
        return $user->hasPermissionTo('update_bad_orders', 'api');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\BadOrder  $badOrder
     * @return mixed
     */
    public function delete(User $user, BadOrder $badOrder)
    {
        return $user->hasPermissionTo('delete_bad_orders', 'api');
    }

}
