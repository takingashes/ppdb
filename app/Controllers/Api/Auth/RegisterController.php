<?php

namespace App\Controllers\Api\Auth;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;
use App\Models\User;

class RegisterController extends BaseController
{
    use ResponseTrait;

    public function __construct()
    {
        $this->model = new User();
    }

    public function index()
    {
        $rules = [
            'email' => ['rules' => 'required|min_length[4]|max_length[255]|valid_email|is_unique[users.email]'],
            'password' => ['rules' => 'required|min_length[8]|max_length[255]'],
            'confirm_password'  => [ 'label' => 'confirm password', 'rules' => 'matches[password]']
        ];

        if($this->validate($rules)){
            $this->model->save([
                'email'    => $this->request->getVar('email'),
                'password' => password_hash($this->request->getVar('password'), PASSWORD_DEFAULT)
            ]);

            return $this->respond(['message' => 'Registered Successfully'], 200);
        }else{
            $response = [
                'errors' => $this->validator->getErrors(),
                'message' => 'Invalid Inputs'
            ];
            return $this->fail($response , 409);

        }
    }
}
