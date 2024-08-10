<?php

namespace App\Models;

use CodeIgniter\Model;

class UsersModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'user_id';
    protected $allowedFields = ['user_id', 'first_name', 'last_name', 'phone_number', 'address', 'pin', 'created_date', 'updated_date'];
    protected $useAutoIncrement = false;
}
