<?php

namespace App\Models;

use App\Traits\Employee\AccessRightsServices;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;

class AccessRights extends Model
{
    use HasFactory, AccessRightsServices;

    protected $fillable = [
        'role_id',
        'back_office',
        'pos'
    ];

    public function roles()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

}
