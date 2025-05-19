<?php

namespace App\Actions\Fortify;

use Illuminate\Support\Facades\Auth;

class RedirectAuthenticatedUsers
{
    public function __invoke()
    {
        return Auth::user()->is_admin ? '/admin/attendances' : '/attendance';
    }
}
