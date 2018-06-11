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
        'api/*'
    ];

    protected function tokensMatch($request)
    {
        $token = $request->input('_token') ?: $request->header('X-CSRF-TOKEN');

        if ( ! $token && $header = $request->header('X-XSRF-TOKEN'))
        {
            $token = $this->encrypter->decrypt($header);
        }

        return true;
        //return StringUtils::equals($request->session()->token(), $token);
    }
}
