<?php

namespace App\Policies\SalesReturn;

use App\Models\SalesReturn;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SalesReturnPolicy
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
        return $user->hasPermissionTo('view_sales_return', 'api');
    }


    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermissionTo('create_sales_return', 'api');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\SalesReturn  $salesReturn
     * @return mixed
     */
    public function update(User $user, SalesReturn $salesReturn)
    {
        return $user->hasPermissionTo('update_sales_return', 'api');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\SalesReturn  $salesReturn
     * @return mixed
     */
    public function delete(User $user, SalesReturn $salesReturn)
    {
        return $user->hasPermissionTo('delete_sales_return', 'api');
    }



    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\SalesReturn  $salesReturn
     * @return mixed
     */
    public function removeItems(User $user, SalesReturn $salesReturn)
    {
        return $user->hasPermissionTo('remove_item_in_sales_return_details', 'api');
    }

}
