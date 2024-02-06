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
        //
        'https://kofasante.com/admin-kofasante/dist/admin-font-gc/',
        'https://kofasante.com/djaabar',
        'https://kofasante.com/mobiles',
        'https://kofasante.com/mobile'
    ];
}
