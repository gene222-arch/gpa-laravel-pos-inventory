<?php

namespace App\Policies\InventoryManagement;

use App\Models\ReceivedStock;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ReceivedStocksPolicy
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
        return $user->hasPermissionTo('view_received_stocks', 'api');
    }

}
