<?php

namespace App\Traits\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

trait AuthServices
{

    public function validatePassword (string $password)
    {
        return Hash::check($password, auth()->user()->getAuthPassword());
    }


    public function changeName (string $name) 
    {
        return User::where('id', '=', Auth::user()->id)
            ->update([
                'name' => $name
            ]);
    }


    public function changeEmail (string $email) 
    {
        return User::where('id', '=', Auth::user()->id)
            ->update([
                'email' => $email
            ]);
    }


    public function changePassword (string $newPassword)
    {
        return User::where('id', '=', Auth::user()->id)
            ->update([
                'password' => Hash::make($newPassword)
            ]);
    }
}
