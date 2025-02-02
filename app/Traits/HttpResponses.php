<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Response;

trait HttpResponses
{
    private function buildResponse(
        string $msg,
        $data,
        int $statusCode,
        $meta,
        array $headers,
        bool $includeCookie,
        array $includeCookies,
        bool $excludeCookie,
        array $excludeCookies
    ): JsonResponse {
        $response = response()->json(
            [
                'message' => $msg,
                'data' => $data,
                'meta' => $meta,
            ],
            $statusCode,
            $headers
        );

        if ($includeCookie) {
            foreach ($includeCookies as $cookie) {
                if ($cookie instanceof Cookie) {
                    $response->withCookie($cookie);
                } else {
                    throw new \InvalidArgumentException('All included cookies must be instances of Symfony\Component\HttpFoundation\Cookie');
                }
            }
        }

        if ($excludeCookie) {
            foreach ($excludeCookies as $cookie) {
                if ($cookie instanceof Cookie) {
                    $response->withoutCookie($cookie->getName());
                } else {
                    throw new \InvalidArgumentException('All excluded cookies must be instances of Symfony\Component\HttpFoundation\Cookie');
                }
            }
        }

        return $response;
    }

    public function successResponse(
        string $msg = '',
        $data = null,
        int $statusCode = Response::HTTP_OK,
        $meta = null,
        array $headers = [],
        bool $includeCookie = false,
        array $includeCookies = [],
        bool $excludeCookie = false,
        array $excludeCookies = []
    ): JsonResponse {
        return $this->buildResponse(
            $msg,
            $data,
            $statusCode,
            $meta,
            $headers,
            $includeCookie,
            $includeCookies,
            $excludeCookie,
            $excludeCookies
        );
    }

    public function failureResponse(
        string $msg,
        $data = null,
        int $statusCode = Response::HTTP_BAD_REQUEST,
        $meta = null,
        array $headers = [],
        bool $includeCookie = false,
        array $includeCookies = [],
        bool $excludeCookie = false,
        array $excludeCookies = []
    ): JsonResponse {
        return $this->buildResponse(
            $msg,
            $data,
            $statusCode,
            $meta,
            $headers,
            $includeCookie,
            $includeCookies,
            $excludeCookie,
            $excludeCookies
        );
    }

    public function serverError(): JsonResponse
    {
        return $this->buildResponse(
            'Unexpected error occurred!',
            null,
            Response::HTTP_INTERNAL_SERVER_ERROR,
            $meta = null,
            $headers = [],
            $includeCookie = false,
            $includeCookies = [],
            $excludeCookie = false,
            $excludeCookies = []
        );
    }
}
