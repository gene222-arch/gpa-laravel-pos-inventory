<?php

namespace App\Policies\Employee;

use App\Models\AccessRights;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AccessRightsPolicy
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
        return $user->hasPermissionTo('view_access_rights', 'api');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AccessRights  $accessRights
     * @return mixed
     */
    public function view(User $user, AccessRights $accessRights)
    {
        return $user->hasPermissionTo('show_access_rights', 'api');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermissionTo('create_access_rights', 'api');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AccessRights  $accessRights
     * @return mixed
     */
    public function update(User $user, AccessRights $accessRights)
    {
        return $user->hasPermissionTo('update_access_rights', 'api');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\AccessRights  $accessRights
     * @return mixed
     */
    public function delete(User $user, AccessRights $accessRights)
    {
        return $user->hasPermissionTo('delete_access_rights', 'api');
    }


}
