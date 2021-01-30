<?php

namespace App\Models;

use App\Traits\Admin\AdminServices;
use Laravel\Passport\HasApiTokens;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;


class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens, HasRoles, SoftDeletes;

    use AdminServices;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    public $timestamps = true;


    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public $user;

    /**
     * Undocumented function
     */
    public function __construct()
    {
        $this->user = $this;
    }

    /**
     * Create token
     *
     * @return \Laravel\Passport\PersonalAccessTokenResult
     */
    public function generateToken()
    {
        return $this->createToken('Personal Access Token')->accessToken;
    }

    public function isAdmin()
    {
        return $this->hasRole('admin');
    }

    public function isAdminOrManager()
    {
        return $this->hasAnyRole(['admin', 'manager']);
    }

}
