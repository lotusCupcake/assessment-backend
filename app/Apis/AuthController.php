<?php

namespace App\Apis;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\UsersModel;
use App\Models\RefreshTokenModel;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\ExpiredException;

class AuthController extends ResourceController
{
    protected $usersModel;
    protected $refreshTokenModel;

    public function __construct()
    {
        $this->usersModel = new UsersModel();
        $this->refreshTokenModel = new RefreshTokenModel();
    }

    use ResponseTrait;

    public function register()
    {
        $data = [
            'user_id' => uuid(),
            'first_name' => $this->request->getVar('first_name'),
            'last_name' => $this->request->getVar('last_name'),
            'phone_number' => $this->request->getVar('phone_number'),
            'address' => $this->request->getVar('address'),
            'pin' => $this->request->getVar('pin'),
            'created_date' => date('Y-m-d H:i:s'),
        ];

        $pin = $data['pin'];
        if (!ctype_digit($pin) || strlen($pin) != 6) {
            return $this->respond(['message' => 'PIN must be a 6-digit number']);
        }

        $cek = $this->usersModel->where('phone_number', $data['phone_number'])->first();

        if ($cek) {
            return $this->respond(['message' => 'Phone Number Already Registered']);
        }

        $this->usersModel->insert($data);

        $newUser = $this->usersModel->find($data['user_id']);

        $response = [
            'status' => 'SUCCESS',
            'result' => [
                'user_id' => $newUser['user_id'],
                'first_name' => $newUser['first_name'],
                'last_name' => $newUser['last_name'],
                'phone_number' => $newUser['phone_number'],
                'address' => $newUser['address'],
                'created_date' => $newUser['created_date'],
            ]
        ];

        return $this->respond($response);
    }

    public function login()
    {
        $phoneNumber = $this->request->getVar('phone_number');
        $pin = $this->request->getVar('pin');

        $user = $this->usersModel->where('phone_number', $phoneNumber)->first();

        if ($user && $user['pin'] === $pin) {

            $accessToken = $this->generateTokens($user['user_id'], 'access');
            $refreshToken = $this->generateTokens($user['user_id'], 'refresh');

            $cekRefresh = $this->refreshTokenModel->where('user_id', $user['user_id'])->first();

            if ($cekRefresh) {
                $this->refreshTokenModel->update($user['user_id'], ['token' => $refreshToken]);
            } else {
                $this->refreshTokenModel->insert(['user_id' => $user['user_id'], 'token' => $refreshToken]);
            }

            $response = [
                'status' => 'SUCCESS',
                'result' => [
                    'access_token' => $accessToken,
                    'refresh_token' => $refreshToken,
                ]
            ];

            return $this->respond($response);
        } else {
            return $this->respond(['message' => 'Phone number and pin doesn\'t match.']);
        }
    }

    public function generateTokens($id, $type)
    {
        $key = getenv('JWT_SECRET');
        $issuedAt = time();
        $expirationTime = $issuedAt + ($type == 'access' ? 3600 : 7200);
        $payload = [
            'iat' => $issuedAt,
            'exp' => $expirationTime,
            'sub' => $id,
        ];

        $token = JWT::encode($payload, $key, 'HS256');

        return $token;
    }

    public function refreshToken()
    {
        $refreshToken = $this->request->getVar('refresh_token');

        if (!$refreshToken) {
            return $this->respond(['message' => 'Invalid refresh token 1'], 400);
        }

        $key = getenv('JWT_SECRET');

        try {
            $decoded = JWT::decode($refreshToken, new Key($key, 'HS256'));
            $validation = $this->isValidRefreshToken($decoded->sub, $refreshToken);
            if ($validation[0]) {
                $newToken = $this->generateTokens($decoded->sub, 'refresh');
                return $this->respond([
                    'status' => 'SUCCESS',
                    'access_token' => $newToken
                ]);
            } else {
                return $this->respond(['message' => $validation[1]], 400);
            }
        } catch (ExpiredException $e) {
            return $this->respond(['message' => 'Refresh token expired'], 401);
        } catch (\Exception $e) {
            return $this->respond(['message' => 'Invalid refresh token'], 400);
        }
    }


    public function isValidRefreshToken($userId, $token)
    {
        $cek = $this->refreshTokenModel->where('user_id', $userId)->first();

        if (!$cek) {
            return [false, 'Refresh token not found'];
        }

        if ($cek['token'] !== $token) {
            return [false, 'Refresh token does not match'];
        }

        return [true, 'Refresh token valid'];
    }

}
