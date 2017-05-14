<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;

class VerifyCsrfToken extends BaseVerifier
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        '/add-celeb',
        'api/add-celeb',
        'api/dislike',
        '/save-user',
        '/api/update-user',
        '/save-admin',
        '/add-admin',
        'follow',
        'un-follow',
        'api/login-email',
        'api/login-facebook',
        '/userSuggestions'
    ];
}
