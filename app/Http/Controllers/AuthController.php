<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    private $cookieKey = 'token';

    /**
     * Register new users
     *
     * POST /api/v1/auth/register
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(RegisterRequest $request)
    {
        $user = User::create($request->validated());

        return $this->successResponse(
            'User registered successfully.',
            $user,
            Response::HTTP_CREATED,
        );
    }

    /**
     * Login user and generate access token
     *
     * POST /api/v1/auth/login
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request)
    {
        // Validate the payload
        $credentials = $request->validated();

        if ($token = Auth::attempt($credentials)) {
            $user = Auth::user();

            // Save the token into cookies
            $cookie = $this->generateCookie($token);

            return $this->successResponse(
                msg: 'User Logged-in successfully.',
                data: [
                    'user' => $user,
                    'token' => $token,
                ],
                includeCookie: true,
                includeCookies: [$cookie]
            );
        } else {
            return $this->failureResponse(
                msg: 'Invalid credentials.',
                statusCode: Response::HTTP_UNAUTHORIZED
            );
        }
    }

    /**
     * Logout the user and invalidate JWT token
     *
     * POST /api/v1/auth/logout
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        Auth::invalidate(true); // true = Blacklist the token

        return $this->successResponse(
            statusCode: Response::HTTP_NO_CONTENT,
            excludeCookie: true,
            excludeCookies: [
                new Cookie($this->cookieKey),
            ]
        );
    }

    // @ route -
    /**
     * Refresh the accesstoken (if refresh token was still valid)
     *
     * GET /api/v1/auth/refresh
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        $token = Auth::refresh(forceForever: true, resetClaims: true);

        // Save the token into cookies
        $cookie = $this->generateCookie($token);

        return $this->successResponse(
            'Token refreshed successfully.',
            $token,
            includeCookie: true,
            includeCookies: [$cookie]
        );
    }

    private function generateCookie(string $token): Cookie
    {
        $expireTime = Carbon::now()->addWeeks(2);
        $encryptedToken = Crypt::encryptString($token);

        $cookie = new Cookie(
            name: $this->cookieKey,
            value: $encryptedToken,
            expire: $expireTime,
            path: null,
            domain: null,
            secure: true,
            httpOnly: true,
            raw: false,
            sameSite: 'Strict'
        );

        return $cookie;
    }
}
