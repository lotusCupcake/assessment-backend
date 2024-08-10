<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\ExpiredException;
use Config\Services;

class JWTAuth implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $key = getenv('JWT_SECRET');
        $authHeader = $request->getHeader("Authorization");

        if (!$authHeader) {
            return Services::response()->setJSON(['message' => 'Unauthenticated'])->setStatusCode(401);
        }

        $token = $authHeader->getValue();
        $token = str_replace('Bearer ', '', $token);

        try {
            $decoded = JWT::decode($token, new Key($key, 'HS256'));
            $request->user = $decoded;
        } catch (ExpiredException $e) {
            return Services::response()->setJSON(['message' => 'Token expired'])->setStatusCode(401);
        } catch (\Exception $e) {
            return Services::response()->setJSON(['message' => 'Unauthenticated'])->setStatusCode(401);
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null) {}
}
