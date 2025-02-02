<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;

class CookieToHeaderMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        if (! $request->headers->has('Authorization')) {
            $encryptedToken = Cookie::get('token');

            if ($encryptedToken) {
                try {
                    $token = Crypt::decryptString($encryptedToken);

                    $request->headers->set('Authorization', 'Bearer '.$token);
                } catch (\Exception $e) {
                    Log::warning('Invalid or tampered cookie detected', ['error' => $e->getMessage()]);
                    Cookie::queue(Cookie::forget('token'));
                    throw $e;
                }
            }
        }

        return $next($request);
    }
}
