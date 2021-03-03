<?php

namespace App\Policies\Pos;

use App\Models\Pos;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PosPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Pos  $pos
     * @return mixed
     */
    public function viewAllReceipts(User $user, Pos $pos)
    {
        return $user->hasPermissionTo('View All Receipts', 'api');
    }
}
