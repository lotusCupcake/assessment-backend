<?php

namespace App\Apis;

use App\Models\UsersModel;
use CodeIgniter\RESTful\ResourceController;

class ProfileController extends ResourceController
{
    protected $usersModel;

    public function __construct()
    {
        $this->usersModel = new UsersModel();
    }

    public function editProfile()
    {
        $user = $this->request->user;

        $data = $_GET;

        // $this->usersModel->update($user->sub, $data);

        // $user = $this->usersModel->find($user->sub);

        // $response = [
        //     'status' => 'SUCCESS',
        //     'result' => $user
        // ];
        return $this->respond($data);
    }
}
