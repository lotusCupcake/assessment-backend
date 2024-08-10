<?php

namespace App\Models;

use CodeIgniter\Model;

class RefreshTokenModel extends Model
{
    protected $table = 'refresh_tokens';
    protected $primaryKey = 'user_id';
    protected $allowedFields = ['user_id', 'token'];
    protected $useAutoIncrement = false;
}
