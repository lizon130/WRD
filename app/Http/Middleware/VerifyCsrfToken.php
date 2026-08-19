<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */

    protected $except = [
        '/Machine/Transfer/Dashboard', 
        '/admin/machine-tranfer/tranfer/dashboard/data',
        'login-post',
        '/Machine/Transfer/Dashboard',
    ];
}
