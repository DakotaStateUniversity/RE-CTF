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
      "/ajax/category/modify",
      "/ajax/challenge/store",
      "/ajax/challenge/modify",
      "/ajax/challenge/attempt",
      "/ajax/challenge/file_put",
      "/ajax/challenge/file_get"
    ];
}
