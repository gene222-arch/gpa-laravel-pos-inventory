<?php

namespace App\Models;

use App\Traits\Employee\EmployeeServices;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;

class Employee extends Model
{
    use HasFactory, EmployeeServices, HasRoles;

    protected $guard_name = 'api';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'role'
    ];

}
