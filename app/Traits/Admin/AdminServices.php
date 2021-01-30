<?php

namespace App\Traits\Admin;

use App\Models\User;

trait AdminServices
{

    /**
     * Get admin info
     *
     * @return App\Models\User
     */
    public function admin()
    {
        return $this->user
                    ->whereHas('roles', fn($q) => $q->where('name', 'admin'))
                    ->get()
                    ->first();
    }
}
