<?php

namespace App\Apis;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\UsersModel;

class AuthController extends ResourceController
{
    protected $usersModel;

    public function __construct()
    {
        $this->usersModel = new UsersModel();
    }

    use ResponseTrait;

    public function register()
    {
        $data = [
            'user_id' => $this->uuid(),
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

    public function uuid()
    {
        $uuid = service('uuid');
        $uuid4 = $uuid->uuid4();
        $string = $uuid4->toString();
        return $string;
    }
}
